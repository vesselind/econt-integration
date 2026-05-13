# PHP Econt Integration Library

A standalone PHP library for integrating with [Econt](https://www.econt.com/) Delivery Services API. Designed to be reusable across multiple projects and fully compatible with **PHP 8.2+**, **Symfony 6/7/8**, and any PSR-18 HTTP client.

---

## Features

- 📦 **Shipment management** — create, confirm, update, cancel labels
- 🗺️ **Address data** — countries, cities, streets, quarters, address validation
- 🏢 **Office / Econtomat lookup** — with filtering by country and city
- 💰 **Price calculation** — real-time shipment cost estimates
- 🚚 **Courier requests** — schedule pickups
- 📬 **Return & delivery instructions** — configurable courier/return instructions
- 🔄 **Dual HTTP adapter** — Symfony HttpClient (default) or any PSR-18 client
- 🧩 **Typed model objects** — all requests and responses map to strongly-typed PHP classes
- ✅ **PHPStan level 6 + PSR-12 compliant**

---

## Requirements

| Dependency | Version |
|------------|---------|
| PHP | `>= 8.2` |
| symfony/http-client | `^6.0 \| ^7.0 \| ^8.0` |
| psr/http-client | `^1.0` |
| psr/http-factory | `^1.0` |
| nyholm/psr7 | `^1.8` |

---

## Installation

```bash
composer require vesselind/econt-integration
```

---

## Quick Start

### Standalone (any PHP project)

```php
<?php
declare(strict_types=1);

use Econt\EcontApi\EcontClient;
use Econt\EcontApi\EcontConfiguration;
use Econt\EcontApi\Http\SymfonyHttpAdapter;
use Symfony\Component\HttpClient\HttpClient;

// 1. Configure — use the factory for the demo/test endpoint (credentials pre-filled)
$config = EcontConfiguration::forDemo();

// Or with explicit credentials for production
$config = new EcontConfiguration(
    username: 'your-username',
    password: 'your-password',
    baseUrl: EcontConfiguration::PRODUCTION_URL,
    timeout: 30,
    language: 'bg'  // 'bg' or 'en'
);

// 2. Create adapter + client
$adapter = new SymfonyHttpAdapter(HttpClient::create());
$client  = new EcontClient($config, $adapter);

// 3. Start querying
$countries = $client->address()->getCountries();
foreach ($countries as $country) {
    echo $country->nameEn . PHP_EOL;
}
```

### With Any PSR-18 Client

```php
use Econt\EcontApi\Http\Psr18HttpAdapter;
use Nyholm\Psr7\Factory\Psr17Factory;
use Symfony\Component\HttpClient\Psr18Client;

$psr17Factory = new Psr17Factory();
$adapter = new Psr18HttpAdapter(new Psr18Client(), $psr17Factory, $psr17Factory);
$client  = new EcontClient($config, $adapter);
```

### Symfony DI Wiring

```yaml
# config/services.yaml
services:
    Econt\EcontApi\EcontConfiguration:
        arguments:
            $username: '%env(ECONT_USERNAME)%'
            $password: '%env(ECONT_PASSWORD)%'
            $baseUrl:  '%env(ECONT_BASE_URL)%'
            $timeout:  30
            $language: 'bg'

    Econt\EcontApi\Http\SymfonyHttpAdapter:
        arguments:
            $httpClient: '@Symfony\Contracts\HttpClient\HttpClientInterface'

    Econt\EcontApi\EcontClient:
        arguments:
            $configuration: '@Econt\EcontApi\EcontConfiguration'
            $adapter:        '@Econt\EcontApi\Http\SymfonyHttpAdapter'
```

```
# .env
ECONT_USERNAME=iasp-dev
ECONT_PASSWORD=1Asp-dev
ECONT_BASE_URL=https://demo.econt.com/ee/services
```

---

## Usage

### Address Service

```php
$addressService = $client->address();

// Countries
$countries = $addressService->getCountries();  // CountryCollection

// Cities for a country
$cities = $addressService->getCities('BGR');   // CityCollection

// Streets for a city (cityID from City model)
$streets = $addressService->getStreets(41);    // StreetCollection

// Quarters for a city
$quarters = $addressService->getQuarters(41); // QuarterCollection

// Validate an address
use Econt\EcontApi\Model\Location\Address;
use Econt\EcontApi\Model\Location\City;
use Econt\EcontApi\Model\Location\Country;

$address = new Address(
    city: City::create(
        country:  Country::bulgaria(),
        postCode: '7000',
        name:     'Русе',
        nameEn:   'Ruse',
    ),
    street: 'Славянска',
    num:    '16',
);

$validated = $addressService->validateAddress($address);
echo $validated->validationStatus; // "normal" | "processed" | "invalid"
```

### Office Service

```php
$officeService = $client->office();

// All offices / Econtomats in Bulgaria
$offices = $officeService->getOffices(countryCode: 'BGR');

// Offices in a specific city
$offices = $officeService->getOffices(countryCode: 'BGR', cityId: 41);

// Filter only physical offices (exclude Econtomat/APS machines)
$regularOffices = array_filter(
    iterator_to_array($offices),
    fn($o) => !$o->isAPS
);
```

### Delivery Routing

The delivery route is determined by which address/office fields you fill in:

| Sender side | Receiver side | `tariffSubCode` |
|-------------|---------------|-----------------|
| `senderAddress` | `receiverAddress` | `DOOR_DOOR` |
| `senderAddress` | `receiverOfficeCode` | `DOOR_OFFICE` |
| `senderOfficeCode` | `receiverAddress` | `OFFICE_DOOR` |
| `senderOfficeCode` | `receiverOfficeCode` | `OFFICE_OFFICE` |

> The office code is the `code` field from the `Office` model returned by `getOffices()`.

**Door-to-office (receiver picks up at an Econt office)**

```php
use Econt\EcontApi\Enum\TariffSubCode;

$label = new ShippingLabel(
    senderClient:       new ClientProfile(name: 'Иван Иванов', phones: ['0888888888']),
    receiverClient:     new ClientProfile(name: 'Богдан Богданов', phones: ['0878787878']),
    packCount:          1,
    shipmentType:       ShipmentType::PACK,
    weight:             2.5,
    senderAddress:      $senderAddress,       // sender's door address
    receiverOfficeCode: '1127',               // Office::getCode() from getOffices()
    tariffSubCode:      TariffSubCode::DOOR_OFFICE,
    shipmentDescription: 'Книги',
);

$result = $client->shipment()->createLabel($label);
```

**Office-to-office**

```php
$label = new ShippingLabel(
    senderClient:       new ClientProfile(name: 'Иван Иванов', phones: ['0888888888']),
    receiverClient:     new ClientProfile(name: 'Богдан Богданов', phones: ['0878787878']),
    packCount:          1,
    shipmentType:       ShipmentType::PACK,
    weight:             2.5,
    senderOfficeCode:   '1034',               // sender drops off at this office
    receiverOfficeCode: '1127',               // receiver picks up at this office
    tariffSubCode:      TariffSubCode::OFFICE_OFFICE,
    shipmentDescription: 'Книги',
);
```

**How to find an office code**

```php
$offices = $client->office()->getOffices(countryCode: 'BGR', cityId: 41);
foreach ($offices as $office) {
    echo $office->getCode() . ' — ' . $office->getName() . PHP_EOL;
}
```

---

### Shipment Service — Price Calculation

```php
use Econt\EcontApi\Model\Shipment\ShippingLabel;
use Econt\EcontApi\Model\Shipment\ClientProfile;
use Econt\EcontApi\Enum\ShipmentType;

$label = new ShippingLabel(
    senderClient:        new ClientProfile(name: 'Иван Иванов', phones: ['0888888888']),
    senderAddress:       $senderAddress,
    receiverClient:      new ClientProfile(name: 'Богдан Богданов', phones: ['0878787878']),
    receiverAddress:     $receiverAddress,
    packCount:           1,
    shipmentType:        ShipmentType::PACK,
    weight:              2.5,
    shipmentDescription: 'Книги',
    mode:                'calculate',
);

$price = $client->shipment()->calculatePrice($label);
echo $price->totalPrice . ' ' . $price->currency; // e.g. "7.32 BGN"
```

### Shipment Service — Create & Cancel Label

```php
// Create
$label->mode = 'create';
$created = $client->shipment()->createLabel($label);
echo $created->waybillNumber;  // e.g. "1234567890"

// Cancel
$client->shipment()->cancelLabel($created->waybillNumber);
```

### Shipment Service — Other Operations

```php
// Confirm a label (after creation)
$client->shipment()->confirmLabel($waybillNumber);

// Update a label
$label->weight = 3.0;
$client->shipment()->updateLabel($label);

// Request courier pickup
use Econt\EcontApi\Model\Shipment\CourierRequest;

$request = new CourierRequest(
    shipmentWaybillNumber:  $waybillNumber,
    requestCourierTimeFrom: '09:00',
    requestCourierTimeTo:   '12:00',
);
$client->shipment()->requestCourier($request);
```

---

## Model Reference

### Key Models

| Model | Namespace | Description |
|-------|-----------|-------------|
| `Country` | `Model\Location` | Country with ISO codes, currency, phone code |
| `City` | `Model\Location` | City with post code, municipality, geo coords |
| `Street` | `Model\Location` | Street name (BG + EN) |
| `Quarter` | `Model\Location` | City quarter/district |
| `Address` | `Model\Location` | Full address with city, street, num, geo |
| `ValidatedAddress` | `Model\Location` | Address extended with `validationStatus` |
| `Office` | `Model\Office` | Econt office or Econtomat with full details |
| `ShippingLabel` | `Model\Shipment` | Complete shipment label with all parameters |
| `ClientProfile` | `Model\Shipment` | Sender or receiver contact details |
| `ShippingLabelServices` | `Model\Shipment` | Additional services (COD, insurance, SMS…) |
| `ReturnInstructionParams` | `Model\Shipment` | Return instructions configuration |
| `PackingListElement` | `Model\Shipment` | Item in a packing list |
| `PriceResult` | `Model\Result` | Calculated price with currency |

### Country Factory Methods

`Country` ships with built-in factories for the most common Econt markets:

```php
$bg = Country::bulgaria();  // BG / BGR
$ro = Country::romania();   // RO / ROU
$gr = Country::greece();    // GR / GRC

// Generic factory for any other country (id defaults to null)
$de = Country::create(code2: 'DE', code3: 'DEU', nameEn: 'Germany');
```

Use them anywhere a `Country` instance is needed — for example when building a `City` for a request payload:

```php
$city = City::create(country: Country::bulgaria(), postCode: '1000', name: 'София', nameEn: 'Sofia');
```

### Enums

```php
use Econt\EcontApi\Enum\ShipmentType;
// PACK, DOCUMENT, PALLET, CARGO, FURNITURE, ...

use Econt\EcontApi\Enum\TariffSubCode;
// OFFICE_TO_OFFICE, OFFICE_TO_DOOR, DOOR_TO_OFFICE, DOOR_TO_DOOR
```

### Array Serialisation

Every model supports `toArray()` / `fromArray()` using the original Econt API key names:

```php
$country = Country::fromArray([
    'code2'  => 'BG',
    'code3'  => 'BGR',
    'name'   => 'България',
    'nameEn' => 'Bulgaria',
]);

$array = $country->toArray();
// ['code2' => 'BG', 'code3' => 'BGR', 'name' => 'България', 'nameEn' => 'Bulgaria']
```

---

## Exception Handling

```php
use Econt\EcontApi\Exception\EcontException;
use Econt\EcontApi\Exception\EcontApiException;
use Econt\EcontApi\Exception\EcontNetworkException;
use Econt\EcontApi\Exception\EcontValidationException;

try {
    $offices = $client->office()->getOffices('BGR', 41);
} catch (EcontValidationException $e) {
    // Invalid input before any HTTP call is made
    echo implode(', ', $e->getViolations());
} catch (EcontApiException $e) {
    // API returned an error response
    echo $e->getApiErrorCode() . ': ' . $e->getApiErrorMessage();
} catch (EcontNetworkException $e) {
    // HTTP transport failure
    echo 'Network error: ' . $e->getMessage();
} catch (EcontException $e) {
    // Base catch-all for all library exceptions
}
```

---

## Running Tests

```bash
# Unit tests only (no network required)
composer test

# Integration tests (requires network access to demo.econt.com)
php vendor/bin/phpunit --group integration

# Static analysis (PHPStan level 6)
composer phpstan

# Code style check (PSR-12)
composer phpcs
```

> **Demo credentials** used in integration tests: `iasp-dev` / `1Asp-dev` against `https://demo.econt.com/ee/services`

---

## Project Structure

```
src/
├── EcontClient.php             # Main entry point
├── EcontConfiguration.php      # Configuration (URL, credentials, language)
├── Collection/                 # Typed iterable collections (CountryCollection, etc.)
├── Enum/
│   ├── ShipmentType.php
│   └── TariffSubCode.php
├── Exception/
│   ├── EcontException.php      # Base exception
│   ├── EcontApiException.php
│   ├── EcontNetworkException.php
│   └── EcontValidationException.php
├── Http/
│   ├── HttpAdapterInterface.php
│   ├── SymfonyHttpAdapter.php  # Default adapter
│   └── Psr18HttpAdapter.php    # PSR-18 adapter
├── Model/
│   ├── Location/               # Country, City, Street, Quarter, Address, ValidatedAddress
│   ├── Office/                 # Office
│   ├── Result/                 # PriceResult
│   └── Shipment/               # ShippingLabel, ClientProfile, CourierRequest, etc.
└── Service/
    ├── AddressService.php
    ├── OfficeService.php
    └── ShipmentService.php

tests/
├── Unit/                       # Mocked unit tests (64 tests)
└── Integration/                # Live API tests (requires network)
```

---

## Configuration Reference

```php
// Factory method for the demo/test endpoint (official demo credentials pre-filled)
$config = EcontConfiguration::forDemo();

// Override language or timeout while still targeting demo
$config = EcontConfiguration::forDemo(language: 'bg', timeout: 10);

// Full constructor for production
$config = new EcontConfiguration(
    username: 'your-username',
    password: 'your-password',
    baseUrl:  EcontConfiguration::PRODUCTION_URL,
    timeout:  30,
    language: 'bg'  // 'bg' (Bulgarian) or 'en' (English)
);
```

| Constant | Value |
|----------|-------|
| `EcontConfiguration::PRODUCTION_URL` | `https://ee.econt.com/services` |
| `EcontConfiguration::DEMO_URL` | `https://demo.econt.com/ee/services` |

---

## License

MIT License. See [LICENSE](LICENSE) for details.

