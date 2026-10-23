# Configuration

Settings are environment variables. Locally they live in `.env` (copy `.env.example`). On a host they are set in its dashboard.

| Variable | What it does | Needed for |
|---|---|---|
| `APP_KEY` | Encrypts sessions and cookies. Make one with `php artisan key:generate` | Everything |
| `APP_URL` | The public address of the site | Links in emails and some redirects |
| `APP_ENV`, `APP_DEBUG` | Use `production` and `false` when the site is public | Safety |
| `APP_BRAND` | The wordmark in the top bar. Default `LUXWATCH` | Branding |
| `DB_CONNECTION` | `sqlite`, `mysql` or `pgsql` | The database |
| `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` | Where the database is. `DB_URL` or `MYSQL*` also work | The database |
| `DB_SSLMODE` | `require` for Neon and other hosted Postgres | Postgres |
| `MYSQL_ATTR_SSL_CA` | Path to the certificates for hosted MySQL such as TiDB | MySQL |
| `SEED_PASSWORD` | Password given to the sample admin, seller and customer. Default `password` | Seeding |
| `STRIPE_SECRET` | Stripe test or live secret key | Checkout and seller payouts |
| `GOOGLE_CLIENT_ID`, `GOOGLE_CLIENT_SECRET`, `GOOGLE_REDIRECT_URL` | Google sign in on the web | Google sign in |
| `FIREBASE_WEB_API_KEY`, `FIREBASE_CREDENTIALS` | Verifies Google sign in from the Flutter app | The mobile app |
| `CORS_ALLOWED_ORIGINS` | Comma separated sites allowed to call the API from a browser. Default: any | The web version of the app |
| `MAIL_*` | A mail service | Email checks, password reset |
| `SESSION_DRIVER`, `CACHE_STORE`, `QUEUE_CONNECTION` | Where sessions, cache and jobs live. `vercel.json` sets cookie, file and sync | Hosting |

Never commit a real `.env`. Keys for Stripe, Google and the database belong in the host's settings.
