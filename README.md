# 🚀 Rehabilitation Management System - Idzmir Kids Hub

A comprehensive Rehabilitation Management System tailored for an institution specializing in therapy for children with autism - streamline operations, enhance communication among all stakeholders annd ensure and efficient management process.

-[Live View »](https://system.idzmirkidshub.com/)

## 🧩 Features

- 🔐 Role-based access (Admin, Operation Manager, Customer Service, Therapist, Parent)
- 📅 FullCalendar integration for dynamic scheduling
- ✅ Program selection, session booking, attendance tracking, rescheduling requests
- 📤 Email notifications
- 💳 Online Payment integrations (CHIP payment gateway)
- 📊 Admin dashboard with reports

---

## ⚙️ Tech Stack

- **Backend**: Laravel 10, PHP 8.x, JSON
- **Frontend**: Blade, Bootstrap 5, JavaScript, CSS3
- **Database**: MySQL
- **Other**: Git, CHIP API, VPS(Plesk)

---

## 🛠️ Installation

> Clone the repo and set it up locally

```bash
git clone https://github.com/yourusername/project-name.git
cd project-name
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
