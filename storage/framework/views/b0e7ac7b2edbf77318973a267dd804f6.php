<?php $__env->startSection('title',$config['label']); ?>
<?php $__env->startSection('content'); ?>
<p><a class="btn" href="/admin/resources/<?php echo e($resource); ?>/create">إضافة جديد</a></p>
<table>
    <tr><th>#</th><th>الاسم</th><th>الحالة</th><th></th></tr>
    <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td><?php echo e($item->id); ?></td>
            <td><?php echo e($item->name ?? $item->slug ?? ('Item '.$item->id)); ?></td>
            <td><?php echo e(isset($item->is_active) ? ($item->is_active ? 'مفعل' : 'متوقف') : '-'); ?></td>
            <td class="actions">
                <a class="btn" href="/admin/resources/<?php echo e($resource); ?>/<?php echo e($item->id); ?>/edit">تعديل</a>
                <form method="post" action="/admin/resources/<?php echo e($resource); ?>/<?php echo e($item->id); ?>"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?><button class="btn red">حذف</button></form>
            </td>
        </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</table>
<?php echo e($items->links()); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\gam3a_backend\resources\views/admin/resources/index.blade.php ENDPATH**/ ?>