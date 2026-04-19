<?php $__env->startSection('title', 'Manage Members'); ?>
<?php $__env->startSection('page-title', 'Members'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div>
        <h1>Members</h1>
        <p><?php echo e(\App\Models\User::members()->count()); ?> total registered members</p>
    </div>
</div>

<form method="GET" action="<?php echo e(route('admin.users.index')); ?>">
    <div class="search-bar">
        <div class="search-input-wrap" style="flex:2">
            <i class="fas fa-search"></i>
            <input type="text" name="search" placeholder="Search name, email, or member ID..." value="<?php echo e(request('search')); ?>">
        </div>
        <select name="status" class="form-control" style="max-width:150px;padding:10px 14px;">
            <option value="">All Status</option>
            <option value="active"    <?php echo e(request('status')=='active'    ? 'selected':''); ?>>Active</option>
            <option value="blocked"   <?php echo e(request('status')=='blocked'   ? 'selected':''); ?>>Blocked</option>
            <option value="suspended" <?php echo e(request('status')=='suspended' ? 'selected':''); ?>>Suspended</option>
        </select>
        <button type="submit" class="btn btn-primary">Filter</button>
        <?php if(request()->anyFilled(['search','status'])): ?>
        <a href="<?php echo e(route('admin.users.index')); ?>" class="btn btn-outline">Clear</a>
        <?php endif; ?>
    </div>
</form>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Member</th>
                    <th>Member ID</th>
                    <th>Phone</th>
                    <th>Active Borrows</th>
                    <th>Total Borrows</th>
                    <th>Outstanding Fine</th>
                    <th>Status</th>
                    <th>Joined</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <img src="<?php echo e($user->avatar_url); ?>" style="width:34px;height:34px;border-radius:50%;object-fit:cover;">
                            <div>
                                <div style="font-weight:600;font-size:14px;"><?php echo e($user->name); ?></div>
                                <div style="font-size:12px;color:var(--text-muted);"><?php echo e($user->email); ?></div>
                            </div>
                        </div>
                    </td>
                    <td style="font-family:monospace;font-size:12px;color:var(--text-muted);"><?php echo e($user->member_id); ?></td>
                    <td style="font-size:13px;"><?php echo e($user->phone ?? '—'); ?></td>
                    <td style="text-align:center;">
                        <span style="font-weight:700;color:<?php echo e($user->active_borrows_count > 0 ? 'var(--navy)' : 'var(--text-muted)'); ?>">
                            <?php echo e($user->active_borrows_count); ?>

                        </span>
                    </td>
                    <td style="text-align:center;font-size:13px;"><?php echo e($user->borrows_count); ?></td>
                    <td>
                        <?php if($user->outstanding_fines > 0): ?>
                            <span style="font-weight:700;color:var(--danger);">$<?php echo e(number_format($user->outstanding_fines, 2)); ?></span>
                        <?php else: ?>
                            <span style="color:var(--success);font-size:13px;"><i class="fas fa-check"></i></span>
                        <?php endif; ?>
                    </td>
                    <td><span class="badge <?php echo e($user->status_badge['class']); ?>"><?php echo e($user->status_badge['label']); ?></span></td>
                    <td style="font-size:12px;color:var(--text-muted);"><?php echo e($user->created_at->format('M d, Y')); ?></td>
                    <td>
                        <div style="display:flex;gap:4px;">
                            <a href="<?php echo e(route('admin.users.show', $user)); ?>" class="btn btn-outline btn-xs" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="<?php echo e(route('admin.users.edit', $user)); ?>" class="btn btn-outline btn-xs" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="<?php echo e(route('admin.users.toggle-block', $user)); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <button class="btn btn-xs <?php echo e($user->status === 'blocked' ? 'btn-success' : 'btn-warning'); ?>" title="<?php echo e($user->status === 'blocked' ? 'Unblock' : 'Block'); ?>">
                                    <i class="fas <?php echo e($user->status === 'blocked' ? 'fa-unlock' : 'fa-ban'); ?>"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="9"><div class="empty-state">No members found.</div></td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($users->hasPages()): ?>
    <div style="padding:8px 16px 16px;"><?php echo e($users->links('pagination::simple-default')); ?></div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Programs\E-Commerce\library-system\resources\views/admin/users/index.blade.php ENDPATH**/ ?>