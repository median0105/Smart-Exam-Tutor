<x-app-layout>
    <x-slot name="header"><div><p class="text-sm uppercase tracking-[0.3em] text-blue-200">Domain Model</p><h1 class="mt-2 text-3xl font-semibold text-white">Kelola Mata Pelajaran</h1></div></x-slot>
    <div class="grid gap-6 lg:grid-cols-[0.9fr_1.1fr]">
        <form method="POST" action="{{ route('admin.subjects.store') }}" class="panel p-6">@csrf
            <h2 class="text-xl font-semibold">Tambah mapel</h2>
            <input name="name" class="input-shell" placeholder="Nama mapel">
            <input name="code" class="input-shell" placeholder="Kode mapel">
            <input name="color" class="input-shell" placeholder="Contoh: from-blue-500 to-violet-500">
            <textarea name="description" class="input-shell" placeholder="Deskripsi singkat"></textarea>
            <button class="mt-4 rounded-full bg-slate-900 px-5 py-3 text-sm font-semibold text-white">Simpan</button>
        </form>
        <div class="panel p-6"><h2 class="text-xl font-semibold">Daftar mapel</h2><div class="mt-4 space-y-4">@foreach($subjects as $subject)<div class="rounded-3xl border border-slate-200 p-4"><div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between"><div><p class="font-semibold">{{ $subject->name }}</p><p class="text-sm text-slate-500">{{ $subject->code }} / {{ $subject->description }}</p></div><form method="POST" action="{{ route('admin.subjects.destroy', $subject) }}">@csrf @method('DELETE')<button class="rounded-full border border-rose-200 px-4 py-2 text-sm text-rose-500">Hapus</button></form></div></div>@endforeach</div></div>
    </div>
</x-app-layout>
