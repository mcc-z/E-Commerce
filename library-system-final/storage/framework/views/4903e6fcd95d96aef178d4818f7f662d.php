<?php $__env->startSection('title', 'Borrows'); ?>
<?php $__env->startSection('page-title', 'Borrows'); ?>

<?php $__env->startSection('content'); ?>


<div style="display:flex;gap:8px;margin-bottom:20px;flex-wrap:wrap;">
    <?php $__currentLoopData = [
        ['label'=>'All',      'value'=>'',         'count'=>array_sum($counts), 'color'=>'var(--navy)'],
        ['label'=>'Active',   'value'=>'active',   'count'=>$counts['active'],  'color'=>'var(--info)'],
        ['label'=>'Overdue',  'value'=>'overdue',  'count'=>$counts['overdue'], 'color'=>'var(--danger)'],
        ['label'=>'Returned', 'value'=>'returned', 'count'=>$counts['returned'],'color'=>'var(--success)'],
    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tab): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <a href="<?php echo e(route('admin.borrows.index', ['status'=>$tab['value']])); ?>"
       style="padding:8px 16px;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;border:1.5px solid;transition:all 0.2s;
              <?php echo e(request('status') == $tab['value'] ? "background:{$tab['color']};color:white;border-color:{$tab['color']};" : 'background:white;color:var(--text-muted);border-color:var(--cream-2);'); ?>">
        <?php echo e($tab['label']); ?>

        <span style="margin-left:4px;background:rgba(255,255,255,0.25);padding:1px 6px;border-radius:10px;font-size:11px;">
            <?php echo e($tab['count']); ?>

        </span>
    </a>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <form action="<?php echo e(route('admin.borrows.update-overdue')); ?>" method="POST" style="margin-left:auto;">
        <?php echo csrf_field(); ?>
        <button class="btn btn-outline btn-sm" title="Mark overdue borrows">
            <i class="fas fa-sync"></i> Refresh Overdue
        </button>
    </form>
</div>

<form method="GET" action="<?php echo e(route('admin.borrows.index')); ?>">
    <input type="hidden" name="status" value="<?php echo e(request('status')); ?>">
    <div class="search-bar">
        <div class="search-input-wrap" style="flex:2">
            <i class="fas fa-search"></i>
            <input type="text" name="search" placeholder="Search by borrow code, member, or book..." value="<?php echo e(request('search')); ?>">
        </div>
        <button type="submit" class="btn btn-primary">Search</button>
        <?php if(request('search')): ?>
        <a href="<?php echo e(route('admin.borrows.index', ['status'=>request('status')])); ?>" class="btn btn-outline">Clear</a>
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
                    <th>Book</th>
                    <th>Borrowed</th>
                    <th>Due Date</th>
                    <th>Returned</th>
                    <th>Status</th>
                    <th>Fine</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $borrows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $borrow): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td style="font-family:monospace;font-size:12px;color:var(--text-muted);"><?php echo e($borrow->borrow_code); ?></td>
                    <td>
                        <a href="<?php echo e(route('admin.users.show', $borrow->user)); ?>" style="text-decoration:none;color:var(--navy);font-weight:600;font-size:14px;">
                            <?php echo e($borrow->user->name); ?>

                        </a>
                        <div style="font-size:11px;color:var(--text-muted);"><?php echo e($borrow->user->member_id); ?></div>
                    </td>
                    <td>
                        <div style="font-size:14px;font-weight:500;"><?php echo e($borrow->book->title); ?></div>
                        <div style="font-size:12px;color:var(--text-muted);"><?php echo e($borrow->book->author); ?></div>
                    </td>
                    <td style="font-size:13px;"><?php echo e($borrow->borrowed_at->format('M d, Y')); ?></td>
                    <td style="font-size:13px;font-weight:<?php echo e($borrow->isOverdue() && !$borrow->returned_at ? '700' : '400'); ?>;color:<?php echo e($borrow->isOverdue() && !$borrow->returned_at ? 'var(--danger)' : 'var(--text)'); ?>;">
                        <?php echo e($borrow->due_date->format('M d, Y')); ?>

                        <?php if($borrow->isOverdue() && !$borrow->returned_at): ?>
                        <div style="font-size:11px;"><?php echo e($borrow->overdue_days); ?>d overdue</div>
                        <?php endif; ?>
                    </td>
                    <td style="font-size:13px;color:var(--text-muted);">
                        <?php echo e($borrow->returned_at ? $borrow->returned_at->format('M d, Y') : '—'); ?>

                    </td>
                    <td><span class="badge <?php echo e($borrow->status_badge['class']); ?>"><?php echo e($borrow->status_badge['label']); ?></span></td>
                    <td>
                        <?php if($borrow->fine): ?>
                            <span style="font-size:13px;font-weight:700;color:<?php echo e($borrow->fine->remaining > 0 ? 'var(--danger)' : 'var(--success)'); ?>">
                                $<?php echo e(number_format($borrow->fine->amount, 2)); ?>

                            </span>
                            <?php if($borrow->fine->remaining > 0): ?>
                                <div style="font-size:11px;color:var(--text-muted);">Rem: $<?php echo e(number_format($borrow->fine->remaining,2)); ?></div>
                            <?php endif; ?>
                        <?php elseif($borrow->isOverdue() && !$borrow->returned_at): ?>
                            <span style="font-size:12px;color:var(--warning);">~$<?php echo e(number_format($borrow->calculateFine(),2)); ?></span>
                        <?php else: ?>
                            <span style="color:var(--text-muted);">—</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if(in_array($borrow->status, ['active','overdue'])): ?>
                        <form action="<?php echo e(route('admin.borrows.return', $borrow)); ?>" method="POST"
                              onsubmit="return confirm('Confirm book return?')">
                            <?php echo csrf_field(); ?>
                            <button class="btn btn-success btn-xs"><i class="fas fa-undo"></i> Return</button>
                        </form>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="9"><div class="empty-state">No borrows found.</div></td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($borrows->hasPages()): ?>
    <div style="padding:8px 16px 16px;"><?php echo e($borrows->links()); ?></div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\library-system\library-system\resources\views/admin/borrows/index.blade.php ENDPATH**/ ?>