# Storage Keys: Website Forms API (mobile app)

These endpoints do the same job as the forms on the Storage Keys website, so the app can show the same forms and send them to the same place (CRM, email to the team, Google Sheet).

| Website form | API |
|---|---|
| "Get a Free Quote" / Contact Us form (every service and location page) | `POST /api/website/inquiries` |
| Booking page search (country, city, location, level, type) | `GET /api/website/storage-units` |
| Reservation form (`/reservation/{unit}`) | `POST /api/website/reservations` |
| Dropdowns used by those forms | `GET /api/website/form-options`, `/countries`, `/cities`, `/locations`, `/warehouses` |

Customer login, register, profile, invoices, contracts, payments, move-in / move-out and account deletion already have their own API under `/customer-api/...`. They are not repeated here.

---

## Basics

- **Base URL:** `https://storagekeys.com/api/website`
- **Login:** none. These are public endpoints, just like the website forms.
- **Send JSON:** `Content-Type: application/json` and `Accept: application/json`.
- **Rate limits:** read endpoints allow 60 requests per minute per IP. `inquiries` and `reservations` allow **5 per minute per IP**, and the 6th gets HTTP `429`.

### Response format

Every response has the same shape.

Success:
```json
{
  "success": true,
  "status": true,
  "message": "Success",
  "data": { }
}
```

Error:
```json
{
  "success": false,
  "status": false,
  "message": "Validation failed.",
  "errors": {
    "email": ["The email field is required."]
  }
}
```

| HTTP | Meaning |
|---|---|
| 200 / 201 | OK / created |
| 404 | Not found (for example, the storage unit is no longer available) |
| 422 | Validation error. `errors` lists the messages for each field; show them under the matching inputs. |
| 429 | Too many requests. Wait a minute and try again. |
| 500 | Server problem. Show "Please try again later." |

---

## 1. Form options

`GET /form-options`

Call this once when the app opens a form. It returns every list the forms need.

```json
{
  "success": true,
  "data": {
    "inquiry_storage_types": ["Personal Storage", "Business Storage", "Warehouse Storage", "Climate Controlled Storage", "Moving", "Luggage Storage", "Car Storage"],
    "countries": [{ "id": 1, "name": "United Arab Emirates", "is_default": true }],
    "storage_levels": [{ "id": 1, "name": "Ground Floor" }],
    "storage_types": [{ "id": 1, "name": "Standard" }],
    "storage_sizes": [{ "id": 1, "name": "Small", "width": "5", "length": "5", "height": "8" }],
    "term_lengths": [{ "id": 1, "title": "1 Month", "term_period": 1, "discount_percentage": 0, "description": "..." }],
    "addons": [{ "id": 1, "name": "Lock", "price": "25" }],
    "insurance_packages": [{ "id": 1, "name": "Basic", "monthly_amount": "30", "cover": "10000" }],
    "reservation_terms_html": "<p>Terms and conditions ...</p>"
  }
}
```

- `inquiry_storage_types` is the "Storage type" dropdown in the quote form.
- `reservation_terms_html` is HTML. Show it in a WebView or an HTML text view next to the "I agree" checkbox on the reservation form.

---

## 2. Location dropdowns (step by step)

The booking and reservation forms pick **Country → City → Location → Warehouse**. Load each list after the user picks the one before it.

| Request | Parameter | Returns |
|---|---|---|
| `GET /countries` | none | `[{ id, name, is_default }]`. Pre-select the country with `is_default: true`. |
| `GET /cities?country_id=1` | `country_id` (required) | `[{ id, name, country_id }]` |
| `GET /locations?city_id=3` | `city_id` (required) | `[{ id, name, city_id, lat, lng }]` |
| `GET /warehouses?location_id=7` | `location_id` (required) | `[{ id, name, location_id, description }]` |

When the user changes a higher dropdown, clear the dropdowns below it.

---

## 3. Search storage units

`GET /storage-units`

Returns only units that are free to book now (vacant, not in maintenance), the same as the website booking page. Every parameter is optional; send only the filters the user picked.

| Parameter | Type | Example |
|---|---|---|
| `country_id` | int | `1` |
| `city_id` | int | `3` |
| `location_id` | int | `7` |
| `warehouse_id` | int | `2` |
| `level_ids[]` | int array | `level_ids[]=1&level_ids[]=2` |
| `type_ids[]` | int array | `type_ids[]=1` |
| `size_id` | int | `4` |

Example: `GET /storage-units?country_id=1&city_id=3&level_ids[]=1&type_ids[]=2`

```json
{
  "success": true,
  "data": [
    {
      "id": 41,
      "name": "A-101",
      "width": "5",
      "length": "10",
      "height": "8",
      "price": 650,
      "status": "vacant",
      "type": { "id": 2, "name": "Standard" },
      "level": { "id": 1, "name": "Ground Floor" },
      "size": { "id": 4, "name": "Medium" },
      "warehouse": { "id": 2, "name": "Al Quoz Warehouse" },
      "location": { "id": 7, "name": "Al Quoz" },
      "city": { "id": 3, "name": "Dubai" },
      "country": { "id": 1, "name": "United Arab Emirates" }
    }
  ]
}
```

### One unit

`GET /storage-units/{id}` returns the same object for a single unit. It returns `404` if the unit no longer exists or has been booked. Call it before opening the reservation screen.

---

## 4. Quote / contact form

`POST /inquiries`

| Field | Required | Type | Notes |
|---|---|---|---|
| `name` | yes | string, max 255 | |
| `email` | yes | email | |
| `phone` | yes | string, max 40 | Phone / WhatsApp, e.g. `+971501234567` |
| `storage_type` | yes | string | One value from `inquiry_storage_types` |
| `message` | no | string, max 5000 | |
| `company` | no | string | Business / warehouse version of the form |
| `size` | no | string | e.g. "Studio", "1 Bedroom" |
| `storing` | no | string | What they are storing |
| `duration` | no | string | e.g. "3 months" |
| `volume` | no | string | e.g. "50 pallets" |
| `items` | no | string | Item list |
| `source` | no | string | Where in the app the form was sent from, e.g. `app-home`, `app-car-storage`. Defaults to `mobile-app`. |

Request:
```json
{
  "name": "Ahmed Khan",
  "email": "ahmed@example.com",
  "phone": "+971501234567",
  "storage_type": "Personal Storage",
  "message": "I need a unit for 3 months",
  "source": "app-home"
}
```

Response `201`:
```json
{
  "success": true,
  "status": true,
  "message": "Thank you. Our team will contact you soon.",
  "data": { "inquiry_id": 128, "reference": "SK-00128" }
}
```

Show `reference` on the thank-you screen.

> The server runs the same spam checks as the website. Messages that look like bots also get a `201` response, but they are not saved. A real user will never notice this.

---

## 5. Reservation form

`POST /reservations`

| Field | Required | Type | Notes |
|---|---|---|---|
| `storage_unit_ids` | yes | int array, 1–20 | Unit IDs from the search. The first one is the main unit. |
| `type` | yes | `individual` or `company` | |
| `company_name` | if `type` = `company` | string | |
| `move_in_date` | yes | date `YYYY-MM-DD` | Today or later |
| `first_name` | yes | string | |
| `last_name` | yes | string | |
| `email` | yes | email | |
| `phone` | yes | string | |
| `mobile1` | no | string | Second number |
| `mobile2` | no | string | Third number |
| `term_length_id` | no | int | An `id` from `term_lengths` |
| `addon_ids` | no | int array | IDs from `addons` |
| `insurance_id` | no | int | An `id` from `insurance_packages`. Leave it out for "No thanks". |
| `goods_value` | no | number | Value of the goods, used with insurance |
| `accept_terms` | yes | `true` | The user must tick "I agree to the terms and conditions" |

Request:
```json
{
  "storage_unit_ids": [41, 42],
  "type": "individual",
  "move_in_date": "2026-12-01",
  "first_name": "Ahmed",
  "last_name": "Khan",
  "email": "ahmed@example.com",
  "phone": "+971501234567",
  "term_length_id": 2,
  "addon_ids": [1, 3],
  "insurance_id": 1,
  "goods_value": 15000,
  "accept_terms": true
}
```

Response `201`:
```json
{
  "success": true,
  "status": true,
  "message": "Thanks for selecting Storage Keys. Our team will contact you soon.",
  "data": { "lead_id": 512, "reference": "SK-L00512" }
}
```

If a unit was booked by someone else in the meantime, you get `422` with a message such as `"Storage unit #41 was not found."` or `"... is not available"`. Show the message and send the user back to the search.

The customer gets a confirmation email and the Storage Keys team is notified, the same as when the form is sent from the website.

---

## Suggested app flow

1. Open the app: `GET /form-options`
2. **Quote:** fill in the form, then `POST /inquiries`, then show the thank-you screen with `reference`.
3. **Booking:** pick country → city → location (step 2), then `GET /storage-units` with the filters, then pick unit(s).
4. **Reservation:** `GET /storage-units/{id}` (check it is still free), fill in the form, then `POST /reservations`.

## Quick test (curl)

```bash
curl https://storagekeys.com/api/website/form-options

curl -X POST https://storagekeys.com/api/website/inquiries \
  -H "Content-Type: application/json" -H "Accept: application/json" \
  -d '{"name":"Test User","email":"test@example.com","phone":"+971501234567","storage_type":"Personal Storage","message":"API test"}'
```

> Note: the test inquiry above is saved and emailed to the team. Tell the Storage Keys team before you send test entries to the live site.
