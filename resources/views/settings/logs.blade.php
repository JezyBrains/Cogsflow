@extends('layouts.app')

@section('title', 'System Health Terminal')
@section('page_title', 'Operational Logs')

@section('content')
    <div class="space-y-8">
        <!-- Log Header -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-display font-black text-zenith-900 uppercase tracking-tight">Health & Audit Logs
                </h2>
                <p class="text-zenith-400 font-medium mt-1">Real-time telemetry of system-wide operations and audit events
                </p>
            </div>
            <div class="flex gap-4">
                <button class="zenith-button-outline !px-4 !py-2 text-[10px]">EXPORT LEDGER</button>
                <form action="{{ route('settings.logs.purge') }}" method="POST"
                    onsubmit="zenithConfirmAction(event, 'Purge Intelligence', 'Authorize the immediate purging of all historical telemetry? This action is irreversible and Dismantles the audit trail.')">
                    @csrf
                    <button type="submit" class="zenith-button !bg-red-500 !px-4 !py-2 text-[10px]">PURGE LOGS</button>
                </form>
            </div>
        </div>

        <!-- Live Telemetry Table -->
        <div class="zenith-card overflow-hidden">
            <div class="p-6 border-b border-zenith-100 bg-zenith-50/20 flex items-center justify-between">
                <h3 class="text-lg font-display font-black text-zenith-900 italic tracking-tight uppercase">Audit Stream
                </h3>
                <div class="flex gap-4">
                    <input type="text" placeholder="FILTER EVENT..."
                        class="bg-zenith-50 border-zenith-100 text-[10px] font-black uppercase px-3 py-1.5 rounded-lg focus:ring-1 focus:ring-zenith-500 focus:outline-none placeholder:text-zenith-200">
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="zenith-table">
                    <thead>
                        <tr>
                            <th>Temporal Marker</th>
                            <th>Operative</th>
                            <th>Event Signature</th>
                            <th>Target Resource</th>
                            <th>Network Trace</th>
                            <th>Context</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($logs as $log)
                            <tr class="hover:bg-zenith-50/50 transition-colors">
                                <td class="whitespace-nowrap">
                                    <span
                                        class="text-xs font-black text-zenith-900">{{ $log->created_at->format('H:i:s') }}</span>
                                    <div class="text-[9px] text-zenith-400 font-bold uppercase">
                                        {{ $log->created_at->format('d M, Y') }}
                                    </div>
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <div
                                            class="w-6 h-6 rounded-lg bg-zenith-900 text-white flex items-center justify-center text-[10px] font-black uppercase">
                                            {{ substr($log->user?->name ?? 'SYS', 0, 2) }}
                                        </div>
                                        <span
                                            class="text-xs font-black text-zenith-800">{{ $log->user?->name ?? 'SYSTEM' }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span
                                        class="text-[10px] font-black px-2 py-0.5 rounded bg-zenith-50 text-zenith-600 uppercase tracking-tighter">
                                        {{ str_replace('_', ' ', $log->event) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="flex flex-col">
                                        <span
                                            class="text-xs font-bold text-zenith-700 truncate max-w-[150px]">{{ class_basename($log->auditable_type) }}</span>
                                        <span class="text-[9px] text-zenith-300 font-bold uppercase tracking-widest">ID:
                                            #{{ $log->auditable_id ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex flex-col">
                                        <span
                                            class="text-[10px] font-mono font-bold text-zenith-500">{{ $log->ip_address }}</span>
                                        <span
                                            class="text-[8px] text-zenith-300 font-bold uppercase truncate max-w-[150px]">{{ $log->user_agent }}</span>
                                    </div>
                                </td>
                                <td>
                                    <button onclick="viewLogPayload({{ json_encode($log) }})"
                                        class="p-1.5 hover:bg-zenith-100 rounded-lg text-zenith-400 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                            </path>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($logs->hasPages())
                <div class="p-6 border-t border-zenith-100 bg-zenith-50/20">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Payload Modal -->
    <div id="payloadModal"
        class="hidden fixed inset-0 bg-zenith-900/40 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-zenith-lg w-full max-w-2xl overflow-hidden animate-zenith-in">
            <div class="px-8 py-6 border-b border-zenith-100 bg-zenith-50/50 flex justify-between items-center">
                <div>
                    <h3 class="text-xl font-display font-black text-zenith-900">Event Matrix Payload</h3>
                    <p class="text-zenith-400 text-xs font-bold uppercase tracking-widest mt-1" id="payloadTitle">
                        EVENT_SIGNATURE</p>
                </div>
                <button onclick="closePayloadModal()" class="text-zenith-300 hover:text-zenith-900 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
            <div class="p-8 space-y-6">
                <div class="bg-zenith-900 rounded-2xl p-6 overflow-auto max-h-[400px]">
                    <pre id="jsonPayload" class="text-zenith-300 text-xs font-mono"></pre>
                </div>
                <div class="flex justify-end">
                    <button onclick="closePayloadModal()"
                        class="zenith-button !py-3 !px-8 text-xs tracking-widest uppercase font-black">Close
                        Terminal</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function viewLogPayload(log) {
            document.getElementById('payloadTitle').textContent = log.event.toUpperCase();
            document.getElementById('jsonPayload').textContent = JSON.stringify({
                old_values: log.old_values,
                new_values: log.new_values,
                url: log.url,
                ip_address: log.ip_address,
                user_agent: log.user_agent,
            }, null, 4);
            document.getElementById('payloadModal').classList.remove('hidden');
        }

        function closePayloadModal() {
            document.getElementById('payloadModal').classList.add('hidden');
        }
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