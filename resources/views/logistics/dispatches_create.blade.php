@extends('layouts.app')

@section('title', 'Activate Dispatch')
@section('page_title', 'Dispatch Node Activation')

@section('content')
    <div class="space-y-8">
        <!-- Header Stream -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-display font-black text-zenith-900">Activate Dispatch</h2>
                <p class="text-zenith-400 font-medium mt-1">Register logistics vector for commodity transit</p>
            </div>
            <a href="{{ route('logistics.dispatches') }}" class="text-zenith-400 hover:text-zenith-900 transition-colors">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </a>
        </div>

        <form action="{{ route('logistics.dispatches.store') }}" method="POST" class="space-y-6">
            @csrf
            <div class="zenith-card p-10 space-y-8">
                <div class="grid grid-cols-2 gap-8">
                    <div class="col-span-2">
                        <label class="block text-[10px] font-black text-zenith-400 uppercase tracking-widest mb-3">Linked
                            Batch Protocol</label>
                        <select name="batch_id" class="zenith-input w-full appearance-none">
                            @foreach($availableBatches as $batch)
                                <option value="{{ $batch->id }}">{{ $batch->batch_number }} - {{ $batch->commodity_type }}
                                    ({{ number_format($batch->total_weight_kg, 2) }} KG)</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-zenith-400 uppercase tracking-widest mb-3">Vehicle
                            Registration</label>
                        <input type="text" name="vehicle_reg_number" class="zenith-input" placeholder="e.g. T 123 ABC"
                            required>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-zenith-400 uppercase tracking-widest mb-3">Trailer
                            Identification</label>
                        <input type="text" name="trailer_number" class="zenith-input" placeholder="e.g. TR-2026">
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-zenith-400 uppercase tracking-widest mb-3">Regional
                            Destination</label>
                        <select name="destination" class="zenith-input w-full appearance-none">
                            <option value="Dar es Salaam Hub">Dar es Salaam Hub</option>
                            <option value="Arusha Storage Node">Arusha Storage Node</option>
                            <option value="Mbeya Processing Node">Mbeya Processing Node</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-zenith-400 uppercase tracking-widest mb-3">Estimated
                            Temporal Arrival</label>
                        <input type="datetime-local" name="estimated_arrival" class="zenith-input"
                            value="{{ now()->addDays(1)->format('Y-m-d\TH:i') }}">
                    </div>
                </div>

                <div class="pt-6 border-t border-zenith-50 flex gap-4">
                    <a href="{{ route('logistics.dispatches') }}" class="zenith-button-outline flex-1">
                        Abort Vector
                    </a>
                    <button type="submit" class="zenith-button flex-1">
                        Activate Dispatch
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection