<!doctype html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $__env->yieldContent('title', 'Gam3a Admin'); ?></title>
    <style>
        body{margin:0;font-family:Tahoma,Arial,sans-serif;background:#f5f7fb;color:#172033}
        a{color:inherit;text-decoration:none}.shell{display:grid;grid-template-columns:260px 1fr;min-height:100vh}
        aside{background:#111827;color:#e5e7eb;padding:24px}aside h1{font-size:22px;margin:0 0 24px}
        nav a{display:block;padding:11px 12px;border-radius:8px;margin:4px 0;color:#cbd5e1}nav a:hover{background:#1f2937;color:#fff}
        main{padding:28px}.top{display:flex;justify-content:space-between;align-items:center;margin-bottom:22px}
        .card{background:#fff;border:1px solid #e5e7eb;border-radius:10px;padding:18px;box-shadow:0 1px 2px rgba(15,23,42,.04)}
        .grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:14px}.stat strong{display:block;font-size:28px;margin-top:6px}
        table{width:100%;border-collapse:collapse;background:#fff;border-radius:10px;overflow:hidden}th,td{padding:12px;border-bottom:1px solid #e5e7eb;text-align:right}
        th{background:#f8fafc;color:#475569}.btn,button{border:0;border-radius:8px;padding:10px 14px;background:#2563eb;color:#fff;cursor:pointer}
        .btn.gray{background:#475569}.btn.red{background:#dc2626}.actions{display:flex;gap:8px;align-items:center}
        input,select,textarea{width:100%;box-sizing:border-box;border:1px solid #cbd5e1;border-radius:8px;padding:10px;background:#fff}
        label{display:block;margin:12px 0 6px;color:#334155}.form-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:14px}
        .full{grid-column:1/-1}.alert{padding:12px;border-radius:8px;margin-bottom:16px}.ok{background:#dcfce7}.warn{background:#fef3c7}.err{background:#fee2e2}
        @media(max-width:900px){.shell{grid-template-columns:1fr}aside{position:static}.grid,.form-grid{grid-template-columns:1fr}}
    </style>
</head>
<body>
<div class="shell">
    <aside>
        <h1>Gam3a Admin</h1>
        <nav>
            <a href="/admin">لوحة التحكم</a>
            <a href="/admin/applications">الطلبات</a>
            <a href="/admin/resources/universities">الجامعات</a>
            <a href="/admin/resources/programs">البرامج</a>
            <a href="/admin/resources/admission-rules">شروط القبول</a>
            <a href="/admin/resources/calculator-rules">قواعد الحاسبة</a>
            <a href="/admin/resources/equivalency-centers">أماكن المعادلة</a>
            <a href="/admin/resources/certificate-tracks">الشهادات</a>
        </nav>
    </aside>
    <main>
        <div class="top">
            <div>
                <strong><?php echo $__env->yieldContent('title'); ?></strong>
                <div style="color:#64748b;margin-top:4px">إدارة العمليات والمحتوى التشغيلي للمنصة</div>
            </div>
            <?php if(auth()->guard()->check()): ?>
                <form method="post" action="/admin/logout"><?php echo csrf_field(); ?><button class="btn gray">خروج</button></form>
            <?php endif; ?>
        </div>
        <?php if(session('success')): ?><div class="alert ok"><?php echo e(session('success')); ?></div><?php endif; ?>
        <?php if(session('warning')): ?><div class="alert warn"><?php echo e(session('warning')); ?></div><?php endif; ?>
        <?php if($errors->any()): ?><div class="alert err"><?php echo e($errors->first()); ?></div><?php endif; ?>
        <?php echo $__env->yieldContent('content'); ?>
    </main>
</div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\gam3a_backend\resources\views/admin/layout.blade.php ENDPATH**/ ?>