<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Zenith Portal - Nipo Enterprise</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-zenith-50 h-full w-full flex items-center justify-center font-sans antialiased text-zenith-800">

    <!-- Zenith Login Shell -->
    <div class="w-full max-w-[440px] p-6 relative">
        <div class="zenith-card-elevated p-12 bg-white relative">

            <!-- Branding Header -->
            <div class="text-center mb-10">
                <div
                    class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-zenith-500 shadow-zenith-sm mb-6">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <h2 class="text-3xl font-display font-black text-zenith-900 tracking-tight mb-2">Executive Portal</h2>
                <p class="text-zenith-400 text-sm font-bold uppercase tracking-widest">Enterprise Access Gateway</p>
            </div>

            <!-- Login Form -->
            <form action="{{ route('login') }}" method="POST" class="space-y-6">
                @csrf

                <div class="space-y-2">
                    <label for="email"
                        class="text-[10px] font-bold text-zenith-400 uppercase tracking-widest ml-1">Access Protocol
                        (Email)</label>
                    <div class="relative">
                        <input type="email" name="email" id="email" required class="zenith-input"
                            placeholder="executive@nipo.io">
                    </div>
                </div>

                <div class="space-y-2">
                    <label for="password"
                        class="text-[10px] font-bold text-zenith-400 uppercase tracking-widest ml-1">Security
                        Key</label>
                    <div class="relative">
                        <input type="password" name="password" id="password" required class="zenith-input"
                            placeholder="••••••••••••">
                    </div>
                </div>

                <div class="flex items-center justify-between text-[11px] font-bold tracking-tight px-1 mt-2">
                    <label
                        class="flex items-center gap-2 cursor-pointer transition-colors hover:text-zenith-900 text-zenith-400">
                        <input type="checkbox"
                            class="w-4 h-4 rounded-lg border-zenith-200 text-zenith-500 focus:ring-4 focus:ring-zenith-500/10 transition-all">
                        <span>Maintain Identity</span>
                    </label>
                    <a href="#"
                        class="text-zenith-500 hover:text-zenith-700 transition-colors uppercase tracking-widest">Recover
                        Access</a>
                </div>

                <button type="submit" class="zenith-button w-full mt-4">
                    <span class="tracking-[0.1em] uppercase">Initialize Session</span>
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </button>
            </form>
        </div>

        <!-- Terminal Signature -->
        <div class="mt-10 text-center">
            <div class="flex items-center justify-center gap-3 mb-2">
                <div class="h-px w-8 bg-zenith-200"></div>
                <p class="text-[9px] uppercase font-bold tracking-[0.4em] text-zenith-300">
                    Trusted Enterprise Governance
                </p>
                <div class="h-px w-8 bg-zenith-200"></div>
            </div>
            <p class="text-[8px] font-bold text-zenith-400 uppercase tracking-widest">
                Node ID: {{ request()->ip() }} &bull; System V3.0
            </p>
        </div>
    </div>
</body>

</html>