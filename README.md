<div align="center">
    <img src="public/logo.svg" width="100" alt="SneakPeek logo">
</div>

<div align="center">
    <a href="https://github.com/fadez/sneakpeek/actions/workflows/tests.yml">
        <img src="https://github.com/fadez/sneakpeek/actions/workflows/tests.yml/badge.svg" alt="Build Status">
    </a>
</div>

# About SneakPeek

> Secure, one-time secret sharing made simple.

Built by **[@fadez](https://github.com/fadez)** in **[Cursor](https://cursor.com)** using **[Laravel](https://laravel.com)**, **[Vue.js](https://vuejs.org)** and **[Tailwind CSS](https://tailwindcss.com)**.

SneakPeek serves as a showcase of my full-stack development skills, combining modern frameworks and best practices to deliver a secure, scalable, and modern web application.

# Live demo

**[sneakpeek.alexfadez.com](https://sneakpeek.alexfadez.com)**

# Features

### Backend

- **Laravel framework**
    - RESTful API with clean controllers and rate-limited routes
    - Eloquent ORM with custom scopes, accessors, and API Resources
    - Database migrations with proper indexing for performance and integrity
    - Scheduled command to auto-delete expired secrets
- **Security-first design**
    - End-to-end privacy — no authentication, no logs
    - Secrets can only be accessed once, then wiped permanently
    - Encrypted content storage using Laravel's built-in encryption
    - Optional hashed passphrase protection for secrets
    - Optional expiration time for secrets
- **Clean architecture**
    - Readable, maintainable code with scoped responsibilities
    - SOLID principles applied throughout

### Frontend

- **Vue.js**
    - Composition API
    - Vue Router
    - Component-based architecture
    - Reactive state management
- **Tailwind CSS**
    - Responsive and beautiful UI
    - Auto-switching light/dark mode support
- **Vite**
    - Fast builds, hot module replacement, production optimization

# Installation

### Prerequisites

You need to have PHP, Node.js and Composer installed globally on your system.

I recommend using **[Laravel Herd](https://herd.laravel.com)** as your development environment.

### Setting up the project

Go to your **[Laravel Valet](https://laravel.com/docs/master/valet)** or **[Laravel Herd](https://herd.laravel.com)** sites folder and run:

```sh
git clone https://github.com/fadez/sneakpeek.git && cd sneakpeek && sh install.sh
```

### Securing site with TLS

SneakPeek should always be accessed over a secure connection. Let's enable TLS to ensure this.

If you're using **[Laravel Herd](https://herd.laravel.com)** run:

```sh
herd secure
```

If you're using **[Laravel Valet](https://laravel.com/docs/master/valet)** run:

```sh
valet secure
```

### Visiting the site

If you're using **[Laravel Herd](https://herd.laravel.com)** or **[Laravel Valet](https://laravel.com/docs/master/valet)**, you can now access the app at **[sneakpeek.test](http://sneakpeek.test)**.
