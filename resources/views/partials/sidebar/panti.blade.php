<nav class="space-y-1">

    <x-sidebar-link href="{{ route('panti.dashboard') }}" :active="request()->routeIs('panti.dashboard')">
        <x-icon name="building" class="h-4 w-4" /> Dashboard
    </x-sidebar-link>

    <x-sidebar-link href="{{ route('panti.profile.edit') }}" :active="request()->routeIs('panti.profile.edit')">
        <x-icon name="clipboard" class="h-4 w-4" /> Profil Panti
    </x-sidebar-link>

    <x-sidebar-link href="{{ route('panti.needs.index') }}" :active="request()->routeIs('panti.needs.*')">
        <x-icon name="package" class="h-4 w-4" /> Kebutuhan
    </x-sidebar-link>

    <x-sidebar-link href="{{ route('panti.donations.index') }}" :active="request()->routeIs('panti.donations.*')">
        <x-icon name="heart" class="h-4 w-4" /> Donasi Masuk
    </x-sidebar-link>

    <x-sidebar-link href="{{ route('panti.volunteers.index') }}" :active="request()->routeIs('panti.volunteers.*')">
        <x-icon name="users" class="h-4 w-4" /> Kunjungan Relawan
    </x-sidebar-link>

    <x-sidebar-link href="{{ route('panti.youth.index') }}" :active="request()->routeIs('panti.youth.*')">
        <x-icon name="book-open" class="h-4 w-4" /> Youth / Usia Produktif
    </x-sidebar-link>

    <x-sidebar-link href="{{ route('panti.reports.index') }}" :active="request()->routeIs('panti.reports.index')">
        <x-icon name="trending-up" class="h-4 w-4" /> Laporan
    </x-sidebar-link>

</nav>
