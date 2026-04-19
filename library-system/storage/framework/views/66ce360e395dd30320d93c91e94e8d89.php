<?php $__env->startSection('title', 'Sign In'); ?>

<?php $__env->startSection('auth-content'); ?>
<h2>Welcome back</h2>
<p class="subtitle">Sign in to your library account</p>

<form action="<?php echo e(route('login.submit')); ?>" method="POST">
    <?php echo csrf_field(); ?>
    <div class="form-group">
        <label class="form-label">Email Address</label>
        <div class="input-wrap">
            <i class="fas fa-envelope"></i>
            <input type="email" name="email" class="form-control" placeholder="you@example.com"
                   value="<?php echo e(old('email')); ?>" required autofocus>
        </div>
    </div>

    <div class="form-group">
        <label class="form-label">Password</label>
        <div class="input-wrap">
            <i class="fas fa-lock"></i>
            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
        </div>
    </div>

    <div style="display:flex;align-items:center;gap:8px;margin-bottom:18px;">
        <input type="checkbox" name="remember" id="remember" style="accent-color:var(--navy)">
        <label for="remember" style="font-size:13px;color:var(--text-muted);cursor:pointer">Remember me</label>
    </div>

    <button type="submit" class="btn-block">
        <i class="fas fa-sign-in-alt"></i> Sign In
    </button>
</form>

<div class="auth-link">
    Don't have an account? <a href="<?php echo e(route('register')); ?>">Create one</a>
</div>

<div class="divider">Demo Accounts</div>
<div style="background:var(--cream);border-radius:9px;padding:12px 14px;font-size:13px;color:var(--text-muted);">
    <div style="margin-bottom:6px;"><strong style="color:var(--navy)">Admin:</strong> admin@library.com / password</div>
    <div><strong style="color:var(--navy)">Member:</strong> alice@example.com / password</div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Programs\library-system\resources\views/auth/login.blade.php ENDPATH**/ ?>