<?php

use App\Http\Controllers\Admin\BookController as AdminBookController;
use App\Http\Controllers\Admin\BorrowController as AdminBorrowController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\AuthController;        
use App\Http\Controllers\User\BookController as UserBookController;
use App\Http\Controllers\User\BorrowController;
use App\Http\Controllers\User\DashboardController as UserDashboard;
use App\Http\Controllers\User\FineController;
use App\Http\Controllers\User\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Models\Book;
use App\Models\User;
use App\Models\Borrow;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route(Auth::user()->isAdmin() ? 'admin.dashboard' : 'user.dashboard');
    }

    return redirect()->route('login');
});

Route::get('/media/avatars/{filename}', [ProfileController::class, 'avatar'])
    ->name('avatars.show');
Route::get('/media/covers/{filename}', [AdminBookController::class, 'cover'])
    ->name('covers.show');

Route::get('/', function () {
    // Fetch dynamic data for the landing page
    $topPicks = Book::take(3)->get();
    $stats = [
        'books_count'    => Book::count(),
        'members_count'  => User::count(),
        'borrows_count'  => Borrow::count(),
    ];

    return view('home', compact('topPicks', 'stats'));
});

// Keep your existing routes below...
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login',   [AuthController::class, 'login'])->name('login.submit');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register',[AuthController::class, 'register'])->name('register.submit');
Route::post('/logout',  [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| User Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {

    Route::get('/dashboard', [UserDashboard::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile',           [ProfileController::class, 'show'])->name('profile');
    Route::get('/profile/edit',      [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile',           [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password',  [ProfileController::class, 'changePassword'])->name('profile.password');
    Route::delete('/profile',        [ProfileController::class, 'deleteAccount'])->name('profile.delete');

    // Books
    Route::get('/books',             [UserBookController::class, 'index'])->name('books.index');
    Route::get('/books/{book}',      [UserBookController::class, 'show'])->name('books.show');
    Route::post('/books/{book}/reserve', [UserBookController::class, 'reserve'])->name('books.reserve');
    Route::delete('/reservations/{reservation}/cancel', [UserBookController::class, 'cancelReservation'])->name('reservations.cancel');

    // Borrows
    Route::get('/borrows',           [BorrowController::class, 'index'])->name('borrows.index');
    Route::post('/borrows/{borrow}/renew', [BorrowController::class, 'renew'])->name('borrows.renew');

    // Fines
    Route::get('/fines',             [FineController::class, 'index'])->name('fines.index');
    Route::post('/fines/{fine}/pay', [FineController::class, 'pay'])->name('fines.pay');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'changePassword'])->name('profile.password');

    // Books
    Route::get('/books',              [AdminBookController::class, 'index'])->name('books.index');
    Route::get('/books/create',       [AdminBookController::class, 'create'])->name('books.create');
    Route::post('/books',             [AdminBookController::class, 'store'])->name('books.store');
    Route::get('/books/{book}/edit',  [AdminBookController::class, 'edit'])->name('books.edit');
    Route::put('/books/{book}',       [AdminBookController::class, 'update'])->name('books.update');
    Route::delete('/books/{book}',    [AdminBookController::class, 'destroy'])->name('books.destroy');

    // Categories
    Route::get('/categories',                       [AdminBookController::class, 'categories'])->name('categories.index');
    Route::post('/categories',                      [AdminBookController::class, 'storeCategory'])->name('categories.store');
    Route::put('/categories/{category}',            [AdminBookController::class, 'updateCategory'])->name('categories.update');
    Route::delete('/categories/{category}',         [AdminBookController::class, 'destroyCategory'])->name('categories.destroy');

    // Users
    Route::get('/users',                            [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}',                     [AdminUserController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit',                [AdminUserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}',                     [AdminUserController::class, 'update'])->name('users.update');
    Route::post('/users/{user}/toggle-block',       [AdminUserController::class, 'toggleBlock'])->name('users.toggle-block');
    Route::delete('/users/{user}',                  [AdminUserController::class, 'destroy'])->name('users.destroy');
    Route::post('/users/{user}/issue-borrow',       [AdminUserController::class, 'issueBorrow'])->name('users.issue-borrow');
    Route::post('/borrows/{borrow}/return',         [AdminUserController::class, 'returnBook'])->name('borrows.return');

    // Borrows
    Route::get('/borrows',                          [AdminBorrowController::class, 'index'])->name('borrows.index');
    Route::post('/borrows/update-overdue',          [AdminBorrowController::class, 'updateOverdue'])->name('borrows.update-overdue');

    // Payments & Fines
    Route::get('/payments',                         [AdminPaymentController::class, 'index'])->name('payments.index');
    Route::post('/fines/{fine}/waive',              [AdminPaymentController::class, 'waiveFine'])->name('fines.waive');
    Route::post('/fines/{fine}/record-payment',     [AdminPaymentController::class, 'recordPayment'])->name('fines.record-payment');
});
