@extends('layouts.app')

@section('title', 'Purchase Terminal')
@section('page_title', 'Procurement Hub')

@section('content')
    <div class="space-y-10" x-data="{ showPOModal: false }">

        <!-- Zenith Section Header -->
        <div class="flex items-end justify-between">
            <div>
                <span class="text-[10px] font-bold text-zenith-400 uppercase tracking-[0.2em] block mb-2">Transaction Management</span>
                <h2 class="text-4xl font-display font-black text-zenith-900 tracking-tight">Purchase Orders</h2>
            </div>
            <button @click="showPOModal = true" class="zenith-button">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>Initialize Purchase Protocol</span>
            </button>
        </div>

        <!-- Metric Stream -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="zenith-card p-8 bg-white border-zenith-200">
                <p class="text-[10px] font-bold text-zenith-400 uppercase tracking-widest mb-4">Cumulative Volume</p>
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-3xl font-display font-black text-zenith-900 tracking-tight">{{ number_format($totalVolumeTons, 1) }} <span class="text-xs text-zenith-400 uppercase">MT</span></h3>
                        <p class="text-[10px] font-bold text-zenith-400 mt-1 uppercase">{{ number_format($totalVolumeKg) }} KG</p>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-zenith-50 flex items-center justify-center text-zenith-500 shadow-zenith-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Catalog Ledger -->
        <div class="zenith-card shadow-zenith-md">
            <div class="overflow-x-auto scrollbar-hide">
                <table class="zenith-table">
                    <thead>
                        <tr>
                            <th class="pl-10">Reference ID</th>
                            <th>Authorized Supplier</th>
                            <th>Volume & Node</th>
                            <th>Protocol Status</th>
                            <th>Dispatched</th>
                            <th>Remaining</th>
                            <th class="text-right pr-10">Verification</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pos as $po)
                            <tr class="hover:bg-zenith-50 transition-colors group">
                                <td class="pl-10 py-8">
                                    <p class="text-sm font-display font-black text-zenith-900 leading-tight">{{ $po->po_number }}</p>
                                    <p class="text-[10px] font-bold text-zenith-400 uppercase tracking-tight mt-1">{{ $po->created_at->format('d M, Y') }}</p>
                                </td>
                                <td class="py-8">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-xl bg-zenith-100 flex items-center justify-center text-xs font-bold text-zenith-600 border border-zenith-200 group-hover:bg-zenith-500 group-hover:text-white transition-all">
                                            {{ substr($po->supplier->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-zenith-800 leading-none">{{ $po->supplier->name }}</p>
                                            <p class="text-[9px] text-zenith-400 font-bold uppercase tracking-widest mt-1.5">Node: {{ $po->supplier->code }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-8">
                                    <p class="text-sm font-bold text-zenith-900">
                                        {{ number_format($po->total_quantity_kg) }} <span class="text-[10px] text-zenith-400 font-bold uppercase ml-0.5">KG</span>
                                    </p>
                                    <p class="text-[10px] text-zenith-500 font-bold uppercase tracking-widest mt-1">
                                        {{ $po->commodity_type }}
                                    </p>
                                </td>
                                <td class="py-8">
                                    @if($po->status === 'issued')
                                        <span class="zenith-badge bg-emerald-50 text-emerald-600 border border-emerald-100 italic">Protocol: Authorized</span>
                                    @else
                                        <span class="zenith-badge bg-zenith-50 text-zenith-400 border border-zenith-100">{{ $po->status }}</span>
                                    @endif
                                </td>
                                <td class="py-8">
                                    <p class="text-sm font-black text-green-600">
                                        {{ number_format($po->supplied_quantity_kg) }} <span class="text-[9px] uppercase">KG</span>
                                    </p>
                                </td>
                                <td class="py-8">
                                    <p class="text-sm font-black text-red-500">
                                        {{ number_format($po->remaining_quantity_kg) }} <span class="text-[9px] uppercase">KG</span>
                                    </p>
                                </td>
                                <td class="text-right pr-10 py-8">
                                    <a href="{{ route('procurement.orders.show', $po->id) }}" class="zenith-button-outline px-4 py-2.5 rounded-xl text-[10px] uppercase tracking-widest bg-slate-50 border-slate-200 text-slate-500 hover:bg-zenith-500 hover:text-white hover:border-zenith-500 transition-all inline-block">
                                        Inspect
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            @if($pos->hasPages())
                <div class="px-10 py-8 bg-zenith-50/50 border-t border-zenith-100">
                    {{ $pos->links() }}
                </div>
            @endif
        </div>

        <!-- Zenith Intelligence Modal -->
        <template x-if="showPOModal">
            <div class="fixed inset-0 z-50 flex items-center justify-center p-6 sm:p-12">
                <div class="absolute inset-0 bg-zenith-900/40 backdrop-blur-sm" @click="showPOModal = false"></div>
                
                <div class="zenith-card-elevated w-full max-w-2xl p-10 sm:p-14 relative z-10 bg-white" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100">
                    
                    <div class="mb-10">
                        <span class="text-[10px] font-bold text-zenith-500 uppercase tracking-[0.3em] block mb-2">Protocol: Purchase Initiation</span>
                        <h3 class="text-3xl font-display font-black text-zenith-900 tracking-tight">Issue Smart Command</h3>
                    </div>

                    <form action="{{ route('procurement.orders.store') }}" method="POST" class="space-y-8">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-zenith-400 uppercase tracking-widest ml-1">Supplier Node</label>
                                <select name="supplier_id" required class="zenith-input appearance-none cursor-pointer">
                                    <option value="">Select Target...</option>
                                    @foreach(\App\Models\Supplier::all() as $supplier)
                                        <option value="{{ $supplier->id }}">{{ $supplier->name }} ({{ $supplier->code }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-zenith-400 uppercase tracking-widest ml-1">Commodity Vector</label>
                                <input type="text" name="commodity_type" placeholder="e.g. Maize, Soya" required class="zenith-input">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-zenith-400 uppercase tracking-widest ml-1">Payload Volume (KG)</label>
                                <input type="number" name="total_quantity_kg" step="0.01" required class="zenith-input" placeholder="0.00">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-zenith-400 uppercase tracking-widest ml-1">Unit Valuation (TZS)</label>
                                <input type="number" name="unit_price" step="0.01" required class="zenith-input" placeholder="0.00">
                            </div>
                        </div>

                        <div class="pt-6 flex flex-col sm:flex-row gap-4">
                            <button type="button" @click="showPOModal = false"
                                class="flex-1 zenith-button-outline">Abort Protocol</button>
                            <button type="submit" class="flex-[2] zenith-button">
                                <span>Execute & Publish PO</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </template>
    </div>
@endsection