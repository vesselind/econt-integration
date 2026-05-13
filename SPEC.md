# Econt API PHP Library Specification

## 1. Project Overview

**Project Name:** `econt/econt-api` (or similar composer-compatible name)
**Type:** Standalone PHP Library
**Core Functionality:** PHP integration library for Econt (Bulgarian delivery company) API, providing all services described in the Econt documentation.
**Target Users:** PHP/Symfony developers integrating Econt delivery services into e-commerce platforms.

---

## 2. Technology Stack

| Requirement | Value |
|-------------|-------|
| PHP Version | 8.1+ |
| Symfony Compatibility | Yes (4.4+) |
| Serializer | `symfony/serializer` |
| HTTP Client | PSR-18 compatible (curl, symfony/http-client) |
| Composer | Yes, PSR-4 autoloading |

---

## 3. Architecture Principles

### SOLID Compliance
- **S**ingle Responsibility: Each class has one job
- **O**pen/Closed: Models and services are extensible via interfaces
- **L**iskov Substitution: Contracts (interfaces) define behavior
- **I**nterface Segregation: Small, focused interfaces
- **D**ependency Inversion: Depend on abstractions, not implementations

### Design Patterns
- Service classes for API operations (e.g., `OfficeService`, `ShipmentService`)
- Model classes for DTOs (Data Transfer Objects)
- Contract interfaces for all services
- Factory pattern for client construction
- Strategy pattern for serializer handling

---

## 4. Model Classes (DTOs)

### Naming Convention
- Properties: **camelCase** (e.g., `firstName`, `postalCode`)
- Classes: **PascalCase** (e.g., `Office`, `Address`)
- XML/JSON mapping via Symfony Serializer attributes

### Model Requirements
1. All properties have type hints
2. Nullable properties explicitly typed
3. Serializable/deserializable via Symfony Serializer
4. Convertible to/from array via `toArray()` method
5. Static factory methods for construction

### Base Model Structure
```php
class AbstractModel {
    public function toArray(): array;
    public static function fromArray(array $data): static;
}
```

---

## 5. API Services (Priority Order)

### Phase 1: Offices (HIGHEST PRIORITY)
```
Services.ExchangeRates.requestOfficesExDay() - offices by city
Services.ExchangeRates.requestOffices() - all offices
Services.ExchangeRates.requestOffice() - single office by name/address
```

### Phase 2: Addresses & Locations
```
requestCountries() - list of countries
requestCities() - cities by country
requestStreets() - streets in a city
requestQuarers() - quarters/neighborhoods in a city
```

### Phase 3: Shipments (Waybills)
```
createBill() - create shipment
confirmBill() - confirm created bill
cancelBill() - cancel/delete bill
updateBill() - update bill data
trackBill() - track shipment status
requestCourier() - request pickup
```

### Phase 4: Payments
```
setPay() - manage payment information
```

---

## 6. Client Structure

```
EcontClient
├── Configuration (username, password, baseUrl, timeout)
├── HttpClient (PSR-18)
├── Serializer (symfony/serializer)
├── Services:
│   ├── OfficeService (HIGHEST PRIORITY - implement first)
│   ├── AddressService
│   ├── ShipmentService
│   └── PaymentService
```

---

## 7. Response Handling

### Standard Response Format
```php
class EcontResponse {
    public bool $isSuccess;
    public ?string $errorMessage;
    public ?string $errorCode;
    public mixed $data; // Model or array
}
```

### Error Handling
- Network errors: `EcontNetworkException`
- API errors: `EcontApiException` (contains error code/message)
- Validation errors: `EcontValidationException`

---

## 8. Configuration

```php
class EcontConfiguration {
    public string $username;
    public string $password;
    public string $baseUrl; // demo or production
    public int $timeout;
    public string $language; // 'bg' or 'en'
}
```

---

## 9. Directory Structure

```
src/
├── Client/
│   ├── EcontClient.php
│   └── EcontClientInterface.php
├── Configuration/
│   └── EcontConfiguration.php
├── Exception/
│   ├── EcontException.php
│   ├── EcontApiException.php
│   ├── EcontNetworkException.php
│   └── EcontValidationException.php
├── Model/
│   ├── AbstractModel.php
│   ├── Address/
│   │   ├── Address.php
│   │   ├── Country.php
│   │   ├── City.php
│   │   ├── Street.php
│   │   └── Quarter.php
│   ├── Office/
│   │   ├── Office.php
│   │   ├── OfficeAddress.php
│   │   └── OfficeWorkHours.php
│   ├── Shipment/
│   │   ├── Shipment.php
│   │   ├── ShipmentParty.php
│   │   └── ShipmentItem.php
│   └── Response/
│       └── EcontResponse.php
├── Service/
│   ├── Contract/
│   │   ├── ServiceInterface.php
│   │   ├── OfficeServiceInterface.php
│   │   ├── AddressServiceInterface.php
│   │   ├── ShipmentServiceInterface.php
│   │   └── PaymentServiceInterface.php
│   ├── OfficeService.php (PRIORITY)
│   ├── AddressService.php
│   ├── ShipmentService.php
│   └── PaymentService.php
└── Serializer/
    └── EcontSerializer.php
```

---

## 10. Implementation Phases

### Phase 1: Core & Offices
- [ ] composer.json setup
- [ ] AbstractModel base class
- [ ] EcontConfiguration
- [ ] EcontClient
- [ ] Exception classes
- [ ] Office models (Office, OfficeAddress, OfficeWorkHours)
- [ ] OfficeServiceInterface
- [ ] OfficeService implementation
- [ ] Basic unit tests for OfficeService

### Phase 2: Address Services
- [ ] Address models (Country, City, Street, Quarter, Address)
- [ ] AddressServiceInterface
- [ ] AddressService implementation

### Phase 3: Shipment Services
- [ ] Shipment models (Shipment, ShipmentParty, ShipmentItem)
- [ ] ShipmentServiceInterface
- [ ] ShipmentService implementation

### Phase 4: Payment Services
- [ ] PaymentServiceInterface
- [ ] PaymentService implementation

### Phase 5: Integration
- [ ] Symfony Bundle (optional, for Symfony users)
- [ ] Full integration tests
- [ ] Documentation (README)

---

## 11. Coding Standards

### PSR-12 Compliance
- 4 spaces for indentation
- PHP opening tag `<?php`
- Namespace: `Econt\EcontApi`
- Strict types declaration in all files

### Code Style
- Public methods: camelCase
- Private/protected methods: camelCase with underscore prefix
- Constants: UPPER_SNAKE_CASE
- Interfaces: suffix with `Interface`
- Abstract classes: prefix with `Abstract`

### Documentation
- All public methods must have docblocks
- `@param` and `@return` tags required
- `@throws` for exception-prone methods

---

## 12. Constraints & Notes

1. **No hardcoded credentials** - always use Configuration
2. **Demo URL:** `https://demo.econt.com/ee/services/`
3. **Production URL:** `https://ee.econt.com/services/`
4. **Default credentials for demo:** username=`iasp-dev`, password=`1Asp-dev`
5. **All API responses should be modeled as DTOs**
6. **Library must work without Symfony** (standalone)
7. **Symfony bundle optional for framework integration**