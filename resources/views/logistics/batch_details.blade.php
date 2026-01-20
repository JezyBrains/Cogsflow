@extends('layouts.app')

@section('title', 'Batch Quality Terminal')
@section('page_title', 'Intake Verification')

@section('content')
    <div class="space-y-8">
        <!-- Terminal Header -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-display font-black text-zenith-900 uppercase tracking-tight">Batch Quality Terminal</h2>
                <p class="text-zenith-400 font-medium mt-1">Batch ID: <span class="text-zenith-600 font-black">{{ $batch->batch_number }}</span> • {{ $batch->supplier->name }}</p>
            </div>
            <div class="flex gap-4">
                <a href="{{ route('logistics.batches') }}" class="zenith-button-outline">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Return to Control
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left: Batch Summary -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Core Metrics -->
                <div class="zenith-card p-6 bg-zenith-900 text-white">
                    <h3 class="text-[10px] font-black uppercase tracking-[0.3em] text-zenith-400 mb-6">Inbound Manifest</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center border-b border-white/10 pb-3">
                            <span class="text-[10px] font-bold uppercase text-zenith-300">Supplier Component</span>
                            <span class="text-xs font-black">{{ $batch->supplier->name }}</span>
                        </div>
                        <div class="flex justify-between items-center border-b border-white/10 pb-3">
                            <span class="text-[10px] font-bold uppercase text-zenith-300">Commodity Matrix</span>
                            <span class="text-xs font-black">{{ $batch->commodity_type }}</span>
                        </div>
                        <div class="flex justify-between items-center border-b border-white/10 pb-3">
                            <span class="text-[10px] font-bold uppercase text-zenith-300">Declared Units</span>
                            <span class="text-xs font-black">{{ $batch->expected_bags }} Units</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-[10px] font-bold uppercase text-zenith-300">Recorded Weight</span>
                            <span class="text-xs font-black text-zenith-500">{{ number_format($batch->total_weight_kg, 2) }} KG</span>
                        </div>
                    </div>
                </div>

                <!-- Document Vault Widget -->
                <x-attachment-widget 
                    :attachable_type="'App\Models\Batch'" 
                    :attachable_id="$batch->id" 
                    :attachments="$batch->attachments" 
                />
            </div>

            <!-- Right: Granular Bag Terminal -->
            <div class="lg:col-span-2 space-y-6">
                <div class="zenith-card">
                    <div class="p-6 border-b border-zenith-100 flex items-center justify-between bg-zenith-50/20">
                        <h3 class="text-lg font-display font-black text-zenith-900 italic tracking-tight">Granular Unit Registry</h3>
                        <div class="flex gap-4">
                             <input type="text" id="bagSearch" placeholder="SCAN SERIAL..."
                                class="bg-zenith-50 border-zenith-100 text-[10px] font-black uppercase px-4 py-2 rounded-lg focus:ring-2 focus:ring-zenith-500 focus:outline-none placeholder:text-zenith-200">
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="zenith-table">
                            <thead>
                                <tr>
                                    <th>Identity</th>
                                    <th>Recorded Wt</th>
                                    <th>Moisture Content</th>
                                    <th>Discrepancy</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($batch->bags as $bag)
                                    <tr class="hover:bg-zenith-50/50 transition-colors" id="bag-row-{{ $bag->id }}">
                                        <td>
                                            <div class="flex flex-col">
                                                <span class="text-xs font-black text-zenith-900 underline decoration-zenith-100">{{ $bag->bag_serial_number ?? 'UNIT-' . str_pad($bag->id, 4, '0', STR_PAD_LEFT) }}</span>
                                                <span class="text-[9px] text-zenith-300 font-bold uppercase">ID #{{ $bag->id }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-xs font-black text-zenith-800">{{ number_format($bag->weight_kg, 2) }} kg</span>
                                        </td>
                                        <td>
                                            <div class="flex items-center gap-2">
                                                <div class="w-8 h-1 bg-zenith-50 rounded-full overflow-hidden">
                                                    <div class="h-full bg-zenith-500" style="width: {{ 100 - ($bag->moisture_content ?? 0) }}%"></div>
                                                </div>
                                                <span class="text-[10px] font-black text-zenith-500">{{ $bag->moisture_content ?? 'N/A' }}%</span>
                                            </div>
                                        </td>
                                        <td>
                                            @if($bag->weight_discrepancy)
                                                <span class="text-[10px] font-black {{ $bag->weight_discrepancy < 0 ? 'text-red-500' : 'text-green-500' }}">
                                                    {{ $bag->weight_discrepancy > 0 ? '+' : '' }}{{ number_format($bag->weight_discrepancy, 2) }}
                                                </span>
                                            @else
                                                <span class="text-[9px] text-zenith-200">NOMINAL</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="text-[10px] font-black uppercase px-2 py-0.5 rounded {{ $bag->inspected_at ? 'bg-green-50 text-green-600' : 'bg-zenith-50 text-zenith-300' }}">
                                                {{ $bag->inspected_at ? 'Verified' : 'Pending' }}
                                            </span>
                                        </td>
                                        <td>
                                            <button onclick='openQuickAdjust({{ $bag->id }}, "{{ $bag->bag_serial_number }}", {{ $bag->weight_kg }}, {{ $bag->moisture_content ?? 0 }})' class="p-2 hover:bg-zenith-50 rounded-xl text-zenith-400 hover:text-zenith-600 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Adjust Modal -->
    <div id="adjustModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-6 bg-zenith-900/60 backdrop-blur-sm">
        <div class="bg-white rounded-[2.5rem] shadow-zenith-xl w-full max-w-lg overflow-hidden animate-zenith-in">
            <div class="p-10">
                <div class="flex justify-between items-center mb-8">
                    <div>
                        <h3 class="text-xl font-display font-black text-zenith-900" id="modalTitle">Unit Intervention</h3>
                        <p class="text-zenith-400 text-xs font-bold uppercase tracking-widest mt-1" id="modalSub">Adjusting Weight & Moisture</p>
                    </div>
                    <button onclick="closeAdjustModal()" class="text-zenith-300 hover:text-zenith-900 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form id="adjustForm" class="space-y-6">
                    @csrf
                    <input type="hidden" id="modal_bag_id">
                    
                    <div class="grid grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black uppercase text-zenith-400 tracking-widest px-1">Actual Weight (KG)</label>
                            <input type="number" id="actual_weight" step="0.01" class="w-full bg-zenith-50 border-none rounded-2xl px-5 py-4 text-sm font-black text-zenith-900 focus:ring-2 focus:ring-zenith-500 transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black uppercase text-zenith-400 tracking-widest px-1">Actual Moisture (%)</label>
                            <input type="number" id="actual_moisture" step="0.1" class="w-full bg-zenith-50 border-none rounded-2xl px-5 py-4 text-sm font-black text-zenith-900 focus:ring-2 focus:ring-zenith-500 transition-all">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[10px] font-black uppercase text-zenith-400 tracking-widest px-1">Quality Note</label>
                        <textarea id="quality_note" rows="2" class="w-full bg-zenith-50 border-none rounded-2xl px-5 py-4 text-sm font-medium text-zenith-800 focus:ring-2 focus:ring-zenith-500 transition-all" placeholder="Observations..."></textarea>
                    </div>

                    <button type="submit" class="w-full zenith-button !py-5 text-xs font-black uppercase tracking-[0.2em] shadow-zenith-lg">
                        COMMIT PROTOCOL UPDATE
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openQuickAdjust(id, serial, weight, moisture) {
            document.getElementById('modal_bag_id').value = id;
            document.getElementById('modalTitle').textContent = 'Intervening: ' + (serial || 'UNIT-' + id);
            document.getElementById('actual_weight').value = weight;
            document.getElementById('actual_moisture').value = moisture;
            document.getElementById('adjustModal').classList.remove('hidden');
        }

        function closeAdjustModal() {
            document.getElementById('adjustModal').classList.add('hidden');
        }

        document.getElementById('adjustForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const id = document.getElementById('modal_bag_id').value;
            
            // We reuse the existing recordBagInspection logic but adapt for this view
            const formData = new FormData();
            formData.append('bag_id', id);
            formData.append('actual_weight', document.getElementById('actual_weight').value);
            formData.append('actual_moisture', document.getElementById('actual_moisture').value);
            formData.append('condition_status', 'Good'); // Default for intake
            formData.append('notes', document.getElementById('quality_note').value);
            formData.append('dispatch_id', '{{ optional($batch->dispatches->first())->id ?? "" }}');

            try {
                // If there's no dispatch yet, we might need a direct batch-bag endpoint
                // But for now, let's see if we can use the existing inspection endpoint
                // Or I should create a specific one for Batch details
                
                const response = await fetch('{{ route("logistics.dispatches.inspect.bag") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await response.json();
                if (data.success) {
                    ZenithUI.notify('success', 'Batch aggregate updated.');
                    location.reload();
                }
            } catch (error) {
                ZenithUI.notify('error', 'Sync failure detected.');
            }
        });
    </script>
@endsection
