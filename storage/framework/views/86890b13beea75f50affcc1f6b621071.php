<?php $__env->startSection('title', 'Login'); ?>

<?php $__env->startSection('content'); ?>
<style>
    .perspective-1000 { perspective: 1000px; }
    .preserve-3d { transform-style: preserve-3d; }
    .login-card-3d {
        transform: rotateY(-8deg) rotateX(4deg);
        transition: transform 0.5s ease;
        box-shadow:
            0 25px 50px -12px rgba(79, 70, 229, 0.25),
            0 0 0 1px rgba(255,255,255,0.1),
            20px 20px 60px rgba(0,0,0,0.15);
    }
    .login-card-3d:hover {
        transform: rotateY(0deg) rotateX(0deg);
    }
    .float-anim {
        animation: float 6s ease-in-out infinite;
    }
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-12px); }
    }
    .glow-orb {
        filter: blur(80px);
        opacity: 0.5;
    }
</style>

<div class="relative min-h-screen flex items-center justify-center px-4 py-12 overflow-hidden select-none
            bg-gradient-to-br from-slate-900 via-indigo-950 to-purple-950">

    
    <div class="absolute top-1/4 -left-20 w-96 h-96 bg-indigo-500 rounded-full glow-orb pointer-events-none"></div>
    <div class="absolute bottom-1/4 -right-20 w-80 h-80 bg-purple-500 rounded-full glow-orb pointer-events-none"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-fuchsia-600/30 rounded-full glow-orb pointer-events-none"></div>

    
    <div class="absolute inset-0 opacity-[0.03]"
         style="background-image: linear-gradient(rgba(255,255,255,.1) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,.1) 1px, transparent 1px);
                background-size: 40px 40px;"></div>

    <div class="relative w-full max-w-md perspective-1000">

        
        <div class="text-center mb-8 float-anim">
            <div class="inline-flex items-center justify-center w-24 h-24 rounded-[28px]
                        bg-gradient-to-tr from-indigo-500 via-violet-500 to-purple-500
                        shadow-2xl shadow-indigo-500/40 mb-5
                        border border-white/20">
                <svg class="w-12 h-12 text-white drop-shadow-lg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
            </div>
            <h1 class="text-4xl font-extrabold text-white tracking-tight drop-shadow-lg">POS Raula</h1>
            <p class="text-sm text-indigo-200/80 mt-2">Silakan masukkan akun Anda untuk melanjutkan</p>
        </div>

        
        <div class="login-card-3d preserve-3d bg-white/10 backdrop-blur-2xl rounded-3xl border border-white/20 p-8 sm:p-10">
            <form id="loginForm" method="POST" action="<?php echo e(route('auth')); ?>" class="space-y-5">
                <?php echo csrf_field(); ?>

                
                <div>
                    <label for="email" class="block text-sm font-medium text-indigo-100 mb-2">Alamat Email</label>
                    <input id="email" type="email" name="email" value="<?php echo e(old('email')); ?>" required autofocus
                        class="w-full px-4 py-3.5 rounded-2xl border border-white/20 bg-white/10 text-white
                               placeholder-indigo-200/50 focus:bg-white/15 focus:border-indigo-400
                               focus:ring-4 focus:ring-indigo-500/20 outline-none text-sm transition-all duration-200"
                        placeholder="nama@email.com">
                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-2 text-xs font-medium text-rose-300"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                
                <div>
                    <label for="password" class="block text-sm font-medium text-indigo-100 mb-2">Kata Sandi</label>
                    <div class="relative">
                        <input id="password" type="password" name="password" required
                            class="w-full px-4 py-3.5 pr-12 rounded-2xl border border-white/20 bg-white/10 text-white
                                   placeholder-indigo-200/50 focus:bg-white/15 focus:border-indigo-400
                                   focus:ring-4 focus:ring-indigo-500/20 outline-none text-sm transition-all duration-200"
                            placeholder="••••••••">
                        <button type="button" id="togglePassword" tabindex="-1"
                            class="absolute inset-y-0 right-0 flex items-center pr-4 text-indigo-300 hover:text-white transition">
                            <svg id="eyeIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg id="eyeOffIcon" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-2 text-xs font-medium text-rose-300"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember" type="checkbox" name="remember" value="1"
                            <?php echo e(old('remember') ? 'checked' : ''); ?>

                            class="w-4 h-4 rounded border-white/30 bg-white/10 text-indigo-500 focus:ring-indigo-500">
                        <label for="remember" class="ml-2.5 text-sm text-indigo-200 select-none cursor-pointer">
                            Ingat saya
                        </label>
                    </div>
                    <a href="<?php echo e(route('password.request')); ?>"
                       class="text-sm font-medium text-indigo-300 hover:text-white transition">
                        Lupa password?
                    </a>
                </div>

                
                <div class="pt-1">
                    <button type="submit" id="btnSubmit"
                        class="w-full py-3.5 px-4 bg-gradient-to-r from-indigo-500 via-violet-500 to-purple-500
                               hover:from-indigo-400 hover:to-purple-400 active:scale-[0.98]
                               text-white font-semibold rounded-2xl shadow-lg shadow-indigo-500/40
                               transition-all duration-200 disabled:opacity-75 disabled:cursor-not-allowed
                               flex items-center justify-center gap-2 border border-white/10">
                        <span id="btnText">Masuk Ke Aplikasi</span>
                        <span id="btnLoading" class="hidden flex items-center gap-2">
                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span>Memproses...</span>
                        </span>
                    </button>
                </div>
            </form>
        </div>

        <p class="text-center text-xs text-indigo-300/60 mt-8">
            © <?php echo e(date('Y')); ?> <span class="font-semibold text-indigo-200/80">Toko Raula.</span>
        </p>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');
    const eyeOffIcon = document.getElementById('eyeOffIcon');

    togglePassword?.addEventListener('click', function() {
        const isPassword = passwordInput.type === 'password';
        passwordInput.type = isPassword ? 'text' : 'password';
        eyeIcon.classList.toggle('hidden', isPassword);
        eyeOffIcon.classList.toggle('hidden', !isPassword);
    });

    const loginForm = document.getElementById('loginForm');
    const btnSubmit = document.getElementById('btnSubmit');
    const btnText = document.getElementById('btnText');
    const btnLoading = document.getElementById('btnLoading');

    loginForm?.addEventListener('submit', function() {
        btnSubmit.disabled = true;
        btnText.classList.add('hidden');
        btnLoading.classList.remove('hidden');
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.guest', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\pos_raul\resources\views/login.blade.php ENDPATH**/ ?>