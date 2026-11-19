<div x-data
     x-show="$store.toast.show" 
     x-cloak 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:translate-x-4"
     x-transition:enter-end="opacity-100 translate-y-0 sm:translate-x-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 translate-y-0 sm:translate-x-0"
     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:translate-x-4"
     class="fixed bottom-6 left-6 z-50 max-w-sm w-full bg-[#242424] text-white rounded-xl shadow-2xl border border-[#3E3E3E] p-4 flex items-center justify-between gap-3"
     role="alert">
    
    <div class="flex items-center gap-3">
        <template x-if="$store.toast.type === 'success'">
            <div class="w-8 h-8 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center flex-shrink-0">
                <x-icon name="check-circle" class="w-5 h-5" />
            </div>
        </template>

        <template x-if="$store.toast.type === 'error'">
            <div class="w-8 h-8 rounded-full bg-red-500/20 text-red-400 flex items-center justify-center flex-shrink-0">
                <x-icon name="x" class="w-5 h-5" />
            </div>
        </template>

        <p class="text-xs font-medium leading-relaxed" x-text="$store.toast.message"></p>
    </div>

    <button @click="$store.toast.show = false" class="text-[#777777] hover:text-white p-1">
        <x-icon name="x" class="w-4 h-4" />
    </button>
</div>

