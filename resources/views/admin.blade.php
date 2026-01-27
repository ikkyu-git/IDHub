<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <title>แอดมิน</title>
</head>
<body class="min-h-screen bg-gray-50 flex items-center justify-center" x-data="{ loading: false }" x-init="window.addEventListener('beforeunload', () => loading = true)">
  
  <!-- Loader -->
  <div x-show="loading" class="fixed inset-0 z-50 flex items-center justify-center bg-white/80 backdrop-blur-sm" style="display: none;">
    <div class="animate-spin rounded-full h-12 w-12 border-4 border-indigo-200 border-t-indigo-600"></div>
  </div>

  <div class="max-w-lg w-full bg-white shadow rounded-xl p-6 space-y-3">
    @if(session('alert'))
      <div class="rounded-lg border-l-4 border-amber-400 bg-amber-50 text-amber-800 px-4 py-3">
        {{ session('alert') }}
      </div>
    @endif
    <h1 class="text-2xl font-semibold text-gray-800">หน้าผู้ดูแลระบบ</h1>
    <p class="text-gray-600">จัดการผู้ใช้ สิทธิ์ และการตั้งค่าระบบ</p>
    <a href="{{ route('admin.dashboard') }}" @click="loading = true" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
      ไปที่ Admin Console
    </a>
  </div>
</body>
</html>
