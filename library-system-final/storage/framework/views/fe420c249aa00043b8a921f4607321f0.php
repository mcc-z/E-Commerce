<?php $__env->startSection('title', 'Edit Book'); ?>
<?php $__env->startSection('page-title', 'Edit Book'); ?>

<?php $__env->startSection('content'); ?>
<div style="max-width:780px;">
    <div class="card">
        <div class="card-header">
            <span class="card-title">Edit: <?php echo e($book->title); ?></span>
            <a href="<?php echo e(route('admin.books.index')); ?>" class="btn btn-outline btn-sm">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
        <div class="card-body">
            <form action="<?php echo e(route('admin.books.update', $book)); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>

                <div style="font-size:12px;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-muted);font-weight:600;margin-bottom:14px;">Basic Info</div>
                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Title *</label>
                        <input type="text" name="title" class="form-control" value="<?php echo e(old('title', $book->title)); ?>" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Author *</label>
                        <input type="text" name="author" class="form-control" value="<?php echo e(old('author', $book->author)); ?>" required>
                    </div>
                </div>
                <div class="form-grid-3">
                    <div class="form-group">
                        <label class="form-label">ISBN *</label>
                        <input type="text" name="isbn" class="form-control" value="<?php echo e(old('isbn', $book->isbn)); ?>" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Publisher</label>
                        <input type="text" name="publisher" class="form-control" value="<?php echo e(old('publisher', $book->publisher)); ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Published Year</label>
                        <input type="number" name="published_year" class="form-control" value="<?php echo e(old('published_year', $book->published_year)); ?>" min="1000" max="<?php echo e(date('Y')); ?>">
                    </div>
                </div>
                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Category *</label>
                        <select name="category_id" class="form-control" style="padding:10px 14px;" required>
                            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($cat->id); ?>" <?php echo e(old('category_id', $book->category_id) == $cat->id ? 'selected' : ''); ?>>
                                <?php echo e($cat->name); ?>

                            </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Language</label>
                        <input type="text" name="language" class="form-control" value="<?php echo e(old('language', $book->language)); ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3"><?php echo e(old('description', $book->description)); ?></textarea>
                </div>

                <div style="border-top:1px solid var(--cream-2);padding-top:20px;margin:20px 0 16px;font-size:12px;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-muted);font-weight:600;">Inventory & Location</div>

                <div style="background:var(--cream);border-radius:10px;padding:12px 16px;margin-bottom:16px;font-size:13px;color:var(--text-muted);">
                    <i class="fas fa-info-circle"></i>
                    Currently: <strong style="color:var(--navy);"><?php echo e($book->available_copies); ?></strong> available,
                    <strong style="color:var(--navy);"><?php echo e($book->total_copies - $book->available_copies); ?></strong> borrowed.
                    Minimum total copies: <?php echo e($book->total_copies - $book->available_copies); ?>

                </div>

                <div class="form-grid-3">
                    <div class="form-group">
                        <label class="form-label">Total Copies *</label>
                        <input type="number" name="total_copies" class="form-control"
                               value="<?php echo e(old('total_copies', $book->total_copies)); ?>"
                               min="<?php echo e($book->total_copies - $book->available_copies); ?>" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Shelf Location</label>
                        <input type="text" name="location" class="form-control" value="<?php echo e(old('location', $book->location)); ?>" placeholder="e.g. A-12">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Pages</label>
                        <input type="number" name="pages" class="form-control" value="<?php echo e(old('pages', $book->pages)); ?>" min="1">
                    </div>
                </div>
                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Replacement Cost ($)</label>
                        <input type="number" name="replacement_cost" class="form-control" value="<?php echo e(old('replacement_cost', $book->replacement_cost)); ?>" step="0.01" min="0">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status *</label>
                        <select name="status" class="form-control" style="padding:10px 14px;" required>
                            <option value="available"   <?php echo e(old('status', $book->status) == 'available'   ? 'selected' : ''); ?>>Available</option>
                            <option value="unavailable" <?php echo e(old('status', $book->status) == 'unavailable' ? 'selected' : ''); ?>>Unavailable</option>
                            <option value="lost"        <?php echo e(old('status', $book->status) == 'lost'        ? 'selected' : ''); ?>>Lost</option>
                        </select>
                    </div>
                </div>

                <div style="border-top:1px solid var(--cream-2);padding-top:20px;margin:20px 0 16px;font-size:12px;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-muted);font-weight:600;">Cover Image</div>
                <?php if($book->cover_image): ?>
                <div style="margin-bottom:10px;">
                    <img src="<?php echo e($book->cover_url); ?>" style="width:70px;height:100px;object-fit:cover;border-radius:6px;border:1px solid var(--cream-2);">
                    <div style="font-size:12px;color:var(--text-muted);margin-top:4px;">Current cover — upload a new one to replace it</div>
                </div>
                <?php endif; ?>
                <label class="btn btn-outline" style="cursor:pointer;display:inline-flex;align-items:center;gap:8px;">
                    <i class="fas fa-image"></i> <?php echo e($book->cover_image ? 'Replace Cover' : 'Upload Cover'); ?>

                    <input type="file" name="cover_image" accept="image/*" style="display:none;" onchange="previewCover(this)">
                </label>
                <img id="coverPreview" src="" style="display:none;margin-top:10px;width:80px;height:110px;object-fit:cover;border-radius:6px;border:1px solid var(--cream-2);">

                <div style="display:flex;gap:12px;margin-top:24px;">
                    <button type="submit" class="btn btn-gold"><i class="fas fa-save"></i> Save Changes</button>
                    <a href="<?php echo e(route('admin.books.index')); ?>" class="btn btn-outline">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
function previewCover(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            const img = document.getElementById('coverPreview');
            img.src = e.target.result;
            img.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Programs\E-Commerce\library-system\resources\views/admin/books/edit.blade.php ENDPATH**/ ?>