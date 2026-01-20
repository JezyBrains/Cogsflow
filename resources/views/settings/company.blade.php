@extends('layouts.app')

@section('page_title', 'Company Profile')

@section('content')
    <div class="space-y-10">
        <!-- Header -->
        <div>
            <nav class="flex text-[10px] font-bold text-zenith-300 uppercase tracking-widest mb-2 gap-2 items-center">
                <span>System</span>
                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path>
                </svg>
                <span class="text-zenith-500">Company Intelligence</span>
            </nav>
            <h2 class="text-3xl font-display font-black text-zenith-900 tracking-tight">Company Profile</h2>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-12 items-start">
            <!-- Sidebar Navigation -->
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-[2.5rem] border border-zenith-100 shadow-zenith-sm p-5 space-y-2">
                    <a href="{{ route('settings.index') }}"
                        class="flex items-center gap-3 px-5 py-4 rounded-3xl text-zenith-400 hover:bg-zenith-50 hover:text-zenith-800 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4">
                            </path>
                        </svg>
                        General Configuration
                    </a>
                    <a href="#"
                        class="flex items-center gap-3 px-5 py-4 rounded-3xl bg-zenith-50 text-zenith-500 font-bold transition-all">
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
                </div>
            </div>

            <div class="lg:col-span-3 space-y-8">
                <div
                    class="bg-white rounded-[2.5rem] border border-zenith-100 shadow-zenith-sm p-20 flex flex-col items-center text-center">
                    <div class="w-20 h-20 rounded-3xl bg-zenith-50 flex items-center justify-center text-zenith-300 mb-6">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-black text-zenith-900 mb-2">Corporate Identity Vault</h3>
                    <p class="text-sm text-zenith-400 font-medium max-w-md mx-auto mb-8">This module scales with your
                        organizational growth. Core company parameters are currently locked to the Master Genesis record.
                    </p>
                    <div class="flex items-center gap-4">
                        <div
                            class="px-6 py-3 rounded-2xl bg-zenith-50 text-zenith-500 font-bold text-[10px] uppercase tracking-widest border border-zenith-100">
                            MASTER GENESIS STATUS: ACTIVE
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection