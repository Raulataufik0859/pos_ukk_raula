@if (session('success') || session('error') || session('warning') || session('info'))
    <div id="toast-container" class="fixed top-5 right-5 z-[100] space-y-3 max-w-sm w-full">
        @if (session('success'))
            <div class="toast-item flex items-start gap-3 px-4 py-3.5 rounded-xl bg-emerald-50 dark:bg-emerald-950/80 border border-emerald-200 dark:border-emerald-800/60 shadow-lg shadow-emerald-500/10 text-emerald-800 dark:text-emerald-200 animate-slide-in">
                <div class="mt-0.5">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="flex-1 text-sm font-medium">{{ session('success') }}</div>
                <button onclick="this.closest('.toast-item').remove()" class="text-emerald-400 hover:text-emerald-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div class="toast-item flex items-start gap-3 px-4 py-3.5 rounded-xl bg-rose-50 dark:bg-rose-950/80 border border-rose-200 dark:border-rose-800/60 shadow-lg shadow-rose-500/10 text-rose-800 dark:text-rose-200 animate-slide-in">
                <div class="mt-0.5">
                    <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="flex-1 text-sm font-medium">{{ session('error') }}</div>
                <button onclick="this.closest('.toast-item').remove()" class="text-rose-400 hover:text-rose-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        @endif
    </div>

    <style>
        @keyframes slide-in {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        .animate-slide-in {
            animation: slide-in 0.35s ease-out;
        }
    </style>

    <script>
        // Auto hide setelah 4 detik
        setTimeout(() => {
            document.querySelectorAll('.toast-item').forEach(el => {
                el.style.transition = 'all 0.4s ease';
                el.style.opacity = '0';
                el.style.transform = 'translateX(100%)';
                setTimeout(() => el.remove(), 400);
            });
        }, 4000);
    </script>
@endif