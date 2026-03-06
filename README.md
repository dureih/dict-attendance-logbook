<<<<<<< HEAD
<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
=======
[README.md](https://github.com/user-attachments/files/25794308/README.md)
# 📋 DICT Attendance Log Book

> A web-based attendance logging system built for the **Department of Information and Communications Technology (DICT) – San Jose, Antique** office.

---

## 📌 Description

The DICT Attendance Log Book is a full-stack web application designed to replace manual paper-based visitor logbooks in government offices. It allows walk-in visitors to log their attendance digitally, while providing administrators with a secure dashboard to manage, filter, and export records.

This project was built as a practical solution for the DICT San Jose, Antique office to streamline their daily visitor management process.

---

## 🚀 Features

- 📝 **Public Visitor Log Form** — Walk-in visitors can log their information easily
- 🔐 **Admin Authentication** — Secure login system powered by Laravel Breeze
- 📊 **Admin Dashboard** — View, search, and filter all visitor records
- 🗺️ **Cascading Address Dropdowns** — Province → Municipality → Barangay (Western Visayas)
- 📱 **Smart Contact Number Input** — Auto-formats to `0912 345 6789`, integers only
- 🎯 **Purpose of Visit** — Dropdown with predefined DICT service options
- 📄 **Export to PDF** — Download filtered records as a PDF report
- 📊 **Export to Excel** — Download filtered records as an Excel spreadsheet
- 🗑️ **Record Management** — Admins can delete records
- 📈 **Live Statistics** — Total visitors, male/female count, municipality count

---

## 🛠️ Tech Stack

| Technology | Purpose |
|---|---|
| **Laravel 11** | Backend PHP Framework |
| **MySQL / MariaDB** | Database |
| **Laravel Breeze** | Authentication |
| **Blade Templates** | Frontend Templating |
| **Maatwebsite/Laravel-Excel** | Excel Export |
| **Barryvdh/Laravel-DomPDF** | PDF Export |
| **Vanilla JavaScript** | Cascading Dropdowns & Input Formatting |

---

## ⚙️ Installation

### Requirements
- PHP >= 8.2
- Composer
- Node.js & NPM
- MySQL or MariaDB

### Steps

**1. Clone the repository**
```bash
git clone https://github.com/YOURUSERNAME/dict-attendance-logbook.git
cd dict-attendance-logbook
```

**2. Install dependencies**
```bash
composer install
npm install && npm run build
```

**3. Configure environment**
```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` and set your database credentials:
```env
APP_NAME="DICT Attendance Log Book"
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dict_logbook
DB_USERNAME=root
DB_PASSWORD=
```

**4. Create the database**
```sql
CREATE DATABASE dict_logbook;
```

**5. Run migrations and seed**
```bash
php artisan migrate
php artisan db:seed --class=AdminSeeder
```

**6. Run the application**
```bash
php artisan serve
```

Visit: `http://localhost:8000`

---

## 🔐 Default Admin Credentials

| Field | Value |
|---|---|
| Email | `admin@dict.gov.ph` |
| Password | `dict2024` |

> ⚠️ Change the default password after first login!

---

## 📸 Screenshots

> *(Add screenshots of your app here)*

---

## 📁 Project Structure

```
dict-logbook/
├── app/
│   ├── Exports/VisitorsExport.php
│   ├── Http/Controllers/
│   │   ├── AdminController.php
│   │   └── VisitorController.php
│   └── Models/
│       ├── User.php
│       └── Visitor.php
├── database/
│   ├── migrations/
│   └── seeders/AdminSeeder.php
├── resources/views/
│   ├── layouts/app.blade.php
│   ├── visitors/index.blade.php
│   ├── admin/dashboard.blade.php
│   └── exports/visitors-pdf.blade.php
└── routes/web.php
```

---

## 👨‍💻 Developer

Built with ❤️ for **DICT San Jose, Antique**

> *This project was developed as part of an on-the-job training / internship at the Department of Information and Communications Technology, San Jose, Antique, Philippines.*

---

## 📄 License

This project is open-source and available under the [MIT License](LICENSE).
>>>>>>> 0b934a94dc380b4c8a481d2b7a32fdc8e5b555a3
