<?php $__env->startSection('title', ($item ?? null) ? 'تعديل '.$config['label'] : 'إضافة '.$config['label']); ?>
<?php $__env->startSection('content'); ?>
<?php
    $item = $item ?? null;
    $fields = [
        'name','slug','university_id','faculty_id','program_id','certificate_track_id','city','country','type','degree','language',
        'duration_years','tuition_amount','tuition_currency','minimum_score','rule_type','authority','address','website_url','phone','email',
        'acceptance_label','image_url','description','notes','processing_notes'
    ];
    $jsonFields = ['requirements','required_subjects','formula','inputs_schema','result_schema','required_documents'];
?>
<form class="card" method="post" action="<?php echo e($item ? "/admin/resources/{$resource}/{$item->id}" : "/admin/resources/{$resource}"); ?>">
    <?php echo csrf_field(); ?>
    <?php if($item): ?> <?php echo method_field('PATCH'); ?> <?php endif; ?>
    <div class="form-grid">
        <?php $__currentLoopData = $fields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div>
                <label><?php echo e($field); ?></label>
                <input name="<?php echo e($field); ?>" value="<?php echo e(old($field, $item?->{$field})); ?>">
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php $__currentLoopData = $jsonFields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="full">
                <label><?php echo e($field); ?> JSON</label>
                <textarea name="<?php echo e($field); ?>" rows="4"><?php echo e(old($field, $item?->{$field} ? json_encode($item->{$field}, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) : '')); ?></textarea>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <div><label><input type="checkbox" name="is_active" value="1" <?php if($item?->is_active ?? true): echo 'checked'; endif; ?>> مفعل</label></div>
    </div>
    <button style="margin-top:14px">حفظ</button>
</form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\gam3a_backend\resources\views/admin/resources/form.blade.php ENDPATH**/ ?>