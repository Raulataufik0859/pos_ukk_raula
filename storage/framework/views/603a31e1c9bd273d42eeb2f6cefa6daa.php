<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Login'); ?> - POS Raula</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>

    <link rel="icon" type="image/jpg" href="<?php echo e(asset('imagelogo/lopos.jpg')); ?>">


</head>

<body class="bg-gray-50 font-sans antialiased">
    <?php echo $__env->yieldContent('content'); ?>
</body>

</html>
<?php /**PATH C:\laragon\www\pos_raul\resources\views/layouts/guest.blade.php ENDPATH**/ ?>