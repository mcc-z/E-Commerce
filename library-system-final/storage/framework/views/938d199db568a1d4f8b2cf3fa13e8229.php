<?php $__env->startSection('title', 'Admin Dashboard'); ?>
<?php $__env->startSection('page-title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>


<div class="stat-grid" style="grid-template-columns:repeat(5,1fr);">
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-books"></i></div>
        <div>
            <div class="stat-value"><?php echo e($stats['total_books']); ?></div>
            <div class="stat-label">Total Books</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-users"></i></div>
        <div>
            <div class="stat-value"><?php echo e($stats['total_members']); ?></div>
            <div class="stat-label">Members</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon gold"><i class="fas fa-book-reader"></i></div>
        <div>
            <div class="stat-value"><?php echo e($stats['active_borrows']); ?></div>
            <div class="stat-label">Active Borrows</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon red"><i class="fas fa-exclamation-circle"></i></div>
        <div>
            <div class="stat-value"><?php echo e($stats['overdue_borrows']); ?></div>
            <div class="stat-label">Overdue</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-dollar-sign"></i></div>
        <div>
            <div class="stat-value">$<?php echo e(number_format($stats['today_payments'], 2)); ?></div>
            <div class="stat-label">Today's Revenue</div>
        </div>
    </div>
</div>

<div class="stat-grid" style="grid-template-columns:repeat(5,1fr);margin-top:-8px;">
    <div class="stat-card">
        <div class="stat-icon teal"><i class="fas fa-check-circle"></i></div>
        <div>
            <div class="stat-value"><?php echo e($stats['available_books']); ?></div>
            <div class="stat-label">Available Books</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon purple"><i class="fas fa-bookmark"></i></div>
        <div>
            <div class="stat-value"><?php echo e($stats['reservations']); ?></div>
            <div class="stat-label">Reservations</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon red"><i class="fas fa-coins"></i></div>
        <div>
            <div class="stat-value">$<?php echo e(number_format($stats['total_fines'], 2)); ?></div>
            <div class="stat-label">Unpaid Fines</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-user-plus"></i></div>
        <div>
            <div class="stat-value"><?php echo e($stats['new_members_month']); ?></div>
            <div class="stat-label">New This Month</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon red"><i class="fas fa-user-slash"></i></div>
        <div>
            <div class="stat-value"><?php echo e($stats['blocked_users']); ?></div>
            <div class="stat-label">Blocked Members</div>
        </div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;margin-bottom:24px;">

    
    <div class="card">
        <div class="card-header">
            <span class="card-title">Top Borrowed Books</span>
            <a href="<?php echo e(route('admin.books.index')); ?>" class="btn btn-outline btn-sm">All Books</a>
        </div>
        <div class="card-body" style="padding-top:12px;">
            <?php $__currentLoopData = $topBooks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div style="display:flex;align-items:center;gap:12px;padding:10px 0;border-bottom:1px solid var(--cream-2);">
                <div style="width:28px;height:28px;border-radius:50%;background:<?php echo e(['var(--gold)','var(--navy)','var(--navy-2)','var(--navy-3)','var(--cream-2)'][$i]); ?>;color:<?php echo e($i < 2 ? ($i===0?'var(--navy)':'white') : 'var(--text-muted)'); ?>;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;flex-shrink:0;">
                    <?php echo e($i + 1); ?>

                </div>
                <div style="flex:1;min-width:0;">
                    <div style="font-weight:600;font-size:14px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?php echo e($book->title); ?></div>
                    <div style="font-size:12px;color:var(--text-muted);"><?php echo e($book->author); ?></div>
                </div>
                <div style="font-size:13px;font-weight:700;color:var(--navy);"><?php echo e($book->borrows_count); ?>x</div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    
    <div class="card">
        <div class="card-header">
            <span class="card-title" style="color:var(--danger);">Overdue Borrows</span>
            <div style="display:flex;gap:8px;align-items:center;">
                <span class="badge badge-danger"><?php echo e($overdueBorrows->count()); ?></span>
                <form action="<?php echo e(route('admin.borrows.update-overdue')); ?>" method="POST" style="display:inline;">
                    <?php echo csrf_field(); ?>
                    <button class="btn btn-outline btn-sm" title="Refresh overdue statuses">
                        <i class="fas fa-sync"></i>
                    </button>
                </form>
            </div>
        </div>
        <div class="card-body" style="padding-top:12px;">
            <?php $__empty_1 = true; $__currentLoopData = $overdueBorrows->take(6); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $borrow): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div style="display:flex;align-items:center;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--cream-2);">
                <div style="min-width:0;flex:1;">
                    <div style="font-size:13px;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?php echo e($borrow->book->title); ?></div>
                    <div style="font-size:12px;color:var(--text-muted);"><?php echo e($borrow->user->name); ?></div>
                </div>
                <div style="text-align:right;flex-shrink:0;margin-left:10px;">
                    <div style="font-size:12px;color:var(--danger);font-weight:600;"><?php echo e($borrow->overdue_days); ?>d overdue</div>
                    <div style="font-size:11px;color:var(--text-muted);">Due <?php echo e($borrow->due_date->format('M d')); ?></div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="empty-state" style="padding:24px 0;">
                <i class="fas fa-check-circle" style="font-size:28px;color:var(--success);"></i>
                <p style="color:var(--success);margin-top:8px;font-size:13px;">No overdue borrows!</p>
            </div>
            <?php endif; ?>
            <?php if($overdueBorrows->count() > 6): ?>
                <div style="text-align:center;padding-top:10px;">
                    <a href="<?php echo e(route('admin.borrows.index', ['status'=>'overdue'])); ?>" class="btn btn-outline btn-sm">
                        View all <?php echo e($overdueBorrows->count()); ?> overdue
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;">

    
    <div class="card">
        <div class="card-header"><span class="card-title">Recent Activity</span></div>
        <div class="card-body" style="padding-top:12px;">
            <?php $__currentLoopData = $recentActivity; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div style="display:flex;gap:10px;padding:8px 0;border-bottom:1px solid var(--cream-2);font-size:13px;">
                <div style="width:28px;height:28px;border-radius:50%;background:var(--cream-2);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="fas <?php echo e(str_contains($log->action,'borrow') ? 'fa-book-reader' : (str_contains($log->action,'pay') ? 'fa-dollar-sign' : (str_contains($log->action,'login') ? 'fa-sign-in-alt' : 'fa-circle'))); ?>" style="font-size:11px;color:var(--text-muted);"></i>
                </div>
                <div style="flex:1;min-width:0;">
                    <div style="font-weight:500;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?php echo e($log->description); ?></div>
                    <div style="font-size:11px;color:var(--text-muted);"><?php echo e($log->user?->name ?? 'System'); ?> · <?php echo e($log->created_at->diffForHumans()); ?></div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    
    <div class="card">
        <div class="card-header"><span class="card-title">Books by Category</span></div>
        <div class="card-body">
            <?php $totalBooks = $categoryStats->sum('count'); ?>
            <?php $__currentLoopData = $categoryStats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $cat = \App\Models\Category::find($stat->category_id);
                $pct = $totalBooks > 0 ? round(($stat->count / $totalBooks) * 100) : 0;
            ?>
            <?php if($cat): ?>
            <div style="margin-bottom:12px;">
                <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:4px;">
                    <span style="font-weight:500;"><?php echo e($cat->name); ?></span>
                    <span style="color:var(--text-muted);"><?php echo e($stat->count); ?> books (<?php echo e($pct); ?>%)</span>
                </div>
                <div style="background:var(--cream-2);border-radius:20px;height:6px;overflow:hidden;">
                    <div style="width:<?php echo e($pct); ?>%;height:100%;background:<?php echo e($cat->color); ?>;border-radius:20px;transition:width 0.5s ease;"></div>
                </div>
            </div>
            <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Programs\E-Commerce\library-system-final\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>