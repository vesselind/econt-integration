# Quickstart: PHP Econt Integration Library

## Installation

```bash
composer require vesselind/econt-integration
```

---

## 1. Standalone Usage (any PHP project)

```php
<?php
declare(strict_types=1);

use Econt\EcontApi\EcontClient;
use Econt\EcontApi\EcontConfiguration;
use Econt\EcontApi\Http\SymfonyHttpAdapter;

// 1. Configure
$config = new EcontConfiguration(
    username: 'iasp-dev',
    password: '1Asp-dev',
    baseUrl: EcontConfiguration::DEMO_URL,   // or PRODUCTION_URL
    timeout: 30,
    language: 'bg'  // or 'en'
);

// 2. Choose HTTP adapter (Symfony client — default)
$adapter = new SymfonyHttpAdapter(\Symfony\Component\HttpClient\HttpClient::create());

// 3. Instantiate client
$client = new EcontClient($config, $adapter);

// 4. Use services
$countries = $client->address()->getCountries();
foreach ($countries as $country) {
    echo $country->nameEn . PHP_EOL;
}
```

---

## 2. Standalone with PSR-18 Client

```php
use Econt\EcontApi\Http\Psr18HttpAdapter;
use Nyholm\Psr7\Factory\Psr17Factory;

$psr17Factory = new Psr17Factory();
$psrClient    = new \Symfony\Component\HttpClient\Psr18Client(); // or any PSR-18 client

$adapter = new Psr18HttpAdapter($psrClient, $psr17Factory, $psr17Factory);
$client  = new EcontClient($config, $adapter);
```

---

## 3. Symfony DI Wiring (manual, no bundle)

```yaml
# config/services.yaml
services:
    Econt\EcontApi\EcontConfiguration:
        arguments:
            $username: '%env(ECONT_USERNAME)%'
            $password: '%env(ECONT_PASSWORD)%'
            $baseUrl: '%env(ECONT_BASE_URL)%'
            $timeout: 30
            $language: 'bg'

    Econt\EcontApi\Http\SymfonyHttpAdapter:
        arguments:
            $httpClient: '@Symfony\Contracts\HttpClient\HttpClientInterface'

    Econt\EcontApi\EcontClient:
        arguments:
            $configuration: '@Econt\EcontApi\EcontConfiguration'
            $adapter: '@Econt\EcontApi\Http\SymfonyHttpAdapter'
```

---

## 4. Key Usage Examples

### Get all countries
```php
$countries = $client->address()->getCountries();
// Returns CountryCollection (iterable, countable)
```

### Get cities for Bulgaria
```php
$cities = $client->address()->getCities('BGR');
```

### Get streets for Sofia (cityID=41)
```php
$streets = $client->address()->getStreets(41);
```

### Get offices in Shumen
```php
$offices = $client->office()->getOffices(countryCode: 'BGR', cityId: 47);

// Filter: only physical offices (no Econtomat)
$regularOffices = array_filter(
    iterator_to_array($offices),
    fn($o) => !$o->isAPS
);
```

### Validate an address
```php
use Econt\EcontApi\Model\Location\Address;
use Econt\EcontApi\Model\Location\City;
use Econt\EcontApi\Model\Location\Country;

$address = new Address(
    city: new City(country: new Country(code2: 'BG', code3: 'BGR', name: 'България', nameEn: 'Bulgaria'), postCode: '7000', name: 'Русе', nameEn: 'Ruse'),
    street: 'Славянска',
    num: '16'
);

$validated = $client->address()->validateAddress($address);
echo $validated->validationStatus; // "normal", "processed", or "invalid"
```

### Calculate shipment price
```php
use Econt\EcontApi\Model\Shipment\ShippingLabel;
use Econt\EcontApi\Model\Shipment\ClientProfile;
use Econt\EcontApi\Enum\ShipmentType;

$label = new ShippingLabel(
    senderClient: new ClientProfile(name: 'Иван Иванов', phones: ['0888888888']),
    senderAddress: $senderAddress,
    receiverClient: new ClientProfile(name: 'Богдан Богданов', phones: ['0878787878']),
    receiverAddress: $receiverAddress,
    packCount: 1,
    shipmentType: ShipmentType::PACK,
    weight: 5.0,
    shipmentDescription: 'обувки',
    mode: 'calculate'
);

$result = $client->shipment()->calculatePrice($label);
echo $result->totalPrice . ' ' . $result->currency;
```

### Create and cancel a shipment label
```php
$label->mode = 'create';
$created = $client->shipment()->createLabel($label);
echo $created->waybillNumber;

// Later, cancel it
$client->shipment()->cancelLabel($created->waybillNumber);
```

---

## 5. Exception Handling

```php
use Econt\EcontApi\Exception\EcontApiException;
use Econt\EcontApi\Exception\EcontNetworkException;
use Econt\EcontApi\Exception\EcontValidationException;

try {
    $offices = $client->office()->getOffices('BGR', 41);
} catch (EcontValidationException $e) {
    // Bad input before HTTP call
    echo implode(', ', $e->getViolations());
} catch (EcontApiException $e) {
    // API-level error
    echo $e->getApiErrorCode() . ': ' . $e->getApiErrorMessage();
} catch (EcontNetworkException $e) {
    // Transport failure
    echo 'Network error: ' . $e->getMessage();
}
```

---

## 6. Running Tests

```bash
# Unit tests only (no network)
composer test

# Integration tests (requires network to demo.econt.com)
php vendor/bin/phpunit --group integration

# Static analysis
composer phpstan

# Code style
composer phpcs
```

