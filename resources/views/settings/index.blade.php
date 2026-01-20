@extends('layouts.app')

@section('page_title', 'System Settings')

@section('content')
    <div class="space-y-10" x-data="settingsTerminal()">
        <!-- Header -->
        <div>
            <nav class="flex text-[10px] font-bold text-zenith-300 uppercase tracking-widest mb-2 gap-2 items-center">
                <span>System</span>
                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path>
                </svg>
                <span class="text-zenith-500">Configuration</span>
            </nav>
            <h2 class="text-3xl font-display font-black text-zenith-900 tracking-tight">System Settings</h2>
        </div>

        <!-- Settings Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-12 items-start">
            <!-- Sidebar Navigation -->
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-[2.5rem] border border-zenith-100 shadow-zenith-sm p-5 space-y-2">
                    <a href="#"
                        class="flex items-center gap-3 px-5 py-4 rounded-3xl bg-zenith-50 text-zenith-500 font-bold transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4">
                            </path>
                        </svg>
                        General Configuration
                    </a>
                    <a href="{{ route('settings.company') }}"
                        class="flex items-center gap-3 px-5 py-4 rounded-3xl text-zenith-400 hover:bg-zenith-50 hover:text-zenith-800 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                            </path>
                        </svg>
                        Company Profile
                    </a>
                    <a href="{{ route('settings.notifications') }}"
                        class="flex items-center gap-3 px-5 py-4 rounded-3xl text-zenith-400 hover:bg-zenith-50 hover:text-zenith-800 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                            </path>
                        </svg>
                        Notification Rules
                    </a>
                    <a href="{{ route('security.users') }}"
                        class="flex items-center gap-3 px-5 py-4 rounded-3xl text-zenith-400 hover:bg-zenith-50 hover:text-zenith-800 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                            </path>
                        </svg>
                        Role Governance
                    </a>
                </div>
            </div>

            <!-- Main Configuration Area -->
            <div class="lg:col-span-3 space-y-8">
                <div class="bg-white rounded-[2.5rem] border border-zenith-100 shadow-zenith-sm p-10">
                    <h4 class="text-xl font-display font-black text-zenith-900 tracking-tight mb-8">System Identifiers</h4>
                    <div class="space-y-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-2">
                                <label
                                    class="text-[10px] font-black text-zenith-400 uppercase tracking-widest px-1">Application
                                    Name</label>
                                <input type="text" x-model="config.app_name"
                                    @change="updateSetting('app_name', config.app_name, 'string')"
                                    class="w-full bg-zenith-50 border-none rounded-2xl px-5 py-4 text-sm font-bold text-zenith-800 focus:ring-2 focus:ring-zenith-500 transition-all">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-zenith-400 uppercase tracking-widest px-1">System
                                    Version</label>
                                <input type="text" value="v2.0.4 - Zenith Stable" readonly
                                    class="w-full bg-zenith-50/50 border-none rounded-2xl px-5 py-4 text-sm font-bold text-zenith-400 cursor-not-allowed">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-[2.5rem] border border-zenith-100 shadow-zenith-sm p-10">
                    <h4 class="text-xl font-display font-black text-zenith-900 tracking-tight mb-8">Performance & Storage
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="flex items-center justify-between p-6 bg-zenith-50 rounded-3xl cursor-pointer"
                            @click="toggleSetting('cors_protection')">
                            <div>
                                <h5 class="text-sm font-bold text-zenith-800">CORS Protection</h5>
                                <p class="text-[10px] text-zenith-400 font-medium">Enhanced edge security</p>
                            </div>
                            <div class="w-12 h-6 rounded-full relative transition-colors duration-300"
                                :class="config.cors_protection === 'true' ? 'bg-green-500' : 'bg-zenith-200'">
                                <div class="absolute top-1 w-4 h-4 bg-white rounded-full transition-all duration-300 shadow-sm"
                                    :class="config.cors_protection === 'true' ? 'right-1' : 'left-1'"></div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between p-6 bg-zenith-50 rounded-3xl cursor-pointer"
                            @click="toggleSetting('ai_telemetry')">
                            <div>
                                <h5 class="text-sm font-bold text-zenith-800">AI Telemetry</h5>
                                <p class="text-[10px] text-zenith-400 font-medium">Real-time usage insights</p>
                            </div>
                            <div class="w-12 h-6 rounded-full relative transition-colors duration-300"
                                :class="config.ai_telemetry === 'true' ? 'bg-green-500' : 'bg-zenith-200'">
                                <div class="absolute top-1 w-4 h-4 bg-white rounded-full transition-all duration-300 shadow-sm"
                                    :class="config.ai_telemetry === 'true' ? 'right-1' : 'left-1'"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-[2.5rem] border border-zenith-100 shadow-zenith-sm p-10">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h4 class="text-xl font-display font-black text-zenith-900 tracking-tight">Identity &
                                Governance</h4>
                            <p class="text-[10px] text-zenith-400 font-bold uppercase tracking-widest mt-1">Personnel
                                Roles and Security Clearance</p>
                        </div>
                        <a href="{{ route('security.users') }}" class="zenith-button !py-3 !px-6 text-[10px]">
                            MANAGE DIRECTORY
                        </a>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach(\App\Models\Role::all() as $role)
                            <div class="p-5 bg-zenith-50 rounded-3xl border border-zenith-100/50">
                                <div class="flex items-center gap-3 mb-2">
                                    <div class="w-2 h-2 rounded-full bg-zenith-500"></div>
                                    <span
                                        class="text-xs font-black text-zenith-900 uppercase tracking-tight">{{ $role->name }}</span>
                                </div>
                                <p class="text-[9px] text-zenith-400 font-medium leading-relaxed">{{ $role->description }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function settingsTerminal() {
            return {
                config: {
                    app_name: '{{ $settings['app_name'] ?? 'Nipo v2.0 Enterprise' }}',
                    cors_protection: '{{ $settings['cors_protection'] ?? 'true' }}',
                    ai_telemetry: '{{ $settings['ai_telemetry'] ?? 'false' }}'
                },
                async updateSetting(key, value, type) {
                    try {
                        const response = await fetch('{{ route('settings.update') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ key, value, type })
                        });
                        const data = await response.json();
                        if (data.status === 'success') {
                            window.ZenithUI.toast('SUCCESS', `Configuration [${key}] synchronized`);
                        }
                    } catch (error) {
                        window.ZenithUI.toast('ERROR', 'Persistence failure');
                    }
                },
                toggleSetting(key) {
                    this.config[key] = this.config[key] === 'true' ? 'false' : 'true';
                    this.updateSetting(key, this.config[key], 'boolean');
                }
            }
        }
    </script>
@endsection