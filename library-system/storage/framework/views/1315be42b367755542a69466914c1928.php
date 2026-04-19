<?php $__env->startSection('title', 'My Fines'); ?>
<?php $__env->startSection('page-title', 'Fines & Payments'); ?>

<?php $__env->startSection('content'); ?>

<?php if($totalUnpaid > 0): ?>
<div style="background:linear-gradient(135deg,#7f1d1d 0%,#dc2626 100%);border-radius:16px;padding:24px 28px;margin-bottom:24px;display:flex;align-items:center;justify-content:space-between;gap:20px;">
    <div>
        <div style="color:rgba(255,255,255,0.7);font-size:13px;margin-bottom:4px;">Total Outstanding</div>
        <div style="font-family:'DM Serif Display',serif;font-size:36px;color:white;line-height:1;">$<?php echo e(number_format($totalUnpaid, 2)); ?></div>
    </div>
    <div style="text-align:right;color:rgba(255,255,255,0.8);font-size:13px;">
        Please pay your fines to<br>continue borrowing books.
    </div>
</div>
<?php else: ?>
<div class="alert alert-success"><i class="fas fa-check-circle"></i> Your account is in good standing — no outstanding fines!</div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <span class="card-title">All Fines</span>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Book</th>
                    <th>Type</th>
                    <th>Days Overdue</th>
                    <th>Total Fine</th>
                    <th>Paid</th>
                    <th>Remaining</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $fines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fine): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td>
                        <div style="font-weight:600;font-size:14px;"><?php echo e($fine->borrow->book->title ?? 'N/A'); ?></div>
                        <div style="font-size:12px;color:var(--text-muted);"><?php echo e($fine->borrow->book->author ?? ''); ?></div>
                    </td>
                    <td><span class="badge badge-secondary"><?php echo e(ucfirst($fine->type)); ?></span></td>
                    <td style="font-size:13px;"><?php echo e($fine->overdue_days > 0 ? $fine->overdue_days . ' days' : '—'); ?></td>
                    <td style="font-weight:700;color:var(--danger);">$<?php echo e(number_format($fine->amount, 2)); ?></td>
                    <td style="color:var(--success);">$<?php echo e(number_format($fine->paid_amount, 2)); ?></td>
                    <td style="font-weight:700;">$<?php echo e(number_format($fine->remaining, 2)); ?></td>
                    <td><span class="badge <?php echo e($fine->status_badge['class']); ?>"><?php echo e($fine->status_badge['label']); ?></span></td>
                    <td>
                        <?php if($fine->remaining > 0 && in_array($fine->status, ['unpaid','partial'])): ?>
                            <button class="btn btn-primary btn-xs" onclick="openPayModal(<?php echo e($fine->id); ?>, <?php echo e($fine->remaining); ?>)">
                                <i class="fas fa-credit-card"></i> Pay
                            </button>
                        <?php else: ?>
                            <span style="font-size:11px;color:var(--text-muted);"><?php echo e(ucfirst($fine->status)); ?></span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="8">
                        <div class="empty-state">
                            <i class="fas fa-receipt"></i>
                            <h3>No fines on record</h3>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($fines->hasPages()): ?>
    <div style="padding:8px 16px 16px;"><?php echo e($fines->links('pagination::simple-default')); ?></div>
    <?php endif; ?>
</div>


<div id="payModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center;">
    <div style="background:white;border-radius:16px;padding:32px;width:100%;max-width:400px;margin:20px;">
        <h3 style="font-family:'DM Serif Display',serif;font-size:22px;color:var(--navy);margin-bottom:6px;">Pay Fine</h3>
        <p style="color:var(--text-muted);font-size:13px;margin-bottom:24px;">Remaining balance: <strong id="modalRemaining"></strong></p>
        <form id="payForm" method="POST">
            <?php echo csrf_field(); ?>
            <div class="form-group">
                <label class="form-label">Amount to Pay ($)</label>
                <input type="number" name="amount" id="payAmount" class="form-control no-icon" step="0.01" min="0.01" required>
            </div>
            <div class="form-group">
                <label class="form-label">Payment Method</label>
                <select name="method" class="form-control" style="padding:10px 14px;">
                    <option value="card">Credit / Debit Card</option>
                    <option value="online">Online Transfer</option>
                </select>
            </div>
            <div style="display:flex;gap:10px;margin-top:4px;">
                <button type="submit" class="btn btn-primary" style="flex:1;justify-content:center;">
                    <i class="fas fa-lock"></i> Pay Now
                </button>
                <button type="button" class="btn btn-outline" onclick="closePayModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
function openPayModal(fineId, remaining) {
    document.getElementById('payForm').action = `/user/fines/${fineId}/pay`;
    document.getElementById('payAmount').max = remaining;
    document.getElementById('payAmount').value = remaining.toFixed(2);
    document.getElementById('modalRemaining').textContent = '$' + remaining.toFixed(2);
    document.getElementById('payModal').style.display = 'flex';
}
function closePayModal() {
    document.getElementById('payModal').style.display = 'none';
}
document.getElementById('payModal').addEventListener('click', function(e) {
    if (e.target === this) closePayModal();
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Programs\E-Commerce\library-system\resources\views/user/fines/index.blade.php ENDPATH**/ ?>