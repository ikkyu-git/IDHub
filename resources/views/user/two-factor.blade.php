<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Two-Factor Authentication</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Sarabun', sans-serif; }</style>
</head>
<body class="bg-gray-50 text-gray-800">
    <div class="max-w-4xl mx-auto px-4 py-12">
        <div class="mb-6">
            @if(($force2fa ?? '0') === '1')
            <a href="{{ route('user.dashboard') }}" class="text-indigo-600 hover:text-indigo-800 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                กลับไปหน้า Dashboard
            </a>
            @endif
        </div>

        <div class="bg-white shadow-lg rounded-xl p-8">
            <h1 class="text-2xl font-bold text-gray-900 mb-6">Two-Factor Authentication (2FA)</h1>

            {{-- Alerts (centralized) --}}
            @include('partials.alerts')

            @if(session('recovery_codes'))
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                    <div class="flex">
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">บันทึกรหัสกู้คืนเหล่านี้ไว้ในที่ปลอดภัย</h3>
                            <div class="mt-2 text-sm text-yellow-700">
                                <p>หากคุณทำโทรศัพท์หาย คุณสามารถใช้รหัสเหล่านี้เพื่อเข้าสู่ระบบได้</p>
                                <ul class="list-disc list-inside mt-2 font-mono bg-white p-4 rounded border border-yellow-200">
                                    @foreach(session('recovery_codes') as $code)
                                        <li>{{ $code }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if($user->two_factor_confirmed_at)
                <div class="flex items-center gap-4 mb-6">
                    <div class="p-3 bg-green-100 rounded-full">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-green-800">เปิดใช้งานแล้ว</h3>
                        <p class="text-gray-600">บัญชีของคุณได้รับการปกป้องด้วยการยืนยันตัวตน 2 ขั้นตอน</p>
                    </div>
                </div>

                <div class="border-t border-gray-100 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">ปิดการใช้งาน 2FA</h3>
                    <form action="{{ route('user.2fa.disable') }}" method="POST" class="max-w-md">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">ยืนยันรหัสผ่านปัจจุบัน</label>
                            <input type="password" name="password" required class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                            @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium shadow-sm transition-colors">
                            ปิดการใช้งาน
                        </button>
                    </form>
                </div>
            @else
                <div class="flex items-center gap-4 mb-6">
                    <div class="p-3 bg-gray-100 rounded-full">
                        <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">ยังไม่ได้เปิดใช้งาน</h3>
                        <p class="text-gray-600">เพิ่มความปลอดภัยให้บัญชีของคุณด้วยการยืนยันตัวตนผ่านแอป Authenticator</p>
                    </div>
                </div>

                <form action="{{ route('user.2fa.enable') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-bold shadow-lg shadow-indigo-500/30 transition-all transform hover:-translate-y-0.5">
                        ตั้งค่า 2FA
                    </button>
                </form>
            @endif
        </div>
    </div>
</body>
</html>
