<!-- Zenith UI Global Bridge -->
<div id="zenith-ui-root" x-data="zenithUI()" class="relative">
    <!-- Toast Matrix -->
    <div id="zenith-toast-matrix" class="fixed top-12 right-12 z-[100] space-y-4 pointer-events-none">
        <template x-for="toast in toasts" :key="toast.id">
            <div x-show="toast.visible" x-transition:enter="transition ease-out duration-300 transform"
                x-transition:enter-start="translate-x-full opacity-0" x-transition:enter-end="translate-x-0 opacity-100"
                x-transition:leave="transition ease-in duration-200 transform"
                x-transition:leave-start="translate-x-0 opacity-100" x-transition:leave-end="translate-x-full opacity-0"
                class="zenith-card !p-5 shadow-zenith-lg pointer-events-auto min-w-[320px] max-w-md border-l-4" :class="{
                    'border-green-500': toast.type === 'success',
                    'border-red-500': toast.type === 'error',
                    'border-zenith-500': toast.type === 'info',
                    'border-yellow-500': toast.type === 'warning'
                }">
                <div class="flex items-start gap-4">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0" :class="{
                            'bg-green-100 text-green-600': toast.type === 'success',
                            'bg-red-100 text-red-600': toast.type === 'error',
                            'bg-zenith-50 text-zenith-600': toast.type === 'info',
                            'bg-yellow-100 text-yellow-600': toast.type === 'warning'
                         }">
                        <template x-if="toast.type === 'success'"><svg class="w-4 h-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M5 13l4 4L19 7"></path>
                            </svg></template>
                        <template x-if="toast.type === 'error'"><svg class="w-4 h-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg></template>
                        <template x-if="toast.type === 'info'"><svg class="w-4 h-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg></template>
                        <template x-if="toast.type === 'warning'"><svg class="w-4 h-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                </path>
                            </svg></template>
                    </div>
                    <div class="flex-grow">
                        <p class="text-xs font-black text-zenith-900 uppercase tracking-widest" x-text="toast.type"></p>
                        <p class="text-[11px] text-zenith-400 font-medium mt-1 leading-tight" x-text="toast.message">
                        </p>
                    </div>
                    <button @click="toast.visible = false"
                        class="text-zenith-200 hover:text-zenith-900 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </template>
    </div>

    <!-- Confirmation Modal Protocol -->
    <div x-show="modal.visible"
        class="fixed inset-0 z-[110] flex items-center justify-center p-6 bg-zenith-900/60 backdrop-blur-sm"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="bg-white rounded-[2.5rem] shadow-zenith-xl w-full max-w-lg overflow-hidden"
            @click.away="if(!modal.locked) modal.visible = false"
            x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="scale-95 translate-y-8" x-transition:enter-end="scale-100 translate-y-0">
            <div class="p-10 text-center">
                <div
                    class="w-20 h-20 rounded-3xl bg-zenith-50 flex items-center justify-center mx-auto mb-8 text-zenith-500">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                        </path>
                    </svg>
                </div>
                <h3 class="text-2xl font-display font-black text-zenith-900 tracking-tight" x-text="modal.title"></h3>
                <p class="text-sm font-medium text-zenith-400 mt-3 leading-relaxed" x-text="modal.message"></p>

                <div class="flex gap-4 mt-10">
                    <button @click="modal.visible = false; modal.onDecline()"
                        class="flex-1 px-8 py-4 rounded-2xl border-2 border-zenith-100 text-xs font-black uppercase tracking-widest text-zenith-400 hover:border-zenith-300 hover:text-zenith-900 transition-all">
                        Abort Sequence
                    </button>
                    <button @click="modal.visible = false; modal.onConfirm()"
                        class="flex-1 px-8 py-4 rounded-2xl bg-zenith-900 text-white text-xs font-black uppercase tracking-widest hover:bg-black transition-all shadow-zenith-lg">
                        Authorize
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    window.zenithUI = () => ({
        toasts: [],
        modal: {
            visible: false,
            title: 'Confirm Operation',
            message: 'Are you sure you want to proceed with this protocol?',
            onConfirm: () => { },
            onDecline: () => { },
            locked: false
        },

        init() {
            window.ZenithUI = {
                notify: (type, message) => this.pushToast(type, message),
                confirm: (title, message, options = {}) => this.openConfirm(title, message, options)
            };
        },

        pushToast(type, message) {
            const id = Date.now();
            this.toasts.push({ id, type, message, visible: true });
            setTimeout(() => {
                const index = this.toasts.findIndex(t => t.id === id);
                if (index !== -1) this.toasts[index].visible = false;
            }, 5000);
            setTimeout(() => {
                this.toasts = this.toasts.filter(t => t.id !== id);
            }, 5500);
        },

        openConfirm(title, message, options = {}) {
            return new Promise((resolve) => {
                this.modal.title = title || 'Confirm Operation';
                this.modal.message = message || 'Proceed with this protocol?';
                this.modal.onConfirm = () => resolve(true);
                this.modal.onDecline = () => resolve(false);
                this.modal.locked = options.locked || false;
                this.modal.visible = true;
            });
        }
    });

    // Interceptor for session flashes
    document.addEventListener('DOMContentLoaded', () => {
        @if(session('success'))
            ZenithUI.notify('success', '{{ session('success') }}');
        @endif
        @if(session('error'))
            ZenithUI.notify('error', '{{ session('error') }}');
        @endif
});

    // Unified confirm handler for form submissions
    window.zenithConfirmAction = (event, title, message) => {
        event.preventDefault();
        const form = event.target;

        ZenithUI.confirm(title, message).then((confirmed) => {
            if (confirmed) {
                // Remove the onsubmit handler to prevent infinite loop
                form.removeAttribute('onsubmit');
                form.submit();
            }
        });

        return false;
    };
</script>