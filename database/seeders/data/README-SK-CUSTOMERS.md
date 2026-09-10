# SK Customers PDF seeder

Data source: `SK_Customers_Details.pdf` → parsed into `database/seeders/data/sk_customers.json`.

## Live deploy (after code deploy + migrate)

```bash
php artisan db:seed --class=Database\\Seeders\\SkCustomersSeeder
```

Or:

```bash
php artisan db:seed --class=SkCustomersSeeder
```

Safe to re-run: existing emails are skipped.

## Re-generate JSON from PDF (local only)

```bash
python scripts/parse_sk_customers_pdf.py
copy storage\app\sk_customers_import.json database\seeders\data\sk_customers.json
```

## Notes

- Unique emails from PDF ≈ **2617** (PDF had ~2729 email rows; duplicate emails collapsed so contacts.email unique constraint is respected).
- Each row creates `customers` + primary `contacts` (**In-Active** `status=0`, `is_deleted=0`).
- Re-running the seeder also sets matching existing emails to In-Active.
- Not called from `DatabaseSeeder` automatically (avoids running on every fresh install).
