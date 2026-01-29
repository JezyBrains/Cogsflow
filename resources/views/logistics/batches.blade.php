@extends('layouts.app')

@section('title', 'Batch Protocols')
@section('page_title', 'Logistics Terminal')

@section('content')
    <div class="space-y-8">
        <!-- Header Stream -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-display font-black text-zenith-900">Batch Protocols</h2>
                <p class="text-zenith-400 font-medium mt-1">Verification and lifecycle management of grain arrivals</p>
            </div>
            <div class="flex gap-4">
                {{-- Button removed: Batches must be initiated from POs --}}
            </div>
        </div>

        <!-- Active Batches -->
        <div class="zenith-card">
            <div class="p-8 border-b border-zenith-100 flex items-center justify-between bg-zenith-50/30">
                <div>
                    <h3 class="text-xl font-display font-black text-zenith-900">System Batches</h3>
                    <p class="text-zenith-400 text-xs font-medium mt-1">Full audit history of commodity intakes</p>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="zenith-table">
                    <thead>
                        <tr>
                            <th>Batch Identity</th>
                            <th>Supplier & Source</th>
                            <th>Commodity</th>
                            <th>Total Volume</th>
                            <th>Quality Metrics</th>
                            <th>Current Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($batches as $batch)
                            <tr class="hover:bg-zenith-50/50 transition-colors">
                                <td>
                                    <div class="flex flex-col">
                                        <span
                                            class="text-xs font-black text-zenith-900 uppercase tracking-tight">{{ $batch->batch_number }}</span>
                                        <span
                                            class="text-[10px] text-zenith-300 font-bold uppercase mt-0.5">{{ $batch->received_at->format('d M Y, H:i') }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-8 h-8 rounded-lg bg-zenith-50 flex items-center justify-center text-zenith-400 font-bold text-[10px]">
                                            {{ substr($batch->supplier->name ?? 'NA', 0, 1) }}
                                        </div>
                                        <div class="flex flex-col">
                                            <span
                                                class="text-sm font-bold text-zenith-800">{{ $batch->supplier->name ?? 'Unknown Supplier' }}</span>
                                            <span
                                                class="text-[10px] text-zenith-400 font-bold uppercase tracking-tight">External
                                                Node</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span
                                        class="text-xs font-bold text-zenith-600 bg-zenith-50 px-2 py-1 rounded-md">{{ $batch->commodity_type }}</span>
                                </td>
                                <td>
                                    <div class="flex flex-col">
                                        <span
                                            class="text-sm font-black text-zenith-900">{{ number_format($batch->total_weight_kg, 2) }}
                                            KG</span>
                                        <span
                                            class="text-[10px] text-zenith-300 font-bold uppercase">{{ $batch->expected_bags }}
                                            Units</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <div class="w-12 h-1 bg-zenith-100 rounded-full overflow-hidden">
                                            <div class="h-full bg-zenith-500"
                                                style="width: {{ 100 - $batch->average_moisture }}%"></div>
                                        </div>
                                        <span
                                            class="text-[10px] font-black text-zenith-500 uppercase">{{ number_format($batch->average_moisture, 1) }}%
                                            M</span>
                                    </div>
                                </td>
                                <td>
                                    <span
                                        class="zenith-badge {{ $batch->status === 'accepted' ? 'bg-green-100 text-green-600' : 'bg-zenith-100 text-zenith-500' }}">
                                        {{ str_replace('_', ' ', $batch->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('logistics.batches.show', $batch->id) }}"
                                        class="zenith-button-outline !px-4 !py-2 text-[10px]">
                                        INSPECT
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-12 text-center text-zenith-300 font-bold italic">
                                    Universal search complete. No batch data found in current temporal window.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($batches instanceof \Illuminate\Pagination\LengthAwarePaginator && $batches->hasPages())
                <div class="p-6 border-t border-zenith-100 bg-zenith-50/20">
                    {{ $batches->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection