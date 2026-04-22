<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap');
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
        .book-gradient { background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%); }
    </style>
</head>
<body class="text-slate-900">

    <header class="py-16 px-4 text-center">
        <div class="mb-6 flex justify-center">
            <div class="bg-blue-600 p-3 rounded-xl shadow-lg">
                <i class="fas fa-book-open text-white text-3xl"></i>
            </div>
        </div>
        <h1 class="text-5xl font-extrabold mb-4 tracking-tight">Welcome to Our Library</h1>
        <p class="text-slate-500 text-lg mb-8 max-w-2xl mx-auto">
            Discover thousands of books, manage your reading journey, and explore endless knowledge.
        </p>
        <div class="flex justify-center gap-4">
            <a href="<?php echo e(route('login')); ?>" class="bg-blue-600 text-white px-8 py-4 rounded-xl font-bold inline-flex items-center gap-2 hover:bg-blue-700 transition shadow-lg shadow-blue-200">
                Get Started <i class="fas fa-arrow-right text-sm"></i>
            </a>
        </div>
    </header>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
        <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
            <div class="text-4xl font-extrabold text-blue-600 mb-2"><?php echo e(number_format($stats['books_count'])); ?></div>
            <div class="text-slate-500 font-medium">Books Available</div>
        </div>
        <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
            <div class="text-4xl font-extrabold text-purple-600 mb-2"><?php echo e(number_format($stats['members_count'])); ?></div>
            <div class="text-slate-500 font-medium">Active Members</div>
        </div>
        <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
            <div class="text-4xl font-extrabold text-indigo-600 mb-2"><?php echo e(number_format($stats['borrows_count'])); ?></div>
            <div class="text-slate-500 font-medium">Books Borrowed</div>
        </div>
    </div>

    <section class="max-w-7xl mx-auto px-4 mb-20">
        <div class="flex justify-between items-end mb-8">
            <div>
                <h2 class="text-3xl font-bold mb-2">Top Picks for You</h2>
                <p class="text-slate-500">Popular and highly-rated books from our collection</p>
            </div>
            <i class="fas fa-star text-yellow-400 text-2xl"></i>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <?php $__currentLoopData = $topPicks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="bg-white rounded-2xl overflow-hidden shadow-md border border-slate-100 hover:shadow-xl transition duration-300">
                <div class="h-64 overflow-hidden relative">
                    <img src="<?php echo e($book->cover_url); ?>" alt="<?php echo e($book->title); ?>" class="w-full h-full object-cover">
                    <div class="absolute top-4 right-4 bg-white/90 backdrop-blur px-3 py-1 rounded-full text-xs font-bold text-blue-600 uppercase tracking-wider">
                        <?php echo e($book->category->name ?? 'Library'); ?>

                    </div>
                </div>
                <div class="p-6">
                    <h3 class="font-bold text-xl mb-1 text-slate-800 line-clamp-1"><?php echo e($book->title); ?></h3>
                    <p class="text-slate-500 mb-4"><?php echo e($book->author); ?></p>
                    <div class="flex items-center justify-between pt-4 border-t border-slate-50">
                        <span class="text-sm font-medium <?php echo e($book->available_copies > 0 ? 'text-green-600' : 'text-red-600'); ?>">
                            <?php echo e($book->available_copies > 0 ? 'Available Now' : 'Out of Stock'); ?>

                        </span>
                        <a href="<?php echo e(route('login')); ?>" class="text-blue-600 font-bold text-sm hover:text-blue-700">View Details</a>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </section>

    <section class="max-w-6xl mx-auto px-4 mb-20">
        <div class="bg-white rounded-3xl p-12 border border-slate-100 shadow-sm">
            <h2 class="text-3xl font-bold text-center mb-12">Why Choose Our Library?</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 text-center">
                <div>
                    <div class="bg-blue-50 text-blue-600 w-14 h-14 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-book text-xl"></i>
                    </div>
                    <h4 class="font-bold mb-3 text-lg">Vast Collection</h4>
                    <p class="text-slate-500 leading-relaxed">Access thousands of books across all genres and categories</p>
                </div>
                <div>
                    <div class="bg-purple-50 text-purple-600 w-14 h-14 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-clock text-xl"></i>
                    </div>
                    <h4 class="font-bold mb-3 text-lg">Easy Borrowing</h4>
                    <p class="text-slate-500 leading-relaxed">Simple and quick book borrowing process with extended loan periods</p>
                </div>
                <div>
                    <div class="bg-green-50 text-green-600 w-14 h-14 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-user-group text-xl"></i>
                    </div>
                    <h4 class="font-bold mb-3 text-lg">Community</h4>
                    <p class="text-slate-500 leading-relaxed">Join a vibrant community of readers and book enthusiasts</p>
                </div>
            </div>
        </div>
    </section>

    <section class="text-center py-20 bg-slate-50">
        <h2 class="text-4xl font-bold mb-4">Ready to Start Your Reading Journey?</h2>
        <p class="text-slate-500 mb-8">Join our library today and explore a world of knowledge</p>
        <a href="<?php echo e(route('login')); ?>" class="bg-slate-950 text-white px-10 py-4 rounded-xl font-bold inline-flex items-center gap-3 hover:bg-slate-800 transition">
            Sign In Now <i class="fas fa-arrow-right"></i>
        </a>
    </section>

    <footer class="bg-slate-950 py-12 text-center border-t border-slate-800">
        <div class="flex items-center justify-center gap-3 text-white mb-6">
            <i class="fas fa-align-left text-xl"></i>
            <span class="font-bold text-lg tracking-tight">Library Management System</span>
        </div>
        <p class="text-slate-500 text-sm">© 2026 Library Management System. All rights reserved.</p>
    </footer>

</body>
</html><?php /**PATH D:\Programs\E-Commerce\library-system-final\resources\views/home.blade.php ENDPATH**/ ?>