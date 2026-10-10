<?php $__env->startSection('title',$config['label']); ?>
<?php $__env->startSection('content'); ?>
<?php if($resource === 'site-content'): ?>
    <div class="card" style="margin-bottom:14px">
        <strong>تحكم الصفحة الرئيسية</strong>
        <p style="color:#64748b">العناصر المعروضة هنا تتحكم في الصفحة الرئيسية في Next.js على <code>http://localhost:3000/</code>: الهيرو، الخدمات، المميزات، الخطوات، الأسئلة، المقالات، والتنقل.</p>
        <div class="actions">
            <a class="btn gray" href="/admin/resources/site-content?scope=home">سيكشنات الرئيسية</a>
            <a class="btn gray" href="/admin/resources/site-content?kind=page">الصفحات</a>
            <a class="btn gray" href="/admin/resources/site-content?kind=service">الخدمات</a>
            <a class="btn gray" href="/admin/resources/site-content?kind=faq">الأسئلة</a>
            <a class="btn gray" href="/admin/resources/site-content?kind=article">المقالات</a>
        </div>
    </div>
<?php endif; ?>
<p><a class="btn" href="/admin/resources/<?php echo e($resource); ?>/create">إضافة جديد</a></p>
<table>
    <tr><th>#</th><th>الاسم</th><?php if($resource === 'site-content'): ?><th>النوع</th><th>Slug</th><th>الترتيب</th><?php endif; ?><th>الحالة</th><th></th></tr>
    <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td><?php echo e($item->id); ?></td>
            <td><?php echo e($item->name ?? $item->slug ?? ('Item '.$item->id)); ?></td>
            <?php if($resource === 'site-content'): ?>
                <td><?php echo e($item->kind); ?></td>
                <td><?php echo e($item->slug); ?></td>
                <td><?php echo e($item->sort_order); ?></td>
            <?php endif; ?>
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