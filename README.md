# Econt Integration Library

PHP library for integrating with [Econt](https://www.econt.com/) API - Bulgarian delivery company services.

## Requirements

- PHP 8.2+
- Symfony Serializer 6.0+
- PSR-18 compatible HTTP client

## Installation

This package is available on [Packagist](https://packagist.org/packages/vesselind/econt-integration) but can also be installed directly from GitHub.

### Via Packagist (recommended when published)

```bash
composer require vesselind/econt-integration
```

### Via GitHub Repository

Add this to your `composer.json`:

```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/vesselind/econt-integration"
        }
    ],
    "require": {
        "vesselind/econt-integration": "^1.0"
    }
}
```

Then run:
```bash
composer update vesselind/econt-integration
```

Or use Composer directly:
```bash
composer require vesselind/econt-integration:dev-main --repository='{"type": "vcs", "url": "https://github.com/vesselind/econt-integration"}'
```

## Quick Start

### 1. Create Configuration

```php
use Econt\EcontApi\Configuration\EcontConfiguration;

// Demo mode (testing)
$config = EcontConfiguration::demo();

// Production mode
$config = EcontConfiguration::production('your_username', 'your_password');
```

### 2. Create Client

```php
use Econt\EcontApi\Client\EcontClient;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\HttpClient\Psr18Client;

// Create HTTP client (any PSR-18 compatible)
$httpClient = new Psr18Client();

// Create serializer
$serializer = new Serializer([new ObjectNormalizer()], [new XmlEncoder()]);

// Create Econt client
$client = new EcontClient($config, $httpClient, $serializer);
```

### 3. Use Services

```php
use Econt\EcontApi\Service\OfficeService;
use Econt\EcontApi\Service\AddressService;
use Econt\EcontApi\Service\ShipmentService;
use Econt\EcontApi\Service\PaymentService;

// Create service instances
$officeService = new OfficeService($client);
$addressService = new AddressService($client);
$shipmentService = new ShipmentService($client);
$paymentService = new PaymentService($client);
```

## Services

### Office Service

```php
use Econt\EcontApi\Service\OfficeService;

$officeService = new OfficeService($client);

// Get all offices
$offices = $officeService->getOffices();

// Get offices in a specific city
$sofiaOffices = $officeService->getOffices('Sofia', 'BG');

// Get single office by code
$office = $officeService->getOffice('BGR001');

// Raw API response
$response = $officeService->requestOffices();
```

**Office Model:**

```php
use Econt\EcontApi\Model\Office\Office;
use Econt\EcontApi\Model\Office\OfficeAddress;

$office->getOfficeCode();      // "BGR001"
$office->getOfficeName();      // "София Център"
$office->getPhone();           // "+35921234567"
$office->getEmail();           // "sofia@econt.bg"

// Address details
$address = $office->getAddress();
$address->getCity();           // "Sofia"
$address->getStreet();         // "Vitosha"
$address->getStreetNum();      // "10"
$address->getLatitude();       // 42.6977
$address->getLongitude();      // 23.3219

// Working hours
$workHours = $office->getWorkHours();
foreach ($workHours as $hours) {
    $hours->getDayOfWeek();    // "Monday"
    $hours->getFromHour();     // "08:00"
    $hours->getToHour();       // "18:00"
}
```

### Address Service

```php
use Econt\EcontApi\Service\AddressService;

$addressService = new AddressService($client);

// Get countries
$countries = $addressService->getCountries();
$bg = $addressService->getCountry('BG');

// Get cities by country
$cities = $addressService->getCities('BG');
$cities = $addressService->getCities('BG', 'Sofia', true); // filter by name, only with office

// Get quarters in a city
$quarters = $addressService->getQuarters('68134'); // cityId
$quarters = $addressService->getQuarters('68134', 'Center'); // filter by name

// Get streets in a city
$streets = $addressService->getStreets('68134');
$streets = $addressService->getStreets('68134', 'Vitosha'); // filter by name
```

**Address Model:**

```php
use Econt\EcontApi\Model\Address\Address;

$address = new Address(
    '68134',         // cityId
    'Sofia',         // cityName
    null,            // quarterId
    'Center',        // quarterName
    null,            // streetId
    'Vitosha',       // streetName
    'bul.',          // streetType
    '10',            // streetNum
    '5',             // buildingNum
    'A',             // entranceNum
    '3',             // floorNum
    '12'             // apartmentNum
);

// Format as Bulgarian address string
echo $address->toFormattedString();
// Output: "bul. Vitosha 10, бл. 5, вх. A, ет. 3, ап. 12, Sofia"
```

### Shipment Service

```php
use Econt\EcontApi\Service\ShipmentService;
use Econt\EcontApi\Model\Shipment\Shipment;
use Econt\EcontApi\Model\Shipment\ShipmentParty;
use Econt\EcontApi\Model\Shipment\ShipmentItem;
use Econt\EcontApi\Model\Address\Address;

$shipmentService = new ShipmentService($client);

// Create sender and receiver
$sender = new ShipmentParty();
$sender->setName('John Doe')
    ->setPhone('+359888111111')
    ->setEmail('john@example.com')
    ->setOfficeCode('BGR001'); // Econt office for pickup

$receiver = new ShipmentParty();
$receiver->setName('Jane Smith')
    ->setPhone('+359888222222')
    ->setAddress(new Address(
        '56784',         // Plovdiv cityId
        'Plovdiv',       // cityName
        null, null, null, null, null, '15' // street num
    ));

// Create shipment item
$item = new ShipmentItem();
$item->setKeyId('ITEM001')
    ->setDescription('Electronics - Phone')
    ->setQuantity(1)
    ->setWeight(0.5)
    ->setPrice(999.99)
    ->setIsFragile(true);

// Create shipment
$shipment = new Shipment();
$shipment->setSender($sender)
    ->setReceiver($receiver)
    ->setPayment('pay_before')
    ->setDeclaredValue(999.99)
    ->setCurrency('BGN')
    ->setWeight('0.5')
    ->setDescription('Phone shipment')
    ->setItems([$item])
    ->setLoadType('package')
    ->setService('EXPRESS')
    ->setServiceType('DOOR_TO_DOOR')
    ->setIsCod(true)
    ->setCodValue(999.99)
    ->setCodCurrency('BGN');

// Create waybill
$response = $shipmentService->createBill($shipment);

if ($response->isSuccess()) {
    $data = $response->getData();
    $billGuid = $data['guid'] ?? null;

    // Confirm waybill
    $confirmResponse = $shipmentService->confirmBill($billGuid);
}

// Track shipment
$trackResponse = $shipmentService->trackBill($billGuid);

// Cancel waybill
$cancelResponse = $shipmentService->cancelBill($billGuid);

// Calculate shipment price
$calcResponse = $shipmentService->getShipmentCalculation($shipment);

// Request courier pickup
$courrierResponse = $shipmentService->requestCourier(
    '2024-01-15',
    '09:00',
    '12:00'
);
```

### Payment Service

```php
use Econt\EcontApi\Service\PaymentService;

$paymentService = new PaymentService($client);

// Process payment
$response = $paymentService->setPay($billGuid, 'cash', 50.00);

// Check payment status
$statusResponse = $paymentService->getPayStatus($billGuid);

// Create invoice
$invoiceResponse = $paymentService->createInvoice($billGuid);

// Get invoice
$invoiceResponse = $paymentService->getInvoice($invoiceId);
```

## Response Handling

All service methods return an `EcontResponse` object:

```php
use Econt\EcontApi\Model\Response\EcontResponse;

$response = $officeService->getOffices();

if ($response->isSuccess()) {
    $data = $response->getData();
    // Process successful response
} else {
    $errorMessage = $response->getErrorMessage();
    $errorCode = $response->getErrorCode();
    // Handle error
}

// Get raw data as array
$array = $response->getData(); // returns array or null
```

## Error Handling

```php
use Econt\EcontApi\Exception\EcontApiException;
use Econt\EcontApi\Exception\EcontNetworkException;
use Econt\EcontApi\Exception\EcontValidationException;

try {
    $response = $shipmentService->createBill($shipment);

    if (!$response->isSuccess()) {
        throw new EcontApiException(
            $response->getErrorCode(),
            $response->getErrorMessage()
        );
    }
} catch (EcontNetworkException $e) {
    // Network connectivity issues
    echo "Network error: " . $e->getMessage();
} catch (EcontApiException $e) {
    // API returned an error
    echo "API error [{$e->getErrorCode()}]: " . $e->getErrorMessage();
} catch (EcontValidationException $e) {
    // Validation failed
    $errors = $e->getValidationErrors();
    print_r($errors);
}
```

## Working with Models

All models support `toArray()` and `fromArray()` for serialization:

```php
use Econt\EcontApi\Model\Office\Office;

// Create from array (e.g., from API response)
$office = Office::fromArray($apiResponseArray);

// Convert to array (e.g., for logging or caching)
$array = $office->toArray();

// All properties are camelCase
$array['officeCode']  // not office_code
$array['officeName']  // not office_name
```

## Symfony Integration

For Symfony applications, create services in `services.yaml`:

```yaml
services:
    Econt\EcontApi\Client\EcontClient:
        arguments:
            $configuration: '@econt.configuration'
            $httpClient: '@psr18.http_client'
            $serializer: '@econt.serializer'

    Econt\EcontApi\Service\OfficeService:
        arguments:
            $client: '@Econt\EcontApi\Client\EcontClient'

    Econt\EcontApi\Service\AddressService:
        arguments:
            $client: '@Econt\EcontApi\Client\EcontClient'

    Econt\EcontApi\Service\ShipmentService:
        arguments:
            $client: '@Econt\EcontApi\Client\EcontClient'

    Econt\EcontApi\Service\PaymentService:
        arguments:
            $client: '@Econt\EcontApi\Client\EcontClient'

    econt.configuration:
        class: Econt\EcontApi\Configuration\EcontConfiguration
        factory: ['Econt\EcontApi\Configuration\EcontConfiguration', 'demo']

    econt.serializer:
        class: Symfony\Component\Serializer\Serializer
        arguments:
            - ['@serializer.normalizer']
            - ['@serializer.encoder.xml']
```

Or use the factory pattern:

```php
use Econt\EcontApi\Configuration\EcontConfiguration;

// In your service configuration
$config = EcontConfiguration::production(
    $_ENV['ECONT_USERNAME'],
    $_ENV['ECONT_PASSWORD']
)->withLanguage('en'); // Change language if needed
```

## Local Development & Contributing

### Cloning the Repository

```bash
git clone https://github.com/vesselind/econt-integration.git
cd econt-integration
composer install
```

### Running Tests

```bash
# Run PHPUnit tests
.\vendor\bin\phpunit

# Run PHPStan static analysis
.\vendor\bin\phpstan analyse src --level=6

# Run PHP CodeSniffer (PSR-12)
.\vendor\bin\phpcs src --standard=PSR12
```

### Using Local Path Repository (for testing in another project)

In your **other project's** `composer.json`:

```json
{
    "repositories": [
        {
            "type": "path",
            "url": "/path/to/econt-integration"
        }
    ],
    "require": {
        "vesselind/econt-integration": "*@dev"
    }
}
```

Then: `composer update vesselind/econt-integration`

### Running Tests

```bash
# Install dependencies
composer install

# Run PHPUnit tests
composer test

# Run PHPStan static analysis
composer phpstan

# Run PHP CodeSniffer (PSR-12)
composer phpcs
```

### Testing Against Demo API

The library uses Econt's demo environment by default. Demo credentials:
- **Username:** `iasp-dev`
- **Password:** `1Asp-dev`

```php
use Econt\EcontApi\Configuration\EcontConfiguration;
use Econt\EcontApi\Client\EcontClient;
use Econt\EcontApi\Service\OfficeService;
use Symfony\Component\HttpClient\Psr18Client;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

$config = EcontConfiguration::demo();
$httpClient = new Psr18Client();
$serializer = new Serializer([new ObjectNormalizer()], [new XmlEncoder()]);
$client = new EcontClient($config, $httpClient, $serializer);

$officeService = new OfficeService($client);

// Test against real demo API
$offices = $officeService->getOffices('Sofia', 'BG');

foreach ($offices as $office) {
    echo $office->getOfficeName() . "\n";
}
```

## API Endpoints

- **Demo:** `https://demo.econt.com/ee/services/`
- **Production:** `https://ee.econt.com/services/`

## License

MIT