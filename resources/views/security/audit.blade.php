@extends('layouts.app')

@section('title', 'Audit Stream')
@section('page_title', 'Security Ledger')

@section('content')
    <div class="space-y-10">

        <!-- Zenith Section Header -->
        <div class="flex items-end justify-between">
            <div>
                <span class="text-[10px] font-bold text-zenith-400 uppercase tracking-[0.2em] block mb-2">Event
                    Telemetry</span>
                <h2 class="text-4xl font-display font-black text-zenith-900 tracking-tight">Audit Stream</h2>
            </div>
            <div class="flex items-center gap-4">
                <button class="zenith-button-outline px-8 py-3.5 text-[10px] uppercase tracking-[0.2em]">
                    Export Raw Logs
                </button>
            </div>
        </div>

        <!-- Zenith Pulse Card -->
        <div class="zenith-card p-10 bg-white border-zenith-200 shadow-zenith-sm">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-6">
                    <div class="relative flex h-5 w-5">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-zenith-400 opacity-75"></span>
                        <span
                            class="relative inline-flex rounded-full h-5 w-5 bg-zenith-500 shadow-zenith-sm border-2 border-white"></span>
                    </div>
                    <div>
                        <h3 class="text-2xl font-display font-black text-zenith-900 tracking-tight">Active Pulse Link</h3>
                        <p class="text-[11px] text-zenith-400 font-bold uppercase tracking-widest mt-1">Real-time encryption
                            monitoring across node network</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-3xl font-display font-black text-zenith-900 leading-none">{{ $logs->total() }}</p>
                    <p class="text-[10px] text-zenith-400 font-bold uppercase tracking-widest mt-2">Total System Events</p>
                </div>
            </div>
        </div>

        <!-- Temporal Ledger -->
        <div class="zenith-card shadow-zenith-md">
            <div class="overflow-x-auto scrollbar-hide">
                <table class="zenith-table">
                    <thead>
                        <tr>
                            <th class="pl-10">Temporal Marker</th>
                            <th>Origin Identity</th>
                            <th>Event Payload</th>
                            <th>Target Vector</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($logs as $log)
                            <tr class="hover:bg-zenith-50 transition-colors group">
                                <td class="pl-10 py-8">
                                    <p class="text-[13px] font-bold text-zenith-800 font-mono">
                                        {{ $log->created_at->format('H:i:s') }}</p>
                                    <p class="text-[9px] text-zenith-400 font-bold uppercase tracking-widest mt-1">
                                        {{ $log->created_at->format('d M, Y') }}
                                    </p>
                                </td>
                                <td class="py-8">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="w-10 h-10 rounded-xl bg-zenith-50 flex items-center justify-center text-[11px] text-zenith-400 font-display font-black border border-zenith-100 group-hover:bg-zenith-500 group-hover:text-white transition-all shadow-zenith-sm">
                                            {{ $log->user ? substr($log->user->name, 0, 1) : 'S' }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-zenith-900 leading-tight">
                                                {{ $log->user ? $log->user->name : 'Autonomous System' }}
                                            </p>
                                            <p class="text-[9px] text-zenith-400 font-bold uppercase tracking-widest mt-1">
                                                Authorized Personnel</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-8">
                                    <span class="zenith-badge bg-zenith-50 text-zenith-600 border border-zenith-100 italic">
                                        {{ str_replace('_', ' ', $log->event) }}
                                    </span>
                                </td>
                                <td class="py-8">
                                    <div>
                                        <p class="text-sm font-bold text-zenith-800 tracking-tight">
                                            {{ class_basename($log->auditable_type) }}
                                        </p>
                                        <p class="text-[9px] font-mono text-zenith-400 uppercase tracking-tighter mt-1">Ref:
                                            #{{ $log->auditable_id ?? 'INIT' }}</p>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($logs->hasPages())
                <div class="px-10 py-8 bg-zenith-50/50 border-t border-zenith-100">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>

    </div>
@endsection