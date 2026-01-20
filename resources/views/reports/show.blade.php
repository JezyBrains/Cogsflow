@extends('layouts.app')

@section('page_title', $title)

@section('content')
    <div class="space-y-8">
        <!-- Header with Actions -->
        <div class="flex items-center justify-between">
            <div>
                <nav class="flex text-[10px] font-bold text-zenith-300 uppercase tracking-widest mb-2 gap-2 items-center">
                    <a href="{{ route('reports.index') }}" class="hover:text-zenith-500 transition-colors">Intelligence
                        Hub</a>
                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path>
                    </svg>
                    <span class="text-zenith-500">View Report</span>
                </nav>
                <h2 class="text-3xl font-display font-black text-zenith-900 tracking-tight">{{ $title }}</h2>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('reports.export', $slug) }}"
                    class="bg-zenith-900 hover:bg-black text-white px-5 py-2.5 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all shadow-zenith-sm flex items-center gap-2">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    Export CSV
                </a>
            </div>
        </div>

        <!-- Data Table Container -->
        <div class="bg-white rounded-[2.5rem] border border-zenith-100 shadow-zenith-sm overflow-hidden">
            <div class="overflow-x-auto">
                @if($slug === 'inventory')
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr
                                class="bg-zenith-50/50 border-b border-zenith-100 text-[10px] uppercase text-zenith-400 font-black tracking-widest">
                                <th class="px-8 py-5">Commodity Type</th>
                                <th class="px-8 py-5 text-right">Total Weight (KG)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zenith-50">
                            @foreach($data as $row)
                                <tr class="hover:bg-zenith-50/30 transition-colors">
                                    <td class="px-8 py-5">
                                        <span
                                            class="font-bold text-zenith-800">{{ ucfirst(str_replace('_', ' ', $row->grain_type)) }}</span>
                                    </td>
                                    <td class="px-8 py-5 text-right">
                                        <span
                                            class="font-display font-black text-zenith-900">{{ number_format($row->total_weight, 0) }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @elseif($slug === 'finance')
                    <div class="p-8 space-y-8">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                            <div class="p-6 bg-zenith-50 rounded-3xl">
                                <h4 class="text-[10px] font-black text-zenith-300 uppercase tracking-widest mb-2">Total Income
                                </h4>
                                <p class="text-2xl font-display font-black text-green-500">
                                    {{ number_format($data['total_income'], 2) }} TZS
                                </p>
                            </div>
                            <div class="p-6 bg-zenith-50 rounded-3xl">
                                <h4 class="text-[10px] font-black text-zenith-300 uppercase tracking-widest mb-2">Total Expense
                                </h4>
                                <p class="text-2xl font-display font-black text-red-500">
                                    {{ number_format($data['total_expense'], 2) }} TZS
                                </p>
                            </div>
                            <div class="p-6 bg-zenith-50 rounded-3xl">
                                <h4 class="text-[10px] font-black text-zenith-300 uppercase tracking-widest mb-2">Net Result
                                </h4>
                                <p class="text-2xl font-display font-black text-zenith-900">
                                    {{ number_format($data['net_balance'], 2) }} TZS
                                </p>
                            </div>
                        </div>
                    </div>
                @elseif($slug === 'suppliers')
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr
                                class="bg-zenith-50/50 border-b border-zenith-100 text-[10px] uppercase text-zenith-400 font-black tracking-widest">
                                <th class="px-8 py-5">Supplier Name</th>
                                <th class="px-8 py-5 text-center">Open POs</th>
                                <th class="px-8 py-5 text-center">Total Batches</th>
                                <th class="px-8 py-5 text-right">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zenith-50">
                            @foreach($data as $row)
                                <tr class="hover:bg-zenith-50/30 transition-colors">
                                    <td class="px-8 py-5">
                                        <span class="font-bold text-zenith-800">{{ $row['name'] }}</span>
                                    </td>
                                    <td class="px-8 py-5 text-center text-sm font-medium text-zenith-500">
                                        {{ $row['po_count'] }}
                                    </td>
                                    <td class="px-8 py-5 text-center text-sm font-medium text-zenith-500">
                                        {{ $row['batch_count'] }}
                                    </td>
                                    <td class="px-8 py-5 text-right">
                                        <span
                                            class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-tighter {{ $row['status'] === 'Active' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                                            {{ $row['status'] }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="p-10 text-center text-zenith-300">
                        <p class="font-bold uppercase tracking-widest text-xs">Report Detail Under Construction</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection