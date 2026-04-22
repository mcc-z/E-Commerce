<?php $__env->startSection('title', 'Payments & Fines'); ?>
<?php $__env->startSection('page-title', 'Payments & Fines'); ?>

<?php $__env->startSection('content'); ?>


<div class="stat-grid" style="grid-template-columns:repeat(4,1fr);margin-bottom:24px;">
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-calendar-day"></i></div>
        <div>
            <div class="stat-value">$<?php echo e(number_format($stats['total_today'],2)); ?></div>
            <div class="stat-label">Today's Revenue</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-calendar-alt"></i></div>
        <div>
            <div class="stat-value">$<?php echo e(number_format($stats['total_month'],2)); ?></div>
            <div class="stat-label">This Month</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon gold"><i class="fas fa-coins"></i></div>
        <div>
            <div class="stat-value">$<?php echo e(number_format($stats['total_all'],2)); ?></div>
            <div class="stat-label">All-Time Revenue</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon red"><i class="fas fa-exclamation-triangle"></i></div>
        <div>
            <div class="stat-value">$<?php echo e(number_format($stats['unpaid_fines'],2)); ?></div>
            <div class="stat-label">Unpaid Fines</div>
        </div>
    </div>
</div>


<form method="GET" action="<?php echo e(route('admin.payments.index')); ?>">
    <div class="search-bar">
        <div class="search-input-wrap" style="flex:2">
            <i class="fas fa-search"></i>
            <input type="text" name="search" placeholder="Search payment code or member name..." value="<?php echo e(request('search')); ?>">
        </div>
        <select name="method" class="form-control" style="max-width:160px;padding:10px 14px;">
            <option value="">All Methods</option>
            <option value="cash"   <?php echo e(request('method')=='cash'   ? 'selected':''); ?>>Cash</option>
            <option value="card"   <?php echo e(request('method')=='card'   ? 'selected':''); ?>>Card</option>
            <option value="online" <?php echo e(request('method')=='online' ? 'selected':''); ?>>Online</option>
            <option value="waived" <?php echo e(request('method')=='waived' ? 'selected':''); ?>>Waived</option>
        </select>
        <button type="submit" class="btn btn-primary">Filter</button>
        <?php if(request()->anyFilled(['search','method'])): ?>
        <a href="<?php echo e(route('admin.payments.index')); ?>" class="btn btn-outline">Clear</a>
        <?php endif; ?>
    </div>
</form>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Member</th>
                    <th>Fine / Book</th>
                    <th>Amount</th>
                    <th>Method</th>
                    <th>Processed By</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td style="font-family:monospace;font-size:12px;color:var(--text-muted);"><?php echo e($payment->payment_code); ?></td>
                    <td>
                        <a href="<?php echo e(route('admin.users.show', $payment->user)); ?>" style="text-decoration:none;color:var(--navy);font-weight:600;font-size:14px;">
                            <?php echo e($payment->user->name); ?>

                        </a>
                        <div style="font-size:11px;color:var(--text-muted);"><?php echo e($payment->user->member_id); ?></div>
                    </td>
                    <td>
                        <?php if($payment->fine?->borrow?->book): ?>
                            <div style="font-size:13px;"><?php echo e($payment->fine->borrow->book->title); ?></div>
                            <div style="font-size:11px;color:var(--text-muted);"><?php echo e($payment->fine->overdue_days); ?>d overdue fine</div>
                        <?php else: ?>
                            <span style="color:var(--text-muted);font-size:13px;">—</span>
                        <?php endif; ?>
                    </td>
                    <td style="font-size:15px;font-weight:700;color:var(--success);">$<?php echo e(number_format($payment->amount,2)); ?></td>
                    <td>
                        <?php
                            $methodIcon = match($payment->method) {
                                'cash'   => 'fa-money-bill',
                                'card'   => 'fa-credit-card',
                                'online' => 'fa-globe',
                                'waived' => 'fa-hand-holding-heart',
                                default  => 'fa-circle',
                            };
                        ?>
                        <span style="display:inline-flex;align-items:center;gap:5px;font-size:13px;font-weight:500;">
                            <i class="fas <?php echo e($methodIcon); ?>" style="color:var(--text-muted);"></i>
                            <?php echo e(ucfirst($payment->method)); ?>

                        </span>
                    </td>
                    <td style="font-size:13px;color:var(--text-muted);">
                        <?php echo e($payment->processedBy?->name ?? 'Self-service'); ?>

                    </td>
                    <td style="font-size:12px;color:var(--text-muted);"><?php echo e($payment->created_at->format('M d, Y H:i')); ?></td>
                    <td>
                        <span class="badge <?php echo e($payment->status === 'completed' ? 'badge-success' : 'badge-secondary'); ?>">
                            <?php echo e(ucfirst($payment->status)); ?>

                        </span>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="8"><div class="empty-state">No payments found.</div></td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($payments->hasPages()): ?>
    <div style="padding:8px 16px 16px;"><?php echo e($payments->links('pagination::simple-default')); ?></div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Programs\E-Commerce\library-system-final\resources\views/admin/payments/index.blade.php ENDPATH**/ ?>