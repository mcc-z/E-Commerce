<?php $__env->startSection('title', 'Manage Books'); ?>
<?php $__env->startSection('page-title', 'Books'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div>
        <h1>Books</h1>
        <p><?php echo e(\App\Models\Book::count()); ?> total books in catalog</p>
    </div>
    <a href="<?php echo e(route('admin.books.create')); ?>" class="btn btn-gold">
        <i class="fas fa-plus"></i> Add New Book
    </a>
</div>

<form method="GET" action="<?php echo e(route('admin.books.index')); ?>">
    <div class="search-bar">
        <div class="search-input-wrap" style="flex:2">
            <i class="fas fa-search"></i>
            <input type="text" name="search" placeholder="Search title, author, ISBN..." value="<?php echo e(request('search')); ?>">
        </div>
        <select name="category" class="form-control" style="max-width:180px;padding:10px 14px;">
            <option value="">All Categories</option>
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($cat->id); ?>" <?php echo e(request('category')==$cat->id ? 'selected' : ''); ?>><?php echo e($cat->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <select name="status" class="form-control" style="max-width:150px;padding:10px 14px;">
            <option value="">All Status</option>
            <option value="available" <?php echo e(request('status')=='available' ? 'selected':''); ?>>Available</option>
            <option value="unavailable" <?php echo e(request('status')=='unavailable' ? 'selected':''); ?>>Unavailable</option>
        </select>
        <button type="submit" class="btn btn-primary">Filter</button>
        <?php if(request()->anyFilled(['search','category','status'])): ?>
        <a href="<?php echo e(route('admin.books.index')); ?>" class="btn btn-outline">Clear</a>
        <?php endif; ?>
    </div>
</form>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Book</th>
                    <th>ISBN</th>
                    <th>Category</th>
                    <th>Location</th>
                    <th>Copies</th>
                    <th>Available</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $books; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:12px;">
                            <img src="<?php echo e($book->cover_url); ?>" alt="<?php echo e($book->title); ?>"
                                 style="width:42px;height:60px;object-fit:cover;border-radius:8px;border:1px solid var(--cream-2);flex-shrink:0;">
                            <div>
                                <div style="font-weight:600;font-size:14px;"><?php echo e($book->title); ?></div>
                                <div style="font-size:12px;color:var(--text-muted);"><?php echo e($book->author); ?></div>
                            </div>
                        </div>
                    </td>
                    <td style="font-family:monospace;font-size:12px;color:var(--text-muted);"><?php echo e($book->isbn); ?></td>
                    <td>
                        <span style="display:inline-flex;align-items:center;gap:5px;font-size:12px;">
                            <span style="width:8px;height:8px;border-radius:50%;background:<?php echo e($book->category->color); ?>"></span>
                            <?php echo e($book->category->name); ?>

                        </span>
                    </td>
                    <td style="font-size:13px;font-family:monospace;"><?php echo e($book->location ?? '—'); ?></td>
                    <td style="text-align:center;font-weight:600;"><?php echo e($book->total_copies); ?></td>
                    <td style="text-align:center;">
                        <span style="font-weight:700;color:<?php echo e($book->available_copies > 0 ? 'var(--success)' : 'var(--danger)'); ?>">
                            <?php echo e($book->available_copies); ?>

                        </span>
                    </td>
                    <td><span class="badge <?php echo e($book->availability_badge['class']); ?>"><?php echo e($book->availability_badge['label']); ?></span></td>
                    <td>
                        <div style="display:flex;gap:4px;">
                            <a href="<?php echo e(route('admin.books.edit', $book)); ?>" class="btn btn-outline btn-xs"><i class="fas fa-edit"></i></a>
                            <form action="<?php echo e(route('admin.books.destroy', $book)); ?>" method="POST"
                                  onsubmit="return confirm('Delete this book?')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button class="btn btn-danger btn-xs"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="8"><div class="empty-state">No books found.</div></td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($books->hasPages()): ?>
    <div style="padding:8px 16px 16px;"><?php echo e($books->links()); ?></div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\library-system\library-system\resources\views/admin/books/index.blade.php ENDPATH**/ ?>