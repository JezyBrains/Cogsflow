@extends('layouts.app')

@section('title', 'Stock Hub')
@section('page_title', 'Inventory Intelligence')

@section('content')
    <div class="space-y-8">
        <!-- Header Stream -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-display font-black text-zenith-900">Stock Tiles</h2>
                <p class="text-zenith-400 font-medium mt-1">Real-time commodity reserves across all nodes</p>
            </div>
            <div class="flex gap-4">
                <button onclick="document.getElementById('adjustmentModal').classList.remove('hidden')"
                    class="zenith-button">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Manual Correction
                </button>
            </div>
        </div>

        <!-- Stock Tiles Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($inventory as $item)
                <div class="zenith-card p-8 group">
                    <div class="flex items-start justify-between">
                        <div
                            class="w-12 h-12 rounded-2xl bg-zenith-50 flex items-center justify-center text-zenith-500 group-hover:bg-zenith-500 group-hover:text-white transition-all duration-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                        <span
                            class="zenith-badge {{ $item->current_stock_mt > $item->minimum_level_mt ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                            {{ $item->current_stock_mt > $item->minimum_level_mt ? 'Stable' : 'Low Stock' }}
                        </span>
                    </div>

                    <div class="mt-6">
                        <h3 class="text-zenith-400 text-xs font-bold uppercase tracking-widest">{{ $item->grain_type }}</h3>
                        <div class="mt-2 flex items-baseline gap-2">
                            <span
                                class="text-4xl font-display font-black text-zenith-900">{{ number_format($item->current_stock_mt, 0) }}</span>
                            <span class="text-zenith-400 font-bold text-sm">KG</span>
                        </div>
                    </div>

                    <div class="mt-6 pt-6 border-t border-zenith-50 flex items-center justify-between">
                        <div class="text-[10px] font-bold text-zenith-300 uppercase tracking-tight">Code: {{ $item->item_code }}
                        </div>
                        <div class="text-[10px] font-bold text-zenith-500 uppercase tracking-tight">{{ $item->location }}</div>
                    </div>
                </div>
            @empty
                <div class="col-span-full zenith-card p-12 text-center">
                    <div
                        class="w-20 h-20 bg-zenith-50 rounded-full flex items-center justify-center mx-auto mb-6 text-zenith-200">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-display font-bold text-zenith-900">No Inventory Found</h3>
                    <p class="text-zenith-400 mt-2">Activate stock tracking by processing deliveries or manual entry.</p>
                </div>
            @endforelse
        </div>

        <!-- Inventory Ledger -->
        <div class="zenith-card">
            <div class="p-8 border-b border-zenith-100 flex items-center justify-between bg-zenith-50/30">
                <div>
                    <h3 class="text-xl font-display font-black text-zenith-900">Stock Ledger</h3>
                    <p class="text-zenith-400 text-xs font-medium mt-1">Audit trail of all inventory movements</p>
                </div>
                <button class="zenith-button-outline !px-4 !py-2 text-xs">
                    Export Data
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="zenith-table">
                    <thead>
                        <tr>
                            <th>Item Identity</th>
                            <th>Commodity</th>
                            <th>Stock Level (KG)</th>
                            <th>Safety Level</th>
                            <th>Location</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($inventory as $item)
                            <tr class="hover:bg-zenith-50/50 transition-colors">
                                <td>
                                    <span class="text-xs font-black text-zenith-900 uppercase">{{ $item->item_code }}</span>
                                </td>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-8 h-8 rounded-lg bg-zenith-100 flex items-center justify-center text-zenith-500 font-bold text-[10px]">
                                            {{ substr($item->grain_type, 0, 2) }}
                                        </div>
                                        <span class="text-sm font-bold text-zenith-800">{{ $item->grain_type }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span
                                        class="text-sm font-black text-zenith-900">{{ number_format($item->current_stock_mt, 0) }}</span>
                                </td>
                                <td>
                                    <span
                                        class="text-xs font-bold text-zenith-400">{{ number_format($item->minimum_level_mt, 0) }}
                                        KG</span>
                                </td>
                                <td>
                                    <span class="text-xs font-bold text-zenith-500">{{ $item->location }}</span>
                                </td>
                                <td>
                                    <span class="zenith-badge bg-zenith-100 text-zenith-600">
                                        {{ $item->status }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Adjustment Modal -->
    <div id="adjustmentModal"
        class="hidden fixed inset-0 z-50 flex items-center justify-center bg-zenith-900/40 backdrop-blur-sm">
        <div class="zenith-card-elevated w-full max-w-lg p-10 m-4">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-2xl font-display font-black text-zenith-900">Manual Stock Correction</h3>
                <button onclick="document.getElementById('adjustmentModal').classList.add('hidden')"
                    class="text-zenith-300 hover:text-zenith-900 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <form action="{{ route('inventory.adjust') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-[10px] font-black text-zenith-400 uppercase tracking-widest mb-3">Commodity
                        Type</label>
                    <select name="grain_type" class="zenith-input w-full appearance-none">
                        <option value="White Maize">White Maize</option>
                        <option value="Yellow Soya">Yellow Soya</option>
                        <option value="Sorghum">Sorghum</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[10px] font-black text-zenith-400 uppercase tracking-widest mb-3">Quantity
                            (KG)</label>
                        <input type="number" step="0.1" name="quantity_mt" class="zenith-input" placeholder="0.0">
                    </div>
                    <div>
                        <label
                            class="block text-[10px] font-black text-zenith-400 uppercase tracking-widest mb-3">Adjustment
                            Type</label>
                        <select name="type" class="zenith-input w-full appearance-none">
                            <option value="Stock In">Stock In (+)</option>
                            <option value="Stock Out">Stock Out (-)</option>
                        </select>
                    </div>
                </div>

                <div class="flex gap-4 pt-4">
                    <button type="button" onclick="document.getElementById('adjustmentModal').classList.add('hidden')"
                        class="zenith-button-outline flex-1">
                        Cancel
                    </button>
                    <button type="submit" class="zenith-button flex-1">
                        Apply Correction
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection