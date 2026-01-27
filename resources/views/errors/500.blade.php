<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - เกิดข้อผิดพลาดในระบบ</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-red-100 via-orange-50 to-yellow-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-2xl w-full text-center">
        <div class="bg-white rounded-3xl shadow-2xl p-12 space-y-8">
            <!-- Icon -->
            <div class="text-red-600">
                <svg class="w-32 h-32 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>

            <!-- Error Code -->
            <div>
                <h1 class="text-8xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-red-600 to-orange-600">
                    500
                </h1>
                <p class="text-2xl font-semibold text-gray-800 mt-4">เกิดข้อผิดพลาดในระบบ</p>
                <p class="text-gray-600 mt-2">ขออภัย เกิดข้อผิดพลาดภายในเซิร์ฟเวอร์ กรุณาลองใหม่อีกครั้งในภายหลัง</p>
                
                @if(config('app.debug') && isset($exception))
                    <div class="mt-4 p-4 bg-red-50 rounded-lg text-left">
                        <p class="text-sm font-mono text-red-800">{{ $exception->getMessage() }}</p>
                    </div>
                @endif
            </div>

            <!-- Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ url('/') }}" class="px-8 py-3 bg-gradient-to-r from-red-600 to-orange-600 text-white rounded-xl font-medium hover:from-red-700 hover:to-orange-700 transition shadow-lg hover:shadow-xl">
                    กลับหน้าหลัก
                </a>
                <button onclick="location.reload()" class="px-8 py-3 bg-white border-2 border-gray-300 text-gray-700 rounded-xl font-medium hover:border-red-600 hover:text-red-600 transition">
                    ลองอีกครั้ง
                </button>
            </div>

            <!-- Support Link -->
            <p class="text-gray-500 text-sm">
                หากปัญหายังคงอยู่ กรุณา <a href="{{ url('/help') }}" class="text-red-600 hover:underline">ติดต่อฝ่ายสนับสนุน</a>
            </p>
        </div>

        <!-- Footer -->
        <p class="text-gray-500 text-sm mt-8">
            © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </p>
    </div>
</body>
</html>
