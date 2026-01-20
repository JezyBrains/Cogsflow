@extends('layouts.app')

@section('page_title', 'Help & Support')

@section('content')
    <div class="max-w-4xl mx-auto space-y-12">
        <!-- Header -->
        <div class="text-center space-y-4">
            <nav
                class="flex text-[10px] font-bold text-zenith-300 uppercase tracking-widest justify-center gap-2 items-center">
                <span>Support</span>
                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path>
                </svg>
                <span class="text-zenith-500">Resources</span>
            </nav>
            <h2 class="text-4xl font-display font-black text-zenith-900 tracking-tight">Need assistance?</h2>
            <p class="text-zenith-400 font-medium max-w-lg mx-auto">Explore our resource center or reach out to our
                dedicated support team for technical guidance.</p>
        </div>

        <!-- Support Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <a href="#"
                class="group p-8 bg-white rounded-[2.5rem] border border-zenith-100 shadow-zenith-sm hover:border-zenith-300 transition-all space-y-6 text-center lg:text-left">
                <div
                    class="w-16 h-16 rounded-3xl bg-zenith-50 flex items-center justify-center text-zenith-500 group-hover:bg-zenith-500 group-hover:text-white transition-all mx-auto lg:mx-0">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                        </path>
                    </svg>
                </div>
                <div class="space-y-2">
                    <h4 class="text-xl font-display font-black text-zenith-900">Documentation</h4>
                    <p class="text-[10px] text-zenith-400 font-bold uppercase tracking-widest">Learn the fundamentals</p>
                    <p class="text-sm text-zenith-500 font-medium leading-relaxed">Detailed guides on managing logistics,
                        financials, and system users.</p>
                </div>
            </a>

            <a href="#"
                class="group p-8 bg-white rounded-[2.5rem] border border-zenith-100 shadow-zenith-sm hover:border-zenith-300 transition-all space-y-6 text-center lg:text-left">
                <div
                    class="w-16 h-16 rounded-3xl bg-zenith-50 flex items-center justify-center text-zenith-500 group-hover:bg-zenith-500 group-hover:text-white transition-all mx-auto lg:mx-0">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z">
                        </path>
                    </svg>
                </div>
                <div class="space-y-2">
                    <h4 class="text-xl font-display font-black text-zenith-900">Direct Support</h4>
                    <p class="text-[10px] text-zenith-400 font-bold uppercase tracking-widest">Speak to an agent</p>
                    <p class="text-sm text-zenith-500 font-medium leading-relaxed">Get personalized assistance from our team
                        for critical operational issues.</p>
                </div>
            </a>
        </div>

        <!-- FAQ Preview -->
        <div class="bg-zenith-900 rounded-[3rem] p-12 text-white">
            <h4 class="text-2xl font-display font-black tracking-tight mb-8">Frequently Asked</h4>
            <div class="space-y-6">
                <div class="border-b border-white/10 pb-6">
                    <h5 class="text-sm font-bold mb-2">How do I reset my security credentials?</h5>
                    <p class="text-xs text-zenith-300 font-medium">Security settings are managed in the Identity Terminal by
                        administrators.</p>
                </div>
                <div class="pb-2">
                    <h5 class="text-sm font-bold mb-2">Can I export historical ledger data?</h5>
                    <p class="text-xs text-zenith-300 font-medium">Yes, the Analytics Hub provides full CSV and Excel export
                        options for all modules.</p>
                </div>
            </div>
        </div>
    </div>
@endsection