# Charj.in Implementation Notes

## MVP modules included

1. Public EV discovery pages
2. Vehicle detail pages
3. Comparison page
4. Running cost calculator
5. Lead form and lead submission controller
6. Admin dashboard
7. Admin vehicle list
8. Admin lead list
9. Brevo email service wrapper
10. MySQL schema with seed data

## Recommended production enhancements

### Authentication
Use CI4 Shield or a custom RBAC system. The included admin auth is a placeholder and must not be used in production.

### Lead assignment
Add a LeadAssignmentService that checks:
- city
- pincode
- brand
- category
- dealer subscription plan
- dealer lead quota
- round-robin sequence

### Brevo workflows
The current service sends transactional emails. For marketing workflows, create contacts in Brevo lists and trigger automations using list IDs and attributes such as:
- LEAD_TYPE
- CITY
- VEHICLE_CATEGORY
- BUDGET
- PURCHASE_TIMELINE
- FINANCE_REQUIRED
- CHARGING_REQUIRED

### SEO
Add dynamic metadata, schema JSON, XML sitemap and programmatic landing page generation.

### Tailwind
This starter uses Tailwind CDN for speed. For production, install Tailwind CLI/Vite and compile CSS.

### Vehicle data
Replace demo entries with accurate data from verified OEM/dealer sources. Keep price, range, warranty and subsidy timestamps.

## Suggested module build order

1. Full vehicle CRUD
2. Full lead CRM
3. Dealer dashboard
4. Recommendation quiz scoring
5. SEO article system
6. City-wise pricing
7. Charging installation workflow
8. Partner dashboards
9. Payment/subscription module
10. Used EV marketplace
