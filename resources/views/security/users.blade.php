@extends('layouts.app')

@section('title', 'Identity Governance')
@section('page_title', 'Security Grid')

@section('content')
    <div class="space-y-10" x-data="{ showCreateModal: false }">

        <!-- Zenith Section Header -->
        <div class="flex items-end justify-between">
            <div>
                <span class="text-[10px] font-bold text-zenith-400 uppercase tracking-[0.2em] block mb-2">Access
                    Management</span>
                <h2 class="text-4xl font-display font-black text-zenith-900 tracking-tight">Identity Directory</h2>
            </div>
            <button @click="showCreateModal = true" class="zenith-button">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>Authorize New Identity</span>
            </button>
        </div>

        <!-- Metric Stream -->
        <div class="flex items-center gap-6 mb-4">
            <div class="zenith-card px-8 py-5 flex items-center gap-4 bg-white border-zenith-200">
                <span class="text-[10px] font-bold text-zenith-400 uppercase tracking-widest">Active Personnel</span>
                <span class="text-2xl font-display font-black text-zenith-900 leading-none">{{ $users->total() }}</span>
            </div>
        </div>

        <!-- Personnel Ledger -->
        <div class="zenith-card shadow-zenith-md">
            <div class="overflow-x-auto scrollbar-hide">
                <table class="zenith-table">
                    <thead>
                        <tr>
                            <th class="pl-10">Personnel Identity</th>
                            <th>Security Clearance</th>
                            <th>Connection Node</th>
                            <th class="text-right pr-10">Verification</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr class="hover:bg-zenith-50 transition-colors group">
                                <td class="pl-10 py-8">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="w-12 h-12 rounded-2xl bg-zenith-50 flex items-center justify-center text-[15px] font-display font-black text-zenith-400 group-hover:bg-zenith-500 group-hover:text-white transition-all shadow-zenith-sm border border-zenith-100">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="text-base font-bold text-zenith-900 leading-tight">{{ $user->name }}</p>
                                            <p class="text-[11px] font-medium text-zenith-400 mt-1">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($user->roles as $role)
                                            <span
                                                class="zenith-badge bg-zenith-100 text-zenith-600 border border-zenith-200 uppercase tracking-widest text-[8px]">
                                                {{ $role->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </td>
                                <td>
                                    <p class="text-[12px] font-bold text-zenith-800 font-mono">192.168.1.{{ $user->id + 10 }}
                                    </p>
                                    <p class="text-[9px] text-zenith-400 font-bold uppercase tracking-widest mt-1">Authorized
                                        Uplink</p>
                                </td>
                                <td class="text-right pr-10">
                                    <button
                                        class="zenith-button-outline px-4 py-2.5 rounded-xl text-[10px] uppercase tracking-widest bg-slate-50 border-slate-200 text-slate-500 hover:bg-zenith-500 hover:text-white hover:border-zenith-500 transition-all">
                                        Modify Access
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($users->hasPages())
                <div class="px-10 py-8 bg-zenith-50/50 border-t border-zenith-100">
                    {{ $users->links() }}
                </div>
            @endif
        </div>

        <!-- Zenith Identity Modal -->
        <template x-if="showCreateModal">
            <div class="fixed inset-0 z-50 flex items-center justify-center p-6 sm:p-12">
                <div class="absolute inset-0 bg-zenith-900/40 backdrop-blur-sm" @click="showCreateModal = false"></div>

                <div class="zenith-card-elevated w-full max-w-2xl p-10 sm:p-14 relative z-10 bg-white"
                    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100">

                    <div class="mb-10">
                        <span class="text-[10px] font-bold text-zenith-500 uppercase tracking-[0.3em] block mb-2">Protocol:
                            Identity Generation</span>
                        <h3 class="text-3xl font-display font-black text-zenith-900 tracking-tight">Authorize Personnel</h3>
                    </div>

                    <form action="{{ route('security.users.store') }}" method="POST" class="space-y-8">
                        @csrf
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-zenith-400 uppercase tracking-widest ml-1">Legal
                                Designation</label>
                            <input type="text" name="name" required class="zenith-input" placeholder="e.g. Marcus Aurelius">
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-zenith-400 uppercase tracking-widest ml-1">Digital Node
                                (Email)</label>
                            <input type="email" name="email" required class="zenith-input" placeholder="marcus@nipo.io">
                        </div>

                        <div class="space-y-4">
                            <label class="text-[10px] font-bold text-zenith-400 uppercase tracking-widest ml-1">Security
                                Clearance Vectors</label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @foreach(\App\Models\Role::all() as $role)
                                    <label
                                        class="flex items-center justify-between p-5 rounded-2xl bg-zenith-50 border border-zenith-100 cursor-pointer hover:border-zenith-500/30 transition-all group">
                                        <div class="flex items-center gap-4">
                                            <input type="checkbox" name="roles[]" value="{{ $role->id }}"
                                                class="w-5 h-5 rounded-lg border-zenith-200 text-zenith-500 focus:ring-4 focus:ring-zenith-500/10 transition-all">
                                            <span
                                                class="text-xs font-bold text-zenith-800 uppercase tracking-widest transition-colors group-hover:text-zenith-500">{{ $role->name }}</span>
                                        </div>
                                        <div
                                            class="w-1.5 h-1.5 rounded-full bg-zenith-200 group-hover:bg-zenith-500 transition-colors">
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="pt-6 flex flex-col sm:flex-row gap-4">
                            <button type="button" @click="showCreateModal = false"
                                class="flex-1 zenith-button-outline">Abort Protocol</button>
                            <button type="submit" class="flex-[2] zenith-button">
                                <span>Execute Identity Issuance</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </template>
    </div>
@endsection