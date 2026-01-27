@extends('layouts.app')

@section('title', 'Identity Mutation | ' . $user->name)
@section('page_title', 'Access Governance')

@section('content')
    <div class="max-w-4xl mx-auto space-y-10">
        <!-- Breadcrumb & Header -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('security.users') }}"
                    class="w-10 h-10 rounded-xl bg-zenith-50 flex items-center justify-center text-zenith-500 hover:bg-zenith-900 hover:text-white transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <div>
                    <span class="text-[10px] font-bold text-zenith-400 uppercase tracking-[0.2em] block mb-1">Clearance
                        Protocol Mutation</span>
                    <h2 class="text-3xl font-display font-black text-zenith-900 tracking-tight uppercase italic">Modify
                        Identity</h2>
                </div>
            </div>
        </div>

        <!-- Configuration Terminal -->
        <div class="zenith-card-elevated bg-white p-10 md:p-14 relative overflow-hidden">
            <form action="{{ route('security.users.update', $user->id) }}" method="POST" class="space-y-10 relative z-10">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <!-- Primary Identity -->
                    <div class="space-y-6">
                        <h3 class="text-xs font-black text-zenith-300 uppercase tracking-[0.3em] flex items-center gap-2">
                            <span class="w-1.5 h-1.5 bg-zenith-500 rounded-full"></span>
                            Legal Designation
                        </h3>

                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-zenith-500 uppercase tracking-widest ml-1">Full
                                Personnel Name</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                class="zenith-input" placeholder="e.g. Marcus Aurelius">
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-zenith-500 uppercase tracking-widest ml-1">Digital Node
                                (Email)</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                class="zenith-input" placeholder="marcus@nipo.io">
                        </div>
                    </div>

                    <!-- Security Access -->
                    <div class="space-y-6">
                        <h3 class="text-xs font-black text-zenith-300 uppercase tracking-[0.3em] flex items-center gap-2">
                            <span class="w-1.5 h-1.5 bg-zenith-500 rounded-full"></span>
                            Security Credentials
                        </h3>

                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-zenith-500 uppercase tracking-widest ml-1">Update
                                Access Key (Optional)</label>
                            <input type="password" name="password" class="zenith-input"
                                placeholder="Leave blank to maintain current encryption">
                            <p class="text-[9px] text-zenith-400 font-bold uppercase mt-2 italic px-1">Minimum 8 characters
                                required for new protocols.</p>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <h3 class="text-xs font-black text-zenith-300 uppercase tracking-[0.3em] flex items-center gap-2">
                        <span class="w-1.5 h-1.5 bg-zenith-500 rounded-full"></span>
                        Security Clearance Vectors
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach($roles as $role)
                            <label
                                class="flex items-center justify-between p-4 rounded-xl bg-zenith-50 border border-zenith-100 cursor-pointer hover:border-zenith-500/30 transition-all group {{ $user->hasRole($role->slug) ? 'border-zenith-500/50 bg-zenith-50/50' : '' }}">
                                <div class="flex items-center gap-3">
                                    <input type="checkbox" name="roles[]" value="{{ $role->slug }}" {{ $user->hasRole($role->slug) ? 'checked' : '' }}
                                        class="w-4 h-4 rounded border-zenith-200 text-zenith-500 focus:ring-4 focus:ring-zenith-500/10 transition-all">
                                    <span
                                        class="text-[10px] font-black text-zenith-800 uppercase tracking-widest transition-colors group-hover:text-zenith-500">{{ $role->name }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Action Matrix -->
                <div class="pt-10 flex flex-col md:flex-row gap-6 border-t border-zenith-50">
                    <a href="{{ route('security.users') }}" class="flex-1 zenith-button-outline text-center">
                        Abort Protocol
                    </a>
                    <button type="submit" class="flex-[2] zenith-button shadow-zenith-lg">
                        Update Identity Configuration
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection