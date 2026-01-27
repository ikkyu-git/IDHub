<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เปลี่ยนรหัสผ่าน</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Sarabun', sans-serif; }</style>
</head>
<body class="bg-gray-50 text-gray-800 h-screen flex items-center justify-center">
    <div class="w-full max-w-md p-6">
        <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
            <div class="p-8">
                <div class="text-center mb-8">
                    <h1 class="text-2xl font-bold text-gray-900">เปลี่ยนรหัสผ่าน</h1>
                    <p class="text-gray-500 mt-2">
                        @if(session('warning'))
                            <span class="text-amber-600 font-medium">{{ session('warning') }}</span>
                        @else
                            กรุณาตั้งรหัสผ่านใหม่เพื่อเข้าใช้งาน
                        @endif
                    </p>
                </div>

                @if($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('password.change.update') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">รหัสผ่านปัจจุบัน</label>
                        <input type="password" name="current_password" id="current_password" required class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">รหัสผ่านใหม่</label>
                        <input type="password" name="password" id="password" required minlength="8" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">ยืนยันรหัสผ่านใหม่</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required minlength="8" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                    </div>

                    <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                        เปลี่ยนรหัสผ่าน
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-sm text-gray-500 hover:text-gray-700 underline">
                            ออกจากระบบ
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
