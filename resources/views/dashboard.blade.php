@extends('layouts.app')

@section('title', 'Intelligence Hub')
@section('page_title', 'System Overview')

@section('content')
    <div class="space-y-10">
        <!-- Zenith Header -->
        <div>
            <span class="text-[10px] font-bold text-zenith-400 uppercase tracking-[0.2em] block mb-2">Executive
                Telemetry</span>
            <h2 class="text-4xl font-display font-black text-zenith-900 tracking-tight">Strategic Intelligence</h2>
        </div>

        <!-- Zenith Hub Grid -->
        <div class="zenith-grid">

            <!-- Primary Metric: Throughput Velocity -->
            <div class="col-span-12 lg:col-span-8 zenith-card p-10 flex flex-col">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h3 class="text-xl font-display font-black text-zenith-900 tracking-tight">Supply Chain Velocity
                        </h3>
                        <p class="text-[11px] text-zenith-400 font-bold uppercase tracking-widest mt-1">Global throughput vs
                            Target</p>
                    </div>
                    <span class="zenith-badge bg-zenith-500/10 text-zenith-500">Live Uplink</span>
                </div>

                <!-- Zenith Stream Chart -->
                <div class="h-72 w-full flex items-end gap-2.5 px-2 mb-8">
                    @for($i = 0; $i < 24; $i++)
                        <div class="flex-1 bg-gradient-to-t from-zenith-500/80 to-zenith-500/10 rounded-t-lg transition-all duration-500 hover:from-zenith-600 hover:translate-y-[-4px]"
                            style="height: {{ rand(40, 90) }}%"></div>
                    @endfor
                </div>

                <div class="grid grid-cols-3 gap-10 pt-8 border-t border-zenith-100 mt-auto">
                    <div>
                        <p class="text-[10px] font-bold text-zenith-400 uppercase tracking-widest mb-1.5">Inbound Volume</p>
                        <p class="text-2xl font-display font-black text-zenith-900">{{ number_format($procurement_volume) }}
                            Bags</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-zenith-400 uppercase tracking-widest mb-1.5">Total Revenue</p>
                        <p class="text-2xl font-display font-black text-zenith-900">{{ number_format($total_revenue) }} TZS
                        </p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-zenith-400 uppercase tracking-widest mb-1.5">Net Profit</p>
                        <p class="text-2xl font-display font-black text-zenith-900">{{ number_format($net_profit) }} TZS</p>
                    </div>
                </div>
            </div>

            <!-- Operator Control -->
            <div class="col-span-12 lg:col-span-4 flex flex-col gap-6">
                <!-- Active Agents Card -->
                <div class="zenith-card p-10 flex-1 flex flex-col justify-between">
                    <div>
                        <span class="text-[10px] font-bold text-zenith-500 uppercase tracking-widest block mb-1">Authorized
                            Agents</span>
                        <h3 class="text-4xl font-display font-black text-zenith-900">{{ $total_users }}</h3>
                        <p class="text-xs text-zenith-400 font-medium mt-3 leading-relaxed">Verified system operators across
                            distributed enterprise nodes.</p>
                    </div>
                    <div class="mt-8 flex items-center justify-between">
                        <div class="flex -space-x-2">
                            <!-- Simple avatars for now -->
                            <div
                                class="w-10 h-10 rounded-xl bg-zenith-800 border-2 border-white flex items-center justify-center text-[9px] font-black text-white shadow-zenith-sm">
                                All
                            </div>
                        </div>
                        <button class="w-px h-8 bg-zenith-100"></button>
                        <a href="{{ route('security.users') }}"
                            class="text-[10px] font-bold text-zenith-500 uppercase tracking-widest hover:text-zenith-700">Manage</a>
                    </div>
                </div>

                <!-- Integrity Status -->
                <div class="zenith-card p-10 bg-zenith-50 border-zenith-200">
                    <span class="text-[10px] font-bold text-zenith-400 uppercase tracking-widest block mb-4">Security
                        Integrity</span>
                    <div class="flex items-center gap-6">
                        <div
                            class="w-14 h-14 rounded-2xl bg-white border border-zenith-200 shadow-zenith-sm flex items-center justify-center relative">
                            <span class="text-xs font-black text-zenith-500">{{ $integrity_score }}%</span>
                            <div class="absolute inset-0 border-2 border-zenith-500/20 rounded-2xl"></div>
                            <div class="absolute inset-0 border-2 border-t-zenith-500 rounded-2xl animate-spin-slow"></div>
                        </div>
                        <div>
                            <p class="text-lg font-display font-black text-zenith-900 leading-tight">System Shield</p>
                            <span class="zenith-badge bg-emerald-100 text-emerald-700 mt-1">Verified Stable</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Global Notifications -->
            <div class="col-span-12 md:col-span-4 zenith-card p-8">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xs font-bold text-zenith-900 uppercase tracking-widest">Pulse Alerts</h3>
                    <div class="w-2 h-2 rounded-full bg-emerald-500 shadow-lg shadow-emerald-500/20"></div>
                </div>
                <div class="space-y-4">
                    <!-- Static alerts for now, could be dynamic later -->
                    <div class="p-4 rounded-xl bg-zenith-50 border border-zenith-100 flex items-start gap-4">
                        <div class="w-2 h-2 rounded-full bg-zenith-500 mt-1.5"></div>
                        <div>
                            <p class="text-xs font-bold text-zenith-800">System Online</p>
                            <p class="text-[10px] text-zenith-400 mt-0.5">All nodes operational</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transaction Ledger Preview -->
            <div class="col-span-12 md:col-span-8 zenith-card p-10">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-lg font-display font-black text-zenith-900 tracking-tight">Recent Financial Stream</h3>
                    <a href="{{ route('finance.index') }}"
                        class="zenith-button-outline py-2.5 px-6 text-[10px] rounded-xl uppercase tracking-widest">View
                        All</a>
                </div>

                <table class="zenith-table">
                    <thead>
                        <tr>
                            <th>Transaction ID</th>
                            <th>Description</th>
                            <th>Volume</th>
                            <th>Type</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recent_transactions as $transaction)
                            <tr class="hover:bg-zenith-50 transition-colors cursor-pointer group">
                                <td class="font-mono text-[11px] text-zenith-500">TX-{{ $transaction->id }}</td>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <p class="text-xs font-bold text-zenith-800">{{ $transaction->description }}</p>
                                    </div>
                                </td>
                                <td class="text-xs font-bold text-zenith-600">
                                    {{ number_format($transaction->amount) }} TZS
                                </td>
                                <td>
                                    @if($transaction->type === 'income')
                                        <span class="zenith-badge bg-emerald-50 text-emerald-600">Income</span>
                                    @else
                                        <span class="zenith-badge bg-red-50 text-red-600">Expense</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-xs text-zenith-400">No recent transactions found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
@endsection