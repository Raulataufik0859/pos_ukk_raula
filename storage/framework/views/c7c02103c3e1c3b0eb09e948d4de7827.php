<?php $__env->startSection('title', 'Profil Saya'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-6 max-w-5xl mx-auto">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Profil Saya</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola foto dan informasi akun Anda</p>
    </div>

    <?php if(session('success')): ?>
        <div class="mb-5 p-4 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 rounded-xl text-sm border border-emerald-200 dark:border-emerald-800">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <div class="mb-5 p-4 bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-300 rounded-xl text-sm">
            <ul class="list-disc list-inside space-y-1">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?php echo e(route('profile.update')); ?>" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm">
                    <div class="bg-gradient-to-br from-indigo-500 to-violet-600 h-28"></div>
                    <div class="px-6 pb-6 -mt-14 text-center">
                        <div class="relative inline-block group">
                            <div class="w-28 h-28 rounded-full overflow-hidden ring-4 ring-white dark:ring-gray-800 shadow-lg bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center">
                                <?php if($user->avatar): ?>
                                    <img src="<?php echo e(asset('storage/'.$user->avatar)); ?>" alt="Avatar"
                                         class="w-full h-full object-cover" id="avatar-preview">
                                <?php else: ?>
                                    <span class="text-white text-4xl font-bold" id="avatar-initial">
                                        <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

                                    </span>
                                    <img src="" alt="" class="w-full h-full object-cover hidden" id="avatar-preview">
                                <?php endif; ?>
                            </div>
                            
                            <label for="avatar-input"
                                   class="absolute bottom-1 right-1 w-9 h-9 rounded-full bg-indigo-600 hover:bg-indigo-700 text-white
                                          flex items-center justify-center cursor-pointer shadow-lg border-2 border-white dark:border-gray-800 transition"
                                   title="Ganti foto profil">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </label>
                            <input type="file" name="avatar" id="avatar-input" accept="image/jpeg,image/png,image/webp" class="hidden">
                        </div>

                        <h2 class="mt-4 text-xl font-bold text-gray-900 dark:text-white"><?php echo e($user->name); ?></h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1"><?php echo e($user->email); ?></p>
                        <span class="inline-flex items-center mt-3 px-3 py-1 rounded-full text-xs font-medium
                            <?php echo e(optional($user->role)->name === 'admin'
                                ? 'bg-purple-100 text-purple-700 dark:bg-purple-900/40 dark:text-purple-300'
                                : 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300'); ?>">
                            <?php echo e(ucfirst(optional($user->role)->name ?? 'User')); ?>

                        </span>
                        <p class="text-[11px] text-gray-400 mt-3">Klik ikon kamera untuk ganti foto</p>
                    </div>

                    <div class="border-t border-gray-100 dark:border-gray-700 px-6 py-4 space-y-3">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500 dark:text-gray-400">Bergabung</span>
                            <span class="font-medium text-gray-900 dark:text-white"><?php echo e($user->created_at?->translatedFormat('d M Y') ?? '-'); ?></span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500 dark:text-gray-400">Terakhir Update</span>
                            <span class="font-medium text-gray-900 dark:text-white"><?php echo e($user->updated_at?->translatedFormat('d M Y H:i') ?? '-'); ?></span>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Edit Informasi</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Perbarui data pribadi Anda di bawah ini</p>
                    </div>

                    <div class="p-6 space-y-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" value="<?php echo e(old('name', $user->name)); ?>" required
                                   class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" value="<?php echo e(old('email', $user->email)); ?>" required
                                   class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                        </div>

                        <div class="pt-2 border-t border-gray-100 dark:border-gray-700">
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Ganti Password <span class="text-gray-400 font-normal">(opsional)</span></p>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm text-gray-600 dark:text-gray-400 mb-1.5">Password Baru</label>
                                    <input type="password" name="password"
                                           class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 outline-none transition"
                                           placeholder="Kosongkan jika tidak ingin mengganti">
                                </div>
                                <div>
                                    <label class="block text-sm text-gray-600 dark:text-gray-400 mb-1.5">Konfirmasi Password Baru</label>
                                    <input type="password" name="password_confirmation"
                                           class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 outline-none transition"
                                           placeholder="Ulangi password baru">
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 pt-4">
                            <button type="submit"
                                    class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl transition shadow-sm">
                                Simpan Perubahan
                            </button>
                            <a href="<?php echo e(route('dashboard')); ?>"
                               class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 text-sm font-medium rounded-xl transition">
                                Batal
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.getElementById('avatar-input')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = function(ev) {
        const img = document.getElementById('avatar-preview');
        const initial = document.getElementById('avatar-initial');
        if (img) {
            img.src = ev.target.result;
            img.classList.remove('hidden');
        }
        if (initial) initial.classList.add('hidden');
    };
    reader.readAsDataURL(file);
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\pos_raul\resources\views/profile/show.blade.php ENDPATH**/ ?>