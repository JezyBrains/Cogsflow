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
                            <tbody>
                                @foreach($dispatch->batch->bags as $bag)
                                    <tr class="hover:bg-zenith-50/50 transition-colors group" id="bag-row-{{ $bag->id }}" data-bag-id="{{ $bag->id }}">
                                        <td>
                                            <div class="flex flex-col">
                                                <span class="text-xs font-black text-zenith-900">{{ $bag->bag_serial_number ?? 'B-' . str_pad($bag->id, 5, '0', STR_PAD_LEFT) }}</span>
                                                <span class="text-[9px] text-zenith-400 font-bold uppercase">Bin ID: #{{ $bag->id }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-xs font-black text-zenith-800">{{ number_format($bag->weight_kg, 2) }}kg</span>
                                            <div class="text-[9px] text-zenith-300 font-bold uppercase">M: {{ $bag->moisture_content ?? 'N/A' }}%</div>
                                        </td>
                                        <td>
                                            <div class="flex flex-col gap-1">
                                                <input type="number" step="0.01" value="{{ $bag->actual_weight ?? $bag->weight_kg }}" 
                                                    class="bag-input w-24 bg-transparent border-none focus:ring-2 focus:ring-zenith-500 rounded-lg text-xs font-black text-zenith-900 p-1"
                                                    data-field="actual_weight" data-ref="{{ $bag->weight_kg }}">
                                                <input type="number" step="0.1" value="{{ $bag->actual_moisture ?? $bag->moisture_content ?? 0 }}" 
                                                    class="bag-input w-24 bg-transparent border-none focus:ring-2 focus:ring-zenith-500 rounded-lg text-[10px] font-bold text-zenith-500 p-1"
                                                    data-field="actual_moisture" placeholder="Moisture %">
                                            </div>
                                        </td>
                                        <td>
                                            <div id="discrepancy-{{ $bag->id }}">
                                                @php $diff = ($bag->actual_weight ?? $bag->weight_kg) - $bag->weight_kg; @endphp
                                                <span class="text-[10px] font-black {{ $diff < 0 ? 'text-red-500' : ($diff > 0 ? 'text-green-500' : 'text-zenith-200') }}">
                                                    {{ $diff > 0 ? '+' : '' }}{{ number_format($diff, 2) }}kg
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <select class="bag-input bg-transparent border-none text-[10px] font-black uppercase focus:ring-0 p-0" data-field="condition_status">
                                                <option value="Good" {{ $bag->condition_status === 'Good' ? 'selected' : '' }}>PRIME</option>
                                                <option value="Damaged" {{ $bag->condition_status === 'Damaged' ? 'selected' : '' }}>DAMAGED</option>
                                                <option value="Wet" {{ $bag->condition_status === 'Wet' ? 'selected' : '' }}>WET</option>
                                                <option value="Contaminated" {{ $bag->condition_status === 'Contaminated' ? 'selected' : '' }}>CONTAM</option>
                                            </select>
                                        </td>
                                        <td>
                                            <div class="flex items-center gap-2">
                                                <span class="status-badge text-[10px] font-black uppercase px-2 py-0.5 rounded {{ $bag->inspected_at ? 'bg-green-50 text-green-600' : 'bg-zenith-50 text-zenith-300' }}">
                                                    {{ $bag->inspected_at ? 'Verified' : 'Pending' }}
                                                </span>
                                                <span class="sync-indicator hidden">
                                                    <svg class="w-3 h-3 animate-spin text-zenith-400" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                                </span>
                                            </div>
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

    <script>
        const bagInputs = document.querySelectorAll('.bag-input');
        
        bagInputs.forEach(input => {
            input.addEventListener('change', async function() {
                const row = this.closest('tr');
                const bagId = row.dataset.bagId;
                const indicator = row.querySelector('.sync-indicator');
                const badge = row.querySelector('.status-badge');
                
                indicator.classList.remove('hidden');
                
                const formData = new FormData();
                formData.append('bag_id', bagId);
                formData.append('actual_weight', row.querySelector('[data-field="actual_weight"]').value);
                formData.append('actual_moisture', row.querySelector('[data-field="actual_moisture"]').value);
                formData.append('condition_status', row.querySelector('[data-field="condition_status"]').value);
                formData.append('dispatch_id', '{{ $dispatch->id }}');

                try {
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
                        indicator.classList.add('hidden');
                        badge.className = 'status-badge text-[10px] font-black uppercase px-2 py-0.5 rounded bg-green-50 text-green-600';
                        badge.textContent = 'Verified';
                        
                        // Update discrepancy styling
                        const ref = parseFloat(row.querySelector('[data-field="actual_weight"]').dataset.ref);
                        const cur = parseFloat(row.querySelector('[data-field="actual_weight"]').value);
                        const diff = cur - ref;
                        const discDiv = document.getElementById('discrepancy-' + bagId);
                        discDiv.innerHTML = `<span class="text-[10px] font-black ${diff < 0 ? 'text-red-500' : (diff > 0 ? 'text-green-500' : 'text-zenith-200')}">${diff > 0 ? '+' : ''}${diff.toFixed(2)}kg</span>`;
                        
                        // Update progress bar if possible
                        updateProgress();
                    }
                } catch (error) {
                    indicator.classList.add('hidden');
                    ZenithUI.notify('error', 'Sync failure.');
                }
            });

            // Keyboard Navigation
            input.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    const row = this.closest('tr');
                    const nextRow = row.nextElementSibling;
                    if (nextRow) {
                        const nextInput = nextRow.querySelector(`[data-field="${this.dataset.field}"]`);
                        if (nextInput) {
                            nextInput.focus();
                            nextInput.select();
                        }
                    }
                }
            });
        });

        function updateProgress() {
            const total = {{ $dispatch->batch->bags->count() }};
            const verified = document.querySelectorAll('.status-badge.bg-green-50').length;
            const percent = Math.round((verified / total) * 100);
            
            document.querySelector('.relative.pt-1 span.bg-zenith-50').textContent = `${verified} / ${total} Bins Cleared`;
            document.querySelector('.text-right span').textContent = `${percent}%`;
            document.querySelector('.overflow-hidden.h-2 div').style.width = `${percent}%`;
        }

        // Search/Scan Jump
        document.getElementById('bagSearch').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                const val = this.value.toUpperCase();
                const rows = document.querySelectorAll('tbody tr');
                for (let row of rows) {
                    const serial = row.querySelector('td:first-child span').textContent.toUpperCase();
                    if (serial.includes(val)) {
                        row.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        row.querySelector('.bag-input').focus();
                        row.querySelector('.bag-input').select();
                        this.value = '';
                        break;
                    }
                }
            }
        });
    </script>
@endsection

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