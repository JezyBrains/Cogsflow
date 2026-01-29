@extends('layouts.app')

@section('title', 'PO Intelligence Terminal')
@section('page_title', 'Purchase Protocol Execution')

@section('content')
    <div class="space-y-8">
        <!-- Terminal Header -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-display font-black text-zenith-900 uppercase tracking-tight">Purchase Protocol
                    Terminal</h2>
                <p class="text-zenith-400 font-medium mt-1">Reference: <span
                        class="text-zenith-600 font-black">{{ $po->po_number }}</span> • {{ $po->supplier->name }}</p>
            </div>
            <div class="flex gap-4">
                <a href="{{ route('procurement.index') }}" class="zenith-button-outline">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Return to Hub
                </a>
                @if($po->remaining_quantity_kg > 0)
                    <a href="{{ route('logistics.batches.create', ['po_id' => $po->id]) }}" class="zenith-button">
                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        New Intake Batch
                    </a>
                @else
                    <span
                        class="zenith-badge bg-emerald-500 text-white px-6 py-3 rounded-2xl italic font-black shadow-emerald-200 shadow-lg">
                        PROTOCOL COMPLETE
                    </span>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left: PO Intelligence -->
            <div class="lg:col-span-1 space-y-6">
                <div class="zenith-card p-8 bg-zenith-900 text-white shadow-zenith-lg">
                    <h3 class="text-[10px] font-black uppercase tracking-[0.3em] text-zenith-400 mb-8">Contractual
                        Parameters</h3>

                    <div class="space-y-6">
                        <div class="pb-4 border-b border-white/10">
                            <span class="text-[10px] font-bold uppercase text-zenith-300 block mb-1">Total Payload</span>
                            <p class="text-2xl font-display font-black leading-tight">
                                {{ number_format($po->total_quantity_kg) }} <span class="text-xs text-zenith-400">KG</span>
                            </p>
                            <p class="text-[10px] text-zenith-400 font-bold uppercase mt-1">
                                {{ number_format($po->total_quantity_kg / 1000, 2) }} Metric Tons (MT)
                            </p>
                        </div>

                        <div class="pb-4 border-b border-white/10">
                            <span class="text-[10px] font-bold uppercase text-zenith-300 block mb-1">Commodity Vector</span>
                            <p class="text-lg font-black">{{ $po->commodity_type }}</p>
                        </div>

                        <div class="pb-4 border-b border-white/10">
                            <span class="text-[10px] font-bold uppercase text-zenith-300 block mb-1">Unit Valuation</span>
                            <p class="text-lg font-black text-emerald-400">{{ number_format($po->unit_price, 2) }} <span
                                    class="text-[10px] text-emerald-600">TZS/KG</span></p>
                        </div>

                        <div class="pb-4 border-b border-white/10">
                            <span class="text-[10px] font-bold uppercase text-zenith-300 block mb-1">Contract Value</span>
                            <p class="text-lg font-black text-white italic tracking-tighter">
                                {{ number_format($po->total_quantity_kg * $po->unit_price, 0) }} <span
                                    class="text-[10px] text-zenith-400">TZS</span>
                            </p>
                        </div>

                        <div>
                            <span class="text-[10px] font-bold uppercase text-zenith-300 block mb-1">Contract Status</span>
                            <span class="zenith-badge bg-white/5 text-white border border-white/10 px-4 py-1.5 italic">
                                {{ strtoupper($po->status) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Fulfillment Gauge -->
                <div class="zenith-card p-6 border-zenith-200 shadow-zenith-sm">
                    <h3 class="text-[10px] font-black uppercase tracking-widest text-zenith-900 mb-6">Fulfillment Velocity
                    </h3>

                    @php
                        $progress = ($po->total_quantity_kg > 0) ? ($po->supplied_quantity_kg / $po->total_quantity_kg) * 100 : 0;
                    @endphp

                    <div class="relative pt-1">
                        <div class="flex mb-2 items-center justify-between">
                            <div>
                                <span
                                    class="text-xs font-black inline-block py-1 px-2 uppercase rounded-full text-zenith-600 bg-zenith-50">
                                    {{ number_format($po->supplied_quantity_kg) }} KG RECEIVED
                                </span>
                            </div>
                            <div class="text-right">
                                <span class="text-sm font-black text-zenith-900">
                                    {{ round($progress) }}%
                                </span>
                            </div>
                        </div>
                        <div
                            class="overflow-hidden h-3 mb-6 text-xs flex rounded-full bg-zenith-50 border border-zenith-100">
                            <div style="width:{{ min(100, $progress) }}%"
                                class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-zenith-500 transition-all duration-1000">
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-4 bg-emerald-50 rounded-2xl border border-emerald-100">
                            <span class="text-[9px] font-bold text-emerald-600 uppercase block mb-1">Dispatched</span>
                            <p class="text-lg font-black text-emerald-700">{{ number_format($po->dispatched_quantity_kg) }}
                                <span class="text-[9px]">KG</span>
                            </p>
                        </div>
                        <div class="p-4 bg-rose-50 rounded-2xl border border-rose-100">
                            <span class="text-[9px] font-bold text-rose-600 uppercase block mb-1">Remaining</span>
                            <p class="text-lg font-black text-rose-700">{{ number_format($po->remaining_quantity_kg) }}
                                <span class="text-[9px]">KG</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Fulfillment Ledger -->
            <div class="lg:col-span-2 space-y-6">
                <div class="zenith-card">
                    <div class="p-6 border-b border-zenith-100 flex items-center justify-between bg-zenith-50/20">
                        <h3 class="text-lg font-display font-black text-zenith-900 italic tracking-tight">Supply Chain
                            History</h3>
                        <span
                            class="text-[10px] font-bold text-zenith-400 uppercase tracking-widest">{{ $po->batches->count() }}
                            Batches Recorded</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="zenith-table">
                            <thead>
                                <tr>
                                    <th class="pl-8">Batch Node</th>
                                    <th>Volume</th>
                                    <th>Unit Cost</th>
                                    <th>Dispatched Status</th>
                                    <th>Custodian</th>
                                    <th class="text-right pr-8">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($po->batches as $batch)
                                    <tr class="hover:bg-zenith-50 transition-colors group">
                                        <td class="pl-8 py-6">
                                            <p class="text-xs font-black text-zenith-900">{{ $batch->batch_number }}</p>
                                            <p class="text-[9px] text-zenith-400 font-bold uppercase mt-1">
                                                {{ $batch->created_at->format('M d, Y • H:i') }}
                                            </p>
                                        </td>
                                        <td class="py-6">
                                            <p class="text-sm font-black text-zenith-800">
                                                {{ number_format($batch->total_weight_kg, 2) }} <span
                                                    class="text-[10px] text-zenith-400">KG</span>
                                            </p>
                                            <p class="text-[9px] text-zenith-400 font-black uppercase">
                                                {{ $batch->expected_bags }} Units
                                            </p>
                                        </td>
                                        <td class="py-6">
                                            <p class="text-xs font-black text-emerald-600">
                                                {{ number_format($batch->total_weight_kg * $po->unit_price, 0) }} <span
                                                    class="text-[8px] uppercase">TZS</span>
                                            </p>
                                        </td>
                                        <td class="py-6">
                                            @php $dispatch = $batch->dispatches->where('status', '!=', 'cancelled')->first(); @endphp
                                            <div class="flex flex-col gap-1">
                                                @if($dispatch)
                                                                            <span class="zenith-badge {{ 
                                                                                                                $batch->status === 'accepted' ? 'bg-green-100 text-green-600' : (
                                                    $batch->status === 'shipped' ? 'bg-amber-100 text-amber-600' :
                                                    'bg-zenith-100 text-zenith-500') 
                                                                                                            }}">
                                                                                DISPATCHED
                                                                            </span>
                                                                            <span class="text-[9px] font-black text-zenith-400 uppercase tracking-tighter">
                                                                                NODE: {{ $dispatch->dispatch_number }}
                                                                            </span>
                                                @else
                                                    <span class="zenith-badge bg-zenith-50 text-zenith-300 italic">
                                                        NOT DISPATCHED
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="py-6">
                                            <div class="flex items-center gap-2">
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($batch->receiver->name ?? 'System') }}&background=E2E8F0&color=475569"
                                                    class="w-6 h-6 rounded-lg" alt="">
                                                <span
                                                    class="text-[10px] font-bold text-zenith-600">{{ $batch->receiver->name ?? 'Automation' }}</span>
                                            </div>
                                        </td>
                                        <td class="text-right pr-8 py-6">
                                            <div class="flex flex-col items-end gap-2">
                                                <a href="{{ route('logistics.batches.show', $batch->id) }}"
                                                    class="text-[10px] font-black uppercase text-zenith-400 hover:text-zenith-900 transition-colors underline decoration-zenith-100">View
                                                    Node</a>
                                                @if($dispatch)
                                                    <a href="{{ route('logistics.dispatches') }}"
                                                        class="text-[9px] font-bold text-zenith-500 hover:text-zenith-900 uppercase">Track
                                                        Transit</a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-20 text-center">
                                            <div class="flex flex-col items-center">
                                                <div
                                                    class="w-16 h-16 rounded-3xl bg-zenith-50 flex items-center justify-center text-zenith-200 mb-4">
                                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4">
                                                        </path>
                                                    </svg>
                                                </div>
                                                <p class="text-xs font-bold text-zenith-400 uppercase tracking-[0.2em]">Zero
                                                    Fulfillment Data Detected</p>
                                                <p class="text-[10px] text-zenith-300 mt-2 italic">Awaiting primary intake of
                                                    commodity volumes against this protocol.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($po->notes)
                    <div class="zenith-card p-6 bg-zenith-50/50 border-zenith-100">
                        <h3 class="text-[10px] font-black uppercase tracking-widest text-zenith-400 mb-3">System Annotations
                        </h3>
                        <p class="text-sm text-zenith-600 font-medium leading-relaxed">{{ $po->notes }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection