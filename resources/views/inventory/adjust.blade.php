@extends('layouts.app')

@section('title', 'Stock Matrix Adjustment')
@section('page_title', 'Operational Intervention')

@section('content')
    <div class="max-w-4xl mx-auto space-y-8">
        <!-- Header Protocol -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-display font-black text-zenith-900 uppercase tracking-tight">Stock Adjustment</h2>
                <p class="text-zenith-400 font-medium mt-1">Manual stock correction and loss management protocol</p>
            </div>
            <a href="{{ route('inventory.index') }}" class="zenith-button-outline">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                    </path>
                </svg>
                Cancel Intervention
            </a>
        </div>

        <!-- Adjustment Terminal -->
        <div class="zenith-card overflow-hidden">
            <div class="p-8 border-b border-zenith-100 bg-zenith-50/30 flex items-center justify-between">
                <h3 class="text-lg font-display font-black text-zenith-900 italic tracking-tight uppercase">Adjustment
                    Parameters</h3>
                <span class="text-[10px] font-black bg-zenith-900 text-white px-3 py-1 rounded-full tracking-widest">ADMIN
                    PRIVILEGE</span>
            </div>

            <form action="{{ route('inventory.adjust') }}" method="POST" class="p-8 space-y-8"
                onsubmit="zenithConfirmAction(event, 'Authorize Intervention', 'Confirm manual stock correction? This override will be recorded in the immutable audit ledger.')">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Grain Selection -->
                    <div class="space-y-3">
                        <label class="block text-[10px] font-black uppercase text-zenith-400 tracking-widest">Target Stock
                            Vector</label>
                        <select name="grain_type" required
                            class="w-full bg-zenith-50 border-2 border-zenith-100 rounded-2xl px-5 py-4 text-sm font-black text-zenith-900 focus:border-zenith-500 focus:outline-none transition-all appearance-none cursor-pointer">
                            <option value="">SELECT COMMODITY...</option>
                            @foreach($inventory as $item)
                                <option value="{{ $item->grain_type }}">{{ strtoupper($item->grain_type) }} (Current:
                                    {{ number_format($item->current_stock_mt, 0) }} KG)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Adjustment Type -->
                    <div class="space-y-3">
                        <label class="block text-[10px] font-black uppercase text-zenith-400 tracking-widest">Correction
                            Vector</label>
                        <select name="adjustment_type" required
                            class="w-full bg-zenith-50 border-2 border-zenith-100 rounded-2xl px-5 py-4 text-sm font-black text-zenith-900 focus:border-zenith-500 focus:outline-none transition-all appearance-none cursor-pointer">
                            <option value="Stock Correction">SYSTEM CORRECTION (+)</option>
                            <option value="Damage/Loss">DAMAGE / LOSS (-)</option>
                            <option value="Stock Out">MANUAL REMOVAL (-)</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Quantity -->
                    <div class="space-y-3">
                        <label class="block text-[10px] font-black uppercase text-zenith-400 tracking-widest">Payload Weight
                            (kg)</label>
                        <div class="relative">
                            <input type="number" name="quantity" step="0.01" required placeholder="0.00"
                                class="w-full bg-zenith-50 border-2 border-zenith-100 rounded-2xl px-5 py-4 text-xl font-black text-zenith-900 focus:border-zenith-500 focus:outline-none transition-all placeholder:text-zenith-200">
                            <span
                                class="absolute right-5 top-1/2 -translate-y-1/2 text-xs font-black text-zenith-300">KG</span>
                        </div>
                    </div>

                    <!-- Reference -->
                    <div class="space-y-3">
                        <label class="block text-[10px] font-black uppercase text-zenith-400 tracking-widest">Reference
                            Identity</label>
                        <input type="text" name="reference" placeholder="LPO / BATCH / AUDIT ID"
                            class="w-full bg-zenith-50 border-2 border-zenith-100 rounded-2xl px-5 py-4 text-sm font-black text-zenith-900 focus:border-zenith-500 focus:outline-none transition-all placeholder:text-zenith-200 uppercase tracking-tight">
                    </div>
                </div>

                <!-- Reason -->
                <div class="space-y-3">
                    <label class="block text-[10px] font-black uppercase text-zenith-400 tracking-widest">Reason for
                        Intervention</label>
                    <textarea name="reason" rows="4" required
                        placeholder="Describe why this manual intervention is required for audit alignment..."
                        class="w-full bg-zenith-50 border-2 border-zenith-100 rounded-2xl px-5 py-4 text-sm font-medium text-zenith-900 focus:border-zenith-500 focus:outline-none transition-all placeholder:text-zenith-200"></textarea>
                </div>

                <!-- Caution Notice -->
                <div class="p-6 bg-red-50 rounded-3xl border border-red-100 flex gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-red-100 flex items-center justify-center text-red-600 shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-black text-red-900 uppercase tracking-tight">Systemic Audit Warning</h4>
                        <p class="text-[11px] text-red-700 font-medium mt-1 leading-relaxed">Manual adjustments bypass
                            automated workflow vectors. Every byte of this transaction is logged in the permanent audit
                            ledger and linked to your administrative identity.</p>
                    </div>
                </div>

                <!-- Action -->
                <div class="pt-4">
                    <button type="submit"
                        class="w-full zenith-button !py-5 text-sm tracking-[0.2em] font-black uppercase shadow-zenith-lg hover:scale-[1.01] active:scale-[0.99] transition-transform">
                        AUTHORIZE ADJUSTMENT SEQUENCE
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection