<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup Two-Factor Authentication</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Sarabun', sans-serif; }</style>
</head>
<body class="bg-gray-50 text-gray-800">
    <div class="max-w-2xl mx-auto px-4 py-12">
        <div class="bg-white shadow-lg rounded-xl p-8">
            <h1 class="text-2xl font-bold text-gray-900 mb-6">ตั้งค่า 2FA</h1>
            
            <div class="space-y-6">
                <div>
                    <p class="font-medium text-gray-900">1. สแกน QR Code</p>
                    <p class="text-sm text-gray-500 mb-4">ใช้แอป Authenticator (เช่น Google Authenticator, Microsoft Authenticator) สแกน QR Code ด้านล่าง</p>
                    <div class="flex justify-center p-4 bg-gray-50 rounded-lg border border-gray-200">
                        {!! $qrCodeSvg !!}
                    </div>
                    <p class="text-xs text-center text-gray-400 mt-2">Secret Key: {{ $secret }}</p>
                </div>

                <div>
                    <p class="font-medium text-gray-900 mb-2">2. กรอกรหัสยืนยัน</p>
                    <p class="text-sm text-gray-500 mb-4">กรอกรหัส 6 หลักที่ปรากฏในแอปเพื่อยืนยันการตั้งค่า</p>
                    
                    <form action="{{ route('user.2fa.confirm') }}" method="POST">
                        @csrf
                        <div class="flex gap-4">
                            <input type="text" name="code" placeholder="XXXXXX" class="flex-1 rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm text-center text-lg tracking-widest" maxlength="6" required autofocus>
                            <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium shadow-sm">
                                ยืนยัน
                            </button>
                        </div>
                        @error('code') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </form>
                </div>
            </div>
            
            <div class="mt-8 pt-6 border-t border-gray-100 text-center">
                <a href="{{ route('user.2fa.show') }}" class="text-gray-500 hover:text-gray-700 text-sm">ยกเลิก</a>
            </div>
        </div>
    </div>
</body>
</html>
