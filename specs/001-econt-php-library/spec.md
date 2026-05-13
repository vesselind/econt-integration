# Feature Specification: PHP Econt Integration Library

**Feature Branch**: `001-econt-php-library`

**Created**: 2025-01-01

**Status**: Draft

**Input**: User description: "Create a standalone PHP library for Econt Delivery services (Econt API). Reusable across multiple projects. PHP 8.1+, Symfony 7/8 compatible. Symfony HTTP Client with PSR-18 fallback. Retrieve offices, streets, cities, countries. All Econt API endpoints from docs. camelCase model objects with toArray() back-conversion. PHPUnit tests. Real API integration tests using demo credentials (iasp-dev / 1Asp-dev)."

---

## User Scenarios & Testing *(mandatory)*

### User Story 1 — Retrieve Locations Data (Priority: P1)

A PHP developer integrating Econt delivery into an e-commerce store needs to populate address-selection dropdowns for their checkout form. They call the library to get all countries Econt delivers to, then fetch cities for a chosen country, then streets and quarters for a chosen city.

**Why this priority**: Address data (countries → cities → streets/quarters → offices) is the foundational read-only lookup layer. Every other feature — shipment creation, price calculation, courier requests — depends on having validated, Econt-recognised location values. It can be validated in isolation against the live demo API and delivers standalone value even before shipment management is built.

**Independent Test**: A developer can instantiate `EcontClient` with demo credentials, call `AddressService::getCountries()`, `AddressService::getCities('BGR')`, `AddressService::getStreets(41)`, and `AddressService::getQuarters(41)`, then assert that returned typed model collections are non-empty and all properties are correctly mapped.

**Acceptance Scenarios**:

1. **Given** valid credentials and network access, **When** `getCountries()` is called, **Then** a typed collection of `Country` models is returned, each having non-empty `code2`, `code3`, `name`, and `nameEn` properties.
2. **Given** a valid ISO Alpha-3 country code (e.g., `BGR`), **When** `getCities('BGR')` is called, **Then** a collection of `City` models is returned, each containing `id`, `postCode`, `name`, `nameEn`, and a nested `Country` model.
3. **Given** a valid city ID (e.g., `41` for Sofia), **When** `getStreets(41)` is called, **Then** a collection of `Street` models is returned with `id`, `cityID`, `name`, and `nameEn`.
4. **Given** a valid city ID, **When** `getQuarters(41)` is called, **Then** a collection of `Quarter` models is returned with `id`, `cityID`, `name`, and `nameEn`.
5. **Given** an invalid country code, **When** any location method is called, **Then** the library throws `EcontApiException` with a descriptive message.

---

### User Story 2 — Retrieve Offices (Priority: P1)

A developer needs to show a list of Econt offices and Econtomat (automated parcel stations) in a chosen city, so customers can select a delivery office. The developer filters out Econtomat locations (`isAPS = true`) and enriches each office result with working-hours information.

**Why this priority**: Office lookup is explicitly flagged as the highest-priority feature in the project task. It is the central piece for office-to-office and address-to-office shipment flows.

**Independent Test**: Call `OfficeService::getOffices(countryCode: 'BGR', cityID: 47)` and assert the returned `Office` collection is non-empty, that each `Office` has a `code`, `name`, nested `Address` with a nested `City`, working-hours timestamps, and a `shipmentTypes` array.

**Acceptance Scenarios**:

1. **Given** a country code and city ID, **When** `getOffices('BGR', 47)` is called, **Then** a typed collection of `Office` models is returned.
2. **Given** the office list, **When** filtering `isAPS === false`, **Then** only regular Econt offices are retained; when filtering `isAPS === true`, only Econtomat stations are retained.
3. **Given** an office model, **When** `toArray()` is called, **Then** the resulting associative array uses the original API snake_case/camelCase keys as documented in the Econt JSON schema.
4. **Given** invalid or empty city data, **When** `getOffices()` is called, **Then** an empty collection is returned (no exception), or `EcontApiException` if the API itself responds with an error.
5. **Given** the demo API, **When** `getOffices()` is called without cityID (country only), **Then** all offices for that country are returned.

---

### User Story 3 — Validate an Address (Priority: P2)

Before creating a shipment label, a developer wants to confirm that the customer-entered delivery address is known to Econt, to prevent label generation failures.

**Why this priority**: Address validation is a prerequisite gate for shipment creation; it prevents wasted API calls and ensures quality data flows into the shipment pipeline.

**Independent Test**: Call `AddressService::validateAddress($address)` with a known valid address (city Ruse, street Slavyanska, num 16) and assert `validationStatus` is `"normal"` or `"processed"`. Call with an obviously invalid address and assert `validationStatus` is `"invalid"`.

**Acceptance Scenarios**:

1. **Given** a valid `Address` model (city + street + num), **When** `validateAddress($address)` is called, **Then** a `ValidatedAddress` model is returned with `validationStatus` = `"normal"` or `"processed"`.
2. **Given** an unrecognised street, **When** `validateAddress($address)` is called, **Then** `validationStatus` = `"invalid"` is returned.
3. **Given** a validated address, **When** `toArray()` is invoked on it, **Then** all nested objects (city, country, location) serialise to their original API keys.

---

### User Story 4 — Calculate Shipment Price (Priority: P2)

A developer wants to show the delivery cost to the customer in real-time at checkout, before any shipment record is created. They submit a full `ShippingLabel` structure with `mode = "calculate"` and receive a price breakdown.

**Why this priority**: Price calculation drives customer purchasing decisions and is foundational for order completion workflows. It shares the same request model as shipment creation, so building it simultaneously exercises most of the `ShippingLabel` model at low cost.

**Independent Test**: Build a `ShippingLabel` with sample sender (Ruse → Sofia, PACK, 5 kg), set `mode = calculate`, call `ShipmentService::calculatePrice($label)`, and assert the response contains numeric `totalPrice` and a non-empty `currency`.

**Acceptance Scenarios**:

1. **Given** a fully populated `ShippingLabel` (sender, senderAddress, receiver, receiverAddress, packCount, shipmentType, weight), **When** `calculatePrice($label)` is called, **Then** a `PriceCalculationResult` model is returned with `totalPrice` (float) and `currency` (string).
2. **Given** a shipment with additional services (declared value, priority hour), **When** the price is calculated, **Then** the response breaks down costs per service.
3. **Given** an incomplete `ShippingLabel` (missing required fields), **When** `calculatePrice()` is called, **Then** `EcontValidationException` is thrown listing the missing fields.

---

### User Story 5 — Create, Confirm, Update, and Cancel a Shipment Label (Priority: P2)

A developer processes a customer order: they generate a shipment label (waybill/товарителница), optionally confirm it, and - if needed - update or cancel it before physical pickup.

**Why this priority**: Shipment creation is the core transactional operation of the library. The validate → create → confirm lifecycle is the primary integration path for e-commerce merchants.

**Independent Test**: Call `ShipmentService::createLabel($label)` in `mode = create` against the demo API, assert a `ShipmentLabelResult` with a non-empty waybill number is returned. Call `cancelLabel($waybillNumber)` and assert cancellation succeeds.

**Acceptance Scenarios**:

1. **Given** a valid `ShippingLabel` with sender, receiver, shipment details, and return instructions, **When** `createLabel($label)` is called in `mode = create`, **Then** a `ShipmentLabelResult` model containing the waybill number is returned.
2. **Given** a created but unconfirmed label, **When** `confirmLabel($waybillNumber)` is called, **Then** the label is confirmed and the result indicates success.
3. **Given** a valid waybill number, **When** `updateLabel($waybillNumber, $updatedLabel)` is called with changed weight, **Then** the label is updated and the result reflects the change.
4. **Given** a valid waybill number, **When** `cancelLabel($waybillNumber)` is called, **Then** the label is deleted and no further billing occurs.
5. **Given** production mode with a wrong credential, **When** any mutation is called, **Then** `EcontApiException` is thrown with the API error code and message.

---

### User Story 6 — Request Courier Pickup (Priority: P3)

After creating labels, a developer schedules a courier to collect parcels from the sender's address within a specified time window.

**Why this priority**: Courier pickup completes the dispatch workflow. It depends on successfully created labels (P2) but is independently testable against the demo API.

**Independent Test**: Call `ShipmentService::requestCourier($courierRequest)` on the demo API and assert a confirmation response is returned.

**Acceptance Scenarios**:

1. **Given** a sender address and a time window, **When** `requestCourier($request)` is called, **Then** a `CourierRequestResult` model is returned indicating success.
2. **Given** a time window outside service hours, **When** the request is submitted, **Then** `EcontApiException` is thrown with the API's error response.

---

### User Story 7 — Track Shipment Status (Priority: P3)

A developer or end-user wants to track the current status of a shipment by its waybill number.

**Why this priority**: Tracking is a frequently used read-only feature that increases customer satisfaction but is not blocking for the core shipment create-and-ship workflow.

**Independent Test**: Call `ShipmentService::trackShipment($waybillNumber)` with a known test waybill and assert a `ShipmentTrackingResult` containing status events is returned.

**Acceptance Scenarios**:

1. **Given** a valid waybill number, **When** `trackShipment($waybillNumber)` is called, **Then** a typed tracking result with status history is returned.
2. **Given** a non-existent waybill number, **When** tracking is attempted, **Then** `EcontApiException` is thrown or an empty result is returned according to API behaviour.

---

### User Story 8 — Manage Additional Shipment Services (Priority: P3)

A developer wants to attach optional services — declared value (insurance), return receipt, review/test-and-choose, priority delivery hour, and SMS/email notifications — to a shipment.

**Why this priority**: These features enhance the base shipment flow and are independently addable as model properties. They exercise `ShippingLabelServices` and `PackingListElement` models.

**Independent Test**: Build a `ShippingLabel` with a `ShippingLabelServices` containing `declaredValueAmount = 200`, `declaredValueCurrency = BGN`, and `deliveryReceipt = true`. Call `calculatePrice()` and assert the breakdown shows declared-value and return-receipt fees.

**Acceptance Scenarios**:

1. **Given** a `ShippingLabelServices` with `declaredValueAmount` and `declaredValueCurrency`, **When** the label is submitted, **Then** declared value coverage is applied.
2. **Given** `deliveryReceipt = true`, **When** the label is calculated or created, **Then** the return-receipt service fee appears in the price breakdown.
3. **Given** `payAfterAccept = true` and `payAfterTest = true` and a `PackingList`, **When** the label is created, **Then** the packing list items are attached to the waybill.
4. **Given** `partialDelivery = true` and a multi-item packing list, **When** the label is created, **Then** partial delivery (Преглед, тест и избор) is enabled.
5. **Given** `priorityTimeFrom = "10:00"` and `priorityTimeTo = "13:00"`, **When** the label is calculated, **Then** the priority-hour fee appears in the breakdown.

---

### User Story 9 — Return Instructions & Courier Instructions (Priority: P3)

A developer attaches return-delivery instructions (`ReturnInstructionParams`) and/or courier handling instructions (`Instruction`) to a shipment label.

**Why this priority**: These are mandatory label components for review/test-and-choose scenarios. They make the spec complete and allow full label construction in one pass.

**Independent Test**: Build a `ReturnInstructionParams` with `returnParcelDestination = "sender"`, attach it to a `ShippingLabel`, call `calculatePrice()`, and assert no validation error is thrown.

**Acceptance Scenarios**:

1. **Given** a `ReturnInstructionParams` with `returnParcelDestination`, `daysUntilReturn`, and `returnParcelPaymentSide`, **When** attached to a label and submitted, **Then** the API accepts the label without a validation error.
2. **Given** an `Instruction` of type `"give"` with a description, **When** attached to a label, **Then** the courier instruction is preserved in the waybill response.
3. **Given** a missing `ReturnInstructionParams` on a label that uses `partialDelivery = true`, **When** `createLabel()` is called, **Then** `EcontValidationException` is thrown.

---

### User Story 10 — Configure the Client and Swap HTTP Adapters (Priority: P1)

A developer who is not using Symfony wants to instantiate the library using curl or any PSR-18 HTTP client they already have available. A Symfony developer wires it up via the provided configuration.

**Why this priority**: Architecture flexibility is a hard requirement from the task ("designed so it can be used in other contexts"). Without this, the library cannot be adopted outside Symfony projects.

**Independent Test**: Instantiate `EcontClient` with a mock PSR-18 `ClientInterface` implementation, call `getCountries()`, and assert the mock was invoked with the correct endpoint URL and Basic Auth header — without touching the real network.

**Acceptance Scenarios**:

1. **Given** an `EcontConfiguration` with demo credentials, **When** `EcontClient` is constructed with the Symfony HTTP client adapter, **Then** all service calls complete successfully against the demo API.
2. **Given** an `EcontConfiguration` and a custom PSR-18 `ClientInterface`, **When** `EcontClient` is constructed, **Then** the custom client is used for all HTTP requests.
3. **Given** a network failure, **When** any service method is called, **Then** `EcontNetworkException` is thrown wrapping the underlying transport error.
4. **Given** a `language` configuration of `"en"`, **When** a request is made, **Then** the `X-Language` header (or equivalent API parameter) is set accordingly.

---

### Edge Cases

- What happens when the Econt API returns a 500 error? → `EcontNetworkException` is thrown.
- What happens when authentication fails (401)? → `EcontApiException` is thrown with `ERR_AUTH` code.
- What happens when `getCities()` is called with a country code for a country with no registered cities? → An empty typed collection is returned.
- What happens when a `ShippingLabel` with `shipmentType = "post_pack"` is submitted without `sizeUnder60cm = true`? → `EcontValidationException` is thrown before the HTTP call.
- What happens when `getOffices()` is filtered and all offices are Econtomat? → Collection is returned; the consumer filters on `isAPS`.
- What happens when `toArray()` is called on a model with null optional properties? → Null values are included or excluded according to the original API's convention (null fields omitted).
- What happens when a waybill number is provided to `trackShipment()` that belongs to a different account? → `EcontApiException` is thrown.
- What happens when `priorityTimeTo` exceeds office closing hours? → The API returns an error caught as `EcontApiException`.
- What happens when Saturday delivery is requested for a city whose `courierRequestBeginTimeSaturday` is null? → The library's validator or the API returns an appropriate error.

---

## Requirements *(mandatory)*

### Functional Requirements

#### FR-100 — HTTP Communication

- **FR-101**: The library MUST communicate with the Econt API exclusively via HTTP POST requests using JSON bodies and Basic Authentication headers.
- **FR-102**: The library MUST support the Symfony HTTP client (`symfony/http-client`) as the default adapter.
- **FR-103**: The library MUST accept any PSR-18 `Psr\Http\Client\ClientInterface` implementation as an alternative adapter, making it usable outside Symfony projects.
- **FR-104**: The library MUST expose two base URL constants: `DEMO_URL = "https://demo.econt.com/ee/services/"` and `PRODUCTION_URL = "https://ee.econt.com/services/"`.
- **FR-105**: The library MUST allow configuration of connection timeout, request language (`bg`/`en`), and an optional PSR-3 logger.

#### FR-200 — Configuration

- **FR-201**: Configuration MUST be encapsulated in an `EcontConfiguration` class with typed properties: `username`, `password`, `baseUrl`, `timeout` (seconds), `language`.
- **FR-202**: An `EcontClient` MUST depend on `EcontConfiguration` and a HTTP adapter; it MUST NOT read credentials from environment variables directly.
- **FR-203**: The `EcontClient` MUST provide access to all service objects (OfficeService, AddressService, ShipmentService) via dedicated getter methods.

#### FR-300 — Model / DTO Layer

- **FR-301**: Every API request and response MUST be represented by a typed PHP 8.1+ model class (DTO).
- **FR-302**: All model properties MUST be in **camelCase** and MUST carry explicit PHP type declarations (including nullable types where the API allows null).
- **FR-303**: Every model MUST implement `toArray(): array` returning the associative array with the **original API keys** (e.g., `code3`, `nameEn`, `normalBusinessHoursFrom`).
- **FR-304**: Every model MUST implement `static fromArray(array $data): static` for constructing from a decoded API response.
- **FR-305**: The Symfony Serializer component MAY be used internally for (de)serialisation but MUST NOT be exposed as a required dependency to library consumers who do not use Symfony.

#### FR-400 — Address Service

- **FR-401**: `AddressService::getCountries(): CountryCollection` — fetches all countries Econt delivers to. Maps to `Nomenclatures/NomenclaturesService.getCountries.json`.
- **FR-402**: `AddressService::getCities(string $countryCode): CityCollection` — fetches cities for a country code. Maps to `Nomenclatures/NomenclaturesService.getCities.json`.
- **FR-403**: `AddressService::getStreets(int $cityId): StreetCollection` — fetches streets for a city. Maps to `Nomenclatures/NomenclaturesService.getStreets.json`.
- **FR-404**: `AddressService::getQuarters(int $cityId): QuarterCollection` — fetches quarters/neighbourhoods for a city. Maps to `Nomenclatures/NomenclaturesService.getQuarters.json`.
- **FR-405**: `AddressService::validateAddress(Address $address): ValidatedAddress` — validates a partial or full address. Maps to `Nomenclatures/NomenclaturesService.validateAddress.json`. Returns a `ValidatedAddress` with `validationStatus` = `"normal" | "processed" | "invalid"`.

#### FR-500 — Office Service

- **FR-501**: `OfficeService::getOffices(?string $countryCode, ?int $cityId): OfficeCollection` — fetches offices filtered by country and/or city. Maps to `Nomenclatures/NomenclaturesService.getOffices.json`.
- **FR-502**: The returned `Office` model MUST expose `isAPS` (Econtomat flag) and `isMPS` (mobile office flag) as typed boolean properties.
- **FR-503**: The `Office` model MUST include nested `Address` model with nested `City` and `Country` models.
- **FR-504**: Business hours MUST be represented as Unix millisecond timestamps (`normalBusinessHoursFrom`, `normalBusinessHoursTo`, `halfDayBusinessHoursFrom`, `halfDayBusinessHoursTo`) matching the raw API values, and the model MAY provide helper methods to convert them to `DateTimeImmutable`.

#### FR-600 — Shipment Service

- **FR-601**: `ShipmentService::createLabel(ShippingLabel $label): ShipmentLabelResult` with `label.mode = "create"` — creates a waybill. Maps to `Shipments/LabelService.createLabel.json`.
- **FR-602**: `ShipmentService::validateLabel(ShippingLabel $label): ShipmentLabelResult` with `mode = "validate"` — validates without creating.
- **FR-603**: `ShipmentService::calculatePrice(ShippingLabel $label): PriceCalculationResult` with `mode = "calculate"` — returns price breakdown.
- **FR-604**: `ShipmentService::confirmLabel(string $waybillNumber): ConfirmLabelResult` — confirms a pending label.
- **FR-605**: `ShipmentService::updateLabel(string $waybillNumber, ShippingLabel $updatedLabel): ShipmentLabelResult` — updates a label.
- **FR-606**: `ShipmentService::cancelLabel(string $waybillNumber): CancelLabelResult` — cancels/deletes a label.
- **FR-607**: `ShipmentService::trackShipment(string $waybillNumber): ShipmentTrackingResult` — returns tracking events.
- **FR-608**: `ShipmentService::requestCourier(CourierRequest $request): CourierRequestResult` — requests courier pickup.

#### FR-700 — ShippingLabel Model

- **FR-701**: `ShippingLabel` MUST carry: `senderClient` (ClientProfile), `senderAddress` (Address), `receiverClient` (ClientProfile), `receiverAddress` (Address), `packCount` (int), `shipmentType` (ShipmentType enum), `weight` (float).
- **FR-702**: `ShippingLabel` MUST carry optional: `envelopeNumbers`, `sizeUnder60cm` (bool), `shipmentDimensionsL/W/H` (float), `shipmentDescription` (string), `orderNumber` (string), `sendDate` (string), `holidayDeliveryDay` (string), `keepUpright` (bool), `payAfterAccept` (bool), `payAfterTest` (bool), `partialDelivery` (bool), `packingListType` (string), `packingList` (array of PackingListElement), `services` (ShippingLabelServices), `returnInstructions` (ReturnInstructionParams), `instructions` (array of Instruction), `mode` (string default `"create"`).
- **FR-703**: `ShipmentType` MUST be a PHP 8.1 backed enum with cases: `DOCUMENT`, `PACK`, `POST_PACK`, `PALLET`, `CARGO`, `DOCUMENTPALLET`, `BIG_LETTER`, `SMALL_LETTER`, `MONEY_TRANSFER`.
- **FR-704**: `TariffSubCode` MUST be a backed enum with cases: `DOOR_DOOR`, `DOOR_OFFICE`, `OFFICE_DOOR`, `OFFICE_OFFICE`, `DOOR_BANK`, `OFFICE_BANK`.

#### FR-800 — ClientProfile and Address Models

- **FR-801**: `ClientProfile` MUST carry: `name` (string), `phones` (array of strings). Additional optional fields: `id`, `nameEn`, `email`, `skypeAccounts`, `clientNumber`, `clientNumberEn`, `juridicalEntity` (int 0/1), `personalIDType` (enum: EGN, PIN, PK, PASSPORT), `personalIDNumber`, `companyType`, `ein`, `ddsEinPrefix`, `ddsEin`, `registrationAddress`, `molName`, `molEGN`, `molIDNum`.
- **FR-802**: `Address` MUST carry: `city` (City model). Optional: `id`, `fullAddress`, `quarter`, `street`, `num`, `other`, `location` (GeoLocation), `zip`.
- **FR-803**: `City` MUST carry: `country` (Country), `postCode`, `name`, `nameEn`. Optional: `id`, `regionName`, `regionNameEn`, `phoneCode`, `location`, `expressCityDeliveries`.
- **FR-804**: `Country` MUST carry: `code2`, `code3`, `name`, `nameEn`. Optional: `id`, `isEU` (bool).
- **FR-805**: `GeoLocation` MUST carry: `latitude` (float), `longitude` (float). Optional: `confidence` (int).

#### FR-900 — Additional Services Models

- **FR-901**: `ShippingLabelServices` MUST carry optional: `declaredValueAmount` (float), `declaredValueCurrency` (string, one of BGN/EUR/USD/RON), `deliveryReceipt` (bool), `priorityTimeFrom` (string), `priorityTimeTo` (string), `smsNotification` (bool).
- **FR-902**: `ReturnInstructionParams` MUST carry all fields from the SOAP/JSON API spec: `returnParcelDestination`, `returnParcelIsDocument` (bool), `daysUntilReturn` (int), `returnParcelPaymentSide`, `rejectAction`, `rejectInstruction`, `rejectContact`, `rejectReturnClient` (ClientProfile), `rejectReturnAgent`, `rejectReturnOfficeCode`, `rejectReturnAddress` (Address), `rejectOriginalParcelPaySide`, `rejectReturnParcelPaySide`, `printReturnParcel` (bool), `signatureDocuments` (bool), `signaturePenColor`, `signatureCount` (int), `signaturePageNumbers`, `signatureOtherInstructions`.
- **FR-903**: `Instruction` MUST carry: `type` (string: `"give"` | `"take"`), `title`, `description`, `attachments` (array), `voiceDescription`, `name` (template name), `applyToAllParcels` (bool), `applyToReceivers` (bool).
- **FR-904**: `PackingListElement` MUST carry: `inventoryNum` (string), `description` (string), `weight` (float), `price` (float), `count` (int). Optional: `file` (string, base64).

#### FR-1000 — Exception Hierarchy

- **FR-1001**: All library exceptions MUST extend `EcontException`.
- **FR-1002**: `EcontApiException` — thrown when the API responds with a business-level error; MUST carry `apiErrorCode` and `apiErrorMessage`.
- **FR-1003**: `EcontNetworkException` — thrown when the HTTP transport fails (connection refused, timeout, DNS failure).
- **FR-1004**: `EcontValidationException` — thrown when the consumer passes invalid data before the HTTP request is made; MUST carry a list of violated field names.

#### FR-1100 — Tests

- **FR-1101**: The library MUST include PHPUnit unit tests for all service classes using mock HTTP responses; minimum coverage target 80%.
- **FR-1102**: The library MUST include PHPUnit integration tests that make real HTTP calls to `https://demo.econt.com/ee/services/` using demo credentials `iasp-dev` / `1Asp-dev`.
- **FR-1103**: Integration tests MUST cover: `getCountries()`, `getCities('BGR')`, `getStreets(cityId)`, `getQuarters(cityId)`, `getOffices('BGR', cityId)`, `validateAddress()`, `calculatePrice()`, and `createLabel()` followed by `cancelLabel()`.
- **FR-1104**: Integration tests MUST assert that returned models are correctly typed and that `toArray()` output keys match the documented API field names.
- **FR-1105**: Integration tests MUST be kept in a separate test suite (`integration`) so they can be excluded from standard CI runs by default.

#### FR-1200 — Documentation & Standards

- **FR-1201**: A `README.md` MUST include: installation instructions (`composer require`), configuration examples (standalone and Symfony), usage examples for each service, a table of API endpoints mapped to library methods, and the demo credentials.
- **FR-1202**: All public methods and interfaces MUST carry PHPDoc blocks with `@param`, `@return`, and `@throws` annotations.
- **FR-1203**: The library MUST comply with PSR-12 coding standards.
- **FR-1204**: The namespace MUST be `Econt\EcontApi\` with PSR-4 autoloading from `src/`.
- **FR-1205**: `declare(strict_types=1)` MUST appear at the top of every PHP file.

---

### Key Entities

- **Country**: A country Econt operates in. Key attributes: `code2` (ISO 2-letter), `code3` (ISO 3-letter), `name` (Bulgarian), `nameEn` (English), `isEU` (bool). Used in city, office, and address lookup.
- **City**: A settlement Econt services. Attributes: `id`, `postCode`, `name`, `nameEn`, `regionName`, country reference, `expressCityDeliveries`, GPS location. Used in address construction and office filtering.
- **Street**: A street within a city in Econt's database. Attributes: `id`, `cityID`, `name`, `nameEn`. Required for creating validated shipment addresses.
- **Quarter**: A neighbourhood/quarter within a city. Attributes: `id`, `cityID`, `name`, `nameEn`. Optional for shipment address.
- **Office**: An Econt branch or Econtomat. Attributes: `id`, `code`, `isAPS`, `isMPS`, `name`, `nameEn`, `phones`, `emails`, `address` (nested), working-hours timestamps, `shipmentTypes`, `hubCode`. Used for office-to-office and door-to-office deliveries.
- **Address**: A structured address for sender or receiver. References City; has `street`, `num`, `quarter`, `other`, GPS location, `zip`.
- **ClientProfile**: A person or company as sender, receiver, or return recipient. References compulsory `name` and `phones`; optional legal entity data (EIK, EGN, MOL).
- **ShippingLabel**: The full shipment creation request. Contains sender/receiver client+address, shipment details, optional services, return instructions, and courier instructions.
- **ShipmentType**: Enum — PACK, DOCUMENT, PALLET, CARGO, DOCUMENTPALLET, BIG_LETTER, SMALL_LETTER, POST_PACK, MONEY_TRANSFER.
- **TariffSubCode**: Enum — DOOR_DOOR, OFFICE_DOOR, DOOR_OFFICE, OFFICE_OFFICE, DOOR_BANK, OFFICE_BANK.
- **ShippingLabelServices**: Declares optional value-added services: declared value (insurance), return receipt, priority delivery hour, SMS notification.
- **ReturnInstructionParams**: Defines what happens when a shipment cannot be delivered or is rejected by the recipient.
- **Instruction**: A free-form or template-based instruction for the collecting/delivering courier.
- **PackingListElement**: A single item in the digital packing list for Преглед, тест и избор (review, test, and choose).
- **PriceCalculationResult**: The price breakdown returned from the `calculate` mode; contains `totalPrice`, `currency`, and per-service line items.
- **ShipmentLabelResult**: The result of label creation; contains waybill number and any generated documents.
- **ValidatedAddress**: An address as returned by `validateAddress`; adds `validationStatus` and enriched city/region data.

---

## Success Criteria *(mandatory)*

### Measurable Outcomes

- **SC-001**: A new developer can retrieve a list of Bulgarian cities from the demo API in under 5 minutes using only the README, with no prior Econt knowledge.
- **SC-002**: All `toArray()` outputs on model instances produce associative arrays whose keys exactly match the documented Econt API field names (verified by integration tests that compare raw JSON responses to `toArray()` output).
- **SC-003**: Integration test suite completes all real-API assertions against `demo.econt.com` within 30 seconds under normal network conditions.
- **SC-004**: A developer can replace the default Symfony HTTP client with any PSR-18 compliant client in under 10 lines of code, with no changes to service classes.
- **SC-005**: PHPUnit unit-test suite achieves ≥ 80% line coverage across all `src/` classes when run against mocked HTTP responses.
- **SC-006**: The library composes a `ShippingLabel` → `calculatePrice()` → `createLabel()` → `cancelLabel()` round-trip against the demo API without manual intervention, confirming end-to-end correctness.
- **SC-007**: All public methods throw one of the three typed exceptions (`EcontApiException`, `EcontNetworkException`, `EcontValidationException`); no raw `\Exception` bubbles to the consumer.
- **SC-008**: PHPStan level 6 analysis reports zero errors on the `src/` directory.
- **SC-009**: PSR-12 code style check (`phpcs src --standard=PSR12`) reports zero violations.
- **SC-010**: The library installs cleanly (`composer install`) on PHP 8.1, 8.2, and 8.3 with Symfony 6, 7, and 8 packages present.

---

## Assumptions

- The Econt SOAP/JSON API version is 1.0 (24 April 2018) as documented in `docs/SOAPJSON API.md`; no later breaking changes are assumed.
- All API endpoints are relative to the base URL; the sub-path format is `Nomenclatures/NomenclaturesService.{method}.json` and `Shipments/LabelService.{method}.json` based on the patterns visible in demo API calls.
- Demo credentials `iasp-dev` / `1Asp-dev` against `https://demo.econt.com/ee/services/` are stable for the lifetime of this library's development and CI pipeline.
- Requests to production with demo credentials will fail with an auth error — integration tests MUST only run against the demo URL.
- Creating a label on the demo API does not trigger real courier dispatch or billing; cancellation is still performed defensively in integration tests.
- The `getQuarters()` endpoint uses the same service path pattern and accepts `cityID` as documented for `getStreets()`.
- The `mode` field on the `ShippingLabel`/label request wrapper follows the SOAP/JSON API specification: `"create"`, `"validate"`, `"calculate"`.
- Model `toArray()` keys follow the API's camelCase JSON field names (not XML snake_case equivalents), since the library targets the JSON interface.
- The library will not implement the XML integration variant; only the SOAP/JSON (JSON over HTTP) interface is in scope.
- A Symfony Bundle / DI extension is out of scope for the initial version but the architecture should not prevent it from being added later.
- Packing-list file attachments (`base64` encoded) are modelled but not validated for content type or size by the library.
- The `payment` service (`setPay`) is out of scope for the initial version and may be added as a future extension.
- The library targets PHP ≥ 8.2 (as declared in `composer.json`) though the task stated 8.1+; 8.2 is the actual minimum and aligns with the repo.
- `symfony/serializer` is a declared dependency and will be used internally for consistent (de)serialisation; consumers who use the library in a non-Symfony project will receive it as a transitive dependency.

