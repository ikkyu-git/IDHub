<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Active Sessions</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Sarabun', sans-serif; }</style>
</head>
<body class="bg-gray-50 text-gray-800">
    <div class="max-w-4xl mx-auto px-4 py-12">
        <div class="mb-6">
            <a href="{{ route('user.dashboard') }}" class="text-indigo-600 hover:text-indigo-800 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                กลับไปหน้า Dashboard
            </a>
        </div>

        <div class="bg-white shadow-lg rounded-xl p-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">อุปกรณ์ที่เข้าสู่ระบบอยู่</h1>
                    <p class="text-gray-500 text-sm mt-1">จัดการเซสชันการใช้งานของคุณบนอุปกรณ์ต่างๆ</p>
                </div>
                @if($sessions->count() > 1)
                <form action="{{ route('user.sessions.destroy_all') }}" method="POST" onsubmit="return confirm('คุณแน่ใจหรือไม่ที่จะออกจากระบบทุกอุปกรณ์อื่น?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 font-medium text-sm border border-red-200 transition-colors">
                        ออกจากระบบทุกอุปกรณ์อื่น
                    </button>
                </form>
                @endif
            </div>

            @if(session('success'))
                <div class="bg-green-50 text-green-700 p-4 rounded-lg mb-6 border border-green-200">
                    {{ session('success') }}
                </div>
            @endif

            <div class="space-y-4">
                @foreach($sessions as $session)
                <div class="flex items-center justify-between p-4 rounded-lg border {{ $session->is_current_device ? 'border-indigo-200 bg-indigo-50' : 'border-gray-200 bg-white' }}">
                    <div class="flex items-center gap-4">
                        <div class="p-3 rounded-full {{ $session->is_current_device ? 'bg-indigo-100 text-indigo-600' : 'bg-gray-100 text-gray-500' }}">
                            @if($session->platform == 'Mobile' || $session->platform == 'Android' || $session->platform == 'iOS')
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                            @else
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            @endif
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="font-semibold text-gray-900">{{ $session->platform }} - {{ $session->browser }}</span>
                                @if($session->is_current_device)
                                    <span class="px-2 py-0.5 rounded text-xs font-medium bg-indigo-100 text-indigo-700">อุปกรณ์นี้</span>
                                @endif
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ $session->ip_address }} &bull; ใช้งานล่าสุด {{ $session->last_active }}
                            </div>
                        </div>
                    </div>

                    @if(!$session->is_current_device)
                    <form action="{{ route('user.sessions.destroy', $session->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-gray-400 hover:text-red-600 transition-colors p-2" title="ออกจากระบบ">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                        </button>
                    </form>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </div>
</body>
</html>
