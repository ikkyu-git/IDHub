<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style> body { font-family: 'Sarabun', sans-serif; } </style>
    <title>สมัครสมาชิก</title>
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500">
    <div class="w-full max-w-md bg-white/90 backdrop-blur-md shadow-2xl rounded-2xl p-8 space-y-8 mx-4 border border-white/20">
        <div class="text-center">
            <h1 class="text-3xl font-bold text-gray-800">สมัครสมาชิก</h1>
            <p class="text-gray-500">สร้างบัญชีผู้ใช้ใหม่</p>
        </div>

        @if(session('alert'))
            <div class="rounded-lg bg-emerald-50 text-emerald-700 px-4 py-3 text-sm border border-emerald-200 flex items-center gap-2">
                {{ session('alert') }}
            </div>
        @endif

        @if($errors->any())
            <div class="rounded-lg bg-red-50 text-red-700 px-4 py-3 text-sm border border-red-200 space-y-1">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form class="space-y-4" method="POST" action="{{ route('register.submit') }}">
            @csrf
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="text-sm font-medium text-gray-700 ml-1">ชื่อ (First name)</label>
                    <input type="text" name="first_name" value="{{ old('first_name') }}" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition bg-gray-50 focus:bg-white">
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 ml-1">นามสกุล (Last name)</label>
                    <input type="text" name="last_name" value="{{ old('last_name') }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition bg-gray-50 focus:bg-white">
                </div>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-700 ml-1">Username (ไม่จำเป็น)</label>
                <input type="text" name="username" value="{{ old('username') }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition bg-gray-50 focus:bg-white">
            </div>

            <div>
                <label class="text-sm font-medium text-gray-700 ml-1">อีเมล</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition bg-gray-50 focus:bg-white">
            </div>

            <div>
                <label class="text-sm font-medium text-gray-700 ml-1">รหัสผ่าน</label>
                <input type="password" name="password" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition bg-gray-50 focus:bg-white">
            </div>

            <div>
                <label class="text-sm font-medium text-gray-700 ml-1">ยืนยันรหัสผ่าน</label>
                <input type="password" name="password_confirmation" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition bg-gray-50 focus:bg-white">
            </div>

            <div class="flex items-center justify-between text-sm">
                <a href="{{ route('login.page') }}" class="text-indigo-600 hover:text-indigo-800">ย้อนกลับไปหน้าเข้าสู่ระบบ</a>
                <button type="submit" class="py-3.5 px-6 bg-indigo-600 text-white rounded-xl font-semibold">สร้างบัญชี</button>
            </div>
        </form>

        <div class="text-center text-xs text-gray-400 mt-4">
            <p>&copy; {{ date('Y') }} SSO System</p>
        </div>
    </div>
</body>
</html>
