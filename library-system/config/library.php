<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Library System Configuration
    |--------------------------------------------------------------------------
    */

    // Fine charged per overdue day (USD)
    'fine_per_day' => env('FINE_PER_DAY', 0.50),

    // Maximum days a book can be borrowed
    'max_borrow_days' => env('MAX_BORROW_DAYS', 14),

    // Maximum number of books a user can borrow at once
    'max_books_per_user' => env('MAX_BOOKS_PER_USER', 5),

    // Hours before a reservation expires after book becomes available
    'reservation_expiry_hours' => env('RESERVATION_EXPIRY_HOURS', 48),

    // Maximum active reservations per user
    'max_reservations_per_user' => 3,

    // Maximum renewal count per borrow
    'max_renewals' => 2,

    // Outstanding fines threshold to block borrowing
    'fine_block_threshold' => 10.00,

    // Library name
    'name' => env('LIBRARY_NAME', 'LibraryMS'),

    // Library address
    'address' => env('LIBRARY_ADDRESS', '123 Knowledge Street, City'),

    // Library contact
    'contact_email' => env('LIBRARY_EMAIL', 'library@example.com'),
    'contact_phone' => env('LIBRARY_PHONE', '+1 (555) 000-0000'),
];
