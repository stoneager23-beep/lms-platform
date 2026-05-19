<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 bg-white/5 border border-white/10 rounded-xl font-bold text-xs text-slate-300 uppercase tracking-widest shadow-lg shadow-black/20 hover:bg-white/10 hover:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-slate-900 disabled:opacity-25 transition ease-in-out duration-150 backdrop-blur-md']) }}>
    {{ $slot }}
</button>
