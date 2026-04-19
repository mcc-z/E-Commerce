<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\Book;
use App\Models\Borrow;
use App\Models\Category;
use App\Models\Fine;
use App\Models\Payment;
use App\Models\Reservation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $faker = fake();

            $categories = $this->seedCategories();
            $admin = $this->seedAdmin();
            $users = $this->seedUsers($faker, 24);
            $books = $this->seedBooks($faker, $categories, 40);

            $this->seedBorrows($faker, $users, $books, $admin, 30);
            $this->seedReservations($faker, $users, $books, 12);
            $this->seedFinesAndPayments($faker, $admin);
            $this->seedActivityLogs($faker, $admin, $users, $books, 60);

            $this->refreshDerivedFields();
        });

        $this->command->info('Database seeded successfully.');
        $this->command->info('Admin login: admin@library.com / password');
        $this->command->info('Demo member login: member1@example.com / password');
    }

    private function seedCategories(): Collection
    {
        $categories = [
            ['name' => 'Fiction', 'slug' => 'fiction', 'color' => '#8B5CF6', 'description' => 'Novels, short stories, and literary works'],
            ['name' => 'Science', 'slug' => 'science', 'color' => '#3B82F6', 'description' => 'Natural sciences, physics, chemistry, and biology'],
            ['name' => 'Technology', 'slug' => 'technology', 'color' => '#10B981', 'description' => 'Programming, software engineering, and digital systems'],
            ['name' => 'History', 'slug' => 'history', 'color' => '#F59E0B', 'description' => 'Civilizations, biographies, and world events'],
            ['name' => 'Philosophy', 'slug' => 'philosophy', 'color' => '#EF4444', 'description' => 'Ethics, logic, metaphysics, and critical thought'],
            ['name' => 'Mathematics', 'slug' => 'mathematics', 'color' => '#6366F1', 'description' => 'Algebra, calculus, probability, and statistics'],
            ['name' => 'Arts', 'slug' => 'arts', 'color' => '#EC4899', 'description' => 'Visual arts, music, architecture, and performance'],
            ['name' => 'Business', 'slug' => 'business', 'color' => '#14B8A6', 'description' => 'Management, finance, economics, and entrepreneurship'],
            ['name' => 'Education', 'slug' => 'education', 'color' => '#22C55E', 'description' => 'Teaching, learning methods, and curriculum design'],
            ['name' => 'Psychology', 'slug' => 'psychology', 'color' => '#F97316', 'description' => 'Behavior, cognition, and mental health'],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(['slug' => $category['slug']], $category);
        }

        return Category::orderBy('name')->get();
    }

    private function seedAdmin(): User
    {
        return User::updateOrCreate(
            ['email' => 'admin@library.com'],
            [
                'name' => 'Library Admin',
                'password' => Hash::make('password'),
                'member_id' => 'LIB-ADMIN-001',
                'role' => 'admin',
                'status' => 'active',
                'phone' => '+1 (555) 000-0001',
                'address' => '123 Library Street, City',
            ]
        );
    }

    private function seedUsers($faker, int $targetCount): Collection
    {
        $existingUsers = User::members()->count();

        for ($i = $existingUsers + 1; $i <= $targetCount; $i++) {
            $status = $i % 11 === 0 ? 'blocked' : ($i % 13 === 0 ? 'suspended' : 'active');

            User::create([
                'name' => $faker->name(),
                'email' => "member{$i}@example.com",
                'phone' => $faker->optional()->phoneNumber(),
                'address' => $faker->optional()->address(),
                'password' => Hash::make('password'),
                'member_id' => User::generateMemberId(),
                'role' => 'user',
                'status' => $status,
            ]);
        }

        return User::members()->inRandomOrder()->get();
    }

    private function seedBooks($faker, Collection $categories, int $targetCount): Collection
    {
        $existingBooks = Book::count();

        for ($i = $existingBooks + 1; $i <= $targetCount; $i++) {
            $title = Str::title($faker->unique()->words(rand(2, 4), true));
            $totalCopies = rand(2, 7);

            Book::create([
                'isbn' => $this->generateIsbn(),
                'title' => $title,
                'author' => $faker->name(),
                'publisher' => $faker->company() . ' Press',
                'published_year' => rand(1960, (int) date('Y')),
                'category_id' => $categories->random()->id,
                'description' => $faker->paragraphs(2, true),
                'total_copies' => $totalCopies,
                'available_copies' => $totalCopies,
                'reserved_copies' => 0,
                'location' => chr(rand(65, 70)) . '-' . rand(1, 25),
                'language' => Arr::random(['English', 'English', 'English', 'Spanish', 'French']),
                'pages' => rand(120, 900),
                'replacement_cost' => rand(15, 80),
                'status' => 'available',
            ]);
        }

        return Book::query()->get();
    }

    private function seedBorrows($faker, Collection $users, Collection $books, User $admin, int $targetCount): void
    {
        $existingBorrows = Borrow::count();

        for ($i = $existingBorrows; $i < $targetCount; $i++) {
            $status = Arr::random([
                'returned', 'returned', 'returned',
                'active', 'active',
                'overdue',
            ]);

            $book = $this->pickBorrowableBook($books, in_array($status, ['active', 'overdue'], true));

            if (! $book) {
                break;
            }

            $user = $users->random();
            $borrowedAt = $this->borrowedAtForStatus($status);
            $dueDate = $this->dueDateForStatus($status, $borrowedAt);
            $returnedAt = $status === 'returned'
                ? (clone $dueDate)->addDays(rand(-3, 8))
                : null;

            Borrow::create([
                'borrow_code' => Borrow::generateCode(),
                'user_id' => $user->id,
                'book_id' => $book->id,
                'borrowed_at' => $borrowedAt,
                'due_date' => $dueDate,
                'returned_at' => $returnedAt,
                'status' => $status,
                'issued_by' => $admin->id,
                'returned_to' => $returnedAt ? $admin->id : null,
                'notes' => $faker->boolean(35) ? $faker->sentence() : null,
                'renewal_count' => $status === 'returned' ? rand(0, 2) : rand(0, 1),
            ]);

            if (in_array($status, ['active', 'overdue'], true)) {
                $book->available_copies = max(0, $book->available_copies - 1);
                $book->save();
            }
        }
    }

    private function seedReservations($faker, Collection $users, Collection $books, int $targetCount): void
    {
        $existingReservations = Reservation::count();

        for ($i = $existingReservations; $i < $targetCount; $i++) {
            $book = $books->random();
            $user = $users->random();

            $alreadyLinked = Reservation::where('book_id', $book->id)
                ->where('user_id', $user->id)
                ->whereIn('status', ['pending', 'ready'])
                ->exists()
                || Borrow::where('book_id', $book->id)
                    ->where('user_id', $user->id)
                    ->whereIn('status', ['active', 'overdue'])
                    ->exists();

            if ($alreadyLinked) {
                continue;
            }

            $status = $book->available_copies > 0 && rand(0, 1) === 1 ? 'ready' : 'pending';
            $reservedAt = now()->subDays(rand(0, 10));
            $expiresAt = (clone $reservedAt)->addHours(config('library.reservation_expiry_hours', 48));

            Reservation::create([
                'reservation_code' => Reservation::generateCode(),
                'user_id' => $user->id,
                'book_id' => $book->id,
                'reserved_at' => $reservedAt,
                'expires_at' => $expiresAt,
                'notified_at' => $status === 'ready' ? $reservedAt->copy()->addHour() : null,
                'status' => $status,
                'queue_position' => Reservation::where('book_id', $book->id)
                    ->whereIn('status', ['pending', 'ready'])
                    ->count() + 1,
                'notes' => $faker->boolean(25) ? $faker->sentence() : null,
            ]);

            if ($status === 'ready') {
                $book->available_copies = max(0, $book->available_copies - 1);
                $book->reserved_copies++;
                $book->save();
            }
        }
    }

    private function seedFinesAndPayments($faker, User $admin): void
    {
        $overdueBorrows = Borrow::with('fine')
            ->where('status', 'overdue')
            ->get();

        foreach ($overdueBorrows as $borrow) {
            if ($borrow->fine) {
                continue;
            }

            $amount = max(0.5, round($borrow->calculateFine(), 2));

            Fine::create([
                'user_id' => $borrow->user_id,
                'borrow_id' => $borrow->id,
                'type' => 'overdue',
                'overdue_days' => $borrow->overdue_days,
                'amount' => $amount,
                'paid_amount' => 0,
                'status' => 'unpaid',
                'reason' => 'Automatically generated for overdue return period.',
            ]);
        }

        $finesWithoutPayments = Fine::doesntHave('payments')->get();

        foreach ($finesWithoutPayments as $fine) {
            $settlement = Arr::random(['unpaid', 'partial', 'paid', 'unpaid']);

            if ($settlement === 'unpaid') {
                continue;
            }

            $paidAmount = $settlement === 'paid'
                ? (float) $fine->amount
                : round(max(0.5, $fine->amount * rand(25, 75) / 100), 2);

            Payment::create([
                'payment_code' => Payment::generateCode(),
                'user_id' => $fine->user_id,
                'fine_id' => $fine->id,
                'amount' => min($paidAmount, (float) $fine->amount),
                'method' => Arr::random(['cash', 'card', 'online']),
                'status' => 'completed',
                'transaction_ref' => 'TXN-' . strtoupper(Str::random(10)),
                'processed_by' => $admin->id,
                'notes' => $faker->boolean(20) ? $faker->sentence() : null,
                'created_at' => now()->subDays(rand(0, 20)),
                'updated_at' => now(),
            ]);
        }

        Fine::with('payments')->get()->each(function (Fine $fine) {
            $paidAmount = round((float) $fine->payments->sum('amount'), 2);
            $status = 'unpaid';

            if ($paidAmount >= (float) $fine->amount) {
                $status = 'paid';
            } elseif ($paidAmount > 0) {
                $status = 'partial';
            }

            $fine->update([
                'paid_amount' => min($paidAmount, (float) $fine->amount),
                'status' => $status,
            ]);
        });
    }

    private function seedActivityLogs($faker, User $admin, Collection $users, Collection $books, int $targetCount): void
    {
        $actions = [
            'login',
            'register',
            'create_book',
            'update_book',
            'issue_borrow',
            'return_book',
            'reserve_book',
            'renew_borrow',
            'record_payment',
        ];

        $existingLogs = ActivityLog::count();

        for ($i = $existingLogs; $i < $targetCount; $i++) {
            $subjectType = Arr::random([Book::class, Borrow::class, Fine::class, null]);
            $subjectId = null;

            if ($subjectType === Book::class) {
                $subjectId = $books->random()->id;
            } elseif ($subjectType === Borrow::class) {
                $subjectId = Borrow::inRandomOrder()->value('id');
            } elseif ($subjectType === Fine::class) {
                $subjectId = Fine::inRandomOrder()->value('id');
            }

            ActivityLog::create([
                'user_id' => Arr::random([$admin->id, $users->random()->id]),
                'action' => Arr::random($actions),
                'subject_type' => $subjectType,
                'subject_id' => $subjectId,
                'description' => $faker->sentence(),
                'ip_address' => $faker->ipv4(),
                'created_at' => now()->subDays(rand(0, 30)),
                'updated_at' => now(),
            ]);
        }
    }

    private function refreshDerivedFields(): void
    {
        User::query()->each(function (User $user) {
            $outstandingFines = Fine::where('user_id', $user->id)
                ->whereIn('status', ['unpaid', 'partial'])
                ->sum(DB::raw('amount - paid_amount'));

            $user->update([
                'total_borrowed' => $user->borrows()->count(),
                'outstanding_fines' => round((float) $outstandingFines, 2),
            ]);
        });

        Book::query()->each(function (Book $book) {
            $activeBorrows = $book->borrows()->whereIn('status', ['active', 'overdue'])->count();
            $readyReservations = $book->reservations()->where('status', 'ready')->count();
            $availableCopies = max(0, $book->total_copies - $activeBorrows - $readyReservations);

            $book->update([
                'available_copies' => $availableCopies,
                'reserved_copies' => $readyReservations,
                'status' => $book->status === 'lost'
                    ? 'lost'
                    : ($availableCopies > 0 ? 'available' : 'unavailable'),
            ]);
        });
    }

    private function generateIsbn(): string
    {
        do {
            $isbn = '978-' . rand(100, 999) . '-' . rand(10000, 99999) . '-' . rand(100, 999);
        } while (Book::where('isbn', $isbn)->exists());

        return $isbn;
    }

    private function pickBorrowableBook(Collection $books, bool $mustBeAvailable): ?Book
    {
        if (! $mustBeAvailable) {
            return $books->random();
        }

        return $books->first(function (Book $book) {
            return $book->available_copies > 0;
        });
    }

    private function borrowedAtForStatus(string $status): Carbon
    {
        return match ($status) {
            'returned' => now()->subDays(rand(20, 120)),
            'overdue' => now()->subDays(rand(18, 35)),
            default => now()->subDays(rand(1, 10)),
        };
    }

    private function dueDateForStatus(string $status, Carbon $borrowedAt): Carbon
    {
        return match ($status) {
            'overdue' => $borrowedAt->copy()->addDays(rand(7, 14)),
            default => $borrowedAt->copy()->addDays(config('library.max_borrow_days', 14)),
        };
    }
}
