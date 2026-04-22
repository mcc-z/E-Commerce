<?php $__env->startSection('title', $book->title); ?>
<?php $__env->startSection('page-title', 'Book Details'); ?>

<?php $__env->startSection('content'); ?>
<div style="display:grid;grid-template-columns:280px 1fr;gap:28px;">

    
    <div>
        <div class="card" style="overflow:hidden;margin-bottom:16px;">
            <div style="height:280px;background:linear-gradient(160deg,var(--navy) 0%,var(--navy-3) 100%);display:flex;align-items:center;justify-content:center;">
                <img src="<?php echo e($book->cover_url); ?>" alt="<?php echo e($book->title); ?>"
                     style="width:100%;height:100%;object-fit:cover;">
            </div>
            <div class="card-body">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
                    <span class="badge <?php echo e($book->availability_badge['class']); ?>" style="font-size:12px;">
                        <?php echo e($book->availability_badge['label']); ?>

                    </span>
                    <span style="font-size:13px;color:var(--text-muted);">
                        <?php echo e($book->available_copies); ?>/<?php echo e($book->total_copies); ?> copies
                    </span>
                </div>

                <?php if($isBorrowed): ?>
                    <div class="btn btn-outline" style="width:100%;justify-content:center;cursor:default;">
                        <i class="fas fa-book-reader"></i> Currently Borrowed
                    </div>
                    <?php if($activeBorrow?->canRenew()): ?>
                        <form action="<?php echo e(route('user.borrows.renew', $activeBorrow)); ?>" method="POST" style="margin-top:8px;">
                            <?php echo csrf_field(); ?>
                            <button class="btn btn-success" style="width:100%;justify-content:center;">
                                <i class="fas fa-redo"></i> Renew Borrow
                            </button>
                        </form>
                    <?php endif; ?>
                <?php elseif($isReserved): ?>
                    <div style="background:var(--cream);border-radius:8px;padding:12px;font-size:13px;margin-bottom:8px;">
                        <div style="font-weight:600;color:var(--navy);margin-bottom:2px;"><i class="fas fa-bookmark" style="color:var(--gold)"></i> Reservation Active</div>
                        <div style="color:var(--text-muted);">Status: <?php echo e($reservation->status_badge['label']); ?></div>
                        <?php if($reservation->status === 'ready'): ?>
                            <div style="color:var(--success);font-weight:600;margin-top:4px;">✓ Ready for pickup! Pick up by <?php echo e($reservation->expires_at->format('M d')); ?></div>
                        <?php endif; ?>
                    </div>
                    <button type="button" class="btn btn-outline" style="width:100%; justify-content:center;" onclick="openCancelModal('<?php echo e(route('user.reservations.cancel', $reservation)); ?>')">
                        <i class="fas fa-times"></i> Cancel Reservation
                    </button>
                <?php else: ?>
                    <form action="<?php echo e(route('user.books.reserve', $book)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <button class="btn <?php echo e($book->isAvailable() ? 'btn-primary' : 'btn-warning'); ?>" style="width:100%;justify-content:center;">
                            <?php if($book->isAvailable()): ?>
                                <i class="fas fa-bookmark"></i> Reserve Book
                            <?php else: ?>
                                <i class="fas fa-clock"></i> Join Queue (Position #<?php echo e($queuePosition); ?>)
                            <?php endif; ?>
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="card">
            <div class="card-body">
                <div style="display:flex;flex-direction:column;gap:10px;">
                    <?php $__currentLoopData = [
                        ['label'=>'ISBN','value'=>$book->isbn,'icon'=>'fa-barcode'],
                        ['label'=>'Publisher','value'=>$book->publisher,'icon'=>'fa-building'],
                        ['label'=>'Year','value'=>$book->published_year,'icon'=>'fa-calendar'],
                        ['label'=>'Language','value'=>$book->language,'icon'=>'fa-globe'],
                        ['label'=>'Pages','value'=>$book->pages,'icon'=>'fa-file-alt'],
                        ['label'=>'Location','value'=>$book->location,'icon'=>'fa-map-marker-alt'],
                    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($info['value']): ?>
                    <div style="display:flex;gap:10px;font-size:13px;">
                        <i class="fas <?php echo e($info['icon']); ?>" style="width:16px;color:var(--text-muted);margin-top:2px;"></i>
                        <div>
                            <div style="color:var(--text-muted);font-size:11px;"><?php echo e($info['label']); ?></div>
                            <div style="font-weight:500;"><?php echo e($info['value']); ?></div>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    </div>

    
    <div>
        <div class="card" style="margin-bottom:20px;">
            <div class="card-body">
                <div style="font-size:12px;text-transform:uppercase;letter-spacing:0.07em;color:var(--text-muted);margin-bottom:6px;">
                    <?php echo e($book->category->name); ?>

                </div>
                <h1 style="font-family:'DM Serif Display',serif;font-size:30px;color:var(--navy);margin-bottom:6px;"><?php echo e($book->title); ?></h1>
                <div style="font-size:16px;color:var(--text-muted);margin-bottom:16px;">by <strong><?php echo e($book->author); ?></strong></div>

                <?php if($book->description): ?>
                <p style="line-height:1.8;color:var(--text);font-size:15px;"><?php echo e($book->description); ?></p>
                <?php endif; ?>
            </div>
        </div>

        
        <?php if($similar->count()): ?>
        <div class="card">
            <div class="card-header"><span class="card-title">More in <?php echo e($book->category->name); ?></span></div>
            <div class="card-body">
                <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(130px,1fr));gap:12px;">
                    <?php $__currentLoopData = $similar; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route('user.books.show', $s)); ?>" style="text-decoration:none;color:inherit;">
                        <div style="background:var(--cream);border-radius:8px;padding:12px;text-align:center;transition:background 0.2s;" onmouseover="this.style.background='var(--cream-2)'" onmouseout="this.style.background='var(--cream)'">
                            <img src="<?php echo e($s->cover_url); ?>" alt="<?php echo e($s->title); ?>"
                                 style="width:56px;height:78px;object-fit:cover;border-radius:6px;border:1px solid var(--cream-2);margin-bottom:8px;">
                            <div style="font-size:12px;font-weight:600;line-height:1.3;"><?php echo e(Str::limit($s->title, 30)); ?></div>
                            <div style="font-size:11px;color:var(--text-muted);margin-top:2px;"><?php echo e($s->author); ?></div>
                        </div>
                    </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<div id="cancelModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center;">
    <div style="background:white;border-radius:16px;padding:32px;max-width:400px;width:90%;">
        <h3 style="font-family:'DM Serif Display',serif;color:var(--navy);margin-bottom:12px;">Cancel Reservation</h3>
        <p style="color:var(--text-muted);margin-bottom:24px;">Are you sure you want to cancel your reservation for this book? This action cannot be undone.</p>
        <form id="cancelForm" method="POST">
            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
            <div style="display:flex;gap:10px;justify-content:flex-end;">
                <button type="button" class="btn btn-outline" onclick="closeCancelModal()">Keep Reservation</button>
                <button type="submit" class="btn btn-danger">Confirm Cancellation</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openCancelModal(url) {
        document.getElementById('cancelForm').action = url;
        document.getElementById('cancelModal').style.display = 'flex';
    }
    function closeCancelModal() {
        document.getElementById('cancelModal').style.display = 'none';
    }
    // Optional: Close if clicking outside the modal
    document.getElementById('cancelModal').addEventListener('click', e => {
        if (e.target === document.getElementById('cancelModal')) closeCancelModal();
    });
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Programs\E-Commerce\library-system-final\resources\views/user/books/show.blade.php ENDPATH**/ ?>