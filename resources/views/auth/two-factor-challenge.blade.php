<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Two-Factor Challenge</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Sarabun', sans-serif; }</style>
</head>
<body class="bg-gray-100 h-screen flex items-center justify-center">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8" x-data="{ recovery: false }">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-indigo-100 mb-4">
                <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-800">ยืนยันตัวตน 2 ขั้นตอน</h2>
            <p class="text-gray-500 text-sm mt-2" x-show="!recovery">กรุณากรอกรหัสจากแอป Authenticator ของคุณ</p>
            <p class="text-gray-500 text-sm mt-2" x-show="recovery" x-cloak>กรุณากรอกรหัสกู้คืน (Recovery Code) ของคุณ</p>
        </div>

        <form action="{{ route('2fa.verify') }}" method="POST">
            @csrf
            
            <div x-show="!recovery">
                <label class="block text-sm font-medium text-gray-700 mb-1">Authentication Code</label>
                <input type="text" name="code" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition text-center text-2xl tracking-widest" placeholder="XXXXXX" maxlength="6" inputmode="numeric" autocomplete="one-time-code">
                @error('code') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div x-show="recovery" x-cloak>
                <label class="block text-sm font-medium text-gray-700 mb-1">Recovery Code</label>
                <input type="text" name="recovery_code" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition" placeholder="XXXXXXXX-XXXXXXXX">
                @error('recovery_code') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="w-full mt-6 py-3 bg-indigo-600 text-white rounded-xl font-semibold shadow-lg hover:bg-indigo-700 transition-all">
                ยืนยัน
            </button>
        </form>

        <div class="mt-6 text-center">
            <button type="button" @click="recovery = !recovery" class="text-sm text-gray-500 hover:text-indigo-600 underline transition-colors">
                <span x-show="!recovery">ใช้รหัสกู้คืน (Recovery Code)</span>
                <span x-show="recovery" x-cloak>ใช้รหัส Authenticator</span>
            </button>
        </div>
    </div>
</body>
</html>
