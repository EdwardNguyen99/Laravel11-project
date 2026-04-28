# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Laravel 11 e-learning platform using a custom modular (HMVC) architecture. Manages online courses, students, teachers, orders, and learning progress. Currently implementing User and Admin modules with Sanctum-based API authentication.

## Development Commands

```bash
# Start development (server, queue, logs, and vite concurrently)
composer run dev

# Run tests
php artisan test
./vendor/bin/phpunit

# Code formatting (auto-fix)
./vendor/bin/pint

# Code standards check / auto-fix
composer phpcs
composer phpcbf

# Create a new module
php artisan make:module ModuleName
```

## Architecture

### Module System

Modules live in `/modules/` with PSR-4 autoloading (`Modules\` namespace). The `ModuleServiceProvider` (`modules/ModuleServiceProvider.php`) auto-discovers and registers all modules during boot — loading routes, migrations, translations, views, helpers, and configs.

Full module structure:

```
modules/{ModuleName}/
├── configs/           # Merged into app config via ModuleServiceProvider
├── helpers/           # PHP files auto-required (helper functions)
├── migrations/        # Auto-discovered migrations
├── seeders/           # Database seeders
├── routes/
│   ├── routes.php     # Web routes (loaded as-is)
│   └── api.php        # API routes (auto-prefixed with /api, api middleware applied)
├── resources/
│   ├── lang/          # Translations
│   └── views/         # Blade templates (namespace: lowercase module name)
└── src/
    ├── Commands/
    ├── Http/
    │   ├── Controllers/       # Web controllers
    │   ├── Controllers/Api/   # API controllers
    │   ├── Middlewares/
    │   ├── Requests/          # Form validation requests
    │   └── Resources/         # API resource transformers
    ├── Models/
    └── Repositories/
```

### Repository Pattern

All database access goes through repositories:
- Interface: `App\Repositories\RepositoryInterface` — `all()`, `find($id)`, `create($data)`, `update($data, $id)`, `delete($id)`
- Base: `App\Repositories\BaseRepository` — abstract, requires `getModel(): string`
- Module repositories extend `BaseRepository` and add domain-specific methods
- Registered as singletons in `ModuleServiceProvider::register()`

### Authentication (Sanctum)

Uses `laravel/sanctum` for API token authentication. The `User` model uses the `HasApiTokens` trait.

**User roles via `group_id`**: `0` = regular user, `1` = admin
**User status**: `1` = active, `0` = inactive

Token creation: `$user->createToken('auth_token')->plainTextToken`
Protected routes use: `middleware('auth:sanctum')`

### Middleware

Custom middleware is registered as aliases in `bootstrap/app.php`:

```php
$middleware->alias([
    'admin' => \Modules\Admin\src\Http\Middlewares\AdminMiddleware::class,
]);
```

The `admin` middleware checks `auth` status and `group_id == 1`.

### API Response Conventions

- All responses use `response()->json()`
- User data is always transformed through `UserResource` (adds computed `group_name`, `status_name`)
- Paginated lists include: `data`, `current_page`, `last_page`, `per_page`, `total`
- HTTP 201 for creation, 200 for everything else

### View Namespacing

Module views use lowercase module name: `view('admin::pages.login')`, `view('user::lists')`

## Key Patterns

- **New API endpoint**: Add controller in `modules/{Module}/src/Http/Controllers/Api/`, validate with a `Requests/` class, transform output with an API Resource, register route in `modules/{Module}/routes/api.php`
- **New web page**: Add controller in `modules/{Module}/src/Http/Controllers/`, add view in `modules/{Module}/resources/views/`, register route in `modules/{Module}/routes/routes.php`
- **Data access**: Always use a repository; never query Eloquent directly in controllers
- **Middleware for new modules**: Register aliases in `bootstrap/app.php`
