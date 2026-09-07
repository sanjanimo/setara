<nav class="space-y-1">

    <x-sidebar-link href="{{ route('donatur.dashboard') }}" :active="request()->routeIs('donatur.dashboard')">
        <x-icon name="building" class="h-4 w-4" /> Dashboard
    </x-sidebar-link>

    <x-sidebar-link href="{{ route('map.index') }}" :active="false">
        <x-icon name="map-pin" class="h-4 w-4" /> Peta Kebutuhan
    </x-sidebar-link>

    <x-sidebar-link href="{{ route('donatur.donations.index') }}" :active="request()->routeIs('donatur.donations.*')">
        <x-icon name="heart" class="h-4 w-4" /> Donasi Saya
    </x-sidebar-link>

    <x-sidebar-link href="{{ route('donatur.profile.edit') }}" :active="request()->routeIs('donatur.profile.*')">
        <x-icon name="users" class="h-4 w-4" /> Profil
    </x-sidebar-link>

</nav>
