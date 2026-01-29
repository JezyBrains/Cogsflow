<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $app_settings['app_name'] ?? 'Nipo Agro Limited' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&family=Poppins:wght@300;400;500;600&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="font-sans antialiased bg-white text-gray-700">

    <!-- Navigation -->
    <nav class="fixed w-full z-50 bg-white/95 backdrop-blur-md border-b border-gray-100 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-24">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center gap-3">
                    <!-- Using the logic for dynamic logo but styling it to match Nipo Agro if needed -->
                    <div class="w-12 h-12 rounded-full border-2 border-[#2E7D32] flex items-center justify-center p-1">
                        <div
                            class="w-full h-full rounded-full bg-[#2E7D32] flex items-center justify-center text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xl font-extrabold text-[#2E7D32] leading-none tracking-tight">NIPO AGRO</span>
                        <span class="text-[9px] font-bold text-gray-400 tracking-[0.2em] uppercase">Limited</span>
                    </div>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-10">
                    <a href="#home"
                        class="text-sm font-semibold text-gray-600 hover:text-[#2E7D32] transition-colors uppercase tracking-wide">Home</a>
                    <a href="#about"
                        class="text-sm font-semibold text-gray-600 hover:text-[#2E7D32] transition-colors uppercase tracking-wide">About</a>
                    <a href="#services"
                        class="text-sm font-semibold text-gray-600 hover:text-[#2E7D32] transition-colors uppercase tracking-wide">Services</a>
                    <a href="#products"
                        class="text-sm font-semibold text-gray-600 hover:text-[#2E7D32] transition-colors uppercase tracking-wide">Product</a>

                    <a href="#contact"
                        class="px-6 py-3 rounded-full border-2 border-[#2E7D32] text-[#2E7D32] text-sm font-bold hover:bg-[#2E7D32] hover:text-white transition-all uppercase tracking-wide">
                        Contact Us
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- 1. Home Section -->
    <section id="home" class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <!-- Text Content -->
                <div>
                    <h1 class="text-5xl lg:text-7xl font-black text-gray-900 tracking-tight leading-[1.1] mb-8">
                        Global <br>
                        <span class="relative inline-block text-gray-900">
                            Agri-
                            <svg class="hand-drawn-oval" viewBox="0 0 200 80" preserveAspectRatio="none">
                                <path
                                    d="M5,40 C5,10 60,5 100,5 C150,5 195,15 195,40 C195,70 140,75 100,75 C50,75 5,65 5,40 Z" />
                            </svg>
                        </span>Traders
                    </h1>
                    <p class="text-lg text-gray-600 mb-10 leading-relaxed font-light max-w-xl">
                        We connect farmers, traders, and buyers worldwide with quality cereals, oil plants,
                        agrochemicals, and machinery. From planting to harvest, we deliver reliable agricultural
                        solutions for global markets.
                    </p>
                    <div class="flex flex-wrap gap-5">
                        <a href="#products"
                            class="px-10 py-4 rounded-full bg-[#2E7D32] text-white font-bold shadow-xl hover:bg-[#1b5e20] transition-all transform hover:-translate-y-1">
                            Explore Products
                        </a>
                        <a href="#contact"
                            class="px-10 py-4 rounded-full border-2 border-[#2E7D32] text-[#2E7D32] font-bold hover:bg-[#2E7D32] hover:text-white transition-all transform hover:-translate-y-1">
                            Contact Us
                        </a>
                    </div>
                </div>

                <!-- Hero Image Composition -->
                <div class="relative h-[600px] hidden lg:block">
                    <!-- Warehouse/Logistics Image -->
                    <div class="absolute top-0 right-0 w-3/4 h-3/5 rounded-[30px] overflow-hidden shadow-2xl z-10">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent z-10"></div>
                        <img src="https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?q=80&w=1000&auto=format&fit=crop"
                            class="w-full h-full object-cover" alt="Warehouse logistics">
                    </div>
                    <!-- Truck/Transport Image -->
                    <div
                        class="absolute bottom-10 left-0 w-3/5 h-1/2 rounded-[30px] overflow-hidden shadow-2xl z-20 border-4 border-white">
                        <img src="https://images.unsplash.com/photo-1601584115197-04ecc0da31d7?q=80&w=1000&auto=format&fit=crop"
                            class="w-full h-full object-cover" alt="Logistics transport">
                    </div>
                    <!-- Decorative Circle -->
                    <div
                        class="absolute top-1/2 left-1/4 w-32 h-32 bg-[#A2D149] rounded-full blur-3xl opacity-30 -z-10">
                    </div>
                </div>
            </div>

            <!-- Services Grid Preview -->
            <div class="mt-32 grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div
                    class="bg-white p-8 rounded-[20px] shadow-[0_10px_40px_-10px_rgba(0,0,0,0.08)] hover:shadow-[0_20px_40px_-10px_rgba(46,125,50,0.15)] transition-all group">
                    <h3 class="font-bold text-gray-900 text-lg mb-2 group-hover:text-[#2E7D32] transition-colors">
                        International</h3>
                    <p class="text-sm text-gray-500">Global trade logistics & compliance.</p>
                </div>
                <div
                    class="bg-white p-8 rounded-[20px] shadow-[0_10px_40px_-10px_rgba(0,0,0,0.08)] hover:shadow-[0_20px_40px_-10px_rgba(46,125,50,0.15)] transition-all group">
                    <h3 class="font-bold text-gray-900 text-lg mb-2 group-hover:text-[#2E7D32] transition-colors">
                        Warehousing</h3>
                    <p class="text-sm text-gray-500">Storage & moisture control.</p>
                </div>
                <div
                    class="bg-white p-8 rounded-[20px] shadow-[0_10px_40px_-10px_rgba(0,0,0,0.08)] hover:shadow-[0_20px_40px_-10px_rgba(46,125,50,0.15)] transition-all group">
                    <h3 class="font-bold text-gray-900 text-lg mb-2 group-hover:text-[#2E7D32] transition-colors">
                        Consultation</h3>
                    <p class="text-sm text-gray-500">Agronomy & market analysis.</p>
                </div>
                <div
                    class="bg-white p-8 rounded-[20px] shadow-[0_10px_40px_-10px_rgba(0,0,0,0.08)] hover:shadow-[0_20px_40px_-10px_rgba(46,125,50,0.15)] transition-all group">
                    <h3 class="font-bold text-gray-900 text-lg mb-2 group-hover:text-[#2E7D32] transition-colors">
                        Maintenance</h3>
                    <p class="text-sm text-gray-500">Installation & machinery support.</p>
                </div>
            </div>

            <!-- Why Choose Section -->
            <div class="mt-32 text-center">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">Why Choose Nipo Agro?</h2>
                <p class="text-gray-500 max-w-2xl mx-auto">We are industry leaders committed to quality, reliability,
                    and sustainable growth.</p>
            </div>

            <!-- Newsletter -->
            <div class="mt-20 max-w-3xl mx-auto bg-gray-50 rounded-[30px] p-10 text-center relative overflow-hidden">
                <div
                    class="absolute top-0 right-0 w-32 h-32 bg-[#A2D149] rounded-full blur-3xl opacity-20 transform translate-x-1/2 -translate-y-1/2">
                </div>

                <h3 class="text-2xl font-bold text-gray-900 mb-6 font-montserrat">Subscribe to our newsletter</h3>
                <form class="flex gap-4 max-w-md mx-auto relative z-10">
                    <input type="email" placeholder="Enter your email"
                        class="flex-1 rounded-full border-gray-200 px-6 py-4 focus:ring-2 focus:ring-[#2E7D32] focus:border-transparent outline-none">
                    <button type="button"
                        class="px-8 py-4 bg-[#2E7D32] text-white font-bold rounded-full hover:bg-[#1b5e20] transition-colors">Subscribe</button>
                </form>
            </div>
        </div>
    </section>

    <!-- 2. About Us Section -->
    <section id="about" class="py-24 bg-white relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-20 items-center">

                <!-- Content Left -->
                <div class="order-2 lg:order-1">
                    <h2 class="text-4xl lg:text-5xl font-black text-gray-900 mb-8 font-montserrat">
                        <span class="relative inline-block z-10">
                            About
                            <svg class="hand-drawn-oval" viewBox="0 0 200 80" preserveAspectRatio="none">
                                <path
                                    d="M5,40 C5,10 60,5 100,5 C150,5 195,15 195,40 C195,70 140,75 100,75 C50,75 5,65 5,40 Z" />
                            </svg>
                        </span>
                        us
                    </h2>

                    <div class="space-y-6 text-gray-600 leading-relaxed text-lg font-light">
                        <p>
                            We are a global agribusiness linking Africa's farmlands to world markets, trading grains,
                            oilseeds, agrochemicals, and machinery. Our mission is to provide sustainable, reliable
                            products that meet global standards while supporting farmers and buyers alike.
                        </p>
                        <p>
                            Every shipment is tested, certified, and supported with professional logistics for smooth
                            international trade. Beyond commodities, we offer agronomy support, training, and machinery
                            services tailored to client needs.
                        </p>
                        <p>
                            We envision modern, efficient agriculture that boosts food security and builds lasting
                            global partnerships.
                        </p>
                    </div>

                    <!-- Core Values Grid -->
                    <div class="grid md:grid-cols-3 gap-6 mt-12">
                        <div class="bg-gray-50 p-6 rounded-[20px] border border-gray-100">
                            <div
                                class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-[#2E7D32] mb-4">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <h4 class="font-bold text-gray-900 mb-2 font-montserrat">Mission</h4>
                            <p class="text-xs text-gray-500">Providing industry standard products via modern machinery.
                            </p>
                        </div>
                        <div class="bg-gray-50 p-6 rounded-[20px] border border-gray-100">
                            <div
                                class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-[#2E7D32] mb-4">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                    </path>
                                </svg>
                            </div>
                            <h4 class="font-bold text-gray-900 mb-2 font-montserrat">Vision</h4>
                            <p class="text-xs text-gray-500">Peak of the largest and most trusted exporters.</p>
                        </div>
                        <div class="bg-gray-50 p-6 rounded-[20px] border border-gray-100">
                            <div
                                class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-[#2E7D32] mb-4">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                    </path>
                                </svg>
                            </div>
                            <h4 class="font-bold text-gray-900 mb-2 font-montserrat">Values</h4>
                            <p class="text-xs text-gray-500">Quality, Loyalty, Reliability & Sustainability.</p>
                        </div>
                    </div>
                </div>

                <!-- Image Right -->
                <div class="order-1 lg:order-2 relative">
                    <div
                        class="absolute inset-0 bg-[#2E7D32] rounded-t-[150px] rounded-b-[20px] transform rotate-3 opacity-10">
                    </div>
                    <img src="https://images.unsplash.com/photo-1542838132-92c53300491e?q=80&w=1000&auto=format&fit=crop"
                        alt="Nipo Agro Representative"
                        class="relative w-full h-[600px] object-cover rounded-t-[150px] rounded-b-[20px] shadow-2xl z-10">
                </div>
            </div>
        </div>
    </section>

    <!-- 3. Services Page -->
    <section id="services" class="py-24 bg-[#F8FAF9]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-20">
                <h2 class="text-4xl lg:text-5xl font-black text-gray-900 font-montserrat">
                    Our
                    <span class="relative inline-block z-10">
                        Services
                        <svg class="hand-drawn-oval" viewBox="0 0 200 80" preserveAspectRatio="none">
                            <path
                                d="M5,40 C5,10 60,5 100,5 C150,5 195,15 195,40 C195,70 140,75 100,75 C50,75 5,65 5,40 Z" />
                        </svg>
                    </span>
                </h2>
                <p class="mt-6 text-gray-500 max-w-2xl mx-auto text-lg">
                    Empowering you designed to simplify international trade; measurement, quality assurance, packaging,
                    warehousing, insurance and logistics — all under one roof!
                </p>
            </div>

            <div class="space-y-24">
                <!-- Service 1 -->
                <div class="grid lg:grid-cols-2 gap-16 items-center">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1578575437130-527eed3abbec?q=80&w=1000&auto=format&fit=crop"
                            alt="International Trade" class="w-full h-80 object-cover rounded-[30px] shadow-lg">
                        <div
                            class="absolute -bottom-6 -right-6 w-24 h-24 bg-[#A2D149] rounded-full blur-2xl opacity-40">
                        </div>
                    </div>
                    <div>
                        <h3 class="text-3xl font-bold text-gray-900 mb-6 font-montserrat">International Trade</h3>
                        <p class="text-gray-600 leading-relaxed mb-6">
                            <strong>How it works:</strong> submission of LOI for analysis, agree quantity, destination &
                            window of responsibility. We handle all documentation and compliance to ensure seamless
                            cross-border movement.
                        </p>
                        <a href="#contact"
                            class="text-[#2E7D32] font-bold uppercase text-sm tracking-widest hover:underline">Get a
                            Quote</a>
                    </div>
                </div>

                <!-- Service 2 -->
                <div class="grid lg:grid-cols-2 gap-16 items-center">
                    <div class="lg:order-2 relative">
                        <img src="https://images.unsplash.com/photo-1553413077-190dd305871c?q=80&w=1000&auto=format&fit=crop"
                            alt="Warehousing" class="w-full h-80 object-cover rounded-[30px] shadow-lg">
                    </div>
                    <div class="lg:order-1">
                        <h3 class="text-3xl font-bold text-gray-900 mb-6 font-montserrat">Warehousing & Storage</h3>
                        <p class="text-gray-600 leading-relaxed mb-6">
                            <strong>Facilities:</strong> All moisture-controlled systems ensuring grain longevity. <br>
                            <strong>Services:</strong> Fumigation, temperature-detection systems, and pest control
                            management to maintain premium quality.
                        </p>
                        <a href="#contact"
                            class="text-[#2E7D32] font-bold uppercase text-sm tracking-widest hover:underline">Learn
                            More</a>
                    </div>
                </div>

                <!-- Service 3 -->
                <div class="grid lg:grid-cols-2 gap-16 items-center">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1530267981375-f0de937f5f13?q=80&w=1000&auto=format&fit=crop"
                            alt="Machinery" class="w-full h-80 object-cover rounded-[30px] shadow-lg">
                    </div>
                    <div>
                        <h3 class="text-3xl font-bold text-gray-900 mb-6 font-montserrat">Machinery Installation</h3>
                        <p class="text-gray-600 leading-relaxed mb-6">
                            <strong>Turnkey service:</strong> Delivery, installation, and operator training. <br>
                            <strong>Spares & logistics:</strong> Assured spare parts availability. <br>
                            <strong>Uptime focus:</strong> Preventative maintenance plans to maximize productivity and
                            reduce total cost of ownership.
                        </p>
                        <a href="#contact"
                            class="text-[#2E7D32] font-bold uppercase text-sm tracking-widest hover:underline">Inquire
                            Now</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 4. Products Page -->
    <section id="products" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-20">
                <h2 class="text-4xl lg:text-5xl font-black text-gray-900 font-montserrat">
                    Our
                    <span class="relative inline-block z-10">
                        Products
                        <svg class="hand-drawn-oval" viewBox="0 0 200 80" preserveAspectRatio="none">
                            <path
                                d="M5,40 C5,10 60,5 100,5 C150,5 195,15 195,40 C195,70 140,75 100,75 C50,75 5,65 5,40 Z" />
                        </svg>
                    </span>
                </h2>
                <p class="mt-6 text-gray-500 max-w-3xl mx-auto text-lg">
                    Premium export-grade cereals and oilseeds—sourced responsibly, graded precisely, and packaged
                    expertly. Available in bulk, container loads, and small packs to suit food processors, traders, and
                    distributors worldwide.
                </p>
            </div>

            <div class="grid md:grid-cols-2 gap-10">
                <!-- Product 1 -->
                <div
                    class="bg-white rounded-[30px] border border-gray-100 p-8 hover:shadow-2xl transition-all duration-300 group">
                    <div
                        class="w-full h-64 rounded-[20px] overflow-hidden mb-8 border-4 border-transparent group-hover:border-[#A2D149] transition-colors">
                        <img src="https://images.unsplash.com/photo-1632128713291-7243c21a4175?q=80&w=1000&auto=format&fit=crop"
                            class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-700"
                            alt="White Maize">
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4 font-montserrat">White Maize</h3>
                    <p class="text-gray-600 mb-4"><strong>Product focus:</strong> Food grade and feed grade maize.</p>
                    <p class="text-gray-600 mb-6"><strong>Buyer benefits:</strong> Consistent quality for milling and
                        feed.</p>
                </div>

                <!-- Product 2 -->
                <div
                    class="bg-white rounded-[30px] border border-gray-100 p-8 hover:shadow-2xl transition-all duration-300 group">
                    <div
                        class="w-full h-64 rounded-[20px] overflow-hidden mb-8 border-4 border-transparent group-hover:border-[#A2D149] transition-colors">
                        <img src="https://images.unsplash.com/photo-1586201375761-83865001e31c?q=80&w=1000&auto=format&fit=crop"
                            class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-700"
                            alt="White Rice">
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4 font-montserrat">White Rice</h3>
                    <p class="text-gray-600 mb-4"><strong>Premium specs:</strong> Double polished, low broken levels.
                    </p>
                    <p class="text-gray-600 mb-6"><strong>Packaging:</strong> 25kg or 50kg bags.</p>
                </div>

                <!-- Product 3 -->
                <div
                    class="bg-white rounded-[30px] border border-gray-100 p-8 hover:shadow-2xl transition-all duration-300 group">
                    <div
                        class="w-full h-64 rounded-[20px] overflow-hidden mb-8 border-4 border-transparent group-hover:border-[#A2D149] transition-colors">
                        <img src="https://images.unsplash.com/photo-1453230806017-56d81464b6c5?q=80&w=1000&auto=format&fit=crop"
                            class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-700"
                            alt="Sorghum">
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4 font-montserrat">Sorghum / Millet / Barley</h3>
                    <p class="text-gray-600 mb-4"><strong>Product focus:</strong> Drought-tolerant cereals.</p>
                    <p class="text-gray-600 mb-6"><strong>Buyer benefits:</strong> Reliable staple supply.</p>
                </div>

                <!-- Product 4 -->
                <div
                    class="bg-white rounded-[30px] border border-gray-100 p-8 hover:shadow-2xl transition-all duration-300 group">
                    <div
                        class="w-full h-64 rounded-[20px] overflow-hidden mb-8 border-4 border-transparent group-hover:border-[#A2D149] transition-colors">
                        <img src="https://images.unsplash.com/photo-1518977676601-b53f82aba655?q=80&w=1000&auto=format&fit=crop"
                            class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-700"
                            alt="Oilseeds">
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4 font-montserrat">Oilseeds / Oil Plants</h3>
                    <p class="text-gray-600 mb-4"><strong>Varieties:</strong> Soybean, Sunflower, Sesame.</p>
                    <p class="text-gray-600 mb-6"><strong>Product focus:</strong> High-oil and high-protein seed;
                        cleaned and dried for crushing, oil extraction, and roasting.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 5. Contact Us -->
    <section id="contact" class="py-24 bg-[#F8FAF9] pattern-grid relative">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

            <div class="text-center mb-16">
                <h2 class="text-4xl lg:text-5xl font-black text-gray-900 font-montserrat">
                    <span class="relative inline-block z-10">
                        Contact
                        <svg class="hand-drawn-oval" viewBox="0 0 200 80" preserveAspectRatio="none">
                            <path
                                d="M5,40 C5,10 60,5 100,5 C150,5 195,15 195,40 C195,70 140,75 100,75 C50,75 5,65 5,40 Z" />
                        </svg>
                    </span>
                    us
                </h2>
                <!-- Support Agent Image (Small and Round) -->
                <div class="mt-8">
                    <img src="https://images.unsplash.com/photo-1560250097-0b93528c311a?q=80&w=1000&auto=format&fit=crop"
                        alt="Support Agent"
                        class="w-24 h-24 rounded-full border-4 border-white shadow-lg mx-auto object-cover">
                    <p class="text-gray-500 mt-4 text-sm">We're here to help you.</p>
                </div>
            </div>

            <div class="bg-white rounded-[30px] shadow-xl p-10 lg:p-16">
                <form class="space-y-8">
                    <div class="grid grid-cols-2 gap-8">
                        <div>
                            <label class="block text-sm font-bold text-gray-800 mb-2 font-montserrat">Full Name</label>
                            <input type="text" placeholder="John Doe"
                                class="w-full bg-[#FAFAFA] border border-gray-100 rounded-xl px-6 py-4 focus:outline-none focus:ring-2 focus:ring-[#2E7D32] transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-800 mb-2 font-montserrat">Subject</label>
                            <input type="text" placeholder="Inquiry..."
                                class="w-full bg-[#FAFAFA] border border-gray-100 rounded-xl px-6 py-4 focus:outline-none focus:ring-2 focus:ring-[#2E7D32] transition-all">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-8">
                        <div>
                            <label class="block text-sm font-bold text-gray-800 mb-2 font-montserrat">Phone
                                Number</label>
                            <input type="text" placeholder="+123..."
                                class="w-full bg-[#FAFAFA] border border-gray-100 rounded-xl px-6 py-4 focus:outline-none focus:ring-2 focus:ring-[#2E7D32] transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-800 mb-2 font-montserrat">Email</label>
                            <input type="email" placeholder="johndoe@gmail.com"
                                class="w-full bg-[#FAFAFA] border border-gray-100 rounded-xl px-6 py-4 focus:outline-none focus:ring-2 focus:ring-[#2E7D32] transition-all">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-800 mb-2 font-montserrat">Enquiry Body</label>
                        <textarea rows="4" placeholder="How can we help you?"
                            class="w-full bg-[#FAFAFA] border border-gray-100 rounded-xl px-6 py-4 focus:outline-none focus:ring-2 focus:ring-[#2E7D32] transition-all"></textarea>
                    </div>

                    <div class="text-center pt-4">
                        <button type="submit"
                            class="w-full lg:w-1/2 bg-[#2E7D32] text-white font-bold py-5 rounded-xl hover:bg-[#1b5e20] transition-all shadow-xl shadow-green-200 text-lg uppercase tracking-wide">
                            Send Message
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white py-20 border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-12 text-sm text-gray-600">
                <!-- Address -->
                <div class="space-y-4">
                    <p class="font-bold text-gray-900 font-montserrat">Headquarters</p>
                    <p>43, Kisasa Street, Kisasa Road,<br>Dodoma, Tanzania.</p>
                </div>

                <!-- Phones -->
                <div class="space-y-4">
                    <p class="font-bold text-gray-900 font-montserrat">Call Us</p>
                    <p>0714349614<br>0713671675</p>
                </div>

                <!-- Emails -->
                <div class="space-y-4">
                    <p class="font-bold text-gray-900 font-montserrat">Email Us</p>
                    <p>info@nipoagro.com<br>sales@nipoagro.com</p>
                </div>

                <!-- Socials -->
                <div class="space-y-4 text-center md:text-right">
                    <p class="font-bold text-gray-900 font-montserrat">Follow Us</p>
                    <p class="text-[#2E7D32] font-bold">nipoagro.insights</p>
                    <div class="flex gap-4 justify-center md:justify-end mt-2 text-[#2E7D32]">
                        <!-- Instagram -->
                        <svg class="w-5 h-5 cursor-pointer hover:scale-110 transition-transform" fill="currentColor"
                            viewBox="0 0 24 24">
                            <path
                                d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                        </svg>
                        <!-- LinkedIn -->
                        <svg class="w-5 h-5 cursor-pointer hover:scale-110 transition-transform" fill="currentColor"
                            viewBox="0 0 24 24">
                            <path
                                d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="mt-16 pt-8 border-t border-gray-100 text-center text-xs text-gray-400">
                &copy; {{ date('Y') }} Nipo Agro Limited. All rights reserved.
            </div>
        </div>
    </footer>

</body>

</html>