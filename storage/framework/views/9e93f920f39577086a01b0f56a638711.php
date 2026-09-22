<?php $__env->startSection('title','لوحة التحكم'); ?>
<?php $__env->startSection('content'); ?>
<section class="grid">
    <?php $__currentLoopData = $stats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="card stat"><span><?php echo e(str_replace('_',' ', $label)); ?></span><strong><?php echo e($value); ?></strong></div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</section>
<section class="card" style="margin-top:18px">
    <h2>آخر الطلبات</h2>
    <table>
        <tr><th>الطالب</th><th>الحالة</th><th>السنة</th><th></th></tr>
        <?php $__empty_1 = true; $__currentLoopData = $latestApplications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $application): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td><?php echo e($application->full_name); ?></td>
                <td><?php echo e($application->status); ?></td>
                <td><?php echo e($application->academic_year); ?></td>
                <td><a class="btn" href="/admin/applications/<?php echo e($application->id); ?>">عرض</a></td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="4">لا توجد بيانات بعد.</td></tr>
        <?php endif; ?>
    </table>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\gam3a_backend\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>