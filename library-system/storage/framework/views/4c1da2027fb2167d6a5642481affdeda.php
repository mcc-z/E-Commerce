<?php $__env->startSection('title', 'Create Account'); ?>

<?php $__env->startSection('auth-content'); ?>
<h2>Create account</h2>
<p class="subtitle">Join our library community today</p>

<form action="<?php echo e(route('register.submit')); ?>" method="POST">
    <?php echo csrf_field(); ?>
    <div class="form-group">
        <label class="form-label">Full Name</label>
        <div class="input-wrap">
            <i class="fas fa-user"></i>
            <input type="text" name="name" class="form-control" placeholder="John Doe"
                   value="<?php echo e(old('name')); ?>" required autofocus>
        </div>
    </div>

    <div class="form-group">
        <label class="form-label">Email Address</label>
        <div class="input-wrap">
            <i class="fas fa-envelope"></i>
            <input type="email" name="email" class="form-control" placeholder="you@example.com"
                   value="<?php echo e(old('email')); ?>" required>
        </div>
    </div>

    <div class="form-group">
        <label class="form-label">Phone Number <span style="color:var(--text-muted);font-weight:400">(optional)</span></label>
        <div class="input-wrap">
            <i class="fas fa-phone"></i>
            <input type="tel" name="phone" class="form-control" placeholder="+1 (555) 000-0000"
                   value="<?php echo e(old('phone')); ?>">
        </div>
    </div>

    <div class="form-group">
        <label class="form-label">Password</label>
        <div class="input-wrap">
            <i class="fas fa-lock"></i>
            <input type="password" name="password" class="form-control" placeholder="Min. 8 characters" required>
        </div>
    </div>

    <div class="form-group">
        <label class="form-label">Confirm Password</label>
        <div class="input-wrap">
            <i class="fas fa-lock"></i>
            <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat password" required>
        </div>
    </div>

    <button type="submit" class="btn-block">
        <i class="fas fa-user-plus"></i> Create Account
    </button>
</form>

<div class="auth-link">
    Already have an account? <a href="<?php echo e(route('login')); ?>">Sign in</a>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Programs\library-system\resources\views/auth/register.blade.php ENDPATH**/ ?>