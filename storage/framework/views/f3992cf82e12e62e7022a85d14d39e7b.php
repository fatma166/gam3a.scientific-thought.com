<!doctype html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login</title>
    <style>body{margin:0;display:grid;place-items:center;min-height:100vh;background:#111827;font-family:Tahoma,Arial}.card{width:min(420px,90vw);background:#fff;border-radius:14px;padding:28px}input{width:100%;box-sizing:border-box;padding:12px;border:1px solid #cbd5e1;border-radius:8px;margin:8px 0 14px}button{width:100%;border:0;border-radius:8px;padding:12px;background:#2563eb;color:white}.err{background:#fee2e2;padding:10px;border-radius:8px;margin-bottom:12px}</style>
</head>
<body>
<form class="card" method="post" action="/admin/login">
    <?php echo csrf_field(); ?>
    <h1>دخول الإدارة</h1>
    <?php if($errors->any()): ?><div class="err"><?php echo e($errors->first()); ?></div><?php endif; ?>
    <label>البريد الإلكتروني</label>
    <input name="email" type="email" value="<?php echo e(old('email', 'admin@gam3a.local')); ?>" required>
    <label>كلمة المرور</label>
    <input name="password" type="password" required>
    <button>دخول</button>
</form>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\gam3a_backend\resources\views/admin/login.blade.php ENDPATH**/ ?>