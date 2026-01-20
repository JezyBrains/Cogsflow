@extends('layouts.app')

@section('title', $supplier->name . ' | Partner Intelligence')
@section('page_title', 'Supplier 360° Profile')

@section('content')
    <div class="space-y-8">
        <!-- Partner Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center gap-6">
                <div
                    class="w-20 h-20 rounded-3xl bg-zenith-900 text-white flex items-center justify-center text-3xl font-black shadow-zenith-lg uppercase">
                    {{ substr($supplier->name, 0, 2) }}
                </div>
                <div>
                    <h2 class="text-4xl font-display font-black text-zenith-900 tracking-tight uppercase">
                        {{ $supplier->name }}</h2>
                    <div class="flex items-center gap-3 mt-2">
                        <span
                            class="text-xs font-black bg-zenith-100 text-zenith-600 px-3 py-1 rounded-full tracking-widest uppercase">ID:
                            {{ $supplier->code }}</span>
                        <span
                            class="text-xs font-black {{ $supplier->is_active ? 'text-green-500' : 'text-red-500' }} uppercase italic tracking-tighter">
                            {{ $supplier->is_active ? 'Operational' : 'Deactivated' }}
                        </span>
                        <div class="flex items-center gap-1 ml-2">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-3 h-3 {{ $i <= $supplier->rating ? 'text-amber-400' : 'text-zenith-100' }}"
                                    fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex gap-4">
                <a href="{{ route('procurement.suppliers') }}" class="zenith-button-outline">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Return to Directory
                </a>
                <button class="zenith-button">
                    EDIT PARTNER GRID
                </button>
            </div>
        </div>

        <!-- Intelligence Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="zenith-card p-6 bg-zenith-50/50">
                <h4 class="text-[10px] font-black text-zenith-400 uppercase tracking-widest mb-3">Issued Orders</h4>
                <div class="flex items-end justify-between">
                    <span
                        class="text-3xl font-display font-black text-zenith-900">{{ $supplier->purchaseOrders->count() }}</span>
                    <span class="text-[10px] font-bold text-green-600 bg-green-50 px-2 py-1 rounded-md uppercase">Active
                        Feed</span>
                </div>
            </div>
            <div class="zenith-card p-6 bg-zenith-50/50">
                <h4 class="text-[10px] font-black text-zenith-400 uppercase tracking-widest mb-3">Batches Received</h4>
                <div class="flex items-end justify-between">
                    <span class="text-3xl font-display font-black text-zenith-900">{{ $supplier->batches->count() }}</span>
                    <span
                        class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded-md uppercase">{{ number_format($supplier->batches->sum('total_weight_kg'), 0) }}
                        KG Total</span>
                </div>
            </div>
            <div class="zenith-card p-6 bg-zenith-50/50">
                <h4 class="text-[10px] font-black text-zenith-400 uppercase tracking-widest mb-3">Aggregate Value</h4>
                <div class="flex items-end justify-between">
                    <span class="text-2xl font-display font-black text-zenith-900">TSH
                        {{ number_format($supplier->purchaseOrders->sum(fn($po) => $po->total_quantity_kg * $po->unit_price) / 1000000, 2) }}M</span>
                    <span class="text-[10px] font-bold text-amber-600 bg-amber-50 px-2 py-1 rounded-md uppercase">LTV
                        Estimate</span>
                </div>
            </div>
            <div class="zenith-card p-6 bg-zenith-900 text-white">
                <h4 class="text-[10px] font-black text-zenith-400 uppercase tracking-widest mb-3">Fulfillment Score</h4>
                <div class="flex items-end justify-between">
                    <span class="text-3xl font-display font-black">94%</span>
                    <div class="w-12 h-1.5 bg-white/10 rounded-full overflow-hidden mb-2">
                        <div class="w-[94%] h-full bg-zenith-500"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Workspace -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Relationship Timeline -->
            <div class="lg:col-span-2 space-y-6">
                <div class="zenith-card">
                    <div class="p-6 border-b border-zenith-100 bg-zenith-50/20">
                        <h3 class="text-lg font-display font-black text-zenith-900 italic tracking-tight">Active
                            Relationship Stream</h3>
                    </div>
                    <div class="p-6">
                        <div
                            class="space-y-8 relative before:absolute before:inset-0 before:ml-5 before:-translate-x-px before:h-full before:w-0.5 before:bg-gradient-to-b before:from-zenith-100 before:via-zenith-50 before:to-transparent">
                            @forelse($supplier->purchaseOrders->sortByDesc('created_at')->take(5) as $po)
                                <div class="relative flex items-center justify-between gap-6 group">
                                    <div class="flex items-center gap-6">
                                        <div
                                            class="w-10 h-10 rounded-full bg-white border-4 border-zenith-50 flex items-center justify-center text-zenith-500 shadow-zenith-sm z-10">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                </path>
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 class="text-xs font-black text-zenith-900 uppercase">Purchase Order Issued:
                                                {{ $po->commodity_type }}</h4>
                                            <p class="text-[10px] text-zenith-400 font-bold uppercase mt-1">Ref:
                                                PO-{{ str_pad($po->id, 5, '0', STR_PAD_LEFT) }} •
                                                {{ $po->created_at->format('d M, Y') }}</p>
                                        </div>
                                    </div>
                                    <span
                                        class="text-xs font-black text-zenith-600 bg-zenith-50 px-3 py-1 rounded-full uppercase">{{ number_format($po->total_quantity_kg, 0) }}
                                        KG</span>
                                </div>
                            @empty
                                <div class="text-center py-8 text-zenith-300 italic font-bold uppercase text-xs">No active
                                    stream threads detected.</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Batches Feed -->
                <div class="zenith-card overflow-hidden">
                    <div class="p-6 border-b border-zenith-100 bg-zenith-50/20">
                        <h3 class="text-lg font-display font-black text-zenith-900 italic tracking-tight">Supply Chain
                            Entries</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="zenith-table">
                            <thead>
                                <tr>
                                    <th>Batch Identity</th>
                                    <th>Commodity</th>
                                    <th>Quantity</th>
                                    <th>Grade</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($supplier->batches->sortByDesc('created_at')->take(10) as $batch)
                                    <tr>
                                        <td>
                                            <div class="flex flex-col">
                                                <span
                                                    class="text-xs font-black text-zenith-900">{{ $batch->batch_number }}</span>
                                                <span
                                                    class="text-[9px] text-zenith-400 font-bold uppercase">{{ $batch->created_at->format('d/m/Y') }}</span>
                                            </div>
                                        </td>
                                        <td><span
                                                class="text-xs font-bold text-zenith-700 bg-zenith-50 px-2 py-1 rounded-md">{{ $batch->commodity_type }}</span>
                                        </td>
                                        <td><span
                                                class="text-xs font-black text-zenith-900">{{ number_format($batch->total_weight_kg, 2) }}
                                                KG</span></td>
                                        <td><span
                                                class="text-[10px] font-black text-zenith-500 uppercase">{{ $batch->quality_grade ?? 'Pending' }}</span>
                                        </td>
                                        <td>
                                            <span
                                                class="zenith-badge {{ $batch->status === 'accepted' ? 'bg-green-100 text-green-600' : 'bg-blue-100 text-blue-600' }}">
                                                {{ strtoupper($batch->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Sidebar Intelligence -->
            <div class="space-y-6">
                <!-- Contact Identity -->
                <div class="zenith-card p-6">
                    <h3 class="text-xs font-black text-zenith-400 uppercase tracking-widest mb-6">Partner Identity</h3>
                    <div class="space-y-6">
                        <div class="flex items-start gap-4">
                            <div
                                class="w-10 h-10 rounded-xl bg-zenith-50 flex items-center justify-center text-zenith-500 shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-zenith-300 uppercase tracking-tighter">Contact
                                    Representative</p>
                                <p class="text-sm font-black text-zenith-900 uppercase">
                                    {{ $supplier->contact_person ?? 'UNSPECIFIED' }}</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div
                                class="w-10 h-10 rounded-xl bg-zenith-50 flex items-center justify-center text-zenith-500 shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-zenith-300 uppercase tracking-tighter">Communication
                                    Vector</p>
                                <p class="text-sm font-black text-zenith-900 uppercase">
                                    {{ $supplier->phone ?? 'NO ACTIVE PHONE' }}</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl bg-zenith-50 flex items-center justify-center text-zenith-500 shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-zenith-300 uppercase tracking-tighter">Geographic
                                    Origin</p>
                                <p class="text-sm font-black text-zenith-900 uppercase leading-snug">
                                    {{ $supplier->address ?? 'UNDISCLOSED' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Document Vault Integration -->
                <x-attachment-widget :attachable_type="'App\Models\Supplier'" :attachable_id="$supplier->id"
                    :attachments="$supplier->attachments" />
            </div>
        </div>
    </div>
@endsection