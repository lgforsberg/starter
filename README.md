# Starter

Minimal PHP starter for small client websites. Server-rendered, explicit, boring.

## Stack

- **PHP 8.3** with Slim 4 for routing
- **Homegrown View class** — layouts, partials, auto-escaping, no magic
- **HTMX** for server-driven interactivity (no SPA, no build step)
- **Alpine.js** for small UI behaviour (menus, toggles)
- **Monolog** for logging
- **PDO + PostgreSQL** when a database is needed
- **Plain CSS** — no preprocessor, no framework

## Quick Start

```bash
# Clone (or create from template)
git clone git@github.com:lgforsberg/starter.git mysite
cd mysite

# Install dependencies
composer install

# Configure environment
cp .env.example .env
# Edit .env with your settings

# Run locally
php -S localhost:8080 -t public
```

## Directory Structure

```
public/              ← Web root (Nginx points here)
  index.php          ← Front controller
  assets/            ← Static files (css, js, images)
app/
  Controllers/       ← Request handlers
  Middleware/        ← HTTP middleware
  Services/          ← Business logic (add as needed)
  Repositories/      ← Data access (add as needed)
  Support/           ← Framework helpers (Container, View, Session, Csrf)
  Views/
    layouts/         ← Page shells
    partials/        ← Reusable fragments (header, footer)
    pages/           ← Full page templates
    fragments/       ← HTMX response fragments
  routes.php         ← All route definitions
config/
  app.php            ← Application config
  database.php       ← Database config
  services.php       ← Dependency wiring (the DI container)
storage/
  cache/             ← App cache (writable by www-data)
  logs/              ← Log files (writable by www-data)
```

## Conventions

### Views

Templates are plain PHP. The View class provides:

- `$__view->layout('layouts/default', ['title' => 'Page'])` — wrap in a layout
- `$__view->partial('partials/header')` — include a partial
- `$__view->e($value)` — HTML-escape a string
- `$__view->isHtmx($request)` — check if the request came from HTMX

Layout files receive `$content` containing the rendered page.

### HTMX

For HTMX responses, use `renderFragment()` to return just the HTML fragment without the layout wrapper.

### CSRF

Forms that mutate data (POST/PUT/DELETE) must include a CSRF token:

```php
<?= $csrf->field() ?>
```

Add `CsrfMiddleware` to the route or group.

### Session & Flash Messages

```php
$session->flash('success', 'Saved!');
$message = $session->getFlash('success');
```

### Adding Services

Wire new services in `config/services.php`:

```php
$container->set(MyService::class, function ($c) {
    return new MyService($c->get(SomeDependency::class));
});
```

Then type-hint in your controller constructor.

### Database

If you need a database, fill in `DB_*` values in `.env`. The PDO instance is available from the container:

```php
$pdo = $container->get(\PDO::class);
```

Returns `null` if database config is empty (no crash on sites that don't need a DB).

### Rate Limiting

The contact form includes file-based rate limiting (5 attempts per IP per 15 min). To use in your own routes:

```php
if ($this->rateLimiter->tooManyAttempts('action:' . $ip, 5, 900)) {
    // Return 429 response
}
$this->rateLimiter->hit('action:' . $ip, 900);
```

Note: Uses `REMOTE_ADDR`. If behind Cloudflare/proxy, adapt to read `X-Forwarded-For`.

### Spam Protection

Forms include a honeypot field. Add to any form:

```php
<?= $honeypot->field() ?>
```

Check in your controller before processing: `$this->honeypot->isSpam($body)`.

### URL Helpers

```php
$__view->url('/about')    // https://yoursite.com/about (absolute)
$__view->asset('css/style.css')  // /assets/css/style.css
```

### SEO / Open Graph

Pass `description`, `ogImage`, and/or `canonical` in your render data:

```php
return $this->view->render($response, 'pages/about', [
    'title' => 'About Us',
    'description' => 'We build great websites.',
    'ogImage' => 'https://yoursite.com/assets/images/og-about.jpg',
]);
```

### Site Imagery

Images are generated at build-time using Khaali's `~/bin/imagine` tool (Nano Banana Pro) or the Cursor built-in image tool, then placed in `public/assets/images/`. They are not generated at runtime.

## Deployment (on the Markedo server)

```bash
# Clone to ~/git/
cd ~/git && git clone git@github.com:lgforsberg/mysite.git

# Create deploy script at ~/bin/deploy/mysite.sh
# Create Nginx vhost, run certbot
# Add to webhook if using auto-deploy
```

See the deploy scripts in `~/bin/deploy/` on the server for reference.

## License

MIT
