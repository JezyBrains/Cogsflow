@extends('layouts.app')

@section('title', 'Financial Intelligence Hub')
@section('page_title', 'Advanced Analytics')

@section('content')
    <div class="space-y-8">
        <!-- Analytics Header -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-display font-black text-zenith-900 uppercase tracking-tight">Financial Intelligence
                </h2>
                <p class="text-zenith-400 font-medium mt-1">Matrix analysis of income, expenditure, and category
                    distribution</p>
            </div>
            <div class="flex gap-4">
                <button class="zenith-button-outline">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    Export Intelligence
                </button>
            </div>
        </div>

        <!-- Stats Stream -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="zenith-card p-8 bg-white border-l-4 border-green-500">
                <h4 class="text-[10px] font-black text-zenith-400 uppercase tracking-widest mb-2">Annual Projection (Income)
                </h4>
                <span class="text-3xl font-display font-black text-zenith-900 leading-none">TSH
                    {{ number_format($data['summary']['total_income'], 2) }}</span>
            </div>
            <div class="zenith-card p-8 bg-white border-l-4 border-red-500">
                <h4 class="text-[10px] font-black text-zenith-400 uppercase tracking-widest mb-2">Annual Expenditure</h4>
                <span class="text-3xl font-display font-black text-zenith-900 leading-none">TSH
                    {{ number_format($data['summary']['total_expense'], 2) }}</span>
            </div>
            <div class="zenith-card p-8 bg-zenith-900 text-white">
                <h4 class="text-[10px] font-black text-zenith-400 uppercase tracking-widest mb-2">Net Operational Balance
                </h4>
                <span class="text-3xl font-display font-black leading-none">TSH
                    {{ number_format($data['summary']['net_balance'], 2) }}</span>
            </div>
        </div>

        <!-- Trends Workspace -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Monthly Trends -->
            <div class="zenith-card p-8">
                <h3 class="text-lg font-display font-black text-zenith-900 italic tracking-tight mb-8 uppercase">Monthly
                    Trajectory (6m)</h3>
                <div class="overflow-x-auto">
                    <table class="zenith-table !border-none">
                        <thead>
                            <tr class="!bg-transparent">
                                <th class="!text-left !pl-0">TEMPORAL NODE</th>
                                <th class="!text-right">INCOME</th>
                                <th class="!text-right">EXPENSE</th>
                                <th class="!text-right">DELTA</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zenith-50">
                            @foreach($data['trends'] as $trend)
                                <tr class="!bg-transparent hover:!bg-zenith-50/50 transition-colors">
                                    <td class="py-4 !pl-0">
                                        <span
                                            class="text-xs font-black text-zenith-900 uppercase">{{ date('F Y', strtotime($trend->month)) }}</span>
                                    </td>
                                    <td class="py-4 text-right">
                                        <span
                                            class="text-xs font-bold text-green-600 tracking-tight">{{ number_format($trend->income, 0) }}</span>
                                    </td>
                                    <td class="py-4 text-right">
                                        <span
                                            class="text-xs font-bold text-red-500 tracking-tight">{{ number_format($trend->expense, 0) }}</span>
                                    </td>
                                    <td class="py-4 text-right">
                                        @php $delta = $trend->income - $trend->expense; @endphp
                                        <span
                                            class="text-[10px] font-black px-2 py-0.5 rounded {{ $delta >= 0 ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-600' }}">
                                            {{ $delta >= 0 ? '+' : '' }}{{ number_format($delta, 0) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Expense Distribution -->
            <div class="zenith-card p-8">
                <h3 class="text-lg font-display font-black text-zenith-900 italic tracking-tight mb-8 uppercase">Expenditure
                    Distribution (YTD)</h3>
                <div class="space-y-6">
                    @php $totalExp = $data['distribution']->sum('total_amount'); @endphp
                    @foreach($data['distribution'] as $item)
                        @php $percent = $totalExp > 0 ? ($item->total_amount / $totalExp) * 100 : 0; @endphp
                        <div class="space-y-2">
                            <div class="flex justify-between items-center text-[10px] font-black uppercase tracking-widest">
                                <span class="text-zenith-900">{{ $item->name }}</span>
                                <span class="text-zenith-400">{{ number_format($percent, 1) }}%</span>
                            </div>
                            <div class="h-2 bg-zenith-50 rounded-full overflow-hidden flex">
                                <div style="width: {{ $percent }}%"
                                    class="bg-zenith-500 rounded-full transition-all duration-1000"></div>
                            </div>
                            <div class="text-right">
                                <span class="text-xs font-bold text-zenith-900">TSH
                                    {{ number_format($item->total_amount, 0) }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection