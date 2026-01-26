@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 font-display">Financial Ledger</h1>
                <p class="text-slate-500 text-sm mt-1">Unified Income & Expense Tracking</p>
            </div>
            <a href="{{ route('finance.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors shadow-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Record Transaction
            </a>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Total Income -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex flex-col justify-between h-32">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-slate-500 text-xs font-semibold uppercase tracking-wider">Total Income</h3>
                        <p class="text-2xl font-bold text-slate-800 mt-2 font-display text-green-600">
                            {{ number_format($summary['total_income'], 2) }} <span
                                class="text-sm text-slate-400 font-normal">TZS</span>
                        </p>
                    </div>
                    <div class="p-2 bg-green-50 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Expenses -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex flex-col justify-between h-32">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-slate-500 text-xs font-semibold uppercase tracking-wider">Total Expenses</h3>
                        <p class="text-2xl font-bold text-slate-800 mt-2 font-display text-red-600">
                            {{ number_format($summary['total_expenses'], 2) }} <span
                                class="text-sm text-slate-400 font-normal">TZS</span>
                        </p>
                    </div>
                    <div class="p-2 bg-red-50 rounded-lg">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 13l-5 5m0 0l-5-5m5 5V6"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Net Balance -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex flex-col justify-between h-32">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-slate-500 text-xs font-semibold uppercase tracking-wider">Net Balance</h3>
                        <p
                            class="text-2xl font-bold text-slate-800 mt-2 font-display {{ $summary['net_balance'] >= 0 ? 'text-blue-600' : 'text-red-500' }}">
                            {{ number_format($summary['net_balance'], 2) }} <span
                                class="text-sm text-slate-400 font-normal">TZS</span>
                        </p>
                    </div>
                    <div class="p-2 bg-blue-50 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Ledger -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <h2 class="font-semibold text-slate-800">Transaction History</h2>
                <div class="flex gap-2">
                    <a href="{{ route('reports.export', 'finance') }}"
                        class="text-xs font-bold text-slate-500 hover:text-blue-600 transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Export CSV
                    </a>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-slate-50/50 border-b border-slate-100 text-xs uppercase text-slate-500 font-semibold tracking-wider">
                            <th class="px-6 py-4">Reference</th>
                            <th class="px-6 py-4">Date</th>
                            <th class="px-6 py-4">Category</th>
                            <th class="px-6 py-4">Description</th>
                            <th class="px-6 py-4 text-right">Amount</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($transactions as $trx)
                            <tr class="hover:bg-slate-50/80 transition-colors group">
                                <td class="px-6 py-4">
                                    <span
                                        class="font-medium text-slate-700 font-mono text-xs">{{ $trx->reference_number }}</span>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600">
                                    {{ $trx->transaction_date->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $trx->category->type === 'income' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $trx->category->name }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600">
                                    {{ Str::limit($trx->notes, 30) ?: '-' }}
                                    <div class="text-xs text-slate-400 mt-0.5">{{ $trx->payee_payer_name }}</div>
                                </td>
                                <td
                                    class="px-6 py-4 text-right font-medium {{ $trx->category->type === 'income' ? 'text-green-600' : 'text-slate-700' }}">
                                    {{ $trx->category->type === 'income' ? '+' : '-' }}{{ number_format($trx->amount, 2) }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($trx->status === 'approved')
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-50 text-green-700 border border-green-100">
                                            <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5"></span>Approved
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-50 text-yellow-700 border border-yellow-100">
                                            <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 mr-1.5"></span>Pending
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    @if($trx->status === 'pending')
                                        <form action="{{ route('finance.approve', $trx->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit"
                                                class="text-xs text-blue-600 hover:text-blue-800 font-medium bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition-colors">
                                                Approve
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-12 h-12 text-slate-200 mb-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                                            </path>
                                        </svg>
                                        <p>No transactions recorded yet.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($transactions->hasPages())
                <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                    {{ $transactions->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection