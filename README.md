# Charj.in CI4 Starter

A mobile-first CodeIgniter 4 + MySQL + Tailwind CSS starter for an EV discovery, comparison, recommendation, review, knowledge and lead generation platform.

## What is included

- Public home page
- Vehicle listing and detail pages
- Vehicle comparison page
- EV running cost calculator
- Lead capture workflow
- Admin login placeholder
- Admin dashboard
- Vehicle and lead admin views
- MySQL schema
- CI4-style models and controllers
- Brevo email service wrapper
- Mobile-first Tailwind layouts
- SEO-ready page metadata structure

## Quick setup

1. Install Composer if not already installed.
2. Run:

```bash
composer install
cp env.example .env
```

3. Create MySQL database:

```sql
CREATE DATABASE charj CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

4. Import the starter schema:

```bash
mysql -u root -p charj < sql/charj_schema.sql
```

5. Update `.env` database and Brevo credentials.
6. Run locally:

```bash
php spark serve
```

Open `http://localhost:8080`.

## Suggested next development steps

- Install full CodeIgniter 4 app skeleton if your server does not already have it.
- Copy the `app`, `public`, `sql`, and `docs` folders into the CI4 app.
- Add authentication using CI4 Shield or custom auth.
- Connect admin form actions to create/update/delete methods.
- Add dealer dashboard and role permissions.
- Replace Tailwind CDN with compiled Tailwind for production.
- Connect Brevo templates and contact lists.

## Production notes

- Use HTTPS.
- Set `CI_ENVIRONMENT = production`.
- Disable debug toolbar.
- Use compiled/minified CSS.
- Configure form rate limits and CSRF.
- Add queue/cron for heavy email workflows.
