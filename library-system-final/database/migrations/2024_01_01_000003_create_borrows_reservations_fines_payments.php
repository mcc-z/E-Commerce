<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Borrows / Loans
        Schema::create('borrows', function (Blueprint $table) {
            $table->id();
            $table->string('borrow_code')->unique(); // e.g., BRW-2024-00001
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('book_id')->constrained()->onDelete('cascade');
            $table->date('borrowed_at');
            $table->date('due_date');
            $table->date('returned_at')->nullable();
            $table->enum('status', ['active', 'returned', 'overdue', 'lost'])->default('active');
            $table->foreignId('issued_by')->nullable()->constrained('users')->nullOnDelete(); // admin who issued
            $table->foreignId('returned_to')->nullable()->constrained('users')->nullOnDelete(); // admin who received
            $table->text('notes')->nullable();
            $table->integer('renewal_count')->default(0);
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['book_id', 'status']);
        });

        // Reservations / Bookings
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->string('reservation_code')->unique(); // e.g., RES-2024-00001
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('book_id')->constrained()->onDelete('cascade');
            $table->dateTime('reserved_at');
            $table->dateTime('expires_at'); // 48 hours after book becomes available
            $table->dateTime('notified_at')->nullable(); // when user was notified book is ready
            $table->enum('status', ['pending', 'ready', 'fulfilled', 'cancelled', 'expired'])->default('pending');
            $table->integer('queue_position')->default(1);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['book_id', 'status']);
        });

        // Fines
        Schema::create('fines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('borrow_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['overdue', 'lost', 'damaged'])->default('overdue');
            $table->integer('overdue_days')->default(0);
            $table->decimal('amount', 8, 2);
            $table->decimal('paid_amount', 8, 2)->default(0.00);
            $table->enum('status', ['unpaid', 'partial', 'paid', 'waived'])->default('unpaid');
            $table->text('reason')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
        });

        // Payments
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_code')->unique(); // e.g., PAY-2024-00001
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('fine_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('amount', 8, 2);
            $table->enum('method', ['cash', 'card', 'online', 'waived'])->default('cash');
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])->default('completed');
            $table->string('transaction_ref')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Activity / Audit Log
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('action'); // e.g., 'borrow_book', 'return_book', 'pay_fine'
            $table->string('subject_type')->nullable(); // model class
            $table->unsignedBigInteger('subject_id')->nullable(); // model id
            $table->text('description')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();

            $table->index(['user_id', 'action']);
            $table->index(['subject_type', 'subject_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('fines');
        Schema::dropIfExists('reservations');
        Schema::dropIfExists('borrows');
    }
};
