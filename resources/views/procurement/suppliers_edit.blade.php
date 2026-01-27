@extends('layouts.app')

@section('title', 'Mutate Partner Data | ' . $supplier->name)
@section('page_title', 'Supplier Configuration')

@section('content')
    <div class="max-w-4xl mx-auto space-y-10">
        <!-- Breadcrumb & Header -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('procurement.suppliers.show', $supplier->id) }}"
                    class="w-10 h-10 rounded-xl bg-zenith-50 flex items-center justify-center text-zenith-500 hover:bg-zenith-900 hover:text-white transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <div>
                    <span class="text-[10px] font-bold text-zenith-400 uppercase tracking-[0.2em] block mb-1">Entity
                        Mutation Control</span>
                    <h2 class="text-3xl font-display font-black text-zenith-900 tracking-tight uppercase italic">Edit
                        Partner Grid</h2>
                </div>
            </div>
            <div class="hidden md:block">
                <span
                    class="text-[10px] font-black bg-zenith-900 text-white px-3 py-1.5 rounded-lg uppercase italic tracking-tighter">Current:
                    {{ $supplier->code }}</span>
            </div>
        </div>

        <!-- Configuration Terminal -->
        <div class="zenith-card-elevated bg-white p-10 md:p-14 relative overflow-hidden">
            <!-- Decorative Accent -->
            <div class="absolute top-0 right-0 w-32 h-32 bg-zenith-50 rounded-bl-full -mr-16 -mt-16 opacity-50"></div>

            <form action="{{ route('procurement.suppliers.update', $supplier->id) }}" method="POST"
                class="space-y-10 relative z-10">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <!-- Primary Identity -->
                    <div class="space-y-6">
                        <h3 class="text-xs font-black text-zenith-300 uppercase tracking-[0.3em] flex items-center gap-2">
                            <span class="w-1.5 h-1.5 bg-zenith-500 rounded-full"></span>
                            Primary Identity
                        </h3>

                        <div class="space-y-2">
                            <label
                                class="text-[10px] font-bold text-zenith-500 uppercase tracking-widest ml-1">Company/Entity
                                Name</label>
                            <input type="text" name="name" value="{{ old('name', $supplier->name) }}" required
                                class="zenith-input" placeholder="Enter formal entity name...">
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-zenith-500 uppercase tracking-widest ml-1">Contact
                                Officer</label>
                            <input type="text" name="contact_person"
                                value="{{ old('contact_person', $supplier->contact_person) }}" class="zenith-input"
                                placeholder="Primary liaison name...">
                        </div>
                    </div>

                    <!-- Communication Vectors -->
                    <div class="space-y-6">
                        <h3 class="text-xs font-black text-zenith-300 uppercase tracking-[0.3em] flex items-center gap-2">
                            <span class="w-1.5 h-1.5 bg-zenith-500 rounded-full"></span>
                            Communication Vectors
                        </h3>

                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-zenith-500 uppercase tracking-widest ml-1">Phone
                                Line</label>
                            <input type="text" name="phone" value="{{ old('phone', $supplier->phone) }}"
                                class="zenith-input" placeholder="+255 ...">
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-zenith-500 uppercase tracking-widest ml-1">Digital
                                Address (Email)</label>
                            <input type="email" name="email" value="{{ old('email', $supplier->email) }}"
                                class="zenith-input" placeholder="partner@domain.com">
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-10 items-start">
                    <!-- Geographic Pivot -->
                    <div class="md:col-span-2 space-y-2">
                        <label
                            class="text-[10px] font-bold text-zenith-500 uppercase tracking-widest ml-1">Physical/Geographic
                            Origin</label>
                        <textarea name="address" rows="3" class="zenith-input resize-none"
                            placeholder="HQ Address or key operational node...">{{ old('address', $supplier->address) }}</textarea>
                    </div>

                    <!-- Operational State -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-zenith-500 uppercase tracking-widest ml-1">Operational
                            State</label>
                        <select name="is_active" class="zenith-input appearance-none bg-white">
                            <option value="1" {{ old('is_active', $supplier->is_active) ? 'selected' : '' }}>ACTIVE /
                                OPERATIONAL</option>
                            <option value="0" {{ !old('is_active', $supplier->is_active) ? 'selected' : '' }}>DEACTIVATED /
                                ON HOLD</option>
                        </select>
                        <p class="text-[9px] text-zenith-400 font-bold uppercase mt-2 italic px-1 leading-relaxed">
                            Deactivated partners are excluded from new protocol initiations.</p>
                    </div>
                </div>

                <!-- Action Matrix -->
                <div class="pt-10 flex flex-col md:flex-row gap-6 border-t border-zenith-50">
                    <a href="{{ route('procurement.suppliers.show', $supplier->id) }}"
                        class="flex-1 zenith-button-outline text-center">
                        Abort Mutation
                    </a>
                    <button type="submit" class="flex-[2] zenith-button shadow-zenith-lg">
                        Execute Partner Update
                    </button>
                </div>
            </form>
        </div>

        <!-- Security Notice -->
        <div class="flex items-center gap-3 p-6 bg-zenith-50/50 rounded-2xl border border-zenith-100 italic">
            <svg class="w-5 h-5 text-zenith-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-[10px] font-bold text-zenith-400 uppercase leading-relaxed">Changes to partner particulars are
                tracked in the security audit vault. Ensure all data follows corporate compliance protocols.</p>
        </div>
    </div>
@endsection