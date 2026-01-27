<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - ไม่พบหน้าที่ต้องการ</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-indigo-100 via-purple-50 to-pink-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-2xl w-full text-center">
        <div class="bg-white rounded-3xl shadow-2xl p-12 space-y-8">
            <!-- Icon -->
            <div class="text-indigo-600">
                <svg class="w-32 h-32 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>

            <!-- Error Code -->
            <div>
                <h1 class="text-8xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600">
                    404
                </h1>
                <p class="text-2xl font-semibold text-gray-800 mt-4">ไม่พบหน้าที่ต้องการ</p>
                <p class="text-gray-600 mt-2">ขออภัย หน้าที่คุณกำลังมองหาไม่มีอยู่ในระบบ</p>
            </div>

            <!-- Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ url('/') }}" class="px-8 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl font-medium hover:from-indigo-700 hover:to-purple-700 transition shadow-lg hover:shadow-xl">
                    กลับหน้าหลัก
                </a>
                <button onclick="history.back()" class="px-8 py-3 bg-white border-2 border-gray-300 text-gray-700 rounded-xl font-medium hover:border-indigo-600 hover:text-indigo-600 transition">
                    ย้อนกลับ
                </button>
            </div>
        </div>

        <!-- Footer -->
        <p class="text-gray-500 text-sm mt-8">
            © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </p>
    </div>
</body>
</html>
