@extends('layouts.dashboard')

@section('title', 'Kategori Kebutuhan')
@section('page_title', 'Kategori Kebutuhan')
@section('page_description', 'Kelola master kategori kebutuhan panti.')

@section('content')
<x-admin-filter :action="route('admin.categories.index')" search-placeholder="Cari nama atau slug kategori..." />

    <div class="mb-6 flex justify-end">
        <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center rounded-lg bg-teal-forest px-4 py-2 text-sm font-medium text-white transition hover:bg-teal-hover">
            Tambah Kategori
        </a>
    </div>

    <x-card class="overflow-hidden p-0">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-border-soft text-sm">
                <thead class="bg-warm-bg text-left text-xs font-semibold uppercase tracking-wide text-stone-gray">
                    <tr>
                        <th class="px-4 py-3">Kategori</th>
                        <th class="px-4 py-3">Target</th>
                        <th class="px-4 py-3">Dipakai</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-soft">
                    @foreach ($categories as $category)
                        <tr>
                            <td class="px-4 py-4">
                                <p class="font-medium text-stone-ink">{{ $category->name }}</p>
                                <p class="mt-1 text-xs text-stone-gray">{{ Str::limit($category->description, 50) ?: '-' }}</p>
                            </td>
                            <td class="px-4 py-4 text-stone-gray">{{ ucfirst($category->target) }}</td>
                            <td class="px-4 py-4 text-stone-gray">{{ $category->panti_needs_count }} kebutuhan</td>
                            <td class="px-4 py-4">
                                @if ($category->is_active)
                                    <span class="rounded-full bg-urgency-green/10 px-3 py-1 text-xs font-semibold uppercase text-urgency-green">Aktif</span>
                                @else
                                    <span class="rounded-full bg-urgency-red/10 px-3 py-1 text-xs font-semibold uppercase text-urgency-red">Nonaktif</span>
                                @endif
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.categories.edit', $category) }}" class="text-teal-forest hover:text-teal-hover">Edit</a>

                                    <form method="POST" action="{{ route('admin.categories.toggle', $category) }}">
                                        @csrf
                                        <button class="rounded-lg border border-border-soft px-3 py-1.5 text-xs font-medium text-stone-gray hover:border-teal-forest hover:text-teal-forest">
                                            {{ $category->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-card>

    <div class="mt-6">{{ $categories->links() }}</div>

@endsection
