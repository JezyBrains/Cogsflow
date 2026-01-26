@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Header -->
        <div class="flex items-center gap-4">
            <a href="{{ route('finance.index') }}" class="p-2 hover:bg-slate-100 rounded-lg transition-colors">
                <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-slate-800 font-display">Edit Transaction</h1>
                <p class="text-slate-500 text-sm">Update financial record: {{ $transaction->reference_number }}</p>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <form action="{{ route('finance.update', $transaction->id) }}" method="POST" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Category -->
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-700">Category</label>
                        <select name="category_id" required
                            class="w-full rounded-lg border-slate-200 focus:ring-blue-500 focus:border-blue-500 text-sm">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $transaction->category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }} ({{ ucfirst($category->type) }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Amount -->
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-700">Amount (TZS)</label>
                        <div class="relative">
                            <input type="number" step="0.01" min="0.01" name="amount" required
                                value="{{ $transaction->amount }}"
                                class="w-full rounded-lg border-slate-200 focus:ring-blue-500 focus:border-blue-500 text-sm pl-12"
                                placeholder="0.00">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-medium text-xs">TZS</span>
                        </div>
                    </div>

                    <!-- Date -->
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-700">Transaction Date</label>
                        <input type="date" name="transaction_date" required
                            value="{{ $transaction->transaction_date->format('Y-m-d') }}"
                            class="w-full rounded-lg border-slate-200 focus:ring-blue-500 focus:border-blue-500 text-sm">
                    </div>

                    <!-- Payment Method -->
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-700">Payment Method</label>
                        <select name="payment_method" required
                            class="w-full rounded-lg border-slate-200 focus:ring-blue-500 focus:border-blue-500 text-sm">
                            <option value="Cash" {{ $transaction->payment_method === 'Cash' ? 'selected' : '' }}>Cash</option>
                            <option value="Bank Transfer" {{ $transaction->payment_method === 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="Mobile Money" {{ $transaction->payment_method === 'Mobile Money' ? 'selected' : '' }}>Mobile Money</option>
                            <option value="Cheque" {{ $transaction->payment_method === 'Cheque' ? 'selected' : '' }}>Cheque</option>
                        </select>
                    </div>
                </div>

                <!-- Payee / Payer -->
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-slate-700">Payee / Payer Name</label>
                    <input type="text" name="payee_payer_name" 
                        value="{{ $transaction->payee_payer_name }}"
                        class="w-full rounded-lg border-slate-200 focus:ring-blue-500 focus:border-blue-500 text-sm"
                        placeholder="Who is this payment for/from?">
                </div>

                <!-- Notes -->
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-slate-700">Additional Notes</label>
                    <textarea name="notes" rows="3"
                        class="w-full rounded-lg border-slate-200 focus:ring-blue-500 focus:border-blue-500 text-sm"
                        placeholder="Add some context to this transaction...">{{ $transaction->notes }}</textarea>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                    <a href="{{ route('finance.index') }}"
                        class="px-4 py-2 rounded-lg text-sm font-medium border border-slate-200 text-slate-600 hover:bg-slate-50 transition-colors">
                        Cancel
                    </a>
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm font-medium transition-colors shadow-sm">
                        Update Transaction
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
