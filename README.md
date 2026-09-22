# Gam3a Backend

Laravel API backend for the university application platform.

## Architecture

- WordPress owns articles, guides, and editorial content.
- Laravel owns users, applications, admission rules, operations dashboards, payments, and business workflow.
- PostgreSQL is the source of truth for business data.
- S3 stores student documents.
- Next.js consumes the API for public pages, student dashboard, and admin operations.

## Main API

- `GET /api/universities`
- `GET /api/universities/{slug}`
- `GET /api/programs`
- `GET /api/certificate-tracks`
- `GET /api/calculator-rules`
- `POST /api/calculate-equivalency`
- `GET /api/equivalency-centers`
- `POST /api/auth/register`
- `POST /api/auth/login`
- `GET /api/dashboard`
- `POST /api/applications`
- `POST /api/applications/{id}/documents`
- `GET /api/content/articles`
- `GET /api/content/articles/{slug}`
- `GET /api/admin/dashboard`
- `GET /api/admin/applications`
- `PATCH /api/admin/applications/{id}/status`
- `PATCH /api/admin/applications/{id}/documents/{document}/status`
- `GET|POST|PATCH|DELETE /api/admin/{resource}`

Admin resources:

- `universities`
- `faculties`
- `programs`
- `certificate-tracks`
- `admission-rules`
- `calculator-rules`
- `equivalency-centers`

## Postman

Import this collection:

```text
postman/gam3a-api.postman_collection.json
```

Collection variables:

- `base_url`: defaults to `http://127.0.0.1:8000/api`
- `token`: set automatically after login if the response contains a token

## Admin Panel

The operations team can use a browser-based admin panel, not only APIs:

```text
http://127.0.0.1:8000/admin/login
```

Seeded admin credentials:

```text
email: admin@gam3a.local
password: password
```

The admin panel supports:

- Applications review and status updates
- Universities, faculties, and programs management
- Certificate tracks
- Admission rules
- Calculator rules
- Equivalency centers

## Setup

Composer is currently blocked on this machine by a local SSL issuer problem. After fixing Composer certificates, run:

```bash
composer install
copy .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

Set PostgreSQL and S3 values in `.env`. For local development without S3, set:

```dotenv
FILESYSTEM_DISK=local
```

Set `WORDPRESS_URL` to the WordPress site root, not the REST path.
