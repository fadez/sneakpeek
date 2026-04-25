<p align="center">
    <img src="public/logo.svg" width="100" alt="SneakPeek" />
    <p align="center">
        <a href="https://github.com/fadez/sneakpeek/actions/workflows/ci.yml"><img alt="CI status" src="https://img.shields.io/github/actions/workflow/status/fadez/sneakpeek/ci.yml?style=flat&logo=github&logoColor=959DA5&label=CI&labelColor=24292E" /></a>
        <a href="https://github.com/fadez/sneakpeek/actions/workflows/cd.yml"><img alt="CD status" src="https://img.shields.io/github/actions/workflow/status/fadez/sneakpeek/cd.yml?style=flat&logo=github&logoColor=959DA5&label=CD&labelColor=24292E" /></a>
        <a href="phpstan.neon"><img alt="PHPStan level" src="https://img.shields.io/badge/PHPStan-Level_10-success?style=flat&labelColor=24292E" /></a>
        <a href="composer.json"><img alt="Type coverage" src="https://img.shields.io/badge/Type_coverage-100%25-success?style=flat&labelColor=24292E" /></a>
    </p>
    <p align="center">
        <a href="https://www.php.net"><img alt="PHP version" src="https://img.shields.io/badge/PHP-v8.5-777bb3?style=flat&logo=php&logoColor=777bb3&labelColor=24292E" /></a>
        <a href="https://laravel.com"><img alt="Laravel version" src="https://img.shields.io/badge/Laravel-v13-ff2d20?style=flat&logo=laravel&logoColor=ff2d20&labelColor=24292E" /></a>
        <a href="https://vuejs.org"><img alt="Vue.js version" src="https://img.shields.io/badge/Vue.js-v3.5-42b883?style=flat&logo=vuedotjs&logoColor=42b883&labelColor=24292E" /></a>
        <a href="https://tailwindcss.com"><img alt="Tailwind CSS version" src="https://img.shields.io/badge/Tailwind_CSS-v4.2-38bdf8?style=flat&logo=tailwindcss&logoColor=38bdf8&labelColor=24292E" /></a>
    </p>
</p>

## Introduction

> Secure, one-time secret sharing made simple.

Designed, developed and maintained by **[Alex Fadez](https://github.com/fadez)**.

**SneakPeek** showcases my expertise in full-stack web development, seamlessly integrating cutting-edge frameworks and industry best practices to deliver a secure, scalable, and high-quality Laravel application.

## Live demo

**[sneakpeek.alexfadez.com](https://sneakpeek.alexfadez.com)**

## Architecture and features

<details>
<summary><strong>Backend</strong></summary>

- **Laravel framework**
    - RESTful API using Laravel API Resources with clean controllers and rate-limited routes
    - Eloquent ORM with custom scopes, accessors, and API Resources
    - Database migrations with proper indexing for performance and integrity
    - A scheduled command to permanently wipe expired secrets from the database
    - Custom error response handling
    - A/B testing and feature flags, powered by **[Laravel Pennant](https://laravel.com/docs/pennant)**
    - Real-time event broadcasting and a live statistics dashboard
- **Maximum privacy & security**
    - End-to-end privacy — no authentication, no logs
    - **[Custom privacy-first session handler](app/Extensions/Session/DatabaseSessionHandler.php)** that doesn't store any user information
    - Secrets can only be accessed once, then wiped permanently
    - Secret content encrypted using Laravel's built-in encryption
    - Secret access tokens (hashed in DB) stored in the URL `#` hash fragment to prevent server-side logging, analytics tracking, or accidental leakage via `Referer` header
    - Secrets have optional passphrase (hashed in DB)
    - Mandatory expiration time for secrets
    - Minimized framework headers and error masking to prevent framework identification, fingerprinting, and targeted exploits
    - API throttling (rate limiting) to prevent brute-force attacks
    - **[Laravel Sanctum](https://laravel.com/docs/sanctum)** integration for robust CSRF protection and secure API state management
    - Hardened session management using JSON serialization to eliminate deserialization risks and strict cookie attributes (`Secure`, `HttpOnly`, `SameSite`) to prevent token leakage and hijacking
- **Clean architecture**
    - Readable, maintainable code with scoped responsibilities
    - **[SOLID](https://en.wikipedia.org/wiki/SOLID)** principles applied throughout
- **Quality Assurance (QA)**
    - Strict code consistency and PSR-12 compliance, enforced by **[Laravel Pint](https://laravel.com/docs/pint)**
    - Strict static analysis with maximum type safety across the entire codebase with **[PHPStan](https://phpstan.org)** level 10 (maximum strictness), enforced by **[Larastan](https://github.com/larastan/larastan)**
    - Comprehensive test suite using **[Pest](https://pestphp.com)** — unit, feature, and architectural tests, as well as E2E browser testing via its native **[Playwright integration](https://pestphp.com/docs/browser-testing)**
    - 100% type coverage enforced by **[Pest's type coverage plugin](https://pestphp.com/docs/type-coverage)**
    - Automated code upgrades and modernization by **[Rector](https://getrector.com)** with **[Laravel-specific rules](https://github.com/driftingly/rector-laravel)** for idiomatic refactoring

</details>

<details>
<summary><strong>Frontend</strong></summary>

- **Vue.js**
    - All Vue components use **[Composition API](https://vuejs.org/guide/extras/composition-api-faq)** with the `<script setup>` syntax
    - Modular, component-based structure with reusable **[Single File Components](https://vuejs.org/guide/scaling-up/sfc)**
    - Single-page application architecture powered by **[Vue Router](https://router.vuejs.org)**
    - Consistent naming conventions and directory organization for ease of navigation and scalability
    - **[Pinia](https://pinia.vuejs.org)** for centralized state management and application-wide reactive data
    - Centralized notification system powered by Pinia store, providing a unified API for toast notifications across the entire application
- **Laravel Echo**
    - Powers real-time event broadcasting support
- **Tailwind CSS**
    - Fully custom UI/UX design crafted from scratch, with no third-party UI component libraries
    - Modern utility-first styling for mobile-first responsive design
    - Light and dark mode support with automatic switching
    - Comprehensive support for seamless keyboard-only navigation, ensuring full accessibility compliance and superior user experience for power users
- **Vite**
    - Lightning-fast builds and production optimization with intelligent code splitting and chunk optimization for optimal load performance
- **Quality Assurance (QA)**
    - JavaScript linting with **[Oxlint](.oxlintrc.json)**, enforcing correctness rules across the frontend codebase

</details>

<details>
<summary><strong>CI/CD</strong></summary>

- **Continuous Integration (CI)**
    - **[Automated CI pipeline](.github/workflows/ci.yml)** that runs linting, static analysis, type coverage checks and the complete test suite on every push
- **Continuous Deployment (CD)**
    - **[Automated CD pipeline](.github/workflows/cd.yml)** that deploys to a **Google Cloud** Compute Engine instance via SSH
    - Secure SSH orchestration using encrypted GitHub Secrets and SSH key pairs for automated remote deployment

</details>

<details>
<summary><strong>Deployment & DX</strong></summary>

- **Deployment**
    - Zero-friction deployment with included **[deployment script](deploy.sh)** and NGINX server **[configuration template](nginx/sneakpeek.conf)**
    - Live demo is deployed on **Google Cloud**, protected by **Cloudflare** using best practices:
        - **[Full (Strict) SSL/TLS](https://developers.cloudflare.com/ssl/origin-configuration/ssl-modes/full-strict)** to eliminate man-in-the-middle vulnerabilities by requiring a trusted **[Cloudflare origin CA](https://developers.cloudflare.com/ssl/origin-configuration/origin-ca)** certificate
        - **[Authenticated Origin Pulls (mTLS)](https://developers.cloudflare.com/ssl/origin-configuration/authenticated-origin-pull)** to ensure that only connections routed through **[Cloudflare WAF](https://www.cloudflare.com/application-services/products/waf)** can reach the server, effectively cloaking the origin server from direct IP-based attacks
        - Region-based **[Cloudflare security rules](https://developers.cloudflare.com/security/rules)** to restrict network access from undesired geographic areas
        - Both **[restoring original visitor IPs](https://developers.cloudflare.com/support/troubleshooting/restoring-visitor-ips/restoring-original-visitor-ips)** and all web server logging are deliberately disabled to maximize visitor privacy by ensuring IP addresses are never retained or logged, eliminating associated risks
- **Developer Experience (DX)**
    - Custom `composer.json` scripts streamline application setup, linting, testing, and automated code refactoring/upgrades, enabling a smooth and modern developer workflow
    - Easy onboarding with a single `composer setup` command that handles environment setup, creates the SQLite database, and installs dependencies
    - **[Laravel Boost](https://laravel.com/ai/boost)** integration that runs the **[MCP](https://modelcontextprotocol.io)** server to accelerate AI-assisted development by providing the essential context and structure that AI needs
    - **[Laravel Debugbar](https://github.com/fruitcake/laravel-debugbar)** is included for local debugging and profiling
    - Automated formatting enforced via **[Prettier](.prettierrc)** and **[EditorConfig](.editorconfig)**

</details>

## Installation

### Prerequisites

> [!NOTE]
> Requires **[PHP v8.5+](https://php.net)**, **[Node.js](https://nodejs.org)** v24 with **[npm](https://www.npmjs.com)**, **[Composer](https://getcomposer.org)** and **[Git](https://git-scm.com)**.

> [!TIP]
> Using **[Laravel Herd](https://herd.laravel.com)** as your local development environment is highly recommended.

### Application setup

Go to your **[Laravel Herd](https://herd.laravel.com/docs/macos/getting-started/sites)** sites folder and run:

```shell
git clone https://github.com/fadez/sneakpeek.git && cd sneakpeek && composer setup
```

### Securing site with TLS

SneakPeek requires HTTPS to enforce secure cookies and ensure the integrity of its CSRF protection.

If you are using **[Laravel Herd](https://herd.laravel.com/docs/macos/sites/securing-sites)**, you can secure the site by running the following command:

```shell
herd secure sneakpeek
```

### Broadcasting setup (optional)

SneakPeek offers real-time event broadcasting support through either the **[Laravel Reverb](https://reverb.laravel.com)** or **[Pusher Channels](https://pusher.com/channels)**.

<details>
<summary><strong>Broadcasting setup with Pusher Channels</strong></summary>

Create a **[Pusher Channels](https://dashboard.pusher.com/channels)** application.

Update the `.env` file with Pusher credentials and set `BROADCAST_CONNECTION` to `pusher`:

```env
BROADCAST_CONNECTION=pusher
PUSHER_APP_ID=your-app-id
PUSHER_APP_KEY=your-app-key
PUSHER_APP_SECRET=your-app-secret
PUSHER_APP_CLUSTER=your-cluster
```

Rebuild frontend assets to apply changes and enable broadcasting:

```sh
composer build
```

</details>

<details>
<summary><strong>Broadcasting setup with Laravel Reverb</strong></summary>

Run this command and enable the Laravel Reverb driver when prompted in the terminal:

```shell
composer install:reverb
```

Rebuild frontend assets to apply changes:

```shell
composer build
```

Start the Laravel Reverb server to enable broadcasting:

```shell
php artisan reverb:start
```

</details>

### Visiting the site

If you're using **[Laravel Herd](https://herd.laravel.com)**, you can now access the application at **[sneakpeek.test](https://sneakpeek.test)**.

## Available commands

### Code quality

- `composer lint` - Runs Rector, Laravel Pint and Oxlint
- `composer test:lint` - Runs Rector, Laravel Pint and Oxlint in dry-run mode for CI/CD pipelines

### Testing

- `composer test:type-coverage` - Runs Pest type coverage checks (ensures 100% type coverage)
- `composer test:types` - Runs PHPStan at level 10 (maximum strictness)
- `composer test:unit` - Runs all Pest tests (unit, feature, browser and architecture tests)
- `composer test` - Runs the complete test suite (type coverage, static analysis, linting, and all Pest tests)

### Maintenance

- `composer update:requirements` - Updates all Composer and npm dependencies and rebuilds frontend assets
