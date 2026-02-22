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
    - Encrypted content storage using Laravel's built-in encryption
    - Optional hashed passphrase protection for secrets
    - Optional expiration time for secrets
    - Minimized framework exposure to prevent framework identification and targeted attacks
    - API throttling (rate limiting) to prevent brute-force attacks
    - **[Laravel Sanctum](https://laravel.com/docs/sanctum)** is used to prevent CSRF and automated request forgery by enforcing stateful, cookie-based validation
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
    - State-driven reactivity ensuring seamless user interaction
- **Tailwind CSS v4.2**
    - Elegant, mobile-first responsive design
    - Light and dark mode support with automatic switching
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
    - Zero-friction deployment with included deployment script and **[nginx](https://nginx.org)** configuration template
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
