<?php $__env->startSection('title','إدارة الطلبات'); ?>
<?php $__env->startSection('content'); ?>
<table>
    <tr><th>#</th><th>الطالب</th><th>الجنسية</th><th>الشهادة</th><th>الحالة</th><th></th></tr>
    <?php $__currentLoopData = $applications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $application): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td><?php echo e($application->id); ?></td>
            <td><?php echo e($application->full_name); ?></td>
            <td><?php echo e($application->nationality); ?></td>
            <td><?php echo e($application->certificateTrack?->name); ?></td>
            <td><?php echo e($application->status); ?></td>
            <td><a class="btn" href="/admin/applications/<?php echo e($application->id); ?>">مراجعة</a></td>
        </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</table>
<?php echo e($applications->links()); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\gam3a_backend\resources\views/admin/applications/index.blade.php ENDPATH**/ ?>