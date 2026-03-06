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
