<?php $__env->startSection('title', 'Categories'); ?>
<?php $__env->startSection('page-title', 'Book Categories'); ?>

<?php $__env->startSection('content'); ?>
<div style="display:grid;grid-template-columns:380px 1fr;gap:24px;align-items:start;">

    
    <div class="card">
        <div class="card-header"><span class="card-title">Add Category</span></div>
        <div class="card-body">
            <form action="<?php echo e(route('admin.categories.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="form-group">
                    <label class="form-label">Category Name *</label>
                    <input type="text" name="name" class="form-control" value="<?php echo e(old('name')); ?>" required placeholder="e.g. Science Fiction">
                </div>
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="2" placeholder="Short description..."><?php echo e(old('description')); ?></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Color</label>
                    <div style="display:flex;gap:10px;align-items:center;">
                        <input type="color" name="color" value="<?php echo e(old('color', '#3B82F6')); ?>"
                               style="width:48px;height:38px;border:1.5px solid var(--cream-2);border-radius:8px;cursor:pointer;background:none;padding:2px;">
                        <span style="font-size:13px;color:var(--text-muted);">Used for category badge color</span>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Add Category</button>
            </form>
        </div>
    </div>

    
    <div class="card">
        <div class="card-header">
            <span class="card-title">All Categories</span>
            <span style="font-size:13px;color:var(--text-muted);"><?php echo e($categories->count()); ?> categories</span>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>Books</th>
                        <th>Color</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <div style="font-weight:600;"><?php echo e($cat->name); ?></div>
                            <?php if($cat->description): ?>
                            <div style="font-size:12px;color:var(--text-muted);"><?php echo e($cat->description); ?></div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge badge-secondary"><?php echo e($cat->books_count); ?> books</span>
                        </td>
                        <td>
                            <div style="display:flex;align-items:center;gap:8px;">
                                <div style="width:20px;height:20px;border-radius:50%;background:<?php echo e($cat->color); ?>;border:1px solid rgba(0,0,0,0.1);"></div>
                                <span style="font-family:monospace;font-size:12px;color:var(--text-muted);"><?php echo e($cat->color); ?></span>
                            </div>
                        </td>
                        <td>
                            <div style="display:flex;gap:4px;">
                                <button class="btn btn-outline btn-xs" onclick="openEditModal(<?php echo e($cat->id); ?>, '<?php echo e(addslashes($cat->name)); ?>', '<?php echo e(addslashes($cat->description ?? '')); ?>', '<?php echo e($cat->color); ?>')">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <?php if($cat->books_count == 0): ?>
                                <form action="<?php echo e(route('admin.categories.destroy', $cat)); ?>" method="POST" onsubmit="return confirm('Delete this category?')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button class="btn btn-danger btn-xs"><i class="fas fa-trash"></i></button>
                                </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="4"><div class="empty-state">No categories found.</div></td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<div id="editModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center;">
    <div style="background:white;border-radius:16px;padding:32px;max-width:420px;width:90%;margin:20px;">
        <h3 style="font-family:'DM Serif Display',serif;font-size:22px;color:var(--navy);margin-bottom:20px;">Edit Category</h3>
        <form id="editForm" method="POST">
            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
            <div class="form-group">
                <label class="form-label">Name *</label>
                <input type="text" name="name" id="editName" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea name="description" id="editDesc" class="form-control" rows="2"></textarea>
            </div>
            <div class="form-group">
                <label class="form-label">Color</label>
                <input type="color" name="color" id="editColor"
                       style="width:48px;height:38px;border:1.5px solid var(--cream-2);border-radius:8px;cursor:pointer;background:none;padding:2px;">
            </div>
            <div style="display:flex;gap:10px;">
                <button type="submit" class="btn btn-primary" style="flex:1;justify-content:center;">Save</button>
                <button type="button" class="btn btn-outline" onclick="closeEditModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
function openEditModal(id, name, desc, color) {
    document.getElementById('editForm').action = `/admin/categories/${id}`;
    document.getElementById('editName').value = name;
    document.getElementById('editDesc').value = desc;
    document.getElementById('editColor').value = color;
    document.getElementById('editModal').style.display = 'flex';
}
function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}
document.getElementById('editModal').addEventListener('click', e => {
    if (e.target === document.getElementById('editModal')) closeEditModal();
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\library-system\library-system\resources\views/admin/books/categories.blade.php ENDPATH**/ ?>