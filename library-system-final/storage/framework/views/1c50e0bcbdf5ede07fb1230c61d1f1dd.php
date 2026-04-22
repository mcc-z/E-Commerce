<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'LibraryMS'); ?> — LibraryMS</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        :root {
            --navy:       #0f1e35;
            --navy-2:     #1a2f4a;
            --navy-3:     #243d5e;
            --gold:       #c9a84c;
            --gold-light: #e8c97a;
            --cream:      #f7f4ef;
            --cream-2:    #ede9e2;
            --text:       #1a1a2e;
            --text-muted: #6b7280;
            --white:      #ffffff;
            --success:    #059669;
            --danger:     #dc2626;
            --warning:    #d97706;
            --info:       #2563eb;
            --radius:     12px;
            --radius-lg:  18px;
            --shadow:     0 2px 16px rgba(15,30,53,0.10);
            --shadow-lg:  0 8px 40px rgba(15,30,53,0.16);
            --sidebar-w:  260px;
            --transition: 0.2s ease;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--cream);
            color: var(--text);
            display: flex;
            min-height: 100vh;
            font-size: 15px;
            line-height: 1.6;
        }

        /* ==================== SIDEBAR ==================== */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--navy);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            z-index: 100;
            transition: transform var(--transition);
        }

        .sidebar-logo {
            padding: 28px 24px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }

        .sidebar-logo a {
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo-icon {
            width: 40px; height: 40px;
            background: var(--gold);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            color: var(--navy);
            font-size: 18px;
        }

        .logo-text {
            font-family: 'DM Serif Display', serif;
            color: var(--white);
            font-size: 20px;
            line-height: 1.1;
        }

        .logo-text span {
            display: block;
            font-family: 'DM Sans', sans-serif;
            font-size: 11px;
            color: var(--gold-light);
            font-weight: 400;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .sidebar-user {
            padding: 16px 24px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sidebar-user img {
            width: 38px; height: 38px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--gold);
        }

        .sidebar-user-info .name {
            color: var(--white);
            font-weight: 600;
            font-size: 14px;
            line-height: 1.2;
        }

        .sidebar-user-info .role {
            color: var(--gold-light);
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .sidebar-nav {
            flex: 1;
            padding: 16px 12px;
            overflow-y: auto;
        }

        .nav-section-title {
            color: rgba(255,255,255,0.3);
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            padding: 8px 12px 4px;
            font-weight: 600;
        }

        .nav-item a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 12px;
            border-radius: 8px;
            color: rgba(255,255,255,0.65);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all var(--transition);
            margin-bottom: 2px;
        }

        .nav-item a:hover,
        .nav-item a.active {
            background: rgba(255,255,255,0.09);
            color: var(--white);
        }

        .nav-item a.active {
            background: rgba(201,168,76,0.15);
            color: var(--gold-light);
        }

        .nav-item a i {
            width: 18px;
            text-align: center;
            font-size: 15px;
            opacity: 0.8;
        }

        .nav-badge {
            margin-left: auto;
            background: var(--danger);
            color: white;
            font-size: 10px;
            font-weight: 700;
            padding: 2px 6px;
            border-radius: 20px;
            min-width: 18px;
            text-align: center;
        }

        .sidebar-footer {
            padding: 12px;
            border-top: 1px solid rgba(255,255,255,0.08);
        }

        .sidebar-footer form button {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 8px;
            background: transparent;
            border: none;
            color: rgba(255,255,255,0.5);
            font-size: 14px;
            font-family: inherit;
            cursor: pointer;
            transition: all var(--transition);
        }

        .sidebar-footer form button:hover {
            background: rgba(220,38,38,0.15);
            color: #fca5a5;
        }

        /* ==================== MAIN CONTENT ==================== */
        .main-content {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .topbar {
            background: var(--white);
            border-bottom: 1px solid var(--cream-2);
            padding: 0 32px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 50;
            box-shadow: 0 1px 8px rgba(0,0,0,0.05);
        }

        .topbar-title {
            font-family: 'DM Serif Display', serif;
            font-size: 20px;
            color: var(--navy);
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .topbar-btn {
            width: 36px; height: 36px;
            border-radius: 8px;
            border: 1px solid var(--cream-2);
            background: var(--cream);
            color: var(--text-muted);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
            transition: all var(--transition);
        }

        .topbar-btn:hover {
            border-color: var(--navy-3);
            color: var(--navy);
        }

        .page-body {
            padding: 32px;
            flex: 1;
        }

        /* ==================== ALERTS ==================== */
        .alert {
            padding: 12px 16px;
            border-radius: var(--radius);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            font-weight: 500;
        }

        .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
        .alert-error   { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
        .alert-warning { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
        .alert-info    { background: #dbeafe; color: #1e40af; border: 1px solid #bfdbfe; }

        /* ==================== CARDS ==================== */
        .card {
            background: var(--white);
            border-radius: var(--radius-lg);
            border: 1px solid var(--cream-2);
            box-shadow: var(--shadow);
        }

        .card-header {
            padding: 20px 24px 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-title {
            font-family: 'DM Serif Display', serif;
            font-size: 17px;
            color: var(--navy);
        }

        .card-body {
            padding: 20px 24px 24px;
        }

        /* ==================== STAT CARDS ==================== */
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 28px;
        }

        .stat-card {
            background: var(--white);
            border-radius: var(--radius-lg);
            padding: 20px;
            border: 1px solid var(--cream-2);
            box-shadow: var(--shadow);
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .stat-icon {
            width: 48px; height: 48px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        .stat-icon.blue   { background: #dbeafe; color: #1d4ed8; }
        .stat-icon.green  { background: #d1fae5; color: #065f46; }
        .stat-icon.gold   { background: #fef3c7; color: #92400e; }
        .stat-icon.red    { background: #fee2e2; color: #991b1b; }
        .stat-icon.purple { background: #ede9fe; color: #5b21b6; }
        .stat-icon.teal   { background: #ccfbf1; color: #0f766e; }

        .stat-value {
            font-size: 26px;
            font-weight: 700;
            color: var(--navy);
            line-height: 1;
        }

        .stat-label {
            font-size: 12px;
            color: var(--text-muted);
            margin-top: 4px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        /* ==================== BUTTONS ==================== */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 9px 18px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            border: none;
            text-decoration: none;
            transition: all var(--transition);
            white-space: nowrap;
        }

        .btn-sm { padding: 6px 12px; font-size: 13px; }
        .btn-xs { padding: 4px 8px; font-size: 12px; border-radius: 6px; }

        .btn-primary   { background: var(--navy);   color: var(--white); }
        .btn-primary:hover { background: var(--navy-2); }
        .btn-gold      { background: var(--gold);   color: var(--navy); }
        .btn-gold:hover { background: var(--gold-light); }
        .btn-success   { background: var(--success); color: var(--white); }
        .btn-danger    { background: var(--danger);  color: var(--white); }
        .btn-warning   { background: var(--warning); color: var(--white); }
        .btn-outline   { background: transparent; border: 1.5px solid var(--cream-2); color: var(--text-muted); }
        .btn-outline:hover { border-color: var(--navy); color: var(--navy); }

        /* ==================== TABLE ==================== */
        .table-wrap { overflow-x: auto; }

        table { width: 100%; border-collapse: collapse; }

        th {
            text-align: left;
            padding: 10px 14px;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            color: var(--text-muted);
            font-weight: 600;
            background: var(--cream);
            border-bottom: 1px solid var(--cream-2);
        }

        td {
            padding: 13px 14px;
            font-size: 14px;
            border-bottom: 1px solid var(--cream-2);
            vertical-align: middle;
        }

        tr:last-child td { border-bottom: none; }
        tr:hover td { background: var(--cream); }

        /* ==================== BADGES ==================== */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 3px 9px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .badge-success   { background: #d1fae5; color: #065f46; }
        .badge-danger    { background: #fee2e2; color: #991b1b; }
        .badge-warning   { background: #fef3c7; color: #92400e; }
        .badge-info      { background: #dbeafe; color: #1e40af; }
        .badge-secondary { background: #f3f4f6; color: #6b7280; }
        .badge-purple    { background: #ede9fe; color: #5b21b6; }

        /* ==================== FORMS ==================== */
        .form-group { margin-bottom: 18px; }
        .form-label { display: block; font-size: 13px; font-weight: 600; color: var(--navy); margin-bottom: 6px; }

        .form-control {
            width: 100%;
            padding: 10px 14px;
            border: 1.5px solid var(--cream-2);
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
            color: var(--text);
            background: var(--white);
            transition: border-color var(--transition);
            outline: none;
        }

        .form-control:focus {
            border-color: var(--navy);
            box-shadow: 0 0 0 3px rgba(15,30,53,0.08);
        }

        textarea.form-control { resize: vertical; min-height: 90px; }

        .form-error { font-size: 12px; color: var(--danger); margin-top: 4px; }

        .form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .form-grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; }

        /* ==================== PAGINATION ==================== */
        .pagination {
            display: flex;
            justify-content: center;
            gap: 4px;
            padding: 20px 0 4px;
            list-style: none;
        }

        .pagination li {
            display: inline-block;
        }

        .pagination a, 
        .pagination span {
            display: inline-block;
            padding: 7px 13px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            color: var(--text-muted);
            border: 1px solid var(--cream-2);
            background: var(--white);
            transition: all var(--transition);
        }

        .pagination a:hover { 
            border-color: var(--navy); 
            color: var(--navy); 
        }

        .pagination .disabled span {
            opacity: 0.5;
            cursor: not-allowed;
            background: var(--cream);
        }

        /* ==================== EMPTY STATE ==================== */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--text-muted);
        }

        .empty-state i {
            font-size: 48px;
            margin-bottom: 16px;
            opacity: 0.3;
        }

        .empty-state h3 {
            font-size: 17px;
            color: var(--navy);
            margin-bottom: 6px;
        }

        /* ==================== SEARCH BAR ==================== */
        .search-bar {
            display: flex;
            gap: 8px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .search-input-wrap {
            position: relative;
            flex: 1;
            min-width: 200px;
        }

        .search-input-wrap i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 14px;
        }

        .search-input-wrap input {
            width: 100%;
            padding: 10px 14px 10px 36px;
            border: 1.5px solid var(--cream-2);
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
            outline: none;
            transition: border-color var(--transition);
        }

        .search-input-wrap input:focus {
            border-color: var(--navy);
        }

        /* ==================== PAGE HEADER ==================== */
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
            gap: 16px;
            flex-wrap: wrap;
        }

        .page-header h1 {
            font-family: 'DM Serif Display', serif;
            font-size: 26px;
            color: var(--navy);
        }

        .page-header p {
            font-size: 14px;
            color: var(--text-muted);
            margin-top: 2px;
        }

        /* Responsive */
        @media (max-width: 900px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main-content { margin-left: 0; }
            .form-grid-2, .form-grid-3 { grid-template-columns: 1fr; }
        }

        .modal-overlay {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.5);
            display: none; /* Hidden by default */
            justify-content: center; align-items: center;
            z-index: 1000;
        }
        .modal-content {
            background: white;
            padding: 20px;
            border-radius: 8px;
            width: 400px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
    </style>

    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>


<aside class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <a href="<?php echo e(auth()->user()->isAdmin() ? route('admin.dashboard') : route('user.dashboard')); ?>">
            <div class="logo-icon"><i class="fas fa-book-open"></i></div>
            <div class="logo-text">
                LibraryMS
                <span>Management System</span>
            </div>
        </a>
    </div>

    <div class="sidebar-user">
        <img src="<?php echo e(auth()->user()->avatar_url); ?>" alt="<?php echo e(auth()->user()->name); ?>">
        <div class="sidebar-user-info">
            <div class="name"><?php echo e(auth()->user()->name); ?></div>
            <div class="role"><?php echo e(auth()->user()->isAdmin() ? 'Administrator' : 'Member · ' . auth()->user()->member_id); ?></div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <?php if(auth()->user()->isAdmin()): ?>
            
            <div class="nav-section-title">Overview</div>
            <div class="nav-item">
                <a href="<?php echo e(route('admin.dashboard')); ?>" class="<?php echo e(request()->routeIs('admin.dashboard') ? 'active' : ''); ?>">
                    <i class="fas fa-chart-line"></i> Dashboard
                </a>
            </div>

            <div class="nav-section-title">Library</div>
            <div class="nav-item">
                <a href="<?php echo e(route('admin.books.index')); ?>" class="<?php echo e(request()->routeIs('admin.books.*') ? 'active' : ''); ?>">
                    <i class="fas fa-books"></i> Books
                </a>
            </div>
            <div class="nav-item">
                <a href="<?php echo e(route('admin.categories.index')); ?>" class="<?php echo e(request()->routeIs('admin.categories.*') ? 'active' : ''); ?>">
                    <i class="fas fa-tags"></i> Categories
                </a>
            </div>

            <div class="nav-section-title">Circulation</div>
            <div class="nav-item">
                <a href="<?php echo e(route('admin.borrows.index')); ?>" class="<?php echo e(request()->routeIs('admin.borrows.*') ? 'active' : ''); ?>">
                    <i class="fas fa-hand-holding-heart"></i> Borrows
                    <?php $overdue = \App\Models\Borrow::where('status','overdue')->count() ?>
                    <?php if($overdue > 0): ?>
                        <span class="nav-badge"><?php echo e($overdue); ?></span>
                    <?php endif; ?>
                </a>
            </div>

            <div class="nav-section-title">Members</div>
            <div class="nav-item">
                <a href="<?php echo e(route('admin.users.index')); ?>" class="<?php echo e(request()->routeIs('admin.users.*') ? 'active' : ''); ?>">
                    <i class="fas fa-users"></i> Members
                </a>
            </div>

            <div class="nav-section-title">Finance</div>
            <div class="nav-item">
                <a href="<?php echo e(route('admin.payments.index')); ?>" class="<?php echo e(request()->routeIs('admin.payments.*') ? 'active' : ''); ?>">
                    <i class="fas fa-money-bill-wave"></i> Payments & Fines
                </a>
            </div>

            <div class="nav-section-title">Account</div>
            <div class="nav-item">
                <a href="<?php echo e(route('admin.profile')); ?>" class="<?php echo e(request()->routeIs('admin.profile*') ? 'active' : ''); ?>">
                    <i class="fas fa-user-circle"></i> My Profile
                </a>
            </div>
        <?php else: ?>
            
            <div class="nav-section-title">My Library</div>
            <div class="nav-item">
                <a href="<?php echo e(route('user.dashboard')); ?>" class="<?php echo e(request()->routeIs('user.dashboard') ? 'active' : ''); ?>">
                    <i class="fas fa-gauge-high"></i> Dashboard
                </a>
            </div>
            <div class="nav-item">
                <a href="<?php echo e(route('user.books.index')); ?>" class="<?php echo e(request()->routeIs('user.books.*') ? 'active' : ''); ?>">
                    <i class="fas fa-search"></i> Browse Books
                </a>
            </div>

            <div class="nav-section-title">My Activity</div>
            <div class="nav-item">
                <a href="<?php echo e(route('user.borrows.index')); ?>" class="<?php echo e(request()->routeIs('user.borrows.*') ? 'active' : ''); ?>">
                    <i class="fas fa-book-reader"></i> My Borrows
                    <?php $active = auth()->user()->activeBorrows()->count() ?>
                    <?php if($active > 0): ?>
                        <span class="nav-badge" style="background:var(--gold);color:var(--navy);"><?php echo e($active); ?></span>
                    <?php endif; ?>
                </a>
            </div>
            <div class="nav-item">
                <a href="<?php echo e(route('user.fines.index')); ?>" class="<?php echo e(request()->routeIs('user.fines.*') ? 'active' : ''); ?>">
                    <i class="fas fa-receipt"></i> Fines & Payments
                    <?php if(auth()->user()->outstanding_fines > 0): ?>
                        <span class="nav-badge">$<?php echo e(number_format(auth()->user()->outstanding_fines, 2)); ?></span>
                    <?php endif; ?>
                </a>
            </div>

            <div class="nav-section-title">Account</div>
            <div class="nav-item">
                <a href="<?php echo e(route('user.profile')); ?>" class="<?php echo e(request()->routeIs('user.profile*') ? 'active' : ''); ?>">
                    <i class="fas fa-user-circle"></i> My Profile
                </a>
            </div>
        <?php endif; ?>
    </nav>

    <div class="sidebar-footer">
        <form action="<?php echo e(route('logout')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <button type="submit"><i class="fas fa-sign-out-alt"></i> Sign Out</button>
        </form>
    </div>
</aside>


<main class="main-content">
    <header class="topbar">
        <div class="topbar-title"><?php echo $__env->yieldContent('page-title', 'Dashboard'); ?></div>
        <div class="topbar-actions">
            <?php if(!auth()->user()->isAdmin()): ?>
                <a href="<?php echo e(route('user.books.index')); ?>" class="topbar-btn" title="Browse Books">
                    <i class="fas fa-search"></i>
                </a>
            <?php endif; ?>
            <a href="<?php echo e(auth()->user()->isAdmin() ? route('admin.profile') : route('user.profile')); ?>" class="topbar-btn">
                <i class="fas fa-user"></i>
            </a>
        </div>
    </header>

    <div class="page-body">
        
        <?php if(session('success')): ?>
            <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?php echo e(session('success')); ?></div>
        <?php endif; ?>
        <?php if(session('error')): ?>
            <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?php echo e(session('error')); ?></div>
        <?php endif; ?>
        <?php if($errors->any()): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-triangle"></i>
                <div>
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div><?php echo e($error); ?></div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        <?php endif; ?>

        <?php echo $__env->yieldContent('content'); ?>
    </div>
</main>

<script>
    // Auto-dismiss alerts
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(el => {
            el.style.transition = 'opacity 0.5s';
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 500);
        });
    }, 4000);
</script>
<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH D:\Programs\E-Commerce\library-system-final\resources\views/layouts/app.blade.php ENDPATH**/ ?>