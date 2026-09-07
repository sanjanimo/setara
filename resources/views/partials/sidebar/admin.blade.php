<nav class="space-y-1">

    <x-sidebar-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard')">
        <x-icon name="building" class="h-4 w-4" /> Dashboard
    </x-sidebar-link>

    <x-sidebar-link href="{{ route('admin.verification.index') }}" :active="request()->routeIs('admin.verification.*')">
        <x-icon name="shield" class="h-4 w-4" /> Verifikasi Panti
    </x-sidebar-link>

    <x-sidebar-link href="{{ route('admin.categories.index') }}" :active="request()->routeIs('admin.categories.*')">
        <x-icon name="clipboard" class="h-4 w-4" /> Kategori Kebutuhan
    </x-sidebar-link>

    <x-sidebar-link href="{{ route('admin.modules.index') }}" :active="request()->routeIs('admin.modules.*')">
        <x-icon name="book-open" class="h-4 w-4" /> Modul Pembekalan
    </x-sidebar-link>

    <x-sidebar-link href="{{ route('admin.donations.index') }}" :active="request()->routeIs('admin.donations.index')">
        <x-icon name="heart" class="h-4 w-4" /> Donasi
    </x-sidebar-link>

    <x-sidebar-link href="{{ route('admin.volunteers.index') }}" :active="request()->routeIs('admin.volunteers.index')">
        <x-icon name="users" class="h-4 w-4" /> Relawan
    </x-sidebar-link>

    <x-sidebar-link href="{{ route('admin.users.index') }}" :active="request()->routeIs('admin.users.*')">
        <x-icon name="users" class="h-4 w-4" /> Pengguna
    </x-sidebar-link>

    <x-sidebar-link href="{{ route('admin.logs.index') }}" :active="request()->routeIs('admin.logs.*')">
        <x-icon name="check-circle" class="h-4 w-4" /> Log Aktivitas
    </x-sidebar-link>

</nav>
