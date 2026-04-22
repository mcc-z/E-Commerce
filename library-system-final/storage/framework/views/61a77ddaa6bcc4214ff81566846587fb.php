<?php $__env->startSection('title', 'Edit Member'); ?>
<?php $__env->startSection('page-title', 'Edit Member'); ?>

<?php $__env->startSection('content'); ?>
<div style="max-width:640px;">
    <div class="card">
        <div class="card-header">
            <span class="card-title">Edit: <?php echo e($user->name); ?></span>
            <a href="<?php echo e(route('admin.users.show', $user)); ?>" class="btn btn-outline btn-sm">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
        <div class="card-body">
            <form action="<?php echo e(route('admin.users.update', $user)); ?>" method="POST">
                <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>

                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Full Name *</label>
                        <input type="text" name="name" class="form-control" value="<?php echo e(old('name', $user->name)); ?>" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email *</label>
                        <input type="email" name="email" class="form-control" value="<?php echo e(old('email', $user->email)); ?>" required>
                    </div>
                </div>
                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Phone</label>
                        <input type="tel" name="phone" class="form-control" value="<?php echo e(old('phone', $user->phone)); ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Account Status *</label>
                        <select name="status" class="form-control" style="padding:10px 14px;" required>
                            <option value="active"    <?php echo e(old('status', $user->status) == 'active'    ? 'selected' : ''); ?>>Active</option>
                            <option value="blocked"   <?php echo e(old('status', $user->status) == 'blocked'   ? 'selected' : ''); ?>>Blocked</option>
                            <option value="suspended" <?php echo e(old('status', $user->status) == 'suspended' ? 'selected' : ''); ?>>Suspended</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Address</label>
                    <input type="text" name="address" class="form-control" value="<?php echo e(old('address', $user->address)); ?>">
                </div>

                <div style="border-top:1px solid var(--cream-2);padding-top:20px;margin:20px 0 16px;">
                    <div style="font-size:12px;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-muted);font-weight:600;margin-bottom:14px;">
                        Reset Password <span style="font-weight:400;font-size:11px;">(leave blank to keep current)</span>
                    </div>
                    <div class="form-grid-2">
                        <div class="form-group">
                            <label class="form-label">New Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Min 8 characters">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control">
                        </div>
                    </div>
                </div>

                <div style="display:flex;gap:12px;">
                    <button type="submit" class="btn btn-gold"><i class="fas fa-save"></i> Save Changes</button>
                    <a href="<?php echo e(route('admin.users.show', $user)); ?>" class="btn btn-outline">Cancel</a>

                    <?php if($user->active_borrows_count == 0): ?>
                    <button type="button" class="btn btn-danger btn-sm" style="margin-left:auto;" onclick="openDeleteModal('<?php echo e(route('admin.users.destroy', $user)); ?>')">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="deleteModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center;">
    <div style="background:white;border-radius:16px;padding:32px;max-width:400px;width:90%;">
        <h3 style="font-family:'DM Serif Display',serif;color:var(--navy);margin-bottom:12px;">Delete User</h3>
        <p>Permanently delete this user? This cannot be undone.</p>
        <form id="deleteForm" method="POST">
            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
            <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:20px;">
                <button type="button" class="btn btn-outline" onclick="closeDeleteModal()">Cancel</button>
                <button type="submit" class="btn btn-danger">Delete</button>
            </div>
        </form>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    function openDeleteModal(url) {
        document.getElementById('deleteForm').action = url;
        document.getElementById('deleteModal').style.display = 'flex';
    }
    function closeDeleteModal() {
        document.getElementById('deleteModal').style.display = 'none';
    }
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Programs\E-Commerce\library-system-final\resources\views/admin/users/edit.blade.php ENDPATH**/ ?>