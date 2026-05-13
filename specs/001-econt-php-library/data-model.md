# Data Model: PHP Econt Integration Library

**Phase 1 output for**: `001-econt-php-library`

---

## Entity Map

```
EcontConfiguration
EcontClient ──────┬── AddressService ─── HTTP adapter
                  ├── OfficeService  ─── HTTP adapter
                  └── ShipmentService ── HTTP adapter

Location Layer:
  Country ◄── City ◄── Address ◄── GeoLocation
                └── Street
                └── Quarter
  ValidatedAddress (extends Address + validationStatus)

Office Layer:
  Office ────── Address (nested)
             └── City → Country

Shipment Layer:
  ShippingLabel ──── ClientProfile     (sender / receiver)
                ──── Address            (senderAddress / receiverAddress)
                ──── ShippingLabelServices
                ──── ReturnInstructionParams ── ClientProfile (rejectReturnClient)
                                             └── Address (rejectReturnAddress)
                ──── Instruction[]
                ──── PackingListElement[]

Enums:
  ShipmentType
  TariffSubCode
  PersonalIDType

Collections:
  CountryCollection<Country>
  CityCollection<City>
  StreetCollection<Street>
  QuarterCollection<Quarter>
  OfficeCollection<Office>

Result DTOs:
  PriceCalculationResult
  ShipmentLabelResult
  ConfirmLabelResult
  CancelLabelResult
  ShipmentTrackingResult
  CourierRequestResult

Exceptions:
  EcontException (base)
  EcontApiException
  EcontNetworkException
  EcontValidationException
```

---

## Entity Specifications

### `Country`

| PHP property | API key | Type | Required | Notes |
|---|---|---|---|---|
| `id` | `id` | `int\|null` | no | |
| `code2` | `code2` | `string` | yes | ISO 3166-1 alpha-2 |
| `code3` | `code3` | `string` | yes | ISO 3166-1 alpha-3 |
| `name` | `name` | `string` | yes | Bulgarian name |
| `nameEn` | `nameEn` | `string` | yes | English name |
| `isEU` | `isEU` | `bool\|null` | no | |

---

### `City`

| PHP property | API key | Type | Required | Notes |
|---|---|---|---|---|
| `id` | `id` | `int\|null` | no | |
| `country` | `country` | `Country` | yes | Nested |
| `postCode` | `postCode` | `string` | yes | |
| `name` | `name` | `string` | yes | Bulgarian |
| `nameEn` | `nameEn` | `string` | yes | English |
| `regionName` | `regionName` | `string\|null` | no | |
| `regionNameEn` | `regionNameEn` | `string\|null` | no | |
| `phoneCode` | `phoneCode` | `string\|null` | no | |
| `location` | `location` | `GeoLocation\|null` | no | |
| `expressCityDeliveries` | `expressCityDeliveries` | `bool\|null` | no | |

---

### `Street`

| PHP property | API key | Type | Required |
|---|---|---|---|
| `id` | `id` | `int` | yes |
| `cityID` | `cityID` | `int` | yes |
| `name` | `name` | `string` | yes |
| `nameEn` | `nameEn` | `string` | yes |

---

### `Quarter`

| PHP property | API key | Type | Required |
|---|---|---|---|
| `id` | `id` | `int` | yes |
| `cityID` | `cityID` | `int` | yes |
| `name` | `name` | `string` | yes |
| `nameEn` | `nameEn` | `string` | yes |

---

### `GeoLocation`

| PHP property | API key | Type | Required |
|---|---|---|---|
| `latitude` | `latitude` | `float` | yes |
| `longitude` | `longitude` | `float` | yes |
| `confidence` | `confidence` | `int\|null` | no |

---

### `Address`

| PHP property | API key | Type | Required | Notes |
|---|---|---|---|---|
| `id` | `id` | `int\|null` | no | |
| `city` | `city` | `City` | yes | |
| `fullAddress` | `fullAddress` | `string\|null` | no | Read-only from API |
| `quarter` | `quarter` | `string\|null` | no | |
| `street` | `street` | `string\|null` | no | |
| `num` | `num` | `string\|null` | no | |
| `other` | `other` | `string\|null` | no | Block/floor/apt info |
| `location` | `location` | `GeoLocation\|null` | no | |
| `zip` | `zip` | `string\|null` | no | |

---

### `ValidatedAddress` (extends `Address`)

| Additional PHP property | API key | Type | Notes |
|---|---|---|---|
| `validationStatus` | `validationStatus` | `string` | `"normal"`, `"processed"`, or `"invalid"` |

---

### `Office`

| PHP property | API key | Type | Required | Notes |
|---|---|---|---|---|
| `id` | `id` | `int` | yes | |
| `code` | `code` | `string` | yes | Use in waybill requests |
| `isMPS` | `isMPS` | `bool` | yes | Mobile office |
| `isAPS` | `isAPS` | `bool` | yes | Econtomat |
| `name` | `name` | `string` | yes | |
| `nameEn` | `nameEn` | `string` | yes | |
| `phones` | `phones` | `string[]` | yes | |
| `emails` | `e-mails` | `string[]` | yes | Note: API key is `e-mails` |
| `address` | `address` | `Address` | yes | |
| `info` | `info` | `string\|null` | no | |
| `currency` | `currency` | `string\|null` | no | |
| `language` | `language` | `string\|null` | no | |
| `normalBusinessHoursFrom` | `normalBusinessHoursFrom` | `int\|null` | no | Unix ms |
| `normalBusinessHoursTo` | `normalBusinessHoursTo` | `int\|null` | no | Unix ms |
| `halfDayBusinessHoursFrom` | `halfDayBusinessHoursFrom` | `int\|null` | no | Unix ms (Saturday) |
| `halfDayBusinessHoursTo` | `halfDayBusinessHoursTo` | `int\|null` | no | Unix ms (Saturday) |
| `shipmentTypes` | `shipmentTypes` | `string[]` | yes | e.g. `["courier","post","cargo"]` |
| `partnerCode` | `partnerCode` | `string\|null` | no | |
| `hubCode` | `hubCode` | `string\|null` | no | |
| `hubName` | `hubName` | `string\|null` | no | |
| `hubNameEn` | `hubNameEn` | `string\|null` | no | |

**Helper methods**:
- `normalBusinessHoursFromAsDateTime(): ?\DateTimeImmutable`
- `normalBusinessHoursToAsDateTime(): ?\DateTimeImmutable`
- `halfDayBusinessHoursFromAsDateTime(): ?\DateTimeImmutable`
- `halfDayBusinessHoursToAsDateTime(): ?\DateTimeImmutable`

---

### `ClientProfile`

| PHP property | API key | Type | Required |
|---|---|---|---|
| `id` | `id` | `int\|null` | no |
| `name` | `name` | `string` | yes |
| `nameEn` | `nameEn` | `string\|null` | no |
| `phones` | `phones` | `string[]` | yes |
| `email` | `e-mail` | `string\|null` | no |
| `skypeAccounts` | `skypeAccounts` | `string[]\|null` | no |
| `clientNumber` | `clientNumber` | `string\|null` | no |
| `clientNumberEn` | `clientNumberEn` | `string\|null` | no |
| `juridicalEntity` | `juridicalEntity` | `int\|null` | no | 0=natural, 1=legal |
| `personalIDType` | `personalIDType` | `string\|null` | no | EGN, PIN, PK, PASSPORT |
| `personalIDNumber` | `personalIDNumber` | `string\|null` | no |
| `companyType` | `companyType` | `string\|null` | no |
| `ein` | `ein` | `string\|null` | no | ЕИК |
| `ddsEinPrefix` | `ddsEinPrefix` | `string\|null` | no |
| `ddsEin` | `ddsEin` | `string\|null` | no |
| `registrationAddress` | `registrationAddress` | `Address\|null` | no |
| `molName` | `molName` | `string\|null` | no |
| `molEGN` | `molEGN` | `string\|null` | no |
| `molIDNum` | `molIDNum` | `string\|null` | no |

---

### `ShippingLabel`

| PHP property | API key | Type | Required | Notes |
|---|---|---|---|---|
| `senderClient` | `senderClient` | `ClientProfile` | yes | |
| `senderAddress` | `senderAddress` | `Address` | yes | |
| `receiverClient` | `receiverClient` | `ClientProfile` | yes | |
| `receiverAddress` | `receiverAddress` | `Address` | yes | |
| `packCount` | `packCount` | `int` | yes | |
| `shipmentType` | `shipmentType` | `ShipmentType` | yes | |
| `weight` | `weight` | `float` | yes | kg |
| `mode` | `mode` | `string` | yes | `"create"`, `"validate"`, `"calculate"` |
| `envelopeNumbers` | `envelopeNumbers` | `int\|null` | no | |
| `sizeUnder60cm` | `sizeUnder60cm` | `bool\|null` | no | Required for `post_pack` |
| `shipmentDimensionsL` | `shipmentDimensionsL` | `float\|null` | no | |
| `shipmentDimensionsW` | `shipmentDimensionsW` | `float\|null` | no | |
| `shipmentDimensionsH` | `shipmentDimensionsH` | `float\|null` | no | |
| `shipmentDescription` | `shipmentDescription` | `string\|null` | no | |
| `orderNumber` | `orderNumber` | `string\|null` | no | |
| `sendDate` | `sendDate` | `string\|null` | no | |
| `holidayDeliveryDay` | `holidayDeliveryDay` | `string\|null` | no | `"workday"` or `"halfday"` or date |
| `keepUpright` | `keepUpright` | `bool\|null` | no | |
| `payAfterAccept` | `payAfterAccept` | `bool\|null` | no | Preview |
| `payAfterTest` | `payAfterTest` | `bool\|null` | no | Preview & test |
| `partialDelivery` | `partialDelivery` | `bool\|null` | no | |
| `packingListType` | `packingListType` | `string\|null` | no | `"file"`, `"digital"`, `"loading"` |
| `packingList` | `packingList` | `PackingListElement[]\|null` | no | |
| `services` | `services` | `ShippingLabelServices\|null` | no | |
| `returnInstructions` | `returnInstructions` | `ReturnInstructionParams\|null` | no | |
| `instructions` | `instructions` | `Instruction[]\|null` | no | |

**Validation rules** (enforced in `EcontValidationException` before HTTP call):
- `shipmentType === ShipmentType::POST_PACK` → `sizeUnder60cm` must be `true`
- `partialDelivery === true` → `returnInstructions` must be set
- `packCount` must be ≥ 1
- `weight` must be > 0

---

### `ShippingLabelServices`

| PHP property | API key | Type | Required |
|---|---|---|---|
| `declaredValueAmount` | `declaredValueAmount` | `float\|null` | no |
| `declaredValueCurrency` | `declaredValueCurrency` | `string\|null` | no | BGN, EUR, USD, RON |
| `deliveryReceipt` | `deliveryReceipt` | `bool\|null` | no |
| `priorityTimeFrom` | `priorityTimeFrom` | `string\|null` | no | `"HH:MM"` |
| `priorityTimeTo` | `priorityTimeTo` | `string\|null` | no | `"HH:MM"` |
| `smsNotification` | `smsNotification` | `bool\|null` | no |

---

### `ReturnInstructionParams`

| PHP property | API key | Type |
|---|---|---|
| `returnParcelDestination` | `returnParcelDestination` | `string\|null` |
| `returnParcelIsDocument` | `returnParcelIsDocument` | `bool\|null` |
| `daysUntilReturn` | `daysUntilReturn` | `int\|null` |
| `returnParcelPaymentSide` | `returnParcelPaymentSide` | `string\|null` |
| `rejectAction` | `rejectAction` | `string\|null` |
| `rejectInstruction` | `rejectInstruction` | `string\|null` |
| `rejectContact` | `rejectContact` | `string\|null` |
| `rejectReturnClient` | `rejectReturnClient` | `ClientProfile\|null` |
| `rejectReturnAgent` | `rejectReturnAgent` | `string\|null` |
| `rejectReturnOfficeCode` | `rejectReturnOfficeCode` | `string\|null` |
| `rejectReturnAddress` | `rejectReturnAddress` | `Address\|null` |
| `rejectOriginalParcelPaySide` | `rejectOriginalParcelPaySide` | `string\|null` |
| `rejectReturnParcelPaySide` | `rejectReturnParcelPaySide` | `string\|null` |
| `printReturnParcel` | `printReturnParcel` | `bool\|null` |
| `signatureDocuments` | `signatureDocuments` | `bool\|null` |
| `signaturePenColor` | `signaturePenColor` | `string\|null` |
| `signatureCount` | `signatureCount` | `int\|null` |
| `signaturePageNumbers` | `signaturePageNumbers` | `string\|null` |
| `signatureOtherInstructions` | `signatureOtherInstructions` | `string\|null` |

---

### `Instruction`

| PHP property | API key | Type |
|---|---|---|
| `type` | `type` | `string` | `"give"` or `"take"` |
| `title` | `title` | `string\|null` |
| `description` | `description` | `string\|null` |
| `attachments` | `attachments` | `array\|null` |
| `voiceDescription` | `voiceDescription` | `string\|null` |
| `name` | `name` | `string\|null` | Template name |
| `applyToAllParcels` | `applyToAllParcels` | `bool\|null` |
| `applyToReceivers` | `applyToReceivers` | `bool\|null` |

---

### `PackingListElement`

| PHP property | API key | Type | Required |
|---|---|---|---|
| `inventoryNum` | `inventoryNum` | `string` | yes |
| `description` | `description` | `string` | yes |
| `weight` | `weight` | `float` | yes |
| `price` | `price` | `float` | yes |
| `count` | `count` | `int` | yes |
| `file` | `file` | `string\|null` | no | base64 |

---

### `CourierRequest`

| PHP property | API key | Type | Required |
|---|---|---|---|
| `senderClient` | `senderClient` | `ClientProfile` | yes |
| `senderAddress` | `senderAddress` | `Address` | yes |
| `fromTime` | `fromTime` | `string` | yes | `"HH:MM"` |
| `toTime` | `toTime` | `string` | yes | `"HH:MM"` |
| `shipmentType` | `shipmentType` | `ShipmentType\|null` | no |
| `weight` | `weight` | `float\|null` | no |

---

## Result DTOs

### `PriceCalculationResult`

| Property | API key | Type |
|---|---|---|
| `totalPrice` | `totalPrice` | `float` |
| `currency` | `currency` | `string` |
| `details` | (varies) | `array` | Raw breakdown for consumers |

### `ShipmentLabelResult`

| Property | API key | Type |
|---|---|---|
| `waybillNumber` | `waybillNumber` | `string` |
| `price` | `price` | `PriceCalculationResult\|null` |
| `rawResponse` | (all) | `array` | Full decoded API response |

### `ConfirmLabelResult`

| Property | API key | Type |
|---|---|---|
| `success` | (derived) | `bool` |
| `rawResponse` | (all) | `array` |

### `CancelLabelResult`

| Property | API key | Type |
|---|---|---|
| `success` | (derived) | `bool` |
| `rawResponse` | (all) | `array` |

### `ShipmentTrackingResult`

| Property | API key | Type |
|---|---|---|
| `waybillNumber` | `waybillNumber` | `string` |
| `events` | (varies) | `array` | Status events |
| `rawResponse` | (all) | `array` |

### `CourierRequestResult`

| Property | API key | Type |
|---|---|---|
| `success` | (derived) | `bool` |
| `rawResponse` | (all) | `array` |

---

## Enums

### `ShipmentType: string`

| Case | Value |
|---|---|
| `DOCUMENT` | `'document'` |
| `PACK` | `'pack'` |
| `POST_PACK` | `'post_pack'` |
| `PALLET` | `'pallet'` |
| `CARGO` | `'cargo'` |
| `DOCUMENTPALLET` | `'documentpallet'` |
| `BIG_LETTER` | `'big_letter'` |
| `SMALL_LETTER` | `'small_letter'` |
| `MONEY_TRANSFER` | `'money_transfer'` |

> `toArray()` on `ShippingLabel` should call `$this->shipmentType->value` for the `shipmentType` key. `fromArray` should handle both upper- and lowercase values via `ShipmentType::from(strtolower($data['shipmentType']))`.

### `TariffSubCode: string`

| Case | Value |
|---|---|
| `DOOR_DOOR` | `'door-door'` |
| `DOOR_OFFICE` | `'door-office'` |
| `OFFICE_DOOR` | `'office-door'` |
| `OFFICE_OFFICE` | `'office-office'` |
| `DOOR_BANK` | `'door-bank'` |
| `OFFICE_BANK` | `'office-bank'` |

---

## State Transitions (ShippingLabel lifecycle)

```
[Draft ShippingLabel]
     │
     ├─ mode="validate"  → [Validated] (no waybill created)
     ├─ mode="calculate" → [PriceCalculated] (no waybill created)
     └─ mode="create"    → [Created] (waybillNumber assigned)
                               │
                               ├─ confirmLabel()   → [Confirmed]
                               ├─ updateLabel()    → [Updated/Created]
                               └─ cancelLabel()    → [Cancelled/Deleted]
```

---

## Exception Hierarchy

```
\Throwable
  └── \Exception
        └── EcontException
              ├── EcontApiException       (+apiErrorCode, +apiErrorMessage)
              ├── EcontNetworkException   (+$previous Throwable)
              └── EcontValidationException (+violations: string[])
```

