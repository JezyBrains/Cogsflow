@extends('layouts.app')

@section('title', 'Initialize Arrival')
@section('page_title', 'Batch Protocol Initiation')

@section('content')
    <div class="space-y-8">
        <!-- Header Stream -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-display font-black text-zenith-900">Initialize Arrival</h2>
                <p class="text-zenith-400 font-medium mt-1">Begin formal grain intake and verification protocol</p>
            </div>
            <a href="{{ route('logistics.batches') }}" class="text-zenith-400 hover:text-zenith-900 transition-colors">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </a>
        </div>

        <form action="{{ route('logistics.batches.store') }}" method="POST" class="space-y-6">
            @csrf
            <div class="zenith-card p-10 space-y-8">
                <!-- PO Selection Matrix -->
                <div class="space-y-3">
                    <label class="block text-[10px] font-black text-zenith-400 uppercase tracking-widest">Select Approved
                        Purchase Order</label>
                    <select id="po_selector" name="purchase_order_id"
                        class="zenith-input w-full appearance-none bg-zenith-900 text-white font-black"
                        onchange="autoFillFromPO(this)">
                        <option value="">-- STANDALONE BATCH (NO PO LINK) --</option>
                        @foreach($purchaseOrders as $po)
                            <option value="{{ $po->id }}" 
                                data-supplier-id="{{ $po->supplier_id }}"
                                data-commodity="{{ $po->commodity_type }}" 
                                data-weight="{{ $po->total_quantity_kg }}"
                                data-remaining="{{ $po->remaining_quantity_kg }}"
                                {{ (isset($selectedPo) && $selectedPo->id == $po->id) ? 'selected' : '' }}>
                                {{ $po->po_number }} | {{ $po->supplier->name }} | {{ $po->commodity_type }}
                                ({{ number_format($po->remaining_quantity_kg, 0) }} KG LEFT)
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-8">
                    <div>
                        <label class="block text-[10px] font-black text-zenith-400 uppercase tracking-widest mb-3">Origin
                            Supplier</label>
                        <select name="supplier_id" id="supplier_id" class="zenith-input w-full appearance-none">
                            <option value="">Select Supplier...</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-zenith-400 uppercase tracking-widest mb-3">Commodity
                            Type</label>
                        <select name="commodity_type" id="commodity_type" class="zenith-input w-full appearance-none">
                            <option value="">Select Commodity...</option>
                            <option value="White Maize">White Maize</option>
                            <option value="Yellow Soya">Yellow Soya</option>
                            <option value="Sorghum">Sorghum</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-8">
                    <div>
                        <label class="block text-[10px] font-black text-zenith-400 uppercase tracking-widest mb-3">Remaining Capacity (KG)</label>
                        <input type="text" id="expected_weight" class="zenith-input w-full bg-zenith-50 font-black text-emerald-600" readonly
                            placeholder="N/A">
                    </div>
                    <div class="flex items-end">
                        <div class="text-[10px] text-zenith-400 font-bold uppercase italic pb-4">
                            Selecting an approved PO will lock the origin & commodity nodes.
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <label class="block text-[10px] font-black text-zenith-400 uppercase tracking-widest">Bag
                            Verification Stream</label>
                        <button type="button" onclick="addBagRow()"
                            class="text-[10px] font-black text-zenith-500 hover:text-zenith-900 transition-colors uppercase tracking-widest flex items-center gap-2">
                            <div class="w-5 h-5 rounded-full bg-zenith-50 flex items-center justify-center">+</div>
                            Add Intake Unit
                        </button>
                    </div>

                    <div id="bagStream" class="space-y-3">
                        <div class="flex items-center gap-2 group relative">
                            <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-zenith-50 text-[10px] font-black text-zenith-400 sn-label">
                                1
                            </div>
                            <div class="grid grid-cols-3 gap-4 flex-1">
                                <input type="number" step="0.1" name="bags[0][weight_kg]" class="zenith-input"
                                    placeholder="Weight (KG)" required>
                                <input type="number" step="0.1" name="bags[0][moisture]" class="zenith-input"
                                    placeholder="Moisture %" required>
                                <input type="text" name="bags[0][serial]" class="zenith-input"
                                    placeholder="Serial (Optional)">
                            </div>
                            <button type="button" onclick="removeBagRow(this)"
                                class="p-2 text-zenith-300 hover:text-red-500 transition-colors" title="Remove Entry">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="pt-6 border-t border-zenith-50 flex gap-4">
                    <a href="{{ route('logistics.batches') }}" class="zenith-button-outline flex-1">
                        Abort Protocol
                    </a>
                    <button type="submit" class="zenith-button flex-1">
                        Finalize Batch Entry
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        let bagCount = 1;

        document.addEventListener('DOMContentLoaded', () => {
            const urlParams = new URLSearchParams(window.location.search);
            const poId = urlParams.get('po_id');

            if (poId) {
                const select = document.getElementById('po_selector');
                select.value = poId;
                // Dispatch change event to trigger auto-fill
                if (select.value === poId) {
                   autoFillFromPO(select);
                }
            }

            // Auto-add row logic
            document.getElementById('bagStream').addEventListener('input', function(e) {
                if (e.target.tagName === 'INPUT') {
                    checkAndAddRow();
                    updateCurrentTotal();
                }
            });

            // Initial calculation
            updateCurrentTotal();
        });

        function updateCurrentTotal() {
            let total = 0;
            document.querySelectorAll('input[name$="[weight_kg]"]').forEach(input => {
                total += parseFloat(input.value || 0);
            });

            const remainingStr = document.getElementById('expected_weight').value;
            if (remainingStr && remainingStr !== 'N/A') {
                const remainingRaw = parseFloat(remainingStr.replace(/,/g, '').replace(' KG', ''));
                if (total > remainingRaw) {
                    document.getElementById('expected_weight').classList.remove('text-emerald-600');
                    document.getElementById('expected_weight').classList.add('text-rose-600', 'ring-2', 'ring-rose-500');
                } else {
                    document.getElementById('expected_weight').classList.remove('text-rose-600', 'ring-2', 'ring-rose-500');
                    document.getElementById('expected_weight').classList.add('text-emerald-600');
                }
            }
        }

        function checkAndAddRow() {
            const rows = document.querySelectorAll('#bagStream > div');
            const lastRow = rows[rows.length - 1];
            const inputs = lastRow.querySelectorAll('input[required]');

            let allFilled = true;
            inputs.forEach(input => {
                if (!input.value) allFilled = false;
            });

            if (allFilled) {
                addBagRow();
            }
        }

        function autoFillFromPO(select) {
            const option = select.options[select.selectedIndex];
            if (!option.value) {
                document.getElementById('expected_weight').value = '';
                return;
            }

            const supplierId = option.getAttribute('data-supplier-id');
            const commodity = option.getAttribute('data-commodity');
            const remaining = option.getAttribute('data-remaining');

            // Set values
            document.getElementById('supplier_id').value = supplierId;
            document.getElementById('expected_weight').value = parseFloat(remaining).toLocaleString() + ' KG';

            // Fuzzy Commodity Matching
            const commoditySelect = document.getElementById('commodity_type');
            for (let i = 0; i < commoditySelect.options.length; i++) {
                if (commoditySelect.options[i].text.toLowerCase().includes(commodity.toLowerCase())) {
                    commoditySelect.selectedIndex = i;
                    break;
                }
            }

            // Visual feedback
            const inputs = ['supplier_id', 'commodity_type', 'expected_weight'];
            inputs.forEach(id => {
                const el = document.getElementById(id);
                el.classList.add('border-zenith-500');
                setTimeout(() => el.classList.remove('border-zenith-500'), 1000);
            });
        }

        function addBagRow() {
            const stream = document.getElementById('bagStream');
            const row = document.createElement('div');
            row.className = 'flex items-center gap-2 group mt-3 relative';
            row.innerHTML = `
                                <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-zenith-50 text-[10px] font-black text-zenith-400 sn-label">
                                    ${document.querySelectorAll('#bagStream > div').length + 1}
                                </div>
                                <div class="grid grid-cols-3 gap-4 flex-1">
                                    <input type="number" step="0.1" name="bags[${bagCount}][weight_kg]" class="zenith-input" placeholder="Weight (KG)" required>
                                    <input type="number" step="0.1" name="bags[${bagCount}][moisture]" class="zenith-input" placeholder="Moisture %" required>
                                    <input type="text" name="bags[${bagCount}][serial]" class="zenith-input" placeholder="Serial (Optional)">
                                </div>
                                <button type="button" onclick="removeBagRow(this)"
                                    class="p-2 text-zenith-300 hover:text-red-500 transition-colors" title="Remove Entry">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            `;
            stream.appendChild(row);
            bagCount++;
        }

        function removeBagRow(btn) {
            if (document.querySelectorAll('#bagStream > div').length > 1) {
                btn.closest('.group').remove();
                recalculateSN();
                updateCurrentTotal();
            }
        }

        function recalculateSN() {
            document.querySelectorAll('#bagStream .sn-label').forEach((el, index) => {
                el.innerText = index + 1;
            });
        }
    </script>
@endsection