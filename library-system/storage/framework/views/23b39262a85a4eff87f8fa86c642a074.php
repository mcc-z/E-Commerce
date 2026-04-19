<?php $__env->startSection('title', 'My Profile'); ?>
<?php $__env->startSection('page-title', 'My Profile'); ?>

<?php $__env->startSection('content'); ?>
<?php ($profileRoutePrefix = auth()->user()->isAdmin() ? 'admin' : 'user'); ?>
<div style="display:grid;grid-template-columns:300px 1fr;gap:24px;">

    
    <div>
        <div class="card" style="text-align:center;padding:28px 20px;margin-bottom:16px;">
            <img src="<?php echo e($user->avatar_url); ?>" alt="<?php echo e($user->name); ?>"
                 style="width:90px;height:90px;border-radius:50%;object-fit:cover;border:3px solid var(--gold);margin:0 auto 14px;">
            <h2 style="font-family:'DM Serif Display',serif;font-size:20px;color:var(--navy);margin-bottom:4px;"><?php echo e($user->name); ?></h2>
            <div style="color:var(--text-muted);font-size:13px;margin-bottom:10px;"><?php echo e($user->email); ?></div>
            <span class="badge badge-info" style="font-size:12px;">
                <?php echo e($user->isAdmin() ? 'Administrator' : $user->member_id); ?>

            </span>

            <div style="margin-top:16px;padding-top:16px;border-top:1px solid var(--cream-2);display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                <div style="text-align:center;">
                    <div style="font-size:22px;font-weight:700;color:var(--navy);">
                        <?php echo e($user->isAdmin() ? ($adminStats['members'] ?? 0) : $user->total_borrowed); ?>

                    </div>
                    <div style="font-size:11px;color:var(--text-muted);text-transform:uppercase;">
                        <?php echo e($user->isAdmin() ? 'Members' : 'Borrowed'); ?>

                    </div>
                </div>
                <div style="text-align:center;">
                    <div style="font-size:22px;font-weight:700;color:<?php echo e($user->isAdmin() ? 'var(--info)' : ($user->outstanding_fines > 0 ? 'var(--danger)' : 'var(--success)')); ?>;">
                        <?php echo e($user->isAdmin() ? ($adminStats['active_borrows'] ?? 0) : '$' . number_format($user->outstanding_fines, 2)); ?>

                    </div>
                    <div style="font-size:11px;color:var(--text-muted);text-transform:uppercase;">
                        <?php echo e($user->isAdmin() ? 'Active Borrows' : 'Fines'); ?>

                    </div>
                </div>
            </div>

            <a href="<?php echo e(route($profileRoutePrefix . '.profile.edit')); ?>" class="btn btn-primary" style="margin-top:16px;width:100%;justify-content:center;">
                <i class="fas fa-edit"></i> Edit Profile
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <div style="font-size:13px;font-weight:600;color:var(--navy);margin-bottom:12px;">Account Info</div>
                <?php $__currentLoopData = [
                    ['icon'=>'fa-phone','label'=>'Phone','value'=>$user->phone ?? 'Not set'],
                    ['icon'=>'fa-map-marker-alt','label'=>'Address','value'=>$user->address ?? 'Not set'],
                    ['icon'=>'fa-calendar','label'=>$user->isAdmin() ? 'Admin Since' : 'Member Since','value'=>$user->created_at->format('M Y')],
                    ['icon'=>'fa-shield-alt','label'=>'Status','value'=>$user->status_badge['label']],
                ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div style="display:flex;gap:10px;margin-bottom:12px;font-size:13px;">
                    <i class="fas <?php echo e($info['icon']); ?>" style="width:16px;color:var(--text-muted);margin-top:2px;"></i>
                    <div>
                        <div style="color:var(--text-muted);font-size:11px;"><?php echo e($info['label']); ?></div>
                        <div style="font-weight:500;"><?php echo e($info['value']); ?></div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <?php if($user->isAdmin()): ?>
        <div class="card">
            <div class="card-body">
                <div style="font-size:13px;font-weight:600;color:var(--navy);margin-bottom:12px;">Quick Access</div>
                <div style="display:grid;gap:10px;">
                    <a href="<?php echo e(route('admin.books.index')); ?>" class="btn btn-outline" style="justify-content:flex-start;">
                        <i class="fas fa-books"></i> Manage Books
                    </a>
                    <a href="<?php echo e(route('admin.users.index')); ?>" class="btn btn-outline" style="justify-content:flex-start;">
                        <i class="fas fa-users"></i> Manage Members
                    </a>
                    <a href="<?php echo e(route('admin.borrows.index')); ?>" class="btn btn-outline" style="justify-content:flex-start;">
                        <i class="fas fa-hand-holding-heart"></i> Review Borrows
                    </a>
                    <a href="<?php echo e(route('admin.payments.index')); ?>" class="btn btn-outline" style="justify-content:flex-start;">
                        <i class="fas fa-money-bill-wave"></i> Payments
                    </a>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <div>
        <?php if($user->isAdmin()): ?>
        <div class="stat-grid">
            <div class="stat-card">
                <div class="stat-icon blue"><i class="fas fa-books"></i></div>
                <div>
                    <div class="stat-value"><?php echo e($adminStats['total_books']); ?></div>
                    <div class="stat-label">Books In Catalog</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon gold"><i class="fas fa-users"></i></div>
                <div>
                    <div class="stat-value"><?php echo e($adminStats['members']); ?></div>
                    <div class="stat-label">Registered Members</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon purple"><i class="fas fa-book-reader"></i></div>
                <div>
                    <div class="stat-value"><?php echo e($adminStats['active_borrows']); ?></div>
                    <div class="stat-label">Active Borrows</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon teal"><i class="fas fa-clock"></i></div>
                <div>
                    <div class="stat-value"><?php echo e($adminStats['reservations']); ?></div>
                    <div class="stat-label">Open Reservations</div>
                </div>
            </div>
        </div>

        <div style="display:grid;grid-template-columns:1.2fr 0.8fr;gap:24px;">
            <div class="card">
                <div class="card-header">
                    <span class="card-title">Recent Activity</span>
                </div>
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>User</th>
                                <th>When</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $recentActivity; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td>
                                    <div style="font-weight:600;font-size:14px;"><?php echo e(ucwords(str_replace('_', ' ', $log->action))); ?></div>
                                    <div style="font-size:12px;color:var(--text-muted);"><?php echo e($log->description ?: 'No description'); ?></div>
                                </td>
                                <td style="font-size:13px;"><?php echo e($log->user?->name ?? 'System'); ?></td>
                                <td style="font-size:13px;"><?php echo e($log->created_at->diffForHumans()); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="3"><div class="empty-state" style="padding:30px;">No recent activity yet.</div></td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div style="display:grid;gap:24px;">
                <div class="card">
                    <div class="card-header">
                        <span class="card-title">Newest Members</span>
                    </div>
                    <div class="card-body">
                        <?php $__empty_1 = true; $__currentLoopData = $recentMembers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 0;border-bottom:1px solid var(--cream-2);gap:12px;">
                                <div>
                                    <div style="font-weight:600;color:var(--navy);"><?php echo e($member->name); ?></div>
                                    <div style="font-size:12px;color:var(--text-muted);"><?php echo e($member->email); ?></div>
                                </div>
                                <span class="badge <?php echo e($member->status_badge['class']); ?>"><?php echo e($member->status_badge['label']); ?></span>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="empty-state" style="padding:20px;">No members found.</div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <span class="card-title">Latest Books</span>
                    </div>
                    <div class="card-body">
                        <?php $__empty_1 = true; $__currentLoopData = $recentBooks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 0;border-bottom:1px solid var(--cream-2);gap:12px;">
                                <div>
                                    <div style="font-weight:600;color:var(--navy);"><?php echo e($book->title); ?></div>
                                    <div style="font-size:12px;color:var(--text-muted);"><?php echo e($book->category?->name ?? 'Uncategorized'); ?></div>
                                </div>
                                <span class="badge <?php echo e($book->availability_badge['class']); ?>"><?php echo e($book->availability_badge['label']); ?></span>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="empty-state" style="padding:20px;">No books found.</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php else: ?>
        <div class="card">
            <div class="card-header">
                <span class="card-title">Borrow History</span>
                <a href="<?php echo e(route('user.borrows.index')); ?>" class="btn btn-outline btn-sm">View All</a>
            </div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Book</th>
                            <th>Borrowed</th>
                            <th>Due Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $borrowHistory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $borrow): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <div style="font-weight:600;font-size:14px;"><?php echo e($borrow->book->title); ?></div>
                                <div style="font-size:12px;color:var(--text-muted);"><?php echo e($borrow->book->author); ?></div>
                            </td>
                            <td style="font-size:13px;"><?php echo e($borrow->borrowed_at->format('M d, Y')); ?></td>
                            <td style="font-size:13px;"><?php echo e($borrow->due_date->format('M d, Y')); ?></td>
                            <td><span class="badge <?php echo e($borrow->status_badge['class']); ?>"><?php echo e($borrow->status_badge['label']); ?></span></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="4"><div class="empty-state" style="padding:30px;">No borrow history yet.</div></td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php if($borrowHistory->hasPages()): ?>
            <div style="padding:8px 16px 16px;"><?php echo e($borrowHistory->links()); ?></div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\library-system\library-system\resources\views/user/profile.blade.php ENDPATH**/ ?>