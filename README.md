<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# HANSA Management Ltd. Website

This is the official corporate website for **HANSA Management Ltd.**, built with Laravel 12 and Filament v4.

## 📚 Technical Documentation

This project contains extensive, source-verified technical documentation. You can find the complete guide in the `docs/technical/` directory.

### Quick Links
- 📖 **[Documentation Index](docs/technical/00-index.md)** — The starting point for all technical docs.
- 🚀 **[Local Development Setup](docs/technical/04-local-development-setup.md)** — How to get the site running on your machine.
- 🛠️ **[Admin Panel Reference](docs/technical/11-admin-panel-reference.md)** — Guide for managing site content.
- 🗺️ **[Routing & URL Map](docs/technical/06-routing-and-url-map.md)** — Overview of all public and legacy routes.
- 🛡️ **[Security & Hardening](docs/technical/17-security-and-hardening.md)** — Details on security implementations.

---

## 🛠 Getting Started

### Prerequisites
- PHP 8.2+
- Composer
- Node.js & NPM
- MySQL or SQLite

### Installation
1. Clone the repository.
2. Run the automated setup script:
   ```bash
   composer setup
   ```
3. Start the development server:
   ```bash
   composer dev
   ```
4. Access the site at `http://127.0.0.1:8000` and the admin panel at `http://127.0.0.1:8000/admin`.

## 🛠 Tech Stack
- **Backend:** Laravel 12, PHP 8.2
- **Admin Panel:** Filament v4
- **Frontend:** Tailwind CSS 4.0, Vite 7.0
- **Templating:** Laravel Blade

## License
The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
