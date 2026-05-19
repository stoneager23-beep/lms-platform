<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-red-600/90 border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-widest hover:bg-red-600 hover:scale-105 active:scale-95 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 focus:ring-offset-slate-900 shadow-lg shadow-red-500/30 transition-all duration-300 ease-in-out']) }}>
    {{ $slot }}
</button>
