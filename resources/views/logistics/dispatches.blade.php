@extends('layouts.app')

@section('title', 'Transit Nodes')
@section('page_title', 'Regional Distribution')

@section('content')
    <div class="space-y-8" x-data="{ 
                    showSwapModal: false, 
                    swapURL: '', 
                    vehicleReg: '', 
                    trailerNum: '', 
                    driverName: '', 
                    driverPhone: '',
                    driverIdType: '',
                    driverIdNumber: ''
                }">
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
                                        class="zenith-badge {{ $dispatch->status === 'delivered' ? 'bg-green-100 text-green-600' : 'bg-zenith-100 text-zenith-600' }}">
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
                                            <button type="button" @click="
                                                                                        showSwapModal = true; 
                                                                                        swapURL = '{{ route('logistics.dispatches.swap_vehicle', $dispatch->id) }}';
                                                                                        vehicleReg = '{{ $dispatch->vehicle_reg_number }}';
                                                                                        trailerNum = '{{ $dispatch->trailer_number }}';
                                                                                        driverName = '{{ $dispatch->driver_name }}';
                                                                                        driverPhone = '{{ $dispatch->driver_phone }}';
                                                                                        driverIdType = '{{ $dispatch->driver_id_type }}';
                                                                                        driverIdNumber = '{{ $dispatch->driver_id_number }}';
                                                                                    "
                                                class="zenith-button !bg-rose-600 !border-rose-600 !px-4 !py-2 text-[10px] w-full">
                                                EMERGENCY SWAP
                                            </button>
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

        <!-- Emergency Swap Modal -->
        <template x-if="showSwapModal">
            <div class="fixed inset-0 z-50 flex items-center justify-center p-6">
                <div class="absolute inset-0 bg-zenith-900/60 backdrop-blur-md" @click="showSwapModal = false"></div>

                <div class="zenith-card-elevated w-full max-w-xl p-10 relative z-10 bg-white shadow-2xl"
                    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100">

                    <div class="mb-10 text-center">
                        <div
                            class="w-16 h-16 bg-rose-50 text-rose-600 rounded-3xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-display font-black text-zenith-900 tracking-tight">Emergency Logistics
                            Mutation</h3>
                        <p class="text-zenith-400 text-xs font-bold uppercase mt-2">Authorized Vehicle & Personnel Swap</p>
                    </div>

                    <form :action="swapURL" method="POST" class="space-y-6">
                        @csrf
                        <div class="grid grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-zenith-400 uppercase tracking-widest ml-1">New
                                    Vehicle Reg</label>
                                <input type="text" name="vehicle_reg_number" x-model="vehicleReg" required
                                    class="zenith-input" placeholder="T 000 AAA">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-zenith-400 uppercase tracking-widest ml-1">New
                                    Trailer ID</label>
                                <input type="text" name="trailer_number" x-model="trailerNum" class="zenith-input"
                                    placeholder="TRL-000">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-zenith-400 uppercase tracking-widest ml-1">Relief
                                    Driver Name</label>
                                <input type="text" name="driver_name" x-model="driverName" required class="zenith-input"
                                    placeholder="Full Name">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-zenith-400 uppercase tracking-widest ml-1">Driver
                                    Comms</label>
                                <input type="text" name="driver_phone" x-model="driverPhone" class="zenith-input"
                                    placeholder="+255 ...">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-zenith-400 uppercase tracking-widest ml-1">ID
                                    Selection</label>
                                <select name="driver_id_type" x-model="driverIdType"
                                    class="zenith-input w-full appearance-none" required>
                                    <option value="National ID">National ID (NIDA)</option>
                                    <option value="Driving License">Driving License</option>
                                    <option value="Voter ID">Voter ID</option>
                                    <option value="Passport">Passport</option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-zenith-400 uppercase tracking-widest ml-1">ID
                                    Number</label>
                                <input type="text" name="driver_id_number" x-model="driverIdNumber" class="zenith-input"
                                    placeholder="ID Number" required>
                            </div>
                        </div>

                        <div class="pt-6 flex gap-4">
                            <button type="button" @click="showSwapModal = false" class="flex-1 zenith-button-outline">Abort
                                Protocol</button>
                            <button type="submit"
                                class="flex-[2] zenith-button !bg-rose-600 !border-rose-600 shadow-rose-200">
                                Authorize Emergency Swap
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </template>
    </div>
@endsection