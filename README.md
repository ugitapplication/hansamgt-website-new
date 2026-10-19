# 🏢 HANSA Management Ltd. — Corporate Website

[![Laravel](https://img.shields.io/badge/Backend-Laravel%2012-red)](https://laravel.com)
[![Filament](https://img.shields.io/badge/Admin-Filament%20v4-orange)](https://filamentphp.com)
[![Tailwind](https://img.shields.io/badge/Frontend-Tailwind%20CSS%204.0-blue)](https://tailwindcss.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-purple)](https://php.net)

The official corporate digital presence for **HANSA Management Ltd.**, a leader in Hospitality and Facility Management services. This platform serves as a professional showcase of the company's expertise in Hospitality, Security, Cleaning, and MEP (Mechanical, Electrical, and Plumbing) services.

---

## 🚀 Quick Start

Get the development environment up and running in minutes using the built-in automation scripts.

### Prerequisites
- **PHP 8.2+**
- **Composer**
- **Node.js & NPM**
- **MySQL** or **SQLite**

### Local Installation
```bash
# 1. Clone the repository
git clone <your-repo-url>
cd hansamgt-website-new

# 2. Run the automated setup (Installs dependencies, configs .env, migrates DB)
composer setup

# 3. Launch the development environment
composer dev
```

### Access the Site
- **Frontend:** [http://127.0.0.1:8000](http://127.0.0.1:8000)
- **Admin CMS:** [http://127.0.0.1:8000/admin](http://127.0.0.1:8000/admin)

---

## 📚 Comprehensive Documentation

This repository contains a full-scale technical audit and documentation set. For detailed implementation details, refer to the `docs/technical/` directory.

### Key Documentation Links:
- 📑 **[Technical Index](docs/technical/00-index.md)** — The master map of all documentation.
- 🛠️ **[Local Development Setup](docs/technical/04-local-development-setup.md)** — Detailed environment configuration.
- ⚙️ **[Admin Panel Reference](docs/technical/11-admin-panel-reference.md)** — How to manage projects, team, and content.
- 🛣️ **[Routing & URL Map](docs/technical/06-routing-and-url-map.md)** — Detailed mapping of all site URLs and legacy 301 redirects.
- 🛡️ **[Security & Hardening](docs/technical/17-security-and-hardening.md)** — Overview of security measures and CSP.
- 📈 **[SEO & Structured Data](docs/technical/15-seo-and-structured-data.md)** — Implementation of JSON-LD and SEO strategies.

---

## 🛠 Technical Architecture

### Core Stack
- **Backend:** Laravel 12 (PHP 8.2) — Providing a robust, scalable foundation.
- **Administration:** Filament v4 — A high-performance TALL stack admin panel for seamless content management.
- **Frontend:** Tailwind CSS 4.0 & Vite 7.0 — Ensuring a modern, responsive, and lightning-fast user experience.
- **Templating:** Laravel Blade — For clean and maintainable frontend components.

### Key Features
- **Dynamic CMS:** Fully manageable Hero slides, Client lists, Project galleries, and Team profiles.
- **SEO Optimized:** Implementation of advanced structured data (Schema.org) and clean URL slugs.
- **Legacy Support:** Specialized middleware to handle 301 redirects from the previous CodeIgniter site.
- **Lead Generation:** Integrated contact system with rate-limiting and admin notification.

---

## 👥 Contribution & Maintenance
Please refer to the **[Known Issues & Roadmap](docs/technical/21-known-issues-decisions-and-roadmap.md)** before starting any new feature development.

© 2026 HANSA Management Ltd. All rights reserved.
