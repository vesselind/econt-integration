# API Contract: Econt SOAP/JSON API (v1.0)

**Contract type**: HTTP REST-like (JSON over HTTP POST with Basic Auth)
**API version**: 1.0 (24 April 2018)
**Base URLs**:
- Demo: `https://demo.econt.com/ee/services/`
- Production: `https://ee.econt.com/services/`

---

## Authentication

All requests use **HTTP Basic Authentication**:

```
Authorization: Basic base64(username:password)
Content-Type: application/json
```

Demo credentials: `iasp-dev` / `1Asp-dev`

---

## Nomenclatures Service

### `getCountries`

**Endpoint**: `POST Nomenclatures/NomenclaturesService.getCountries.json`

**Request**:
```json
{"GetCountriesRequest": ""}
```

**Response**:
```json
{
  "countries": [
    {
      "id": null,
      "code2": "BG",
      "code3": "BGR",
      "name": "България",
      "nameEn": "Bulgaria",
      "isEU": true
    }
  ]
}
```

---

### `getCities`

**Endpoint**: `POST Nomenclatures/NomenclaturesService.getCities.json`

**Request**:
```json
{"countryCode": "BGR"}
```

**Response**:
```json
{
  "cities": [
    {
      "id": 41,
      "country": {"id": null, "code2": "BG", "code3": "BGR", "name": "България", "nameEn": "Bulgaria", "isEU": true},
      "postCode": "1000",
      "name": "Sofia",
      "nameEn": "Sofia",
      "regionName": "Sofia",
      "regionNameEn": "Sofia",
      "phoneCode": "2",
      "location": {"latitude": 42.698, "longitude": 23.322, "confidence": 3},
      "expressCityDeliveries": true
    }
  ]
}
```

---

### `getStreets`

**Endpoint**: `POST Nomenclatures/NomenclaturesService.getStreets.json`

**Request**:
```json
{"cityID": "41"}
```

**Response**:
```json
{
  "streets": [
    {"id": 6127, "cityID": 46, "name": "ул. Пейо К.Яворов", "nameEn": "ul. Peyo K.YAvorov"}
  ]
}
```

---

### `getQuarters`

**Endpoint**: `POST Nomenclatures/NomenclaturesService.getQuarters.json`

**Request**:
```json
{"cityID": "41"}
```

**Response**:
```json
{
  "quarters": [
    {"id": 1, "cityID": 41, "name": "кв. Лозенец", "nameEn": "kv. Lozenets"}
  ]
}
```

---

### `getOffices`

**Endpoint**: `POST Nomenclatures/NomenclaturesService.getOffices.json`

**Request** (both parameters optional):
```json
{"countryCode": "BGR", "cityID": "47"}
```

**Response**:
```json
{
  "offices": [
    {
      "id": 839,
      "code": "9707",
      "isMPS": false,
      "isAPS": false,
      "name": "Шумен Осми март",
      "nameEn": "Shumen Osmi mart",
      "phones": [],
      "e-mails": [],
      "address": {
        "id": null,
        "city": {
          "id": 47,
          "country": {"id": null, "code2": "BG", "code3": "BGR", "name": null, "nameEn": null, "isEU": null},
          "postCode": "9700",
          "name": "Шумен",
          "nameEn": "Shumen",
          "regionName": null,
          "regionNameEn": null,
          "phoneCode": null,
          "location": null,
          "expressCityDeliveries": null
        },
        "fullAddress": "Шумен кв. Шумен ул. Петра №22",
        "quarter": "кв. Шумен",
        "street": "ул. Петра",
        "num": "22",
        "other": "",
        "location": {"latitude": 43.265506, "longitude": 26.932889, "confidence": 3},
        "zip": null
      },
      "info": "ТП;...",
      "currency": "BGN",
      "language": "bg",
      "normalBusinessHoursFrom": 1524117600000,
      "normalBusinessHoursTo": 1524150000000,
      "halfDayBusinessHoursFrom": 1524117600000,
      "halfDayBusinessHoursTo": 1524132000000,
      "shipmentTypes": ["courier", "post", "cargo"],
      "partnerCode": "",
      "hubCode": "9709",
      "hubName": "Шумен",
      "hubNameEn": "Shumen"
    }
  ]
}
```

---

### `validateAddress`

**Endpoint**: `POST Nomenclatures/NomenclaturesService.validateAddress.json`

**Request**:
```json
{
  "address": {
    "city": {
      "country": {"code2": "BG"},
      "name": "Русе"
    },
    "street": "Славянска",
    "num": "16"
  }
}
```

**Response**:
```json
{
  "address": {
    "id": null,
    "city": {
      "id": 35,
      "country": {"id": 1033, "code2": "BG", "code3": null, "name": "България", "nameEn": "Bulgaria", "isEU": true},
      "postCode": "7000",
      "name": "Русе",
      "nameEn": "Ruse",
      "regionName": "Русе",
      "regionNameEn": "Ruse",
      "phoneCode": "82",
      "location": null,
      "expressCityDeliveries": null
    },
    "fullAddress": "ул. Славянска 16",
    "quarter": "",
    "street": "ул. Славянска",
    "num": "16",
    "other": null,
    "location": {"latitude": 43.8466058, "longitude": 25.9486055, "confidence": 3},
    "zip": null
  },
  "validationStatus": "normal"
}
```

**`validationStatus` values**: `"normal"` | `"processed"` | `"invalid"`

---

## Label Service

### `createLabel` / `validateLabel` / `calculatePrice`

All three share one endpoint, differentiated by `mode`.

**Endpoint**: `POST Shipments/LabelService.createLabel.json`

**Request** (minimum for price calculation):
```json
{
  "label": {
    "senderClient": {"name": "Иван Иванов", "phones": ["0888888888"]},
    "senderAddress": {
      "city": {"country": {"code3": "BGR"}, "name": "Русе", "postCode": "7012"},
      "street": "Алея Младост", "num": "7"
    },
    "receiverClient": {"name": "Богдан Богданов", "phones": ["0878787878"]},
    "receiverAddress": {
      "city": {"country": {"code3": "BGR"}, "name": "Русе", "postCode": "7010"},
      "street": "Муткурова", "num": "84", "other": "бл. 5, вх. А, ет. 6"
    },
    "packCount": 1,
    "shipmentType": "PACK",
    "weight": 5,
    "shipmentDescription": "обувки"
  },
  "mode": "calculate"
}
```

**mode values**: `"create"` | `"validate"` | `"calculate"`

**Response** (calculate mode — price fields):
```json
{
  "label": {
    "price": {
      "totalPrice": 5.40,
      "currency": "BGN",
      ...
    }
  }
}
```

**Response** (create mode — waybill assigned):
```json
{
  "label": {
    "shipmentNumber": "1234567890",
    "price": {...}
  }
}
```

---

### `confirmLabel` (processLabel)

**Endpoint**: `POST Shipments/LabelService.processLabel.json`

**Request**:
```json
{"waybillNumber": "1234567890"}
```

---

### `cancelLabel` (deleteLabel)

**Endpoint**: `POST Shipments/LabelService.deleteLabel.json`

**Request**:
```json
{"waybillNumber": "1234567890"}
```

---

### `trackShipment` (getWaybillContents)

**Endpoint**: `POST Shipments/LabelService.getWaybillContents.json`

**Request**:
```json
{"waybillNumber": "1234567890"}
```

---

### `requestCourier`

**Endpoint**: `POST Shipments/LabelService.requestCourier.json`

**Request**:
```json
{
  "senderClient": {"name": "...", "phones": ["..."]},
  "senderAddress": {...},
  "fromTime": "09:00",
  "toTime": "17:00"
}
```

---

## Error Responses

When the API encounters a business-level error it returns an `error` object in the JSON body (or an HTTP 4xx status). The library maps these to `EcontApiException`:

```json
{
  "error": {
    "code": "ERR_AUTH",
    "message": "Authentication failed"
  }
}
```

Network-level failures (5xx, timeout, DNS) are mapped to `EcontNetworkException`.

