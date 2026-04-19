# 📚 LibraryMS — Laravel Library Management System

A full-featured library management system built with Laravel 10. Clean, modern UI with complete admin and user functionality.

---

## ✨ Features

### 👤 User Side
- Register & login with email/password
- **Dashboard** — borrow stats, overdue alerts, recommended books, fines overview
- **Browse books** — search by title/author/ISBN, filter by category, availability, sort options
- **Reserve books** — reserve available books or join a queue for unavailable ones (max 3 active reservations)
- **My Borrows** — full history with status, due dates, overdue warnings
- **Renew borrows** — up to 2 renewals per book (if not overdue or reserved by another)
- **Fines & Payments** — view overdue fines, pay online/card
- **Profile** — edit name, phone, address, avatar, change password, delete account

### 🔧 Admin Side
- **Dashboard** — live stats: books, members, active borrows, overdue, revenue, charts
- **Books** — full CRUD: add, edit, delete with cover upload, ISBN, location, inventory management
- **Categories** — manage book categories with custom colors
- **Borrows** — view all borrows filtered by status, mark books as returned (auto-generates fines)
- **Members** — view/edit/block/unblock users, see their full borrow & fine history, issue books directly
- **Payments & Fines** — record cash/card payments, waive fines with reason, revenue stats

### ⚙️ Automatic Features
- Fine auto-calculation ($0.50/day configurable)
- Overdue status auto-update when admin clicks "Refresh Overdue"
- Next reservation notified when book is returned
- Member IDs auto-generated (LIB-2024-0001)
- Borrow/payment codes auto-generated (BRW-2024-00001)
- Activity logging for all key actions
- User blocking after excessive fines (>$10 threshold)

---

## 🚀 Installation

### Prerequisites
- PHP 8.1+
- Composer
- MySQL 8.0+
- Node.js (optional, for assets)

### Step 1 — Clone & Install
```bash
git clone <your-repo-url> library-system
cd library-system
composer install
```

### Step 2 — Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` and set your database credentials:
```
DB_DATABASE=library_system
DB_USERNAME=root
DB_PASSWORD=your_password
```

### Step 3 — Database Setup
```bash
# Create the database first
mysql -u root -p -e "CREATE DATABASE library_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Run migrations
php artisan migrate

# Seed with demo data
php artisan db:seed
```

### Step 4 — Storage Link
```bash
php artisan storage:link
```

### Step 5 — Run the App
```bash
php artisan serve
```

Visit: **http://localhost:8000**

---

## 🔑 Demo Credentials

| Role  | Email | Password |
|-------|-------|----------|
| Admin | admin@library.com | password |
| User  | alice@example.com | password |
| User  | bob@example.com   | password |

---

## ⚙️ Configuration

Edit `config/library.php` or `.env` to customize:

| Setting | Default | Description |
|---------|---------|-------------|
| `FINE_PER_DAY` | $0.50 | Fine charged per overdue day |
| `MAX_BORROW_DAYS` | 14 | Days before a book is due |
| `MAX_BOOKS_PER_USER` | 5 | Max books a user can borrow at once |
| `RESERVATION_EXPIRY_HOURS` | 48 | Hours before a reservation expires |

---

## 📁 Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php
│   │   ├── User/
│   │   │   ├── DashboardController.php
│   │   │   ├── BookController.php
│   │   │   ├── ProfileController.php
│   │   │   └── BorrowFineController.php
│   │   └── Admin/
│   │       ├── DashboardController.php
│   │       ├── BookController.php
│   │       ├── UserController.php
│   │       └── BorrowPaymentController.php
│   ├── Middleware/
│   │   └── AdminMiddleware.php
│   └── Kernel.php
├── Models/
│   ├── User.php
│   ├── Book.php
│   ├── Category.php
│   ├── Borrow.php
│   ├── Reservation.php
│   ├── Fine.php
│   ├── Payment.php
│   └── ActivityLog.php
config/
└── library.php
database/
├── migrations/       (3 migration files)
└── seeders/
    └── DatabaseSeeder.php
resources/views/
├── layouts/
│   ├── app.blade.php     (main layout with sidebar)
│   └── auth.blade.php    (split-screen auth layout)
├── auth/
│   ├── login.blade.php
│   └── register.blade.php
├── user/
│   ├── dashboard.blade.php
│   ├── profile.blade.php
│   ├── profile-edit.blade.php
│   ├── books/
│   │   ├── index.blade.php
│   │   └── show.blade.php
│   ├── borrows/
│   │   └── index.blade.php
│   └── fines/
│       └── index.blade.php
└── admin/
    ├── dashboard.blade.php
    ├── books/
    │   ├── index.blade.php
    │   ├── create.blade.php
    │   ├── edit.blade.php
    │   └── categories.blade.php
    ├── users/
    │   ├── index.blade.php
    │   ├── show.blade.php
    │   └── edit.blade.php
    ├── borrows/
    │   └── index.blade.php
    └── payments/
        └── index.blade.php
routes/
└── web.php
```

---

## 🎨 Design System

The UI uses a custom CSS design system built directly into the layout — no Tailwind or Bootstrap needed. Key design tokens:

- **Navy** `#0f1e35` — primary dark color
- **Gold** `#c9a84c` — accent color
- **Cream** `#f7f4ef` — page background
- **Fonts**: DM Serif Display (headings) + DM Sans (body)

---

## 🔄 Scheduled Commands (Optional)

To auto-mark overdue borrows daily, add to `app/Console/Kernel.php`:

```php
$schedule->call(function () {
    \App\Models\Borrow::where('status', 'active')
        ->where('due_date', '<', now())
        ->update(['status' => 'overdue']);
})->daily();
```

Then run: `php artisan schedule:run` (or set up a cron job)

---

## 📝 Notes for Laravel Class

This project demonstrates:
- **MVC Architecture** — Models, Views, Controllers properly separated
- **Eloquent ORM** — Relationships (hasMany, belongsTo), scopes, accessors
- **Migrations** — Database versioning with proper foreign keys
- **Seeders** — Test data generation
- **Middleware** — Auth + AdminMiddleware for route protection
- **Route Groups** — Prefixed routes with named routes
- **Blade Templates** — Layout inheritance with `@extends`, `@yield`, `@section`, `@stack`
- **Form Validation** — Server-side validation in controllers
- **File Uploads** — Avatar and book cover storage with `Storage::disk()`
- **Database Transactions** — `DB::transaction()` for data integrity
- **Soft Deletes** — Books and users are soft-deleted
- **Query Scopes** — Reusable query logic on models
- **Model Accessors** — Computed attributes (`avatar_url`, `status_badge`, etc.)
