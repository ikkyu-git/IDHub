<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <title>ลืมรหัสผ่าน</title>
</head>
<body class="min-h-screen flex items-center justify-center bg-gray-100" x-data="{ loading: false }">
  
  <!-- Loader -->
  <div x-show="loading" class="fixed inset-0 z-50 flex items-center justify-center bg-white/80 backdrop-blur-sm" style="display: none;">
    <div class="animate-spin rounded-full h-12 w-12 border-4 border-indigo-200 border-t-indigo-600"></div>
  </div>

  <div class="w-full max-w-md bg-white shadow-lg rounded-xl p-6 space-y-6">
    @if(session('alert'))
      <div class="rounded-lg border-l-4 border-amber-400 bg-amber-50 text-amber-800 px-4 py-3">
        {{ session('alert') }}
      </div>
    @endif
    @if($errors->any())
      <div class="rounded-lg border-l-4 border-red-500 bg-red-50 text-red-800 px-4 py-3 space-y-1">
        @foreach($errors->all() as $error)
          <div>{{ $error }}</div>
        @endforeach
      </div>
    @endif
    <div class="text-center">
      <h1 class="text-2xl font-semibold text-gray-800">กู้คืนรหัสผ่าน</h1>
      <p class="text-sm text-gray-500">กรอกอีเมลเพื่อรับลิงก์รีเซ็ตรหัสผ่าน</p>
    </div>
    <form class="space-y-4" method="POST" action="{{ route('forgot.submit') }}" @submit="loading = true">
      @csrf
      <div class="space-y-1">
        <label class="text-sm font-medium text-gray-700">อีเมล</label>
        <input type="email" name="email" class="w-full rounded-lg border px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
      </div>
      <button type="submit" class="w-full px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">ส่งลิงก์รีเซ็ต</button>
      <div class="text-center text-sm">
        <a href="{{ route('login.page') }}" class="text-gray-600 hover:text-gray-800">กลับไปหน้าล็อกอิน</a>
      </div>
    </form>
  </div>
</body>
</html>
