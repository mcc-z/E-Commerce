📋 QUICK START GUIDE - LIBRARY MANAGEMENT SYSTEM

═══════════════════════════════════════════════════════════

🚀 START THE DEVELOPMENT SERVER
────────────────────────────────────────────────────────────

From Command Prompt/PowerShell:
  cd c:\xampp\htdocs\library-system\library-system
  php artisan serve

Then visit: http://localhost:8000


📦 COMMON ARTISAN COMMANDS
────────────────────────────────────────────────────────────

Database Operations:
  php artisan migrate              - Run all migrations
  php artisan migrate:rollback     - Rollback last migration
  php artisan migrate:reset        - Reset all migrations
  php artisan db:seed              - Run database seeders
  php artisan db:show              - Show database info
  php artisan db:table users       - Show table structure

Generate New Files:
  php artisan make:model ModelName
  php artisan make:controller ControllerName
  php artisan make:migration create_table_name
  php artisan make:middleware MiddlewareName

Cache & Config:
  php artisan cache:clear          - Clear all caches
  php artisan config:clear         - Clear config cache
  php artisan optimize             - Optimize framework

Debugging:
  php artisan tinker              - Interactive PHP shell
  php artisan logs                - Show recent logs
  php artisan migrate:status      - Check migration status
  php artisan env                 - Show current environment


🔧 PROJECT STRUCTURE
────────────────────────────────────────────────────────────

app/
├── Console/           # Artisan commands
├── Exceptions/        # Exception handlers
├── Http/
│   ├── Controllers/   # Route handlers
│   ├── Middleware/    # HTTP middleware
│   └── Kernel.php     # HTTP kernel
├── Models/            # Eloquent models
│   ├── Book.php
│   ├── User.php
│   ├── Borrow.php
│   ├── Fine.php
│   ├── Payment.php
│   └── Reservation.php
└── Providers/         # Service providers

database/
├── migrations/        # Schema migrations
│   ├── 2024_01_01_000001_create_users_table.php
│   ├── 2024_01_01_000002_create_books_table.php
│   └── 2024_01_01_000003_create_borrows_reservations_fines_payments.php
└── seeders/          # Data seeders

routes/
└── web.php           # Web routes

resources/
├── views/            # Blade templates
└── assets/           # CSS, JS, images

public/
├── index.php         # Entry point
└── css/, js/, etc.   # Assets

config/
├── app.php           # Application config
├── database.php      # Database config
└── logging.php       # Logging config

storage/
├── logs/             # Application logs
├── framework/        # Cache, sessions, views
└── app/              # Application storage


📊 DATABASE SCHEMA
────────────────────────────────────────────────────────────

Tables Created:

users
├── id (Primary Key)
├── name
├── email
├── password
└── timestamps

books
├── id (Primary Key)
├── title
├── author
├── isbn
├── quantity_available
├── quantity_total
├── category_id
└── timestamps

borrows
├── id (Primary Key)
├── user_id (Foreign Key)
├── book_id (Foreign Key)
├── borrow_date
├── return_date
├── due_date
└── timestamps

fines
├── id (Primary Key)
├── user_id (Foreign Key)
├── amount
├── reason
└── timestamps

payments
├── id (Primary Key)
├── user_id (Foreign Key)
├── amount
├── payment_date
└── timestamps

reservations
├── id (Primary Key)
├── user_id (Foreign Key)
├── book_id (Foreign Key)
├── reservation_date
└── timestamps


🌐 WORKING WITH ROUTES
────────────────────────────────────────────────────────────

Edit routes/web.php to define routes:

Example:
  Route::get('/', [HomeController::class, 'index']);
  Route::get('/books', [BookController::class, 'index']);
  Route::post('/borrow', [BorrowController::class, 'store']);

Then create corresponding controllers:
  php artisan make:controller BookController
  php artisan make:controller BorrowController


🛠️ CREATING YOUR FIRST FEATURE
────────────────────────────────────────────────────────────

1. Create a Controller:
   php artisan make:controller UserController

2. Create a Model:
   php artisan make:model User -c  # With controller

3. Define Routes in routes/web.php:
   Route::resource('users', UserController::class);

4. Create Views in resources/views/users/
   - index.blade.php    (List users)
   - create.blade.php   (Create form)
   - edit.blade.php     (Edit form)
   - show.blade.php     (Show single)

5. Implement Controller Methods:
   - index()    - Show all
   - create()   - Show form
   - store()    - Save to DB
   - show()     - Show single
   - edit()     - Edit form
   - update()   - Update in DB
   - destroy()  - Delete


📚 USEFUL RESOURCES
────────────────────────────────────────────────────────────

Official Docs:
  - Laravel Docs: https://laravel.com/docs/10.x
  - Blade Templates: https://laravel.com/docs/10.x/blade
  - Eloquent ORM: https://laravel.com/docs/10.x/eloquent

Commands Available:
  php artisan list          - See all commands with descriptions
  php artisan help migrate  - Get help for specific command
  php artisan docs          - Access Laravel documentation


✅ VERIFICATION CHECKLIST
────────────────────────────────────────────────────────────

[✓] PHP 8.2.12 installed
[✓] MySQL connection working
[✓] Database "libsystem" created
[✓] All migrations applied (4/4)
[✓] Laravel 10.50.2 installed
[✓] Artisan CLI working
[✓] .env file configured
[✓] APP_KEY generated
[✓] All 77 packages installed
[✓] File permissions correct
[✓] Routes ready (edit routes/web.php)
[✓] Models ready (in app/Models/)
[✓] Controllers ready (in app/Http/Controllers/)


🎯 YOUR NEXT ACTION
────────────────────────────────────────────────────────────

Ready to start? Run:

  php artisan serve

Then open browser to: http://localhost:8000

Or with XAMPP, access via your configured domain.

═══════════════════════════════════════════════════════════
Happy coding! Your system is fully operational.
═══════════════════════════════════════════════════════════
