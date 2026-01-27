<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600&display=swap" rel="stylesheet">
  <style> body { font-family: 'Sarabun', sans-serif; } </style>
  <title>เข้าสู่ระบบ</title>
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500" x-data="{ loading: false }">
  
  <!-- Loader -->
  <div x-show="loading" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" style="display: none;">
    <div class="animate-spin rounded-full h-12 w-12 border-4 border-white/30 border-t-white"></div>
  </div>

  <div class="w-full max-w-md bg-white/90 backdrop-blur-md shadow-2xl rounded-2xl p-8 space-y-8 mx-4 border border-white/20">
    {{-- Header --}}
    <div class="text-center space-y-2">
      @if(isset($client))
        <!-- <img src="{{ $client->logo_uri }}" alt="{{ $client->name }}" class="h-20 mx-auto mb-4 object-contain"> -->
        <h1 class="text-2xl font-bold text-gray-800">เข้าสู่ระบบเพื่อใช้งาน</h1>
        <p class="text-indigo-600 font-semibold text-lg">{{ $client->name }}</p>
      @else
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-indigo-100 text-indigo-600 mb-4">
          <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
        </div>
        <h1 class="text-3xl font-bold text-gray-800">ยินดีต้อนรับ</h1>
        <p class="text-gray-500">ลงชื่อเข้าใช้เพื่อจัดการระบบ</p>
      @endif
    </div>

    {{-- Alerts (centralized) --}}
    @include('partials.alerts')

    {{-- Login Form (แสดงเฉพาะตอนยังไม่ล็อกอิน) --}}
    @guest
    <form class="space-y-6" method="POST" action="{{ route('login.submit') }}" @submit="loading = true">
      @csrf
      <div class="space-y-2">
        <label class="text-sm font-medium text-gray-700 ml-1">อีเมล หรือ ชื่อผู้ใช้</label>
        <input type="text" name="login" value="{{ old('login') }}" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition bg-gray-50 focus:bg-white" placeholder="name@example.com หรือ username">
      </div>
      
      <div class="space-y-2">
        <label class="text-sm font-medium text-gray-700 ml-1">รหัสผ่าน</label>
        <input type="password" name="password" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition bg-gray-50 focus:bg-white" placeholder="••••••••">
      </div>

      <div class="flex items-center justify-between text-sm">
        <label class="inline-flex items-center space-x-2 cursor-pointer">
          <input type="checkbox" name="remember" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
          <span class="text-gray-600">จดจำฉัน</span>
        </label>
        <a href="{{ route('forgot.page') }}" class="text-indigo-600 hover:text-indigo-800 font-medium">ลืมรหัสผ่าน?</a>
      </div>

      <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl hover:from-indigo-700 hover:to-purple-700 transform hover:-translate-y-0.5 transition-all duration-200">
        เข้าสู่ระบบ
      </button>

      @if(($settings['social_login_google_enable'] ?? '0') == '1')
      <div class="relative my-6">
        <div class="absolute inset-0 flex items-center">
          <div class="w-full border-t border-gray-200"></div>
        </div>
        <div class="relative flex justify-center text-sm">
          <span class="px-2 bg-white text-gray-500">หรือเข้าสู่ระบบด้วย</span>
        </div>
      </div>

      <div class="grid grid-cols-1 gap-3">
        @if(($settings['social_login_google_enable'] ?? '0') == '1')
        <a href="{{ route('social.redirect', 'google') }}" class="flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
          <svg class="h-5 w-5 mr-2" viewBox="0 0 24 24"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
          Google
        </a>
        @endif
      </div>
      @endif
    </form>

    @if(($settings['allow_registration'] ?? '1') == '1')
    <div class="text-center mt-4">
      <a href="{{ route('register.page') }}" class="inline-block px-4 py-2 bg-white border border-gray-300 rounded-lg text-indigo-600 font-medium hover:bg-gray-50">สมัครสมาชิก</a>
    </div>
    @endif

    @endguest

    {{-- Logged In State (แสดงตอนล็อกอินแล้ว) --}}
    @auth
    <div class="text-center space-y-4">
        <div class="p-4 bg-indigo-50 rounded-xl border border-indigo-100">
            <p class="text-sm text-gray-600">คุณเข้าสู่ระบบแล้วในชื่อ</p>
            <p class="font-bold text-indigo-700 text-lg">{{ auth()->user()->name }}</p>
            <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
        </div>
        
        <div class="grid grid-cols-2 gap-3">
            <a href="{{ route('user.dashboard') }}" class="py-2 px-4 bg-white border border-gray-200 text-gray-700 rounded-lg hover:bg-gray-50 font-medium shadow-sm flex items-center justify-center">
                Dashboard
            </a>
            <form method="POST" action="{{ route('logout') }}" @submit="loading = true">
                @csrf
                <button type="submit" class="w-full py-2 px-4 bg-red-50 text-red-600 border border-red-100 rounded-lg hover:bg-red-100 font-medium">
                    ออกจากระบบ
                </button>
            </form>
        </div>
    </div>
    @endauth

    {{-- Footer --}}
    <div class="text-center text-xs text-gray-400 mt-8">
      <div class="space-x-4 mb-2">
        <a href="{{ route('policy') }}" class="hover:text-gray-600">นโยบายความเป็นส่วนตัว</a>
        <span>&bull;</span>
        <a href="{{ route('terms') }}" class="hover:text-gray-600">ข้อกำหนดการใช้งาน</a>
        <span>&bull;</span>
        <a href="{{ route('help') }}" class="hover:text-gray-600">ช่วยเหลือ</a>
      </div>
      <p>&copy; {{ date('Y') }} SSO System. All rights reserved.</p>
    </div>

  </div>
</body>
</html>
