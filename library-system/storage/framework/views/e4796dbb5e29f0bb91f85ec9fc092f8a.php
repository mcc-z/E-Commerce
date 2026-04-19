<?php $__env->startSection('title', $user->name); ?>
<?php $__env->startSection('page-title', 'Member Profile'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div style="display:flex;align-items:center;gap:16px;">
        <img src="<?php echo e($user->avatar_url); ?>" style="width:56px;height:56px;border-radius:50%;object-fit:cover;border:2px solid var(--gold);">
        <div>
            <h1 style="font-size:22px;"><?php echo e($user->name); ?></h1>
            <div style="display:flex;align-items:center;gap:10px;margin-top:4px;">
                <span style="font-family:monospace;font-size:13px;color:var(--text-muted);"><?php echo e($user->member_id); ?></span>
                <span class="badge <?php echo e($user->status_badge['class']); ?>"><?php echo e($user->status_badge['label']); ?></span>
            </div>
        </div>
    </div>
    <div style="display:flex;gap:8px;">
        <a href="<?php echo e(route('admin.users.edit', $user)); ?>" class="btn btn-outline btn-sm">
            <i class="fas fa-edit"></i> Edit
        </a>
        <form action="<?php echo e(route('admin.users.toggle-block', $user)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <button class="btn btn-sm <?php echo e($user->status === 'blocked' ? 'btn-success' : 'btn-warning'); ?>">
                <i class="fas <?php echo e($user->status === 'blocked' ? 'fa-unlock' : 'fa-ban'); ?>"></i>
                <?php echo e($user->status === 'blocked' ? 'Unblock' : 'Block'); ?> User
            </button>
        </form>
        <a href="<?php echo e(route('admin.users.index')); ?>" class="btn btn-outline btn-sm">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<div style="display:grid;grid-template-columns:280px 1fr;gap:24px;">

    
    <div>
        <div class="card" style="margin-bottom:16px;">
            <div class="card-body">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px;padding-bottom:16px;border-bottom:1px solid var(--cream-2);">
                    <div style="text-align:center;">
                        <div style="font-size:22px;font-weight:700;color:var(--navy);"><?php echo e($borrowStats['total']); ?></div>
                        <div style="font-size:11px;color:var(--text-muted);text-transform:uppercase;">Total</div>
                    </div>
                    <div style="text-align:center;">
                        <div style="font-size:22px;font-weight:700;color:<?php echo e($borrowStats['active'] > 0 ? 'var(--info)' : 'var(--text-muted)'); ?>;"><?php echo e($borrowStats['active']); ?></div>
                        <div style="font-size:11px;color:var(--text-muted);text-transform:uppercase;">Active</div>
                    </div>
                    <div style="text-align:center;">
                        <div style="font-size:22px;font-weight:700;color:<?php echo e($borrowStats['overdue'] > 0 ? 'var(--danger)' : 'var(--text-muted)'); ?>;"><?php echo e($borrowStats['overdue']); ?></div>
                        <div style="font-size:11px;color:var(--text-muted);text-transform:uppercase;">Overdue</div>
                    </div>
                    <div style="text-align:center;">
                        <div style="font-size:22px;font-weight:700;color:var(--success);"><?php echo e($borrowStats['returned']); ?></div>
                        <div style="font-size:11px;color:var(--text-muted);text-transform:uppercase;">Returned</div>
                    </div>
                </div>
                <?php $__currentLoopData = [
                    ['icon'=>'fa-envelope','label'=>'Email','value'=>$user->email],
                    ['icon'=>'fa-phone','label'=>'Phone','value'=>$user->phone ?? '—'],
                    ['icon'=>'fa-map-marker-alt','label'=>'Address','value'=>$user->address ?? '—'],
                    ['icon'=>'fa-calendar','label'=>'Joined','value'=>$user->created_at->format('M d, Y')],
                    ['icon'=>'fa-dollar-sign','label'=>'Outstanding Fines','value'=>'$'.number_format($user->outstanding_fines,2)],
                ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div style="display:flex;gap:10px;margin-bottom:10px;font-size:13px;">
                    <i class="fas <?php echo e($info['icon']); ?>" style="width:16px;color:var(--text-muted);margin-top:2px;"></i>
                    <div>
                        <div style="color:var(--text-muted);font-size:11px;"><?php echo e($info['label']); ?></div>
                        <div style="font-weight:500;"><?php echo e($info['value']); ?></div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        
        <?php if($user->canBorrow()): ?>
        <div class="card">
            <div class="card-header"><span class="card-title" style="font-size:15px;">Issue Book</span></div>
            <div class="card-body">
                <form action="<?php echo e(route('admin.users.issue-borrow', $user)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="form-group">
                        <label class="form-label">Book ID</label>
                        <input type="number" name="book_id" class="form-control" placeholder="Enter Book ID" required>
                        <div style="font-size:11px;color:var(--text-muted);margin-top:4px;">Find the Book ID from the Books list.</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Optional notes..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-gold" style="width:100%;justify-content:center;">
                        <i class="fas fa-hand-holding-heart"></i> Issue Book
                    </button>
                </form>
            </div>
        </div>
        <?php else: ?>
        <div class="card">
            <div class="card-body" style="text-align:center;color:var(--danger);">
                <i class="fas fa-ban" style="font-size:24px;margin-bottom:8px;"></i>
                <div style="font-size:13px;font-weight:600;">Cannot Issue Books</div>
                <div style="font-size:12px;color:var(--text-muted);margin-top:4px;">
                    <?php if($user->isBlocked()): ?> Account is blocked. <?php endif; ?>
                    <?php if($user->outstanding_fines > 10): ?> Outstanding fines exceed $10. <?php endif; ?>
                    <?php if($user->activeBorrows->count() >= 5): ?> Max borrow limit reached. <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    
    <div>
        
        <div class="card" style="margin-bottom:20px;">
            <div class="card-header"><span class="card-title">Active Borrows</span></div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr><th>Book</th><th>Borrowed</th><th>Due Date</th><th>Status</th><th>Action</th></tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $user->borrows->whereIn('status',['active','overdue']); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $borrow): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <div style="font-weight:600;font-size:14px;"><?php echo e($borrow->book->title); ?></div>
                                <div style="font-size:12px;color:var(--text-muted);"><?php echo e($borrow->borrow_code); ?></div>
                            </td>
                            <td style="font-size:13px;"><?php echo e($borrow->borrowed_at->format('M d, Y')); ?></td>
                            <td style="font-size:13px;color:<?php echo e($borrow->isOverdue() ? 'var(--danger)' : 'var(--text)'); ?>;font-weight:<?php echo e($borrow->isOverdue() ? '700' : '400'); ?>;">
                                <?php echo e($borrow->due_date->format('M d, Y')); ?>

                                <?php if($borrow->isOverdue()): ?>
                                    <div style="font-size:11px;"><?php echo e($borrow->overdue_days); ?>d overdue</div>
                                <?php endif; ?>
                            </td>
                            <td><span class="badge <?php echo e($borrow->status_badge['class']); ?>"><?php echo e($borrow->status_badge['label']); ?></span></td>
                            <td>
                                <form action="<?php echo e(route('admin.borrows.return', $borrow)); ?>" method="POST"
                                      onsubmit="return confirm('Mark this book as returned?')">
                                    <?php echo csrf_field(); ?>
                                    <button class="btn btn-success btn-xs"><i class="fas fa-undo"></i> Return</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="5"><div class="empty-state" style="padding:20px;">No active borrows.</div></td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        
        <div class="card">
            <div class="card-header"><span class="card-title">Fines</span></div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr><th>Book</th><th>Type</th><th>Amount</th><th>Remaining</th><th>Status</th><th>Action</th></tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $user->fines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fine): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td style="font-size:13px;"><?php echo e($fine->borrow->book->title ?? '—'); ?></td>
                            <td><span class="badge badge-secondary"><?php echo e(ucfirst($fine->type)); ?></span></td>
                            <td style="font-weight:700;">$<?php echo e(number_format($fine->amount,2)); ?></td>
                            <td style="font-weight:700;color:<?php echo e($fine->remaining > 0 ? 'var(--danger)' : 'var(--success)'); ?>">
                                $<?php echo e(number_format($fine->remaining,2)); ?>

                            </td>
                            <td><span class="badge <?php echo e($fine->status_badge['class']); ?>"><?php echo e($fine->status_badge['label']); ?></span></td>
                            <td>
                                <?php if($fine->remaining > 0): ?>
                                    <div style="display:flex;gap:4px;">
                                        <button class="btn btn-success btn-xs" onclick="openPayModal(<?php echo e($fine->id); ?>, <?php echo e($fine->remaining); ?>)">
                                            <i class="fas fa-dollar-sign"></i> Pay
                                        </button>
                                        <button class="btn btn-outline btn-xs" onclick="openWaiveModal(<?php echo e($fine->id); ?>)">
                                            Waive
                                        </button>
                                    </div>
                                <?php else: ?>
                                    <span style="font-size:11px;color:var(--text-muted);">Settled</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="6"><div class="empty-state" style="padding:20px;">No fines on record.</div></td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<div id="payModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center;">
    <div style="background:white;border-radius:16px;padding:32px;max-width:380px;width:90%;margin:20px;">
        <h3 style="font-family:'DM Serif Display',serif;font-size:20px;color:var(--navy);margin-bottom:20px;">Record Payment</h3>
        <form id="payForm" method="POST">
            <?php echo csrf_field(); ?>
            <div class="form-group">
                <label class="form-label">Amount ($) — max: <span id="maxAmount"></span></label>
                <input type="number" name="amount" id="payAmount" class="form-control" step="0.01" min="0.01" required>
            </div>
            <div class="form-group">
                <label class="form-label">Method</label>
                <select name="method" class="form-control" style="padding:10px 14px;">
                    <option value="cash">Cash</option>
                    <option value="card">Card</option>
                    <option value="online">Online</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Notes</label>
                <input type="text" name="notes" class="form-control" placeholder="Optional...">
            </div>
            <div style="display:flex;gap:10px;">
                <button type="submit" class="btn btn-success" style="flex:1;justify-content:center;">Record</button>
                <button type="button" class="btn btn-outline" onclick="closePayModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>


<div id="waiveModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center;">
    <div style="background:white;border-radius:16px;padding:32px;max-width:380px;width:90%;margin:20px;">
        <h3 style="font-family:'DM Serif Display',serif;font-size:20px;color:var(--navy);margin-bottom:20px;">Waive Fine</h3>
        <form id="waiveForm" method="POST">
            <?php echo csrf_field(); ?>
            <div class="form-group">
                <label class="form-label">Reason for Waiving *</label>
                <textarea name="reason" class="form-control" rows="3" required placeholder="Explain why this fine is being waived..."></textarea>
            </div>
            <div style="display:flex;gap:10px;">
                <button type="submit" class="btn btn-warning" style="flex:1;justify-content:center;">Waive Fine</button>
                <button type="button" class="btn btn-outline" onclick="closeWaiveModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
function openPayModal(fineId, remaining) {
    document.getElementById('payForm').action = `/admin/fines/${fineId}/record-payment`;
    document.getElementById('payAmount').value = remaining.toFixed(2);
    document.getElementById('payAmount').max = remaining;
    document.getElementById('maxAmount').textContent = '$' + remaining.toFixed(2);
    document.getElementById('payModal').style.display = 'flex';
}
function closePayModal() { document.getElementById('payModal').style.display = 'none'; }

function openWaiveModal(fineId) {
    document.getElementById('waiveForm').action = `/admin/fines/${fineId}/waive`;
    document.getElementById('waiveModal').style.display = 'flex';
}
function closeWaiveModal() { document.getElementById('waiveModal').style.display = 'none'; }
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Programs\E-Commerce\library-system\resources\views/admin/users/show.blade.php ENDPATH**/ ?>