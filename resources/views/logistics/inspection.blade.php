@extends('layouts.app')

@section('title', 'Physical Inspection Terminal')
@section('page_title', 'Quality Control Protocol')

@section('content')
    <div class="space-y-8">
        <!-- Terminal Header -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-display font-black text-zenith-900 uppercase tracking-tight">Inspection Terminal
                </h2>
                <p class="text-zenith-400 font-medium mt-1">Dispatch Node: <span
                        class="text-zenith-600 font-black">{{ $dispatch->dispatch_number }}</span></p>
            </div>
            <div class="flex gap-4">
                <a href="{{ route('logistics.dispatches') }}" class="zenith-button-outline">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Abort Sequence
                </a>
            </div>
        </div>

        <!-- Inspection Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Summary Dashboard -->
            <div class="lg:col-span-1 space-y-6">
                <div class="zenith-card p-6 bg-zenith-900 text-white">
                    <h3 class="text-sm font-black uppercase tracking-widest text-zenith-400 mb-4">Node Intelligence</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center border-b border-white/10 pb-3">
                            <span class="text-xs font-bold uppercase text-zenith-300">Supplier</span>
                            <span class="text-sm font-black">{{ $dispatch->batch->supplier->name }}</span>
                        </div>
                        <div class="flex justify-between items-center border-b border-white/10 pb-3">
                            <span class="text-xs font-bold uppercase text-zenith-300">Commodity</span>
                            <span class="text-sm font-black">{{ $dispatch->batch->commodity_type }}</span>
                        </div>
                        <div class="flex justify-between items-center border-b border-white/10 pb-3">
                            <span class="text-xs font-bold uppercase text-zenith-300">Transit Load</span>
                            <span class="text-sm font-black">{{ $dispatch->batch->bags->count() }} Bags</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs font-bold uppercase text-zenith-300">Expected Load</span>
                            <span
                                class="text-sm font-black text-zenith-500">{{ number_format($dispatch->batch->total_weight_kg, 2) }}
                                KG</span>
                        </div>
                    </div>
                </div>

                <!-- Global Inspection Progress -->
                <div class="zenith-card p-6">
                    <h3 class="text-sm font-black uppercase tracking-tight text-zenith-900 mb-6">Inspection Progress</h3>
                    <div class="relative pt-1">
                        @php
                            $inspectedCount = $dispatch->batch->bags->whereNotNull('inspected_at')->count();
                            $totalBags = $dispatch->batch->bags->count();
                            $percent = $totalBags > 0 ? ($inspectedCount / $totalBags) * 100 : 0;
                        @endphp
                        <div class="flex mb-2 items-center justify-between">
                            <div>
                                <span
                                    class="text-xs font-black inline-block py-1 px-2 uppercase rounded-full text-zenith-600 bg-zenith-50">
                                    {{ $inspectedCount }} / {{ $totalBags }} Bins Cleared
                                </span>
                            </div>
                            <div class="text-right">
                                <span class="text-xs font-black inline-block text-zenith-900">
                                    {{ round($percent) }}%
                                </span>
                            </div>
                        </div>
                        <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-zenith-100">
                            <div style="width:{{ $percent }}%"
                                class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-zenith-500 transition-all duration-500">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bag List Terminal -->
            <div class="lg:col-span-2 space-y-6">
                <div class="zenith-card">
                    <div class="p-6 border-b border-zenith-100 flex items-center justify-between bg-zenith-50/20">
                        <h3 class="text-lg font-display font-black text-zenith-900 italic tracking-tight">Load Manifest</h3>
                        <div class="relative">
                            <input type="text" id="bagSearch" placeholder="SCAN QR..."
                                class="bg-zenith-50 border-zenith-100 text-xs font-black uppercase px-4 py-2 rounded-lg focus:ring-2 focus:ring-zenith-500 focus:outline-none placeholder:text-zenith-200">
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="zenith-table">
                            <thead>
                                <tr>
                                    <th>Identity</th>
                                    <th>Reference</th>
                                    <th>Actual</th>
                                    <th>Discrepancy</th>
                                    <th>Grade</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dispatch->batch->bags as $bag)
                                    <tr class="hover:bg-zenith-50/50 transition-colors {{ $bag->inspected_at ? 'opacity-60 grayscale' : '' }}"
                                        id="bag-row-{{ $bag->id }}">
                                        <td>
                                            <div class="flex flex-col">
                                                <span
                                                    class="text-xs font-black text-zenith-900">{{ $bag->bag_serial_number ?? 'B-' . str_pad($bag->id, 5, '0', STR_PAD_LEFT) }}</span>
                                                <span class="text-[9px] text-zenith-400 font-bold uppercase">Bin ID:
                                                    #{{ $bag->id }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span
                                                class="text-xs font-black text-zenith-800">{{ number_format($bag->weight_kg, 2) }}kg</span>
                                            <div class="text-[9px] text-zenith-300 font-bold uppercase">M:
                                                {{ $bag->moisture_content ?? 'N/A' }}%</div>
                                        </td>
                                        <td id="actual-td-{{ $bag->id }}">
                                            @if($bag->inspected_at)
                                                <span
                                                    class="text-xs font-black text-zenith-900">{{ number_format($bag->actual_weight, 2) }}kg</span>
                                                <div class="text-[9px] text-zenith-400 font-bold uppercase">M:
                                                    {{ $bag->actual_moisture }}%</div>
                                            @else
                                                <span class="text-[9px] text-zenith-200 uppercase font-bold italic">Awaiting
                                                    Scale...</span>
                                            @endif
                                        </td>
                                        <td id="discrepancy-td-{{ $bag->id }}">
                                            @if($bag->inspected_at)
                                                @php $diff = $bag->weight_discrepancy; @endphp
                                                <span
                                                    class="text-xs font-black {{ $diff < 0 ? 'text-red-500' : ($diff > 0 ? 'text-green-500' : 'text-zenith-400') }}">
                                                    {{ $diff > 0 ? '+' : '' }}{{ number_format($diff, 2) }}kg
                                                </span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if($bag->inspected_at)
                                                <span
                                                    class="zenith-badge {{ $bag->condition_status === 'Damaged' ? 'bg-red-50 text-red-600' : 'bg-green-50 text-green-600' }}">
                                                    {{ strtoupper($bag->condition_status) }}
                                                </span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if(!$bag->inspected_at)
                                                <button
                                                    onclick="openInspectionModal({{ $bag->id }}, '{{ $bag->bag_serial_number }}', {{ $bag->weight_kg }})"
                                                    class="zenith-button !px-3 !py-1 text-[10px]">
                                                    INSPECT
                                                </button>
                                            @else
                                                <svg class="w-5 h-5 text-green-500 mx-auto" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                        d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Global Document Vault -->
            <x-attachment-widget 
                :attachable_type="'App\Models\Dispatch'" 
                :attachable_id="$dispatch->id" 
                :attachments="$dispatch->attachments" 
            />
        </div>
    </div>

    <!-- Inspection Modal -->
    <div id="inspectionModal"
        class="hidden fixed inset-0 bg-zenith-900/40 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-zenith-lg w-full max-w-lg overflow-hidden animate-zenith-in">
            <div class="px-8 py-6 border-b border-zenith-100 bg-zenith-50/50 flex justify-between items-center">
                <div>
                    <h3 class="text-xl font-display font-black text-zenith-900" id="modalBagTitle">Bag Inspection</h3>
                    <p class="text-zenith-400 text-xs font-bold uppercase tracking-widest mt-1" id="modalBagSub">Reference:
                        50.00kg</p>
                </div>
                <button onclick="closeInspectionModal()" class="text-zenith-300 hover:text-zenith-900 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <form id="inspectionForm" class="p-8 space-y-6">
                @csrf
                <input type="hidden" id="modal_bag_id" name="bag_id">
                <input type="hidden" name="dispatch_id" value="{{ $dispatch->id }}">

                <div class="grid grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black uppercase text-zenith-400 tracking-widest">Scale Weight
                            (kg)</label>
                        <input type="number" name="actual_weight" id="actual_weight" step="0.01" required
                            class="w-full bg-zenith-50 border-2 border-zenith-100 rounded-2xl px-4 py-3 text-lg font-black text-zenith-900 focus:border-zenith-500 focus:outline-none transition-all">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black uppercase text-zenith-400 tracking-widest">Moisture
                            (%)</label>
                        <input type="number" name="actual_moisture" step="0.1"
                            class="w-full bg-zenith-50 border-2 border-zenith-100 rounded-2xl px-4 py-3 text-lg font-black text-zenith-900 focus:border-zenith-500 focus:outline-none transition-all">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-[10px] font-black uppercase text-zenith-400 tracking-widest">Visual Grade /
                        Condition</label>
                    <select name="condition_status"
                        class="w-full bg-zenith-50 border-2 border-zenith-100 rounded-2xl px-4 py-3 text-sm font-black text-zenith-900 focus:border-zenith-500 focus:outline-none transition-all appearance-none">
                        <option value="Good">PRIME QUALITY (GOOD)</option>
                        <option value="Damaged">DAMAGED / TORN</option>
                        <option value="Wet">WET / MOISTURE DAMAGE</option>
                        <option value="Contaminated">CONTAMINATED</option>
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="block text-[10px] font-black uppercase text-zenith-400 tracking-widest">Inspection
                        Notes</label>
                    <textarea name="notes" rows="2"
                        class="w-full bg-zenith-50 border-2 border-zenith-100 rounded-2xl px-4 py-3 text-sm font-medium text-zenith-900 focus:border-zenith-500 focus:outline-none transition-all"></textarea>
                </div>

                <div class="pt-4">
                    <button type="submit"
                        class="w-full zenith-button !py-4 text-sm tracking-widest font-black uppercase shadow-zenith-lg">
                        COMMIT INSPECTION RECORD
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openInspectionModal(id, serial, refWeight) {
            document.getElementById('modal_bag_id').value = id;
            document.getElementById('modalBagTitle').textContent = 'Inspecting: ' + (serial || 'Bag #' + id);
            document.getElementById('modalBagSub').textContent = 'Reference Load: ' + refWeight.toFixed(2) + 'kg';
            document.getElementById('actual_weight').value = refWeight;
            document.getElementById('inspectionModal').classList.remove('hidden');
            document.getElementById('actual_weight').focus();
        }

        function closeInspectionModal() {
            document.getElementById('inspectionModal').classList.add('hidden');
            document.getElementById('inspectionForm').reset();
        }

        document.getElementById('inspectionForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch('{{ route("logistics.dispatches.inspect.bag") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        closeInspectionModal();
                        location.reload(); // Simple reload for now to refresh status
                    }
                });
        });

        // QR Scanning Simulation via Keyboard
        document.getElementById('bagSearch').addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                const val = this.value.toUpperCase();
                console.log('Searching for QR:', val);
                // Find row with this serial and click inspect
                const rows = document.querySelectorAll('tbody tr');
                rows.forEach(row => {
                    const serialText = row.querySelector('td:first-child span:first-child').textContent;
                    if (serialText.toUpperCase().includes(val) && !row.classList.contains('opacity-60')) {
                        row.querySelector('button').click();
                    }
                });
                this.value = '';
            }
        });
    </script>

    <style>
        @keyframes zenith-in {
            from {
                opacity: 0;
                transform: scale(0.95) translateY(10px);
            }

            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .animate-zenith-in {
            animation: zenith-in 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
    </style>
@endsection