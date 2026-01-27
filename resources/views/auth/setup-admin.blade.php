<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <title>สร้างแอดมินคนแรก</title>
</head>
<body class="min-h-screen flex items-center justify-center bg-gray-100" x-data="{ loading: false }">
  
  <!-- Loader -->
  <div x-show="loading" class="fixed inset-0 z-50 flex items-center justify-center bg-white/80 backdrop-blur-sm" style="display: none;">
    <div class="animate-spin rounded-full h-12 w-12 border-4 border-indigo-200 border-t-indigo-600"></div>
  </div>

  <div class="w-full max-w-md bg-white shadow-lg rounded-xl p-6 space-y-6">
    <div class="text-center">
      <h1 class="text-2xl font-semibold text-gray-800">ตั้งค่าระบบ</h1>
      <p class="text-sm text-gray-500">สร้างบัญชีผู้ดูแลระบบคนแรก</p>
    </div>

    @if(session('alert'))
      <div class="rounded-lg border-l-4 border-amber-400 bg-amber-50 text-amber-800 px-4 py-3">
        {{ session('alert') }}
      </div>
    @endif

    @if($errors->any())
      <div class="rounded-lg border-l-4 border-red-500 bg-red-50 text-red-800 px-4 py-3 space-y-1 text-sm">
        @foreach($errors->all() as $error)
          <div>{{ $error }}</div>
        @endforeach
      </div>
    @endif

    <form method="POST" action="{{ route('setup.admin.submit') }}" class="space-y-4" @submit="loading = true">
      @csrf
      <div class="space-y-1">
        <label class="text-sm font-medium text-gray-700">ชื่อ</label>
        <input type="text" name="name" required class="w-full rounded-lg border px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
      </div>
      <div class="space-y-1">
        <label class="text-sm font-medium text-gray-700">อีเมล</label>
        <input type="email" name="email" required class="w-full rounded-lg border px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
      </div>
      <div class="space-y-1">
        <label class="text-sm font-medium text-gray-700">รหัสผ่าน (อย่างน้อย 8 ตัว)</label>
        <input type="password" name="password" required minlength="8" class="w-full rounded-lg border px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
      </div>
      <button type="submit" class="w-full px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">สร้างบัญชีแอดมิน</button>
    </form>
  </div>
</body>
</html>
