# Charj.in — Hostinger Deployment Checklist

## Database
- Host: localhost
- DB Name: u504377054_charj
- DB User: u504377054_charj
- DB Pass: Anushka3221

## Pre-deployment Steps

1. Upload all files EXCEPT:
   - `.env` (create fresh on server)
   - `vendor/` (run composer install on server, or upload)
   - `writable/` (create fresh, set permissions)

2. Upload `sql/charj_schema.sql` and import via Hostinger phpMyAdmin

3. Create `.env` on server with:
```
CI_ENVIRONMENT = production

app.baseURL = 'https://charj.in/'

database.default.hostname = localhost
database.default.database = u504377054_charj
database.default.username = u504377054_charj
database.default.password = Anushka3221
database.default.DBDriver = MySQLi

BREVO_API_KEY = YOUR_BREVO_API_KEY
BREVO_SENDER_EMAIL = hello@charj.in
BREVO_SENDER_NAME = Charj.in
ADMIN_EMAIL = admin@charj.in

GA_MEASUREMENT_ID = G-XXXXXXXXXX
META_PIXEL_ID = XXXXXXXXXXXXXXXXXX

ADMIN_USERNAME = admin@charj.in
```

4. Run `composer install --no-dev` on server (via SSH)

5. Set `writable/` folder permissions to 755

6. Point domain to `public/` folder as document root in Hostinger

## Admin Panel
- URL: https://charj.in/admin/login
- Email: admin@charj.in
- Password: Charj@2024!

## Post-launch
- [ ] Add GA4 Measurement ID to .env
- [ ] Add Meta Pixel ID to .env
- [ ] Add Brevo API key to .env
- [ ] Submit sitemap.xml to Google Search Console: https://charj.in/sitemap.xml
- [ ] Add vehicles, brands, dealers via Admin panel
- [ ] Configure Brevo email lists
