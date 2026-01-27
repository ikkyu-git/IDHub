{{-- ...existing layout/header... --}}
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<div class="min-h-screen flex items-center justify-center bg-gray-50 px-4" x-data="{ loading: false }">
  
  <!-- Loader -->
  <div x-show="loading" class="fixed inset-0 z-50 flex items-center justify-center bg-white/80 backdrop-blur-sm" style="display: none;">
    <div class="animate-spin rounded-full h-12 w-12 border-4 border-indigo-200 border-t-indigo-600"></div>
  </div>

  <div class="w-full max-w-md bg-white shadow-lg rounded-xl p-6 space-y-6">
    <div class="text-center">
      <h1 class="text-2xl font-semibold text-gray-800">เข้าสู่ระบบ SSO</h1>
      <p class="text-sm text-gray-500">ลงชื่อเข้าใช้เพื่อกลับไปยังแอปปลายทาง</p>
    </div>
    <form method="POST" action="{{ route('sso.authenticate') }}" class="space-y-4" @submit="loading = true">
      @csrf
      <input type="hidden" name="callback" value="{{ $callback }}">
      <input type="hidden" name="state" value="{{ $state }}">
      <div class="space-y-1">
        <label class="block text-sm font-medium text-gray-700">อีเมล</label>
        <input type="email" name="email" required
               class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
      </div>
      <div class="space-y-1">
        <label class="block text-sm font-medium text-gray-700">รหัสผ่าน</label>
        <input type="password" name="password" required
               class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
      </div>
      @error('email')
        <div class="text-sm text-red-600">{{ $message }}</div>
      @enderror
      <button type="submit"
              class="w-full inline-flex justify-center items-center px-4 py-2 rounded-lg bg-indigo-600 text-white font-medium hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
        ลงชื่อเข้าใช้
      </button>
    </form>
  </div>
</div>
{{-- ...existing layout/footer... --}}
