@extends('layouts.app')

@section('page_title', 'Intelligence Hub')

@section('content')
    <div class="space-y-10">
        <!-- Header with Breadcrumbs -->
        <div class="flex items-center justify-between">
            <div>
                <nav class="flex text-[10px] font-bold text-zenith-300 uppercase tracking-widest mb-2 gap-2 items-center">
                    <span>System</span>
                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path>
                    </svg>
                    <span class="text-zenith-500">Analytics</span>
                </nav>
                <h2 class="text-3xl font-display font-black text-zenith-900 tracking-tight">Intelligence Hub</h2>
            </div>
        </div>

        <!-- Quick Stats Summary -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white p-6 rounded-3xl border border-zenith-100 shadow-zenith-sm">
                <h4 class="text-[10px] font-black text-zenith-300 uppercase tracking-widest mb-3">Finance (MTD)</h4>
                <p class="text-2xl font-display font-black text-zenith-900">
                    {{ number_format($quickStats['finance']['total_income'], 0) }} TZS</p>
                <p class="text-[10px] font-bold text-green-500 mt-1 uppercase">Income recorded</p>
            </div>
            <div class="bg-white p-6 rounded-3xl border border-zenith-100 shadow-zenith-sm">
                <h4 class="text-[10px] font-black text-zenith-300 uppercase tracking-widest mb-3">Active Logistics</h4>
                <p class="text-2xl font-display font-black text-zenith-900">
                    {{ $quickStats['logistics']['active_dispatches'] }}</p>
                <p class="text-[10px] font-bold text-zenith-400 mt-1 uppercase">Ongoing dispatches</p>
            </div>
            <div class="bg-white p-6 rounded-3xl border border-zenith-100 shadow-zenith-sm">
                <h4 class="text-[10px] font-black text-zenith-300 uppercase tracking-widest mb-3">Inventory</h4>
                <p class="text-2xl font-display font-black text-zenith-900">
                    {{ number_format($quickStats['inventory']->sum('total_weight'), 0) }} KG</p>
                <p class="text-[10px] font-bold text-zenith-400 mt-1 uppercase">Total stock on hand</p>
            </div>
            <div class="bg-white p-6 rounded-3xl border border-zenith-100 shadow-zenith-sm">
                <h4 class="text-[10px] font-black text-zenith-300 uppercase tracking-widest mb-3">Procurement</h4>
                <p class="text-2xl font-display font-black text-zenith-900">{{ $quickStats['procurement']['open_pos'] }}</p>
                <p class="text-[10px] font-bold text-yellow-500 mt-1 uppercase">Pending purchase orders</p>
            </div>
        </div>

        <!-- Report Categories -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Logistics & Inventory -->
            <div class="space-y-4">
                <h3 class="text-xs font-black text-zenith-400 uppercase tracking-[0.2em] px-2">Logistics & Inventory</h3>
                <div class="grid gap-3">
                    <a href="{{ route('reports.show', 'inventory') }}"
                        class="group bg-white p-5 rounded-3xl border border-zenith-100 shadow-zenith-sm hover:border-zenith-300 transition-all flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-12 h-12 rounded-2xl bg-zenith-50 flex items-center justify-center text-zenith-500 group-hover:bg-zenith-500 group-hover:text-white transition-all">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-zenith-800">Stock Summary</h4>
                                <p class="text-[10px] text-zenith-400 font-medium">Global commodity levels</p>
                            </div>
                        </div>
                        <svg class="w-4 h-4 text-zenith-200 group-hover:text-zenith-500 transform translate-x-0 group-hover:translate-x-1 transition-all"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 5l7 7-7 7">
                            </path>
                        </svg>
                    </a>
                    <a href="{{ route('reports.show', 'logistics') }}"
                        class="group bg-white p-5 rounded-3xl border border-zenith-100 shadow-zenith-sm hover:border-zenith-300 transition-all flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-12 h-12 rounded-2xl bg-zenith-50 flex items-center justify-center text-zenith-500 group-hover:bg-zenith-500 group-hover:text-white transition-all">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-zenith-800">Fulfillment Metrics</h4>
                                <p class="text-[10px] text-zenith-400 font-medium">Batch to Dispatch reliability</p>
                            </div>
                        </div>
                        <svg class="w-4 h-4 text-zenith-200 group-hover:text-zenith-500 transform translate-x-0 group-hover:translate-x-1 transition-all"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 5l7 7-7 7">
                            </path>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Finance & Procurement -->
            <div class="space-y-4">
                <h3 class="text-xs font-black text-zenith-400 uppercase tracking-[0.2em] px-2">Finance & Procurement</h3>
                <div class="grid gap-3">
                    <a href="{{ route('reports.show', 'finance') }}"
                        class="group bg-white p-5 rounded-3xl border border-zenith-100 shadow-zenith-sm hover:border-zenith-300 transition-all flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-12 h-12 rounded-2xl bg-zenith-50 flex items-center justify-center text-zenith-500 group-hover:bg-zenith-500 group-hover:text-white transition-all">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-zenith-800">Financial Ledger</h4>
                                <p class="text-[10px] text-zenith-400 font-medium">Income and Expense statement</p>
                            </div>
                        </div>
                        <svg class="w-4 h-4 text-zenith-200 group-hover:text-zenith-500 transform translate-x-0 group-hover:translate-x-1 transition-all"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 5l7 7-7 7">
                            </path>
                        </svg>
                    </a>
                    <a href="{{ route('reports.show', 'suppliers') }}"
                        class="group bg-white p-5 rounded-3xl border border-zenith-100 shadow-zenith-sm hover:border-zenith-300 transition-all flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-12 h-12 rounded-2xl bg-zenith-50 flex items-center justify-center text-zenith-500 group-hover:bg-zenith-500 group-hover:text-white transition-all">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-zenith-800">Supplier Analytics</h4>
                                <p class="text-[10px] text-zenith-400 font-medium">Delivery performance tracking</p>
                            </div>
                        </div>
                        <svg class="w-4 h-4 text-zenith-200 group-hover:text-zenith-500 transform translate-x-0 group-hover:translate-x-1 transition-all"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 5l7 7-7 7">
                            </path>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- System Governance -->
            <div class="space-y-4">
                <h3 class="text-xs font-black text-zenith-400 uppercase tracking-[0.2em] px-2">System Governance</h3>
                <div class="grid gap-3">
                    <a href="{{ route('security.audit') }}"
                        class="group bg-white p-5 rounded-3xl border border-zenith-100 shadow-zenith-sm hover:border-zenith-300 transition-all flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-12 h-12 rounded-2xl bg-zenith-50 flex items-center justify-center text-zenith-500 group-hover:bg-zenith-500 group-hover:text-white transition-all">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-zenith-800">Audit Stream</h4>
                                <p class="text-[10px] text-zenith-400 font-medium">Compliance and system logs</p>
                            </div>
                        </div>
                        <svg class="w-4 h-4 text-zenith-200 group-hover:text-zenith-500 transform translate-x-0 group-hover:translate-x-1 transition-all"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 5l7 7-7 7">
                            </path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection