<nav class="space-y-1">

    <x-sidebar-link href="{{ route('relawan.dashboard') }}" :active="request()->routeIs('relawan.dashboard')">
        <x-icon name="building" class="h-4 w-4" /> Dashboard
    </x-sidebar-link>

    <x-sidebar-link href="{{ route('relawan.modules.index') }}" :active="request()->routeIs('relawan.modules.*')">
        <x-icon name="book-open" class="h-4 w-4" /> Modul Pembekalan
    </x-sidebar-link>

    <x-sidebar-link href="{{ route('relawan.applications.search') }}" :active="request()->routeIs('relawan.applications.search')">
        <x-icon name="map-pin" class="h-4 w-4" /> Cari Panti
    </x-sidebar-link>

    <x-sidebar-link href="{{ route('relawan.applications.index') }}" :active="request()->routeIs('relawan.applications.index')">
        <x-icon name="clipboard" class="h-4 w-4" /> Pengajuan Saya
    </x-sidebar-link>

    <x-sidebar-link href="{{ route('relawan.reports.index') }}" :active="request()->routeIs('relawan.reports.index')">
        <x-icon name="check-circle" class="h-4 w-4" /> Laporan Kunjungan
    </x-sidebar-link>

</nav>
