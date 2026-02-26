<p align="center">
    <img src="public/logo.svg" width="100" alt="SneakPeek">
    <p align="center">
        <a href="https://github.com/fadez/sneakpeek/actions/workflows/ci.yml"><img alt="CI status" src="https://github.com/fadez/sneakpeek/actions/workflows/ci.yml/badge.svg"></a>
        <a href="https://github.com/fadez/sneakpeek/actions/workflows/cd.yml"><img alt="CD status" src="https://github.com/fadez/sneakpeek/actions/workflows/cd.yml/badge.svg"></a>
    </p>
</p>

---

> Secure, one-time secret sharing made simple.

Built by **[@fadez](https://github.com/fadez)** in **[Cursor](https://cursor.com)** using **[Laravel](https://laravel.com)**, **[Vue.js](https://vuejs.org)** and **[Tailwind CSS](https://tailwindcss.com)**.

**SneakPeek** showcases my full-stack development skills and workflow, combining modern frameworks and best practices to deliver a secure, scalable, and production-quality web application.

# Live demo

**[sneakpeek.alexfadez.com](https://sneakpeek.alexfadez.com)**

# Features

### Backend

- **Laravel framework v12**
    - RESTful API using Laravel API Resources with clean controllers and rate-limited routes
    - Eloquent ORM with custom scopes, accessors, and API Resources
    - Database migrations with proper indexing for performance and integrity
    - A scheduled command to permanently wipe expired secrets from the database
    - Custom error response handling
- **Security-first design**
    - End-to-end privacy — no authentication, no logs
    - Secrets can only be accessed once, then wiped permanently
    - Secret content encrypted using Laravel's built-in encryption
    - Secret access tokens (hashed in DB) stored in the URL `#` hash fragment to prevent server-side logging, analytics tracking, or accidental leakage via `Referer` header
    - Secrets have optional passphrase (hashed in DB)
    - Mandatory expiration time for secrets
    - Minimized framework headers and error masking to prevent framework identification, fingerprinting and targeted exploits
    - API throttling (rate limiting) to prevent brute-force attacks
    - **[Laravel Sanctum](https://laravel.com/docs/sanctum)** integration for robust CSRF protection and secure API state management
- **Clean architecture**
    - Readable, maintainable code with scoped responsibilities
    - **[SOLID](https://en.wikipedia.org/wiki/SOLID)** principles applied throughout
- **Quality Assurance (QA)**
    - A comprehensive suite of unit, feature, and browser tests using **[Pest](https://pestphp.com)**, utilizing its native **[Playwright](https://playwright.dev)** integration for E2E browser testing
    - Strict code consistency and PSR-12 compliance, enforced by **[Laravel Pint](https://laravel.com/docs/pint)**
    - Strict static analysis with maximum type safety across the entire codebase with **[PHPStan](https://phpstan.org)** level 10, enforced by **[Larastan](https://github.com/larastan/larastan)**

### Frontend

- **Vue.js v3.5**
    - **[Composition API](https://vuejs.org/guide/extras/composition-api-faq)** with the `<script setup>` syntax for clean components
    - Modular, component-based structure with reusable Single File Components (SFC)
    - Single-page application (SPA) architecture powered by **[Vue Router](https://router.vuejs.org)**
    - Consistent naming conventions and directory organization for ease of navigation and scalability
    - **[Pinia](https://pinia.vuejs.org)** is used for centralized state management and application-wide reactive data
    - A notification system implemented in the Pinia store that abstracts the underlying toast notification library for clean, consistent usage across the app
- **Tailwind CSS v4.2**
    - Modern utility-first styling for mobile-first responsive design
    - Light and dark mode support with automatic switching
    - Bespoke toast notification styling that aligns with the app's core design language
- **Vite**
    - Lightning-fast builds and production optimization

### CI/CD

- **Continuous Integration (CI)**
    - **[Automated CI pipeline](.github/workflows/ci.yml)** using **[Laravel Pint](https://laravel.com/docs/pint)**, **[PHPStan](https://phpstan.org)**, **[Pest](https://pestphp.com)**, and **[Playwright](https://playwright.dev)** via **[GitHub Actions](https://github.com/features/actions)** on every push
- **Continuous Deployment (CD)**
    - **[Automated CD pipeline](.github/workflows/cd.yml)** that deploys to a **[Google Cloud](https://cloud.google.com)** Compute Engine instance via SSH
    - Secure SSH orchestration using encrypted GitHub Secrets and SSH key pairs for automated remote deployment

### Deployment & DX

- **Deployment**
    - Zero-friction deployment with included **[deployment script](deploy.sh)** and **[nginx](https://nginx.org)** server **[configuration template](nginx/sneakpeek.conf)**
    - Live demo is deployed on **[Google Cloud](https://cloud.google.com)**, protected by **[Cloudflare](https://www.cloudflare.com)** using best practices:
        - **[Full (Strict) SSL/TLS](https://developers.cloudflare.com/ssl/origin-configuration/ssl-modes/full-strict)** to eliminate man-in-the-middle vulnerabilities by requiring a trusted **[Cloudflare origin CA](https://developers.cloudflare.com/ssl/origin-configuration/origin-ca)** certificate
        - **[Authenticated Origin Pulls (mTLS)](https://developers.cloudflare.com/ssl/origin-configuration/authenticated-origin-pull)** to ensure that only connections routed through **[Cloudflare WAF](https://www.cloudflare.com/application-services/products/waf)** can reach the server, effectively cloaking the origin server from direct IP-based attacks
        - Region-based **[Cloudflare security rules](https://developers.cloudflare.com/security/rules)** to restrict network access from undesired geographic areas
        - Both **[restoring original visitor IPs](https://developers.cloudflare.com/support/troubleshooting/restoring-visitor-ips/restoring-original-visitor-ips)** and all web server logging are deliberately disabled to maximize visitor privacy by ensuring IP addresses are never retained or logged, eliminating associated risks
- **Developer Experience (DX)**
    - Automated onboarding via a single command that handles environment setup, SQLite database creation, and dependency installation
    - **[Laravel Boost](https://laravel.com/ai/boost)** integration for **[Cursor](https://cursor.com)** via **[MCP (Model Context Protocol)](https://modelcontextprotocol.io)** server accelerates AI-assisted development by providing the essential context and structure that AI needs
    - Automated linting and formatting using Prettier to ensure a standardized code style across all Vue and CSS files

# Installation

### Prerequisites

Before beginning installation, make sure that your local machine has:

- **[PHP](https://php.net)** v8.3+
- **[Node.js and npm](https://nodejs.org)** v20+
- **[Composer](https://getcomposer.org)**
- **[Git](https://git-scm.com)**

I highly recommend using **[Laravel Herd](https://herd.laravel.com)** as your local development environment.

### Setting up the project

Go to your **[Laravel Herd](https://herd.laravel.com)** or **[Laravel Valet](https://laravel.com/docs/valet)** sites folder and run:

```sh
git clone https://github.com/fadez/sneakpeek.git && cd sneakpeek && composer setup
```

### Visiting the site

If you're using **[Laravel Herd](https://herd.laravel.com)** or **[Laravel Valet](https://laravel.com/docs/valet)**, you can now access the app at **[sneakpeek.test](http://sneakpeek.test)**.

# Testing

You can run the full test suite, PHPStan and Laravel Pint with a single command:

```sh
composer test
```
