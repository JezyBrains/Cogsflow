@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('finance.index') }}" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <h1 class="text-2xl font-bold text-slate-800 font-display">Record Transaction</h1>
            </div>
            <p class="text-slate-500 text-sm ml-7">Log a new income or expense entry.</p>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <form action="{{ route('finance.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Category -->
                <div>
                    <label for="category_id" class="block text-sm font-medium text-slate-700 mb-1">Category</label>
                    <select name="category_id" id="category_id"
                        class="w-full rounded-lg border-slate-300 text-slate-700 focus:border-zenith-500 focus:ring-zenith-500 shadow-sm"
                        required>
                        <option value="" disabled selected>Select a category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">
                                {{ $category->name }} ({{ ucfirst($category->type) }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Amount -->
                    <div>
                        <label for="amount" class="block text-sm font-medium text-slate-700 mb-1">Amount (TZS)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-slate-500 sm:text-sm">TSh</span>
                            </div>
                            <input type="number" name="amount" id="amount" step="0.01" min="0.01"
                                class="pl-12 w-full rounded-lg border-slate-300 text-slate-700 focus:border-zenith-500 focus:ring-zenith-500 shadow-sm"
                                placeholder="0.00" required>
                        </div>
                    </div>

                    <!-- Date -->
                    <div>
                        <label for="transaction_date" class="block text-sm font-medium text-slate-700 mb-1">Date</label>
                        <input type="date" name="transaction_date" id="transaction_date" value="{{ date('Y-m-d') }}"
                            class="w-full rounded-lg border-slate-300 text-slate-700 focus:border-zenith-500 focus:ring-zenith-500 shadow-sm"
                            required>
                    </div>
                </div>

                <!-- Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Payment Method -->
                    <div>
                        <label for="payment_method" class="block text-sm font-medium text-slate-700 mb-1">Payment
                            Method</label>
                        <select name="payment_method" id="payment_method"
                            class="w-full rounded-lg border-slate-300 text-slate-700 focus:border-zenith-500 focus:ring-zenith-500 shadow-sm"
                            required>
                            <option value="cash">Cash</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="mobile_money">Mobile Money</option>
                            <option value="cheque">Cheque</option>
                        </select>
                    </div>

                    <!-- Payee/Payer -->
                    <div>
                        <label for="payee_payer_name" class="block text-sm font-medium text-slate-700 mb-1">Payee / Payer
                            Name</label>
                        <input type="text" name="payee_payer_name" id="payee_payer_name"
                            class="w-full rounded-lg border-slate-300 text-slate-700 focus:border-zenith-500 focus:ring-zenith-500 shadow-sm"
                            placeholder="e.g. John Doe">
                    </div>
                </div>

                <!-- Notes -->
                <div>
                    <label for="notes" class="block text-sm font-medium text-slate-700 mb-1">Description / Notes</label>
                    <textarea name="notes" id="notes" rows="3"
                        class="w-full rounded-lg border-slate-300 text-slate-700 focus:border-zenith-500 focus:ring-zenith-500 shadow-sm"
                        placeholder="Additional details..."></textarea>
                </div>

                <!-- Actions -->
                <div class="pt-4 flex justify-end gap-3 border-t border-slate-100">
                    <a href="{{ route('finance.index') }}"
                        class="px-4 py-2 text-sm font-medium text-slate-600 hover:text-slate-800 bg-white hover:bg-slate-50 border border-slate-300 rounded-lg transition-colors">Cancel</a>
                    <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-zenith-500 hover:bg-zenith-600 rounded-lg shadow-sm transition-colors">Record
                        Transaction</button>
                </div>
            </form>
        </div>
    </div>
@endsection