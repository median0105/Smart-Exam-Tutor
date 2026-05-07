@if (session('status'))
    <div class="mb-6 rounded-3xl border border-emerald-300/40 bg-emerald-500/10 px-5 py-4 text-sm text-emerald-100">
        {{ session('status') }}
    </div>
@endif
