<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $app_settings['app_name'] ?? config('app.name', 'Nipo Enterprise') }} - @yield('title')</title>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="antialiased">
    <!-- Zenith Application Shell -->
    <div class="zenith-shell">

        <!-- Sidebar Navigation -->
        <aside class="zenith-sidebar">
            <!-- Branding -->
            <a href="{{ route('dashboard') }}"
                class="h-24 flex items-center px-10 border-b border-zenith-100 shrink-0 hover:bg-zenith-50 transition-all group">
                <div class="flex items-center gap-4">
                    <div
                        class="w-10 h-10 rounded-xl bg-zenith-500 flex items-center justify-center text-white shadow-zenith-sm group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <span class="text-xl font-display font-black text-zenith-900 leading-none block">
                            {{ \Illuminate\Support\Str::of($app_settings['app_name'] ?? 'Zenith')->before(' ') }}
                        </span>
                        <span class="text-[9px] font-bold text-zenith-400 uppercase tracking-widest mt-0.5 block">
                            {{ \Illuminate\Support\Str::of($app_settings['app_name'] ?? 'Enterprise OS')->after(' ') }}
                        </span>
                    </div>
                </div>
            </a>

            <!-- Navigation Links -->
            <nav class="flex-1 overflow-y-auto p-6 scrollbar-hide text-zenith-800">
                <ul class="space-y-2">
                    <!-- Dashboard -->
                    <li>
                        <a href="{{ route('dashboard') }}"
                            class="flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all duration-200 group {{ request()->routeIs('dashboard') ? 'bg-zenith-50 text-zenith-500 font-bold' : 'text-zenith-400 hover:text-zenith-800 hover:bg-zenith-50' }}">
                            <svg class="w-5 h-5 {{ request()->routeIs('dashboard') ? 'text-zenith-500' : 'text-zenith-300 group-hover:text-zenith-500' }}"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                                </path>
                            </svg>
                            <span class="text-sm">Main Hub</span>
                        </a>
                    </li>

                    <li class="px-4 pt-8 pb-3 text-[10px] font-black text-zenith-300 uppercase tracking-[0.3em]">
                        Procurement
                    </li>

                    <li>
                        <a href="{{ route('procurement.index') }}"
                            class="flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all duration-200 group {{ request()->routeIs('procurement.index') ? 'bg-zenith-50 text-zenith-500 font-bold' : 'text-zenith-400 hover:text-zenith-800 hover:bg-zenith-50' }}">
                            <svg class="w-5 h-5 {{ request()->routeIs('procurement.index') ? 'text-zenith-500' : 'text-zenith-300 group-hover:text-zenith-500' }}"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                                </path>
                            </svg>
                            <span class="text-sm">Order Control</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('procurement.suppliers') }}"
                            class="flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all duration-200 group {{ request()->routeIs('procurement.suppliers') ? 'bg-zenith-50 text-zenith-500 font-bold' : 'text-zenith-400 hover:text-zenith-800 hover:bg-zenith-50' }}">
                            <svg class="w-5 h-5 {{ request()->routeIs('procurement.suppliers') ? 'text-zenith-500' : 'text-zenith-300 group-hover:text-zenith-500' }}"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                            <span class="text-sm">Supplier Network</span>
                        </a>
                    </li>

                    <li class="px-4 pt-8 pb-3 text-[10px] font-black text-zenith-300 uppercase tracking-[0.3em]">
                        Logistics
                    </li>

                    <li>
                        <a href="{{ route('logistics.batches') }}"
                            class="flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all duration-200 group {{ request()->routeIs('logistics.batches*') ? 'bg-zenith-50 text-zenith-500 font-bold' : 'text-zenith-400 hover:text-zenith-800 hover:bg-zenith-50' }}">
                            <svg class="w-5 h-5 {{ request()->routeIs('logistics.batches*') ? 'text-zenith-500' : 'text-zenith-300 group-hover:text-zenith-500' }}"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4">
                                </path>
                            </svg>
                            <span class="text-sm">Batch Protocols</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('logistics.dispatches') }}"
                            class="flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all duration-200 group {{ request()->routeIs('logistics.dispatches*') ? 'bg-zenith-50 text-zenith-500 font-bold' : 'text-zenith-400 hover:text-zenith-800 hover:bg-zenith-50' }}">
                            <svg class="w-5 h-5 {{ request()->routeIs('logistics.dispatches*') ? 'text-zenith-500' : 'text-zenith-300 group-hover:text-zenith-500' }}"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0">
                                </path>
                            </svg>
                            <span class="text-sm">Transit Nodes</span>
                        </a>
                    </li>

                    <li class="px-4 pt-8 pb-3 text-[10px] font-black text-zenith-300 uppercase tracking-[0.3em]">
                        Inventory
                    </li>

                    <li>
                        <a href="{{ route('inventory.index') }}"
                            class="flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all duration-200 group {{ request()->routeIs('inventory.index') ? 'bg-zenith-50 text-zenith-500 font-bold' : 'text-zenith-400 hover:text-zenith-800 hover:bg-zenith-50' }}">
                            <svg class="w-5 h-5 {{ request()->routeIs('inventory.index') ? 'text-zenith-500' : 'text-zenith-300 group-hover:text-zenith-500' }}"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                </path>
                            </svg>
                            <span class="text-sm">Stock Hub</span>
                        </a>
                    </li>

                    <li class="px-4 pt-8 pb-3 text-[10px] font-black text-zenith-300 uppercase tracking-[0.3em]">
                        Finance
                    </li>

                    <li>
                        <a href="{{ route('finance.index') }}"
                            class="flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all duration-200 group {{ request()->routeIs('finance.*') && !request()->routeIs('finance.categories.*') ? 'bg-zenith-50 text-zenith-500 font-bold' : 'text-zenith-400 hover:text-zenith-800 hover:bg-zenith-50' }}">
                            <svg class="w-5 h-5 {{ request()->routeIs('finance.*') && !request()->routeIs('finance.categories.*') ? 'text-zenith-500' : 'text-zenith-300 group-hover:text-zenith-500' }}"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                            <span class="text-sm">Financial Ledger</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('finance.categories.index') }}"
                            class="flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all duration-200 group {{ request()->routeIs('finance.categories.*') ? 'bg-zenith-50 text-zenith-500 font-bold' : 'text-zenith-400 hover:text-zenith-800 hover:bg-zenith-50' }}">
                            <svg class="w-5 h-5 {{ request()->routeIs('finance.categories.*') ? 'text-zenith-500' : 'text-zenith-300 group-hover:text-zenith-500' }}"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 7h.01M7 11h.01M7 15h.01M11 7h.01M11 11h.01M11 15h.01M15 7h.01M15 11h.01M15 15h.01M19 7h.01M19 11h.01M19 15h.01M4 3h16a1 1 0 011 1v16a1 1 0 01-1 1H4a1 1 0 01-1-1V4a1 1 0 011-1z">
                                </path>
                            </svg>
                            <span class="text-sm">Finance Categories</span>
                        </a>
                    </li>

                    <li class="px-4 pt-8 pb-3 text-[10px] font-black text-zenith-300 uppercase tracking-[0.3em]">
                        Intelligence
                    </li>

                    <li>
                        <a href="{{ route('reports.index') }}"
                            class="flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all duration-200 group {{ request()->routeIs('reports.*') ? 'bg-zenith-50 text-zenith-500 font-bold' : 'text-zenith-400 hover:text-zenith-800 hover:bg-zenith-50' }}">
                            <svg class="w-5 h-5 {{ request()->routeIs('reports.*') ? 'text-zenith-500' : 'text-zenith-300 group-hover:text-zenith-500' }}"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                </path>
                            </svg>
                            <span class="text-sm">Analytics Hub</span>
                        </a>
                    </li>

                    <li class="px-4 pt-8 pb-3 text-[10px] font-black text-zenith-300 uppercase tracking-[0.3em]">
                        System
                    </li>

                    <li>
                        <a href="{{ route('settings.index') }}"
                            class="flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all duration-200 group {{ request()->routeIs('settings.*') ? 'bg-zenith-50 text-zenith-500 font-bold' : 'text-zenith-400 hover:text-zenith-800 hover:bg-zenith-50' }}">
                            <svg class="w-5 h-5 {{ request()->routeIs('settings.*') ? 'text-zenith-500' : 'text-zenith-300 group-hover:text-zenith-500' }}"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span class="text-sm">System Settings</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('support.index') }}"
                            class="flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all duration-200 group {{ request()->routeIs('support.*') ? 'bg-zenith-50 text-zenith-500 font-bold' : 'text-zenith-400 hover:text-zenith-800 hover:bg-zenith-50' }}">
                            <svg class="w-5 h-5 {{ request()->routeIs('support.*') ? 'text-zenith-500' : 'text-zenith-300 group-hover:text-zenith-500' }}"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z">
                                </path>
                            </svg>
                            <span class="text-sm">Help & Support</span>
                        </a>
                    </li>

                    <li class="px-4 pt-8 pb-3 text-[10px] font-black text-zenith-300 uppercase tracking-[0.3em]">
                        Security
                    </li>

                    <li>
                        <a href="{{ route('security.users') }}"
                            class="flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all duration-200 group {{ request()->routeIs('security.users') ? 'bg-zenith-50 text-zenith-500 font-bold' : 'text-zenith-400 hover:text-zenith-800 hover:bg-zenith-50' }}">
                            <svg class="w-5 h-5 {{ request()->routeIs('security.users') ? 'text-zenith-500' : 'text-zenith-300 group-hover:text-zenith-500' }}"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A10.003 10.003 0 0012 21a10.003 10.003 0 008.384-4.51l.054.09m-4.288-8.764a5.25 5.25 0 00-5.93 5.93c.852 1.026 1.973 1.8 3.23 2.262M9 19c-1.212-1.296-2.064-2.877-2.507-4.608M9 9c.31-2.042 1.134-3.93 2.422-5.47M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                            <span class="text-sm">Access Control</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('security.audit') }}"
                            class="flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all duration-200 group {{ request()->routeIs('security.audit') ? 'bg-zenith-50 text-zenith-500 font-bold' : 'text-zenith-400 hover:text-zenith-800 hover:bg-zenith-50' }}">
                            <svg class="w-5 h-5 {{ request()->routeIs('security.audit') ? 'text-zenith-500' : 'text-zenith-300 group-hover:text-zenith-500' }}"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                </path>
                            </svg>
                            <span class="text-sm">Audit Stream</span>
                        </a>
                    </li>

                    <!-- Quick Actions Section -->
                    <li class="px-4 pt-12 pb-4 text-[10px] font-black text-zenith-500 uppercase tracking-[0.4em]">
                        Quick Actions
                    </li>
                    <li class="px-4 space-y-2">
                        <a href="{{ route('procurement.suppliers') }}#create-po"
                            class="flex items-center gap-3 px-5 py-4 rounded-2xl bg-zenith-900 text-white text-[10px] font-black uppercase tracking-widest hover:bg-black transition-all shadow-xl">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M12 4v16m8-8H4"></path>
                            </svg>
                            New PO
                        </a>
                        <a href="{{ route('finance.create') }}"
                            class="flex items-center gap-3 px-5 py-4 rounded-2xl bg-white border border-zenith-100 text-zenith-800 text-[10px] font-black uppercase tracking-widest hover:border-zenith-300 transition-all">
                            <svg class="w-4 h-4 text-zenith-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                            Log Finance
                        </a>
                    </li>
                </ul>
            </nav>

            <!-- User Widget -->
            <div class="p-6 border-t border-zenith-100 bg-zenith-50/50">
                <div
                    class="flex items-center gap-3 px-4 py-3 rounded-2xl bg-white border border-zenith-200 shadow-zenith-sm">
                    <img src="https://ui-avatars.com/api/?name=John+Doe&background=2E7D32&color=fff"
                        class="w-8 h-8 rounded-lg shadow-sm" alt="User">
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-zenith-800 truncate">John Doe</p>
                        <p class="text-[9px] font-bold text-zenith-400 uppercase tracking-tight">Executive Root</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Workspace -->
        <main class="zenith-main text-zenith-800">
            <!-- Global Topbar -->
            <header class="h-24 bg-white border-b border-zenith-100 flex items-center justify-between px-10 shrink-0">
                <div class="flex items-center gap-6">
                    <a href="{{ route('settings.index') }}"
                        class="w-10 h-10 flex items-center justify-center rounded-xl bg-zenith-50 text-zenith-400 hover:bg-zenith-100 hover:text-zenith-600 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </a>
                    <div class="h-8 w-px bg-zenith-200 hidden lg:block"></div>
                    <h1 class="text-2xl font-display font-black text-zenith-900 tracking-tight">
                        @yield('page_title', 'Workspace')</h1>
                </div>

                <div class="flex items-center gap-8">
                    <div class="relative hidden lg:block">
                        <input type="text" placeholder="Search system resources..."
                            class="w-80 bg-zenith-50 border border-transparent focus:bg-white focus:border-zenith-200 px-10 py-2.5 rounded-xl text-xs transition-all outline-none">
                        <svg class="absolute left-3.5 top-2.5 w-4 h-4 text-zenith-300" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>

                    <div class="flex items-center gap-4 text-zenith-800 border-l border-zenith-100 pl-8">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-11 h-11 flex items-center justify-center rounded-xl bg-zenith-50 text-zenith-400 hover:bg-red-50 hover:text-red-500 transition-all relative group"
                                title="Secure Logout">
                                <svg class="w-5 h-5 group-hover:hidden" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                    </path>
                                </svg>
                                <svg class="w-5 h-5 hidden group-hover:block" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                    </path>
                                </svg>
                                <span
                                    class="absolute top-3 right-3 w-2.5 h-2.5 bg-zenith-500 rounded-full border-2 border-white shadow-sm group-hover:bg-red-500"></span>
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Scrollable Content Area -->
            <div class="zenith-content text-zenith-800">
                @yield('content')

                <!-- Standardized Zenith Footer -->
                <footer class="mt-24 pt-10 border-t border-zenith-100 flex flex-col items-center gap-4 pb-12">
                    <div class="flex items-center gap-3">
                        <div class="w-6 h-6 rounded-lg bg-zenith-100 flex items-center justify-center text-zenith-400">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <span class="text-[10px] font-bold text-zenith-400 uppercase tracking-[0.3em]">Zenith Business
                            Intelligence</span>
                    </div>
                    <p class="text-[9px] font-bold text-zenith-300 uppercase tracking-widest">
                        {{ $app_settings['app_name'] ?? 'Nipo Enterprise Systems' }} &copy; {{ date('Y') }} &bull;
                        Proprietary Governance
                    </p>
                </footer>
            </div>
        </main>
    </div>

    <!-- Zenith UI Bridge -->
    <x-zenith-ui />
</body>

</html>