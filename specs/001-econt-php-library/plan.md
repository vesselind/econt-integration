# Implementation Plan: PHP Econt Integration Library

**Branch**: `main` | **Date**: 2025-01-01 | **Spec**: [spec.md](spec.md)

**Input**: Feature specification from `/specs/001-econt-php-library/spec.md`

---

## Summary

Build a standalone, reusable PHP 8.2+ library that wraps the Econt SOAP/JSON API (v1.0). The library exposes three service objects (`AddressService`, `OfficeService`, `ShipmentService`) behind a single `EcontClient` entry point with pluggable HTTP adapters (Symfony HTTP client as default, any PSR-18 client as fallback). All API request/response structures are mapped to typed PHP model classes with `fromArray` / `toArray` serialisation. The library must meet ≥ 80 % PHPUnit unit-test coverage plus live integration tests against the demo API, pass PHPStan level-6, and comply with PSR-12.

---

## Technical Context

**Language/Version**: PHP 8.2+ (minimum declared in `composer.json`)

**Primary Dependencies**:
- `symfony/http-client` ^6|^7|^8 — default HTTP transport
- `psr/http-client` ^1.0 — PSR-18 interface for alternative adapters
- `psr/http-factory` ^1.0 — PSR-17 request factory
- `psr/log` ^3.0 — optional PSR-3 logger injection
- `nyholm/psr7` ^1.8 — PSR-7 message implementations
- `symfony/serializer` ^6|^7|^8 — internal (de)serialisation helper
- PHPUnit ^10|^11, PHPStan ^1|^2, phpcs (PSR-12)

**Storage**: None (stateless HTTP library)

**Testing**: PHPUnit — `unit` suite (mocked) + `integration` suite (live demo API)

**Target Platform**: PHP 8.2+ on any OS; Symfony 6/7/8 compatible

**Project Type**: PHP library (PSR-4, Composer package)

**Performance Goals**: All integration tests complete < 30 s on normal network (SC-003)

**Constraints**:
- No environment-variable credential reads (FR-202)
- PSR-12 zero violations (SC-009)
- PHPStan level 6 zero errors (SC-008)
- `declare(strict_types=1)` in every PHP file (FR-1205)

**Scale/Scope**: ~40 PHP source files, ~30 test files

---

## Constitution Check

*GATE: Constitution file is a placeholder template (not yet ratified). No active gates to evaluate. Proceeding with principles inferred from spec requirements.*

| Concern | Status | Notes |
|---|---|---|
| Library-first design | ✅ PASS | Pure library, no framework coupling in public API |
| Testability | ✅ PASS | Interfaces + adapters enable full mock-based unit testing |
| PSR standards | ✅ PASS | PSR-4 autoloading, PSR-12 style, PSR-3 logger, PSR-18 client |
| SOLID principles | ✅ PASS | Each service = single responsibility; adapter interface = OCP/DIP |
| Exception safety | ✅ PASS | All exceptions are typed; no raw `\Exception` escapes |

---

## Project Structure

### Documentation (this feature)

```text
specs/001-econt-php-library/
├── plan.md              # This file (/speckit.plan command output)
├── research.md          # Phase 0 output — API research & decisions
├── data-model.md        # Phase 1 output — entity specifications
├── quickstart.md        # Phase 1 output — installation & usage guide
├── contracts/
│   └── econt-api-contract.md   # All API endpoint schemas
└── tasks.md             # Phase 2 output (/speckit.tasks command — NOT created here)
```

### Source Code (repository root)

```text
src/
├── EcontClient.php                        # Main entry point; exposes service getters
├── EcontConfiguration.php                 # Config DTO (credentials, baseUrl, timeout, language)
│
├── Http/
│   ├── HttpAdapterInterface.php           # post(string $endpoint, array $payload): array
│   ├── SymfonyHttpAdapter.php             # Wraps Symfony HttpClientInterface
│   └── Psr18HttpAdapter.php              # Wraps PSR-18 ClientInterface + PSR-17 factory
│
├── Service/
│   ├── AddressService.php                 # getCountries, getCities, getStreets, getQuarters, validateAddress
│   ├── OfficeService.php                  # getOffices
│   └── ShipmentService.php               # createLabel, validateLabel, calculatePrice, confirmLabel,
│                                         #   updateLabel, cancelLabel, trackShipment, requestCourier
│
├── Model/
│   ├── Location/
│   │   ├── Country.php
│   │   ├── City.php
│   │   ├── Street.php
│   │   ├── Quarter.php
│   │   ├── Address.php
│   │   ├── GeoLocation.php
│   │   └── ValidatedAddress.php          # extends Address + validationStatus
│   ├── Office/
│   │   └── Office.php
│   ├── Shipment/
│   │   ├── ShippingLabel.php
│   │   ├── ClientProfile.php
│   │   ├── ShippingLabelServices.php
│   │   ├── ReturnInstructionParams.php
│   │   ├── Instruction.php
│   │   ├── PackingListElement.php
│   │   └── CourierRequest.php
│   └── Result/
│       ├── PriceCalculationResult.php
│       ├── ShipmentLabelResult.php
│       ├── ConfirmLabelResult.php
│       ├── CancelLabelResult.php
│       ├── ShipmentTrackingResult.php
│       └── CourierRequestResult.php
│
├── Collection/
│   ├── AbstractCollection.php             # ArrayAccess + Countable + IteratorAggregate base
│   ├── CountryCollection.php
│   ├── CityCollection.php
│   ├── StreetCollection.php
│   ├── QuarterCollection.php
│   └── OfficeCollection.php
│
├── Enum/
│   ├── ShipmentType.php                   # Backed string enum
│   └── TariffSubCode.php                  # Backed string enum
│
└── Exception/
    ├── EcontException.php                 # Base exception
    ├── EcontApiException.php              # + apiErrorCode, apiErrorMessage
    ├── EcontNetworkException.php          # + wraps transport Throwable
    └── EcontValidationException.php       # + violations: string[]

tests/
├── Unit/
│   ├── Service/
│   │   ├── AddressServiceTest.php
│   │   ├── OfficeServiceTest.php
│   │   └── ShipmentServiceTest.php
│   ├── Model/
│   │   ├── CountryTest.php
│   │   ├── CityTest.php
│   │   ├── StreetTest.php
│   │   ├── QuarterTest.php
│   │   ├── AddressTest.php
│   │   ├── OfficeTest.php
│   │   ├── ShippingLabelTest.php
│   │   ├── ClientProfileTest.php
│   │   └── ShippingLabelServicesTest.php
│   └── Http/
│       ├── SymfonyHttpAdapterTest.php
│       └── Psr18HttpAdapterTest.php
└── Integration/
    ├── AddressServiceIntegrationTest.php  # @group integration
    ├── OfficeServiceIntegrationTest.php   # @group integration
    └── ShipmentServiceIntegrationTest.php # @group integration
```

**Structure Decision**: Single-project PHP library layout. No frontend or mobile layers. `src/` is PSR-4 root for `Econt\EcontApi\`; `tests/` is PSR-4 root for `Econt\EcontApi\Tests\`. Both declared in `composer.json` (already present).

---

## Phase 0: Research Output

→ See [research.md](research.md) for full findings.

**Key decisions resolved**:
- API protocol: HTTP POST + Basic Auth + JSON body (confirmed from docs)
- All 14 endpoint paths confirmed and tabulated
- Dual-adapter HTTP strategy (`SymfonyHttpAdapter` + `Psr18HttpAdapter`)
- Manual `fromArray`/`toArray` (no Symfony Serializer in public contract)
- Enum case values use lowercase (matches JSON API; `fromArray` normalises input)
- Business hours stored as Unix milliseconds with optional `DateTimeImmutable` helpers
- Saturday delivery enforcement delegated to API
- No unresolved NEEDS CLARIFICATION items

---

## Phase 1: Design Output

→ See [data-model.md](data-model.md), [contracts/econt-api-contract.md](contracts/econt-api-contract.md), and [quickstart.md](quickstart.md).

---

## Implementation Steps

Implementation is ordered by dependency depth and user-story priority (P1 first).

### Step 1 — Project Bootstrap (no dependencies)

1. Verify `phpunit.xml` has separate `unit` and `integration` test suites (filter by `@group integration`).
2. Create all `Exception/` classes: `EcontException`, `EcontApiException`, `EcontNetworkException`, `EcontValidationException`.
3. Create `EcontConfiguration` with constants `DEMO_URL`, `PRODUCTION_URL`.
4. Create `Http/HttpAdapterInterface` with single method `post(string $endpoint, array $payload): array`.

**Acceptance**: `composer phpstan` and `composer phpcs` pass on the bootstrap files.

---

### Step 2 — HTTP Adapters (depends on Step 1)

1. Implement `SymfonyHttpAdapter`:
   - Constructor accepts `HttpClientInterface` and `EcontConfiguration`.
   - Calls `$client->request('POST', $url, ['json' => $payload, 'auth_basic' => [...]])`.
   - On HTTP 4xx/5xx or network error → throw `EcontNetworkException`.
   - On response with `error` key → throw `EcontApiException`.
2. Implement `Psr18HttpAdapter`:
   - Constructor accepts `ClientInterface`, `RequestFactoryInterface`, `StreamFactoryInterface`, and `EcontConfiguration`.
   - Builds PSR-7 request with JSON body and `Authorization: Basic` header.
   - Same error mapping as above.
3. Write `Unit/Http/SymfonyHttpAdapterTest` — mock `HttpClientInterface`, assert correct URL assembly, Basic Auth header, and exception mapping.
4. Write `Unit/Http/Psr18HttpAdapterTest` — same for PSR-18 path.

**Acceptance**: Unit tests for both adapters pass.

---

### Step 3 — Location Models (depends on Step 1) [P1]

Build models in order of nesting (deepest first):

1. `GeoLocation` — `latitude`, `longitude`, `confidence`
2. `Country` — `id`, `code2`, `code3`, `name`, `nameEn`, `isEU`
3. `City` — `id`, `country` (Country), `postCode`, `name`, `nameEn`, `regionName`, `regionNameEn`, `phoneCode`, `location` (GeoLocation|null), `expressCityDeliveries`
4. `Street` — `id`, `cityID`, `name`, `nameEn`
5. `Quarter` — `id`, `cityID`, `name`, `nameEn`
6. `Address` — `id`, `city` (City), `fullAddress`, `quarter`, `street`, `num`, `other`, `location`, `zip`
7. `ValidatedAddress` extends `Address` — adds `validationStatus`

**Contract for every model**:
```php
interface ModelInterface {
    public static function fromArray(array $data): static;
    public function toArray(): array;
}
```
`toArray()` keys MUST match the original API JSON field names exactly (see data-model.md).

8. Write unit tests for each model: `fromArray` round-trip, `toArray` key fidelity, nullable-field handling.

**Acceptance**: Each model's `toArray(fromArray($apiJson)) === $apiJson` for the documented example payloads.

---

### Step 4 — Collections (depends on Step 3)

1. Create `AbstractCollection` implementing `ArrayAccess`, `Countable`, `IteratorAggregate`.
2. Create `CountryCollection`, `CityCollection`, `StreetCollection`, `QuarterCollection` — typed wrappers.
3. `OfficeCollection` (placeholder until Step 6).

---

### Step 5 — AddressService (depends on Steps 2–4) [P1]

1. `AddressService` constructor accepts `HttpAdapterInterface`.
2. `getCountries(): CountryCollection`
   - POST `Nomenclatures/NomenclaturesService.getCountries.json` with `{"GetCountriesRequest": ""}`
   - Map `response['countries']` → `CountryCollection`
3. `getCities(string $countryCode): CityCollection`
   - POST `Nomenclatures/NomenclaturesService.getCities.json` with `{"countryCode": $countryCode}`
4. `getStreets(int $cityId): StreetCollection`
   - POST `Nomenclatures/NomenclaturesService.getStreets.json` with `{"cityID": (string) $cityId}`
5. `getQuarters(int $cityId): QuarterCollection`
   - POST `Nomenclatures/NomenclaturesService.getQuarters.json` with `{"cityID": (string) $cityId}`
6. `validateAddress(Address $address): ValidatedAddress`
   - POST `Nomenclatures/NomenclaturesService.validateAddress.json` with `{"address": $address->toArray()}`
   - Map response to `ValidatedAddress`
7. Unit tests: mock `HttpAdapterInterface`, assert endpoint + payload, assert returned typed collection/model.

**Acceptance**: User Story 1 and User Story 3 unit tests pass.

---

### Step 6 — Office Model + OfficeService (depends on Steps 2–4) [P1]

1. `Office` model — all fields per data-model.md including `emails` mapping from `e-mails` API key.
   - Add `normalBusinessHoursFromAsDateTime()` etc. helpers.
2. `OfficeCollection` (finalize).
3. `OfficeService::getOffices(?string $countryCode = null, ?int $cityId = null): OfficeCollection`
   - POST `Nomenclatures/NomenclaturesService.getOffices.json`
   - Payload: include only non-null params
4. Unit tests.

**Acceptance**: User Story 2 unit tests pass.

---

### Step 7 — Shipment Models (depends on Step 3)

Build in order:

1. `ShipmentType` enum (backed string) — 9 cases
2. `TariffSubCode` enum (backed string) — 6 cases
3. `ClientProfile` — all fields per data-model.md
4. `ShippingLabelServices` — 6 optional fields
5. `PackingListElement` — 6 fields
6. `Instruction` — 8 fields
7. `ReturnInstructionParams` — 19+ fields; nested `ClientProfile` and `Address`
8. `ShippingLabel` — all fields per data-model.md; `mode` defaults to `"create"`
   - `validate()` method enforces pre-HTTP validation rules (throws `EcontValidationException`):
     - `POST_PACK` requires `sizeUnder60cm === true`
     - `partialDelivery === true` requires `returnInstructions !== null`
     - `packCount >= 1`, `weight > 0`
9. Unit tests for each model (fromArray/toArray).

---

### Step 8 — Result DTOs (depends on Step 7)

1. `CourierRequest` model
2. `PriceCalculationResult`
3. `ShipmentLabelResult`
4. `ConfirmLabelResult`, `CancelLabelResult`
5. `ShipmentTrackingResult`
6. `CourierRequestResult`

---

### Step 9 — ShipmentService (depends on Steps 2, 7–8) [P2/P3]

1. `ShipmentService` constructor accepts `HttpAdapterInterface`.
2. `calculatePrice(ShippingLabel $label): PriceCalculationResult`
   - Calls `$label->validate()` first (throws `EcontValidationException` on bad data)
   - Clones label, sets `mode = "calculate"`, POSTs to `Shipments/LabelService.createLabel.json`
3. `validateLabel(ShippingLabel $label): ShipmentLabelResult`
   - Sets `mode = "validate"`
4. `createLabel(ShippingLabel $label): ShipmentLabelResult`
   - Sets `mode = "create"`
5. `confirmLabel(string $waybillNumber): ConfirmLabelResult`
   - POST `Shipments/LabelService.processLabel.json`
6. `updateLabel(string $waybillNumber, ShippingLabel $updatedLabel): ShipmentLabelResult`
   - POST with `mode = "create"` + waybillNumber in payload
7. `cancelLabel(string $waybillNumber): CancelLabelResult`
   - POST `Shipments/LabelService.deleteLabel.json`
8. `trackShipment(string $waybillNumber): ShipmentTrackingResult`
   - POST `Shipments/LabelService.getWaybillContents.json`
9. `requestCourier(CourierRequest $request): CourierRequestResult`
   - POST `Shipments/LabelService.requestCourier.json`
10. Unit tests: mock adapter, assert all 8 methods.

**Acceptance**: User Stories 4–9 unit tests pass.

---

### Step 10 — EcontClient (depends on Steps 5, 6, 9) [P1]

```php
class EcontClient {
    public function __construct(
        private readonly EcontConfiguration $configuration,
        private readonly HttpAdapterInterface $adapter,
        private readonly ?LoggerInterface $logger = null,
    ) {}

    public function address(): AddressService { ... }
    public function office(): OfficeService { ... }
    public function shipment(): ShipmentService { ... }
}
```

- Services are lazily instantiated (or constructed in constructor — either is fine).
- Logger is optionally injected and forwarded to adapters for request/response debug logging.

---

### Step 11 — Integration Tests (depends on Steps 5, 6, 9–10) [FR-1102/1103]

All tagged `@group integration`. Run with `php vendor/bin/phpunit --group integration`.

`AddressServiceIntegrationTest`:
- `testGetCountriesReturnsNonEmptyCollection()` — asserts count > 0; each Country has `code2`, `code3`, `name`, `nameEn`
- `testGetCitiesBGRReturnsNonEmptyCollection()` — `getCities('BGR')`; asserts City fields
- `testGetStreetsByCity41()` — `getStreets(41)`; asserts Street fields
- `testGetQuartersByCity41()` — `getQuarters(41)`; asserts Quarter fields
- `testValidateKnownAddress()` — Ruse/Славянска/16 → `validationStatus === "normal"`
- `testValidateInvalidAddressReturnsInvalid()` — garbage input → `"invalid"` or `EcontApiException`

`OfficeServiceIntegrationTest`:
- `testGetOfficesBGRShumen()` — `getOffices('BGR', 47)`; asserts collection non-empty; Office has `code`, `name`, `address.city`

`ShipmentServiceIntegrationTest`:
- `testCalculatePriceRuseToSofia()` — `calculatePrice($label)`; asserts `totalPrice > 0` and `currency === 'BGN'`
- `testCreateAndCancelLabel()` — `createLabel($label)` asserts non-empty `waybillNumber`; `cancelLabel($waybill)` asserts success
- FR-1104: Assert `toArray()` output key-by-key against raw API JSON response for each model type

---

### Step 12 — Documentation & Standards

1. **README.md** — installation (`composer require`), Symfony DI wiring, standalone-PHP usage, all services with examples, API endpoint table, demo credentials, CI badge.
2. PHPDoc on all public methods: `@param`, `@return`, `@throws`.
3. Run `composer phpstan` → zero errors.
4. Run `composer phpcs` → zero violations.
5. Run `composer test` → ≥ 80 % coverage.

---

## Complexity Tracking

> No constitution violations requiring justification.

---

## API Endpoint Reference

| Library method | PHP method signature | HTTP endpoint |
|---|---|---|
| Get countries | `AddressService::getCountries()` | `Nomenclatures/NomenclaturesService.getCountries.json` |
| Get cities | `AddressService::getCities(string $countryCode)` | `Nomenclatures/NomenclaturesService.getCities.json` |
| Get streets | `AddressService::getStreets(int $cityId)` | `Nomenclatures/NomenclaturesService.getStreets.json` |
| Get quarters | `AddressService::getQuarters(int $cityId)` | `Nomenclatures/NomenclaturesService.getQuarters.json` |
| Validate address | `AddressService::validateAddress(Address $address)` | `Nomenclatures/NomenclaturesService.validateAddress.json` |
| Get offices | `OfficeService::getOffices(?string $countryCode, ?int $cityId)` | `Nomenclatures/NomenclaturesService.getOffices.json` |
| Create label | `ShipmentService::createLabel(ShippingLabel $label)` | `Shipments/LabelService.createLabel.json` |
| Validate label | `ShipmentService::validateLabel(ShippingLabel $label)` | `Shipments/LabelService.createLabel.json` |
| Calculate price | `ShipmentService::calculatePrice(ShippingLabel $label)` | `Shipments/LabelService.createLabel.json` |
| Confirm label | `ShipmentService::confirmLabel(string $waybillNumber)` | `Shipments/LabelService.processLabel.json` |
| Update label | `ShipmentService::updateLabel(string $waybillNumber, ShippingLabel $label)` | `Shipments/LabelService.createLabel.json` |
| Cancel label | `ShipmentService::cancelLabel(string $waybillNumber)` | `Shipments/LabelService.deleteLabel.json` |
| Track shipment | `ShipmentService::trackShipment(string $waybillNumber)` | `Shipments/LabelService.getWaybillContents.json` |
| Request courier | `ShipmentService::requestCourier(CourierRequest $request)` | `Shipments/LabelService.requestCourier.json` |

---

## Key Design Decisions Summary

| Decision | Choice | Reason |
|---|---|---|
| HTTP transport | Dual adapter (Symfony default + PSR-18 fallback) | FR-102/103; avoids Symfony lock-in |
| Serialisation | Manual `fromArray`/`toArray` on every model | FR-303/304; predictable; no annotation overhead |
| Collections | Typed wrapper classes | Type-safe return values; IDE support |
| Enum values | Lowercase (`'pack'`, `'post_pack'`) | Matches SOAP/JSON API table; `fromArray` normalises via `strtolower()` |
| Business hours | Unix milliseconds (raw) + helper to `DateTimeImmutable` | FR-504; raw values preserved, ergonomic access added |
| Exception hierarchy | 3 typed exceptions under `EcontException` | FR-1001–1004; consumers can catch specifically |
| Pre-HTTP validation | `ShippingLabel::validate()` throws `EcontValidationException` | Prevents wasted HTTP calls; fails fast |
| Integration tests | Separate `@group integration` suite | FR-1105; excluded from default CI |
