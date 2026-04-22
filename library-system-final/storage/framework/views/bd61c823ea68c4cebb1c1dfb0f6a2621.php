<?php $__env->startSection('title', 'My Borrows'); ?>
<?php $__env->startSection('page-title', 'My Borrows'); ?>

<?php $__env->startSection('content'); ?>

<?php if($overdueBorrows->count()): ?>
<div class="alert alert-error" style="margin-bottom:20px;">
    <i class="fas fa-exclamation-triangle"></i>
    <div>
        <strong>You have <?php echo e($overdueBorrows->count()); ?> overdue book(s).</strong>
        Total estimated fine: $<?php echo e(number_format($overdueBorrows->sum('calculated_fine'), 2)); ?>

        <a href="<?php echo e(route('user.fines.index')); ?>" style="color:inherit;font-weight:700;"> — Pay Now →</a>
    </div>
</div>
<?php endif; ?>

<div class="card">
    <div class="card-header" style="padding-bottom:16px;">
        <span class="card-title">Borrow History</span>
        <div style="display:flex;gap:8px;">
            <span style="font-size:13px;color:var(--text-muted);"><?php echo e($borrows->total()); ?> total records</span>
        </div>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Book</th>
                    <th>Borrowed</th>
                    <th>Due Date</th>
                    <th>Returned</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $borrows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $borrow): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td style="font-family:monospace;font-size:12px;color:var(--text-muted);"><?php echo e($borrow->borrow_code); ?></td>
                    <td>
                        <div style="font-weight:600;font-size:14px;"><?php echo e($borrow->book->title); ?></div>
                        <div style="font-size:12px;color:var(--text-muted);"><?php echo e($borrow->book->author); ?></div>
                    </td>
                    <td style="font-size:13px;"><?php echo e($borrow->borrowed_at->format('M d, Y')); ?></td>
                    <td style="font-size:13px;font-weight:600;color:<?php echo e($borrow->isOverdue() && !$borrow->returned_at ? 'var(--danger)' : 'var(--text)'); ?>;">
                        <?php echo e($borrow->due_date->format('M d, Y')); ?>

                        <?php if($borrow->isOverdue() && !$borrow->returned_at): ?>
                            <div style="font-size:11px;color:var(--danger);"><?php echo e($borrow->overdue_days); ?> days overdue</div>
                        <?php endif; ?>
                    </td>
                    <td style="font-size:13px;color:var(--text-muted);">
                        <?php echo e($borrow->returned_at ? $borrow->returned_at->format('M d, Y') : '—'); ?>

                    </td>
                    <td>
                        <span class="badge <?php echo e($borrow->status_badge['class']); ?>"><?php echo e($borrow->status_badge['label']); ?></span>
                    </td>
                    <td>
                        <?php if($borrow->canRenew()): ?>
                            <form action="<?php echo e(route('user.borrows.renew', $borrow)); ?>" method="POST" style="display:inline;">
                                <?php echo csrf_field(); ?>
                                <button class="btn btn-success btn-xs">
                                    <i class="fas fa-redo"></i> Renew
                                </button>
                            </form>
                        <?php elseif(in_array($borrow->status, ['active','overdue']) && !$borrow->canRenew() && $borrow->renewal_count >= 2): ?>
                            <span style="font-size:11px;color:var(--text-muted);">Max renewals</span>
                        <?php else: ?>
                            <span style="font-size:11px;color:var(--text-muted);">—</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <i class="fas fa-book-reader"></i>
                            <h3>No borrows yet</h3>
                            <p>Start by browsing our book collection.</p>
                            <a href="<?php echo e(route('user.books.index')); ?>" class="btn btn-primary" style="margin-top:16px;">Browse Books</a>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($borrows->hasPages()): ?>
    <div style="padding:8px 16px 16px;">
        <?php echo e($borrows->links('pagination::simple-default')); ?>

    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Programs\E-Commerce\library-system-final\resources\views/user/borrows/index.blade.php ENDPATH**/ ?>