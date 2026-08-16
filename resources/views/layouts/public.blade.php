<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php
        $metaTitle = trim($__env->yieldContent('title')) ?: 'ตำบลท่าสาป';
        $metaDescription = trim(strip_tags($__env->yieldContent('description'))) ?: (__('site.footer.tagline') . ' — ' . __('site.home.hero_badge'));
        $metaImage = trim($__env->yieldContent('og_image')) ?: asset('images/logo.png');
    @endphp
    <title>{!! $metaTitle !!}</title>
    <meta name="description" content="{!! $metaDescription !!}">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="ตำบลท่าสาป">
    <meta property="og:locale" content="{{ app()->getLocale() === 'th' ? 'th_TH' : 'ms_MY' }}">
    <meta property="og:title" content="{!! $metaTitle !!}">
    <meta property="og:description" content="{!! $metaDescription !!}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{!! $metaImage !!}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{!! $metaTitle !!}">
    <meta name="twitter:description" content="{!! $metaDescription !!}">
    <meta name="twitter:image" content="{!! $metaImage !!}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>:root { --navbar-offset: 6.5rem; }</style>
</head>
<body class="bg-[#f7f5f0] overflow-x-hidden" style="font-family: 'Sarabun', sans-serif;">

    {{-- Navbar --}}
    <nav id="navbar" class="fixed top-0 left-0 right-0 z-50 px-4 md:px-6 py-3 flex items-center justify-between transition-all duration-300 bg-transparent">
        <a href="/" class="flex items-center gap-2 flex-shrink-0">
            <img src="/images/logo.png" class="h-20 w-auto" alt="ท่าสาป">
            <div>
                <div class="text-white text-base font-medium">ท่าสาป</div>
                <div class="text-white/70 text-xs">ตำบลท่าสาป จ.ยะลา</div>
            </div>
        </a>

        {{-- Mobile Menu Button --}}
        <button id="menu-btn" class="md:hidden text-white p-2">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>

        {{-- Desktop Menu --}}
        <div class="hidden md:flex items-center gap-6">
            <a href="/" class="text-white/90 text-sm hover:text-white">{{ __('site.nav.home') }}</a>
            <a href="/products" class="text-white/90 text-sm hover:text-white">{{ __('site.nav.products') }}</a>
            <a href="/attractions" class="text-white/90 text-sm hover:text-white">{{ __('site.nav.attractions') }}</a>
            <a href="/map" class="text-white/90 text-sm hover:text-white">{{ __('site.nav.map') }}</a>
            <a href="/posts" class="text-white/90 text-sm hover:text-white">{{ __('site.nav.posts') }}</a>
            <a href="/villages" class="text-white/90 text-sm hover:text-white">{{ __('site.nav.villages') }}</a>

            {{-- Language Switcher --}}
            <div class="flex items-center gap-1 border-l border-white/30 pl-4">
                <a href="{{ route('lang.switch', 'th') }}" class="text-xs px-2 py-1 rounded-full {{ app()->getLocale() === 'th' ? 'bg-white text-[#2d5a27] font-medium' : 'text-white/70 hover:text-white' }}">TH</a>
                <a href="{{ route('lang.switch', 'ms') }}" class="text-xs px-2 py-1 rounded-full {{ app()->getLocale() === 'ms' ? 'bg-white text-[#2d5a27] font-medium' : 'text-white/70 hover:text-white' }}">MS</a>
            </div>
        </div>
    </nav>

    {{-- Mobile Menu --}}
    <div id="mobile-menu" class="hidden fixed top-[6.5rem] left-0 right-0 z-50 bg-[#1a3d16] md:hidden">
        <div class="flex flex-col px-4 py-3 gap-3">
            <a href="/" class="text-[#c8e6c0] text-sm py-2 border-b border-[#2d5a27]">{{ __('site.nav.home') }}</a>
            <a href="/products" class="text-[#c8e6c0] text-sm py-2 border-b border-[#2d5a27]">{{ __('site.nav.products') }}</a>
            <a href="/attractions" class="text-[#c8e6c0] text-sm py-2 border-b border-[#2d5a27]">{{ __('site.nav.attractions') }}</a>
            <a href="/map" class="text-[#c8e6c0] text-sm py-2 border-b border-[#2d5a27]">{{ __('site.nav.map') }}</a>
            <a href="/posts" class="text-[#c8e6c0] text-sm py-2 border-b border-[#2d5a27]">{{ __('site.nav.posts') }}</a>
            <a href="/villages" class="text-[#c8e6c0] text-sm py-2 border-b border-[#2d5a27]">{{ __('site.nav.villages') }}</a>

            {{-- Language Switcher --}}
            <div class="flex items-center gap-2 py-2">
                <a href="{{ route('lang.switch', 'th') }}" class="text-xs px-3 py-1 rounded-full {{ app()->getLocale() === 'th' ? 'bg-white text-[#2d5a27] font-medium' : 'text-[#c8e6c0] border border-[#2d5a27]' }}">ไทย (TH)</a>
                <a href="{{ route('lang.switch', 'ms') }}" class="text-xs px-3 py-1 rounded-full {{ app()->getLocale() === 'ms' ? 'bg-white text-[#2d5a27] font-medium' : 'text-[#c8e6c0] border border-[#2d5a27]' }}">Melayu (MS)</a>
            </div>
        </div>
    </div>

    {{-- Content --}}
    @yield('content')

    {{-- Footer --}}
    {{-- หมายเหตุ: ที่อยู่/เบอร์โทร/อีเมล เป็นข้อมูลชั่วคราว รอผู้ดูแลแก้ไขเป็นข้อมูลจริง --}}
    <footer class="bg-[#2d5a27] px-6 py-10 mt-12">
        <div class="max-w-5xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8 text-center md:text-left">
            <div>
                <p class="text-[#e8f5e3] text-base font-medium">ตำบลท่าสาป</p>
                <p class="text-[#8fc085] text-sm mt-1">{{ __('site.footer.tagline') }}</p>
            </div>

            <div>
                <h3 class="text-[#e8f5e3] text-sm font-medium mb-3">{{ __('site.footer.contact_us') }}</h3>
                <ul class="text-[#c8e6c0] text-sm space-y-2">
                    <li>{{ __('site.footer.office_name') }}<br>{{ __('site.footer.address_line') }}</li>
                    <li><a href="tel:073000000" class="hover:text-white">โทร. 073-000-000</a></li>
                    <li><a href="mailto:contact@thasap.go.th" class="hover:text-white">contact@thasap.go.th</a></li>
                </ul>
            </div>

            <div>
                <h3 class="text-[#e8f5e3] text-sm font-medium mb-3">{{ __('site.footer.follow_us') }}</h3>
                <div class="flex gap-3 justify-center md:justify-start">
                    <a
                        href="https://www.facebook.com/thasapyala/"
                        target="_blank"
                        rel="noopener"
                        aria-label="Facebook"
                        class="w-9 h-9 flex items-center justify-center bg-white/10 rounded-full hover:bg-white/20 transition-colors"
                    >
                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12.06C22 6.5 17.52 2 12 2S2 6.5 2 12.06c0 5 3.66 9.15 8.44 9.94v-7.03H7.9v-2.91h2.54V9.85c0-2.5 1.49-3.89 3.77-3.89 1.09 0 2.24.2 2.24.2v2.46h-1.26c-1.24 0-1.63.77-1.63 1.56v1.88h2.78l-.44 2.91h-2.34V22c4.78-.79 8.44-4.94 8.44-9.94Z"/></svg>
                    </a>
                    <a
                        href="https://line.me/R/ti/p/@428etzji"
                        target="_blank"
                        rel="noopener"
                        aria-label="LINE"
                        class="w-9 h-9 flex items-center justify-center bg-white/10 rounded-full hover:bg-white/20 transition-colors"
                    >
                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M19.365 9.863c.349 0 .63.285.63.631 0 .349-.281.631-.63.631h-1.755v1.125h1.755c.349 0 .63.283.63.63 0 .349-.281.631-.63.631h-2.386c-.348 0-.629-.282-.629-.631V8.108c0-.348.281-.63.63-.63h2.385c.349 0 .63.282.63.63 0 .35-.281.631-.63.631h-1.755v1.125h1.755zm-3.855 3.016c0 .27-.174.51-.432.596-.064.021-.133.031-.199.031-.211 0-.391-.09-.51-.25l-2.443-3.317v2.94c0 .349-.282.63-.631.63-.346 0-.626-.281-.626-.63V8.108c0-.27.173-.51.43-.595.06-.023.136-.033.194-.033.195 0 .375.104.495.254l2.462 3.33V8.108c0-.348.282-.63.63-.63.35 0 .63.282.63.63v4.771zm-5.741 0c0 .349-.282.63-.631.63-.349 0-.631-.281-.631-.63V8.108c0-.348.282-.63.631-.63.349 0 .631.282.631.63v4.771zm-2.466.63H4.917c-.349 0-.631-.282-.631-.63V8.108c0-.348.282-.63.63-.63.35 0 .63.282.63.63v4.14h1.756c.349 0 .629.283.629.631 0 .349-.28.631-.63.631M24 10.314C24 4.943 18.615.575 12 .575S0 4.943 0 10.314c0 4.811 4.27 8.842 10.037 9.608.391.082.923.258 1.058.59.12.301.079.766.038 1.08l-.164.98c-.045.301-.24 1.177 1.032.641 1.271-.539 6.858-4.038 9.357-6.914C23.176 14.393 24 12.458 24 10.314"/></svg>
                    </a>
                    <a
                        href="https://www.youtube.com/channel/UCUgY-es3wB_16T3D3stSI_Q"
                        target="_blank"
                        rel="noopener"
                        aria-label="YouTube"
                        class="w-9 h-9 flex items-center justify-center bg-white/10 rounded-full hover:bg-white/20 transition-colors"
                    >
                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                    </a>
                </div>
            </div>
        </div>

        <div class="border-t border-white/10 max-w-5xl mx-auto mt-8 pt-6 text-center">
            <p class="text-[#8fc085] text-xs">© {{ date('Y') }} ตำบลท่าสาป {{ __('site.footer.copyright') }}</p>
        </div>
    </footer>

    <script>
        const navbar = document.getElementById('navbar');
        let lastScroll = 0;
        const heroHeight = window.innerHeight;

        // ถ้าไม่มี hero-slider ให้ navbar มีสีเขียวเลย
        if (!document.getElementById('hero-slider')) {
            navbar.classList.remove('bg-transparent');
            navbar.classList.add('bg-[#2d5a27]', 'shadow-md');
        }

        // อัปเดตความสูงจริงของ navbar ไว้เป็น CSS variable เพื่อให้
        // แถบที่ sticky ต่อจาก navbar (เช่น แถบตัวกรอง) อ้างอิงตำแหน่งได้ถูกต้อง
        // ไม่ว่า navbar จะซ่อนหรือแสดงอยู่ตอนนั้น
        function updateNavbarOffset() {
            const hidden = navbar.classList.contains('-translate-y-full');
            document.documentElement.style.setProperty('--navbar-offset', hidden ? '0px' : navbar.offsetHeight + 'px');
        }

        let scrollTicking = false;

        function handleScroll() {
            const currentScroll = window.scrollY;

            if (document.getElementById('hero-slider')) {
                if (currentScroll <= 50) {
                    navbar.classList.remove('bg-[#2d5a27]', 'shadow-md', '-translate-y-full');
                    navbar.classList.add('bg-transparent');
                } else if (currentScroll < heroHeight) {
                    navbar.classList.remove('bg-transparent', '-translate-y-full');
                    navbar.classList.add('bg-[#2d5a27]', 'shadow-md');
                } else {
                    if (currentScroll > lastScroll) {
                        navbar.classList.add('-translate-y-full');
                    } else {
                        navbar.classList.remove('-translate-y-full');
                        navbar.classList.add('bg-[#2d5a27]', 'shadow-md');
                    }
                }
            } else {
                if (currentScroll > lastScroll && currentScroll > 100) {
                    navbar.classList.add('-translate-y-full');
                } else {
                    navbar.classList.remove('-translate-y-full');
                }
            }

            updateNavbarOffset();
            lastScroll = currentScroll;
            scrollTicking = false;
        }

        window.addEventListener('scroll', () => {
            if (!scrollTicking) {
                window.requestAnimationFrame(handleScroll);
                scrollTicking = true;
            }
        });

        updateNavbarOffset();

        document.getElementById('menu-btn').addEventListener('click', function() {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });
    </script>

</body>
</html>
