<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <title>ผู้ใช้</title>
</head>
<body class="min-h-screen bg-gray-50 flex items-center justify-center" x-data="{ loading: false }" x-init="window.addEventListener('beforeunload', () => loading = true)">
  
  <!-- Loader -->
  <div x-show="loading" class="fixed inset-0 z-50 flex items-center justify-center bg-white/80 backdrop-blur-sm" style="display: none;">
    <div class="animate-spin rounded-full h-12 w-12 border-4 border-emerald-200 border-t-emerald-600"></div>
  </div>

  <div class="max-w-lg w-full bg-white shadow rounded-xl p-6 space-y-3">
    @include('partials.alerts')
    @auth
      @if(!auth()->user()->email_verified_at)
        <div class="rounded-lg border border-amber-100 bg-amber-50 text-amber-800 px-4 py-3">
          <div class="flex items-center justify-between gap-4">
            <div>
              <div class="font-medium">อีเมลยังไม่ได้ยืนยัน</div>
              <div class="text-sm text-amber-700">กรุณายืนยันอีเมลเพื่อเปิดใช้งานฟังก์ชันบางอย่าง</div>
            </div>
            <form method="POST" action="{{ route('verification.resend') }}">
              @csrf
              <button type="submit" class="px-3 py-2 bg-amber-600 text-white rounded-md">ส่งอีเมลยืนยันอีกครั้ง</button>
            </form>
          </div>
        </div>
      @endif
    @endauth
    <h1 class="text-2xl font-semibold text-gray-800">หน้าผู้ใช้</h1>
    <p class="text-gray-600">ข้อมูลโปรไฟล์และการตั้งค่า</p>
    <a href="{{ route('user.dashboard') }}" @click="loading = true" class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">
      ไปที่ Dashboard
    </a>
  </div>
</body>
</html>
