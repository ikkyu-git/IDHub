<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>503 - ระบบปิดปรับปรุง</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-100 via-cyan-50 to-teal-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-2xl w-full text-center">
        <div class="bg-white rounded-3xl shadow-2xl p-12 space-y-8">
            <!-- Icon -->
            <div class="text-blue-600">
                <svg class="w-32 h-32 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </div>

            <!-- Error Code -->
            <div>
                <h1 class="text-8xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-cyan-600">
                    503
                </h1>
                <p class="text-2xl font-semibold text-gray-800 mt-4">ระบบปิดปรับปรุง</p>
                <p class="text-gray-600 mt-2">กำลังอัพเกรดระบบเพื่อให้บริการที่ดียิ่งขึ้น กรุณากลับมาใหม่ในอีกสักครู่</p>
            </div>

            <!-- Progress Animation -->
            <div class="flex justify-center">
                <div class="flex space-x-2">
                    <div class="w-3 h-3 bg-blue-600 rounded-full animate-bounce" style="animation-delay: 0ms"></div>
                    <div class="w-3 h-3 bg-blue-600 rounded-full animate-bounce" style="animation-delay: 150ms"></div>
                    <div class="w-3 h-3 bg-blue-600 rounded-full animate-bounce" style="animation-delay: 300ms"></div>
                </div>
            </div>

            <!-- Button -->
            <div>
                <button onclick="location.reload()" class="px-8 py-3 bg-gradient-to-r from-blue-600 to-cyan-600 text-white rounded-xl font-medium hover:from-blue-700 hover:to-cyan-700 transition shadow-lg hover:shadow-xl">
                    ลองอีกครั้ง
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
