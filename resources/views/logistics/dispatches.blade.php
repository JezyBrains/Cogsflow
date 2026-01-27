@extends('layouts.app')

@section('title', 'Transit Nodes')
@section('page_title', 'Regional Distribution')

@section('content')
    <div class="space-y-8">
        <!-- Header Stream -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-display font-black text-zenith-900">Transit Nodes</h2>
                <p class="text-zenith-400 font-medium mt-1">Active distribution vectors and arrival confirmation</p>
            </div>
            <div class="flex gap-4">
                <a href="{{ route('logistics.dispatches.create') }}" class="zenith-button">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Activate Dispatch
                </a>
            </div>
        </div>

        <!-- Active Dispatches -->
        <div class="zenith-card">
            <div class="p-8 border-b border-zenith-100 flex items-center justify-between bg-zenith-50/30">
                <div>
                    <h3 class="text-xl font-display font-black text-zenith-900">Regional Distribution Feed</h3>
                    <p class="text-zenith-400 text-xs font-medium mt-1">Live tracking of commodity movement</p>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="zenith-table">
                    <thead>
                        <tr>
                            <th>Dispatch Identity</th>
                            <th>Vehicle & Vector</th>
                            <th>Commodity Link</th>
                            <th>Temporal Markers</th>
                            <th>Current Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dispatches as $dispatch)
                            <tr class="hover:bg-zenith-50/50 transition-colors">
                                <td>
                                    <div class="flex flex-col">
                                        <span
                                            class="text-xs font-black text-zenith-900 uppercase tracking-tight">{{ $dispatch->dispatch_number }}</span>
                                        <span class="text-[10px] text-zenith-300 font-bold uppercase mt-0.5">EST:
                                            {{ $dispatch->estimated_arrival->format('d M, H:i') }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-9 h-9 rounded-xl bg-zenith-500 flex items-center justify-center text-white shadow-zenith-sm shrink-0">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                    d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0zM13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0">
                                                </path>
                                            </svg>
                                        </div>
                                        <div class="flex flex-col">
                                            <span
                                                class="text-sm font-black text-zenith-800">{{ $dispatch->vehicle_reg_number }}</span>
                                            <span class="text-[10px] text-zenith-400 font-bold uppercase tracking-tight">TO:
                                                {{ $dispatch->destination }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($dispatch->batch)
                                        <div class="flex flex-col">
                                            <span
                                                class="text-xs font-bold text-zenith-600 bg-zenith-50 px-2 py-1 rounded-md inline-block self-start">{{ $dispatch->batch->commodity_type }}</span>
                                            <span
                                                class="text-[10px] text-zenith-300 font-bold uppercase mt-1">{{ $dispatch->batch->batch_number }}</span>
                                        </div>
                                    @else
                                        <span class="text-[10px] text-zenith-300 font-bold uppercase">Consolidated Load</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="flex flex-col">
                                        <div class="flex items-center gap-2">
                                            <div class="w-2 h-2 rounded-full bg-green-500"></div>
                                            <span
                                                class="text-[11px] font-black text-zenith-800 uppercase">{{ $dispatch->dispatched_at->format('H:i') }}</span>
                                        </div>
                                        <span class="text-[9px] text-zenith-400 font-bold uppercase mt-1">DEPARTED HQ</span>
                                    </div>
                                </td>
                                <td>
                                    <span
                                        class="zenith-badge {{ $dispatch->status === 'delivered' ? 'bg-green-100 text-green-600' : 'bg-blue-100 text-blue-600' }}">
                                        {{ strtoupper($dispatch->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="flex flex-col gap-2">
                                        @if($dispatch->batch_id)
                                            <a href="{{ route('logistics.batches.show', $dispatch->batch_id) }}"
                                                class="zenith-button-outline !px-4 !py-2 text-[10px] text-center">
                                                VIEW BATCH
                                            </a>
                                        @endif

                                        @if($dispatch->status !== 'delivered')
                                            <a href="{{ route('logistics.dispatches.inspect', $dispatch->id) }}"
                                                class="zenith-button-outline !px-4 !py-2 text-[10px] text-center">
                                                PHYSICAL INSPECTION
                                            </a>
                                            <form action="{{ route('logistics.dispatches.deliver', $dispatch->id) }}" method="POST"
                                                onsubmit="zenithConfirmAction(event, 'Operational Node Arrival', 'Authorize arrival confirmation and localized stock integration?')">
                                                @csrf
                                                <button type="submit" class="zenith-button !px-4 !py-2 text-[10px] w-full">
                                                    CONFIRM ARRIVAL
                                                </button>
                                            </form>
                                        @else
                                            <button
                                                class="zenith-button-outline !px-4 !py-2 text-[10px] opacity-50 pointer-events-none w-full">
                                                COMPLETED
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-12 text-center text-zenith-300 font-bold italic">
                                    Scanner active. No transit nodes detected in regional airspace.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($dispatches->hasPages())
                <div class="p-6 border-t border-zenith-100 bg-zenith-50/20">
                    {{ $dispatches->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection