# Research: PHP Econt Integration Library

**Phase 0 output for**: `001-econt-php-library`
**Generated**: 2025-01-01

---

## 1. API Protocol & Authentication

**Decision**: HTTP POST with JSON bodies + HTTP Basic Authentication.

**Rationale**: Confirmed in `docs/SOAPJSON API.md`. Every request is a POST; credentials are passed as Basic Auth header. SSL is supported; client-certificate auth is not.

**Demo credentials**: `iasp-dev` / `1Asp-dev` → `https://demo.econt.com/ee/services/`
**Production base**: `https://ee.econt.com/services/`

**Alternatives considered**: OAuth2 / API keys — not supported by Econt API v1.0.

---

## 2. Confirmed API Endpoint Catalogue

All endpoints are relative to the base URL. Path pattern:
- `Nomenclatures/NomenclaturesService.{method}.json`
- `Shipments/LabelService.{method}.json`

| Library method | HTTP path | Request payload keys |
|---|---|---|
| `getCountries()` | `Nomenclatures/NomenclaturesService.getCountries.json` | `{"GetCountriesRequest":""}` |
| `getCities($countryCode)` | `Nomenclatures/NomenclaturesService.getCities.json` | `{"countryCode":"BGR"}` |
| `getStreets($cityId)` | `Nomenclatures/NomenclaturesService.getStreets.json` | `{"cityID":"41"}` |
| `getQuarters($cityId)` | `Nomenclatures/NomenclaturesService.getQuarters.json` | `{"cityID":"41"}` |
| `getOffices($countryCode, $cityId)` | `Nomenclatures/NomenclaturesService.getOffices.json` | `{"countryCode":"BGR","cityID":"47"}` |
| `validateAddress($address)` | `Nomenclatures/NomenclaturesService.validateAddress.json` | `{"address":{...}}` |
| `createLabel($label)` | `Shipments/LabelService.createLabel.json` | `{"label":{...},"mode":"create"}` |
| `validateLabel($label)` | `Shipments/LabelService.createLabel.json` | `{"label":{...},"mode":"validate"}` |
| `calculatePrice($label)` | `Shipments/LabelService.createLabel.json` | `{"label":{...},"mode":"calculate"}` |
| `confirmLabel($waybill)` | `Shipments/LabelService.processLabel.json` | `{"waybillNumber":"..."}` |
| `updateLabel($waybill, $label)` | `Shipments/LabelService.createLabel.json` | `{"label":{...},"mode":"create","waybillNumber":"..."}` |
| `cancelLabel($waybill)` | `Shipments/LabelService.deleteLabel.json` | `{"waybillNumber":"..."}` |
| `trackShipment($waybill)` | `Shipments/LabelService.getWaybillContents.json` | `{"waybillNumber":"..."}` |
| `requestCourier($request)` | `Shipments/LabelService.requestCourier.json` | `{...CourierRequest fields...}` |

**Rationale**: Endpoint patterns confirmed from JSON example calls in docs. The `createLabel`, `validateLabel`, and `calculatePrice` share one endpoint, differentiated by the `mode` field.

---

## 3. Response JSON Structure (confirmed from docs)

### `getCountries` response
```json
{
  "countries": [
    {"id": null, "code2": "BG", "code3": "BGR", "name": "България", "nameEn": "Bulgaria", "isEU": true}
  ]
}
```

### `getCities` response
```json
{
  "cities": [
    {
      "id": 204964,
      "country": {"id": null, "code2": "LU", "code3": "LUX", "name": "Люксембург", "nameEn": "Luxembourg", "isEU": true},
      "postCode": "0", "name": "LUXEMBOURG", "nameEn": "LUXEMBOURG",
      "regionName": "", "regionNameEn": "", "phoneCode": "0",
      "location": null, "expressCityDeliveries": false
    }
  ]
}
```

### `getOffices` response (key fields)
```json
{
  "offices": [{
    "id": 839, "code": "9707", "isMPS": false, "isAPS": false,
    "name": "Шумен Осми март", "nameEn": "Shumen Osmi mart",
    "phones": [], "e-mails": [],
    "address": {
      "id": null,
      "city": {"id": 47, "country": {...}, "postCode": "9700", "name": "Шумен", "nameEn": "Shumen", ...},
      "fullAddress": "...", "quarter": "...", "street": "...", "num": "22", "other": "",
      "location": {"latitude": 43.26, "longitude": 26.93, "confidence": 3},
      "zip": null
    },
    "info": "...", "currency": "BGN", "language": "bg",
    "normalBusinessHoursFrom": 1524117600000, "normalBusinessHoursTo": 1524150000000,
    "halfDayBusinessHoursFrom": 1524117600000, "halfDayBusinessHoursTo": 1524132000000,
    "shipmentTypes": ["courier", "post", "cargo"],
    "partnerCode": "", "hubCode": "9709", "hubName": "Шумен", "hubNameEn": "Shumen"
  }]
}
```

### `validateAddress` response
```json
{
  "address": {
    "id": null, "city": {...},
    "fullAddress": "ул. Славянска 16", "quarter": "", "street": "ул. Славянска", "num": "16",
    "other": null, "location": {"latitude": 43.84, "longitude": 25.94, "confidence": 3}, "zip": null
  },
  "validationStatus": "normal"
}
```

---

## 4. HTTP Adapter Strategy

**Decision**: Dual-adapter design behind a single `HttpAdapterInterface`.

- **Primary** (`SymfonyHttpAdapter`): Wraps `symfony/http-client` (`Symfony\Contracts\HttpClient\HttpClientInterface`). Used by default in Symfony projects.
- **Fallback** (`Psr18HttpAdapter`): Wraps any `Psr\Http\Client\ClientInterface` + `Psr\Http\Message\RequestFactoryInterface` (supplied via `nyholm/psr7`). Enables standalone usage.

**Rationale**: Both are declared in `composer.json` already. PSR-18 interface is the industry standard for interchangeable HTTP clients. The abstraction means service classes need not know which transport is used.

**Alternatives considered**: Guzzle-only approach — rejected because it would add a heavy dependency and conflict with Symfony's own client in Symfony apps.

---

## 5. Serialisation / Deserialisation

**Decision**: Manual `fromArray(array $data): static` and `toArray(): array` on every model — no Symfony Serializer in the public contract.

**Rationale**:
- FR-304/FR-305 require `fromArray` / `toArray` on every model.
- `symfony/serializer` is available as a dependency (`composer.json`) but must not be exposed as a required consumer dependency (FR-305).
- For complex nested structures, manual mapping is more predictable and avoids annotation overhead.
- `symfony/serializer` may still be used optionally inside factory helpers if it simplifies deep deserialization.

**Alternatives considered**: Full Symfony Serializer with attributes — adds coupling to Symfony annotation ecosystem; rejected.

---

## 6. Exception Strategy

**Decision**: Three concrete exception classes all extending a base `EcontException`:

| Class | When thrown | Extra data |
|---|---|---|
| `EcontApiException` | API returns a business error (any HTTP 200 with `error` field, or 4xx) | `apiErrorCode`, `apiErrorMessage` |
| `EcontNetworkException` | HTTP transport failure (timeout, DNS, SSL, 5xx server error) | Wraps `\Throwable $previous` |
| `EcontValidationException` | Consumer passes invalid data before any HTTP call | `string[] $violations` (field names) |

**Rationale**: Confirmed by spec FR-1001 through FR-1004 and edge-case list. Every public method's `@throws` annotation will list the applicable subset.

---

## 7. Collection Pattern

**Decision**: Typed collection classes (`CountryCollection`, `CityCollection`, `StreetCollection`, `QuarterCollection`, `OfficeCollection`) each wrapping `array<int, ModelType>` with `ArrayAccess`, `Countable`, and `IteratorAggregate`.

**Rationale**: Provides typed return values without PHP generics. Service methods return `XCollection` rather than `array`, giving consumers IDE auto-completion and ensuring correct element types. Empty collections are returned for empty API results (not exceptions).

---

## 8. ShipmentType / TariffSubCode Enums

**Decision**: PHP 8.1+ backed string enums.

```php
enum ShipmentType: string {
    case DOCUMENT = 'document';      // note: API sends lowercase
    case PACK = 'pack';
    case POST_PACK = 'post_pack';
    case PALLET = 'pallet';
    case CARGO = 'cargo';
    case DOCUMENTPALLET = 'documentpallet';
    case BIG_LETTER = 'big_letter';
    case SMALL_LETTER = 'small_letter';
    case MONEY_TRANSFER = 'money_transfer';
}
```

> **Note**: The Econt JSON API documentation shows values such as `"PACK"`, `"document"`, and `"post_pack"` in different places. The SOAP/JSON table uses lowercase (`pack`, `document`, `post_pack`), while the raw JSON examples use uppercase (`"PACK"`). Implementation must handle case-insensitive matching; `fromArray` should use `ShipmentType::from(strtolower($value))`.

**TariffSubCode**:
```php
enum TariffSubCode: string {
    case DOOR_DOOR = 'door-door';
    case DOOR_OFFICE = 'door-office';
    case OFFICE_DOOR = 'office-door';
    case OFFICE_OFFICE = 'office-office';
    case DOOR_BANK = 'door-bank';
    case OFFICE_BANK = 'office-bank';
}
```

---

## 9. Business Hours Representation

**Decision**: Store as `int|null` Unix millisecond timestamps (raw API values). Add optional helper methods `normalBusinessHoursFromAsDateTime(): ?\DateTimeImmutable` by dividing by 1000.

**Rationale**: FR-504 mandates matching raw API values. Helpers provide ergonomic access without breaking the contract. The API example shows `1524117600000` (milliseconds).

---

## 10. Namespace & Autoloading

**Decision**: `Econt\EcontApi\` PSR-4 from `src/`. Tests under `Econt\EcontApi\Tests\` from `tests/`.

**Rationale**: Already declared in `composer.json`. Every file starts with `declare(strict_types=1)` (FR-1205).

---

## 11. Test Strategy

**Decision**: Two PHPUnit test suites, separated in `phpunit.xml`:
- `unit` — Uses mock/fake HTTP adapters; no real network. Target ≥ 80% line coverage.
- `integration` — Makes live calls to `https://demo.econt.com/ee/services/`. Excluded from default CI by `@group integration` annotation and separate `phpunit.xml` suite.

**Alternatives considered**: Single test suite with VCR cassettes — adds `php-vcr` dependency; rejected in favour of simpler dual-suite approach.

---

## 12. Saturday Delivery Constraint

**Decision**: Library does not enforce Saturday delivery business rules. The API enforces them and returns `EcontApiException`. The `City` model exposes `courierRequestBeginTimeSaturday` / `courierRequestEndTimeSaturday` as read-only fields for consumers to check before submitting.

---

## 13. Dependencies Resolution

All required packages already declared in `composer.json`:

| Package | Version | Role |
|---|---|---|
| `symfony/http-client` | `^6\|^7\|^8` | Default HTTP transport |
| `psr/http-client` | `^1.0` | PSR-18 interface |
| `psr/http-factory` | `^1.0` | PSR-17 request factory |
| `psr/log` | `^3.0` | Optional PSR-3 logger |
| `nyholm/psr7` | `^1.8` | PSR-7 message implementations |
| `symfony/serializer` | `^6\|^7\|^8` | Internal (de)serialisation helper |
| `phpunit/phpunit` | `^10\|^11` | Test runner |
| `phpstan/phpstan` | `^1\|^2` | Level 6 static analysis |
| `squizlabs/php_codesniffer` | `^3.7` | PSR-12 code style |

No additional dependencies required.

