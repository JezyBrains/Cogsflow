@extends('layouts.app')

@section('title', 'Supplier Directory')
@section('page_title', 'Supplier Network')

@section('content')
    <div class="space-y-10" x-data="{ showSupplierModal: false }">
        
        <!-- Zenith Section Header -->
        <div class="flex items-end justify-between">
            <div>
                <span class="text-[10px] font-bold text-zenith-400 uppercase tracking-[0.2em] block mb-2">Network intelligence</span>
                <h2 class="text-4xl font-display font-black text-zenith-900 tracking-tight">Supplier Ecosystem</h2>
            </div>
            <button @click="showSupplierModal = true" class="zenith-button">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                <span>Register New Node</span>
            </button>
        </div>

        <!-- Metric Stream -->
        <div class="flex items-center gap-6 mb-4">
            <div class="zenith-card px-8 py-5 flex items-center gap-4 bg-white">
                <span class="text-[10px] font-bold text-zenith-400 uppercase tracking-widest">Active Suppliers</span>
                <span class="text-2xl font-display font-black text-zenith-900 leading-none">{{ $suppliers->total() }}</span>
            </div>
        </div>

        <!-- Suppliers Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($suppliers as $supplier)
                <div class="zenith-card p-10 bg-white border-zenith-100 hover:border-zenith-500/30 transition-all group relative overflow-hidden flex flex-col">
                    <div class="flex items-center justify-between mb-8">
                        <div class="w-16 h-16 rounded-2xl bg-zenith-50 flex items-center justify-center text-2xl font-display font-black text-zenith-400 group-hover:bg-zenith-500 group-hover:text-white transition-all shadow-zenith-sm border border-zenith-100">
                            {{ substr($supplier->name, 0, 1) }}
                        </div>
                        <div class="text-right">
                            <p class="text-[9px] font-bold text-zenith-400 uppercase tracking-widest mb-1">Status Protocol</p>
                            <span class="zenith-badge bg-emerald-50 text-emerald-600">Active Node</span>
                        </div>
                    </div>
                    
                    <div class="flex-1">
                        <h3 class="text-2xl font-display font-black text-zenith-900 mb-2 tracking-tight">{{ $supplier->name }}</h3>
                        <p class="text-xs text-zenith-400 mb-8 font-medium leading-relaxed">{{ $supplier->address ?? 'Enterprise Secure Address' }}</p>
                    </div>
                    
                    <div class="space-y-4 pt-6 border-t border-zenith-100">
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-bold text-zenith-400 uppercase tracking-widest">Node Code</span>
                            <span class="text-[11px] font-bold text-zenith-800 font-mono">{{ $supplier->code }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-bold text-zenith-400 uppercase tracking-widest">Performance</span>
                            <div class="flex gap-1">
                                @for($i=0; $i<5; $i++)
                                    <div class="w-2.5 h-3.5 rounded-md {{ $i < round($supplier->rating) ? 'bg-zenith-500' : 'bg-zenith-100' }}"></div>
                                @endfor
                            </div>
                        </div>
                    </div>

                    <div class="mt-8">
                        <a href="{{ route('procurement.suppliers.show', $supplier->id) }}" class="zenith-button-outline w-full py-3 text-[10px] uppercase tracking-widest text-center">Analyze Node Metrics</a>
                    </div>
                </div>
            @endforeach
        </div>

        @if($suppliers->hasPages())
            <div class="mt-12">
                {{ $suppliers->links() }}
            </div>
        @endif

        <!-- Create Supplier Modal -->
        <template x-if="showSupplierModal">
            <div class="fixed inset-0 z-50 flex items-center justify-center p-6 sm:p-12">
                <div class="absolute inset-0 bg-zenith-900/40 backdrop-blur-sm" @click="showSupplierModal = false"></div>
                
                <div class="zenith-card-elevated w-full max-w-xl p-10 sm:p-14 relative z-10 bg-white"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100">
                     
                    <div class="mb-10">
                        <span class="text-[10px] font-bold text-zenith-500 uppercase tracking-[0.3em] block mb-2">Protocol: Network Registration</span>
                        <h3 class="text-3xl font-display font-black text-zenith-900 tracking-tight">Expand Ecosystem</h3>
                    </div>
                    
                    <form action="{{ route('procurement.suppliers.store') }}" method="POST" class="space-y-6">
                        @csrf
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-zenith-400 uppercase tracking-widest ml-1">Entity Identity</label>
                            <input type="text" name="name" required class="zenith-input" placeholder="e.g. Mwanza Agri-Node">
                        </div>
                        
                        <!-- Unique Node Code is now auto-generated -->

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-zenith-400 uppercase tracking-widest ml-1">Communication Line</label>
                                <input type="text" name="phone" placeholder="+255..." class="zenith-input">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-zenith-400 uppercase tracking-widest ml-1">Digital Node (Email)</label>
                                <input type="email" name="email" class="zenith-input" placeholder="node@enterprise.io">
                            </div>
                        </div>

                        <div class="pt-6 flex flex-col sm:flex-row gap-4">
                            <button type="button" @click="showSupplierModal = false"
                                class="flex-1 zenith-button-outline">Abort</button>
                            <button type="submit" class="flex-[2] zenith-button">
                                <span>Execute Registration</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </template>
    </div>
@endsection
