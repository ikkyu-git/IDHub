<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authorized Applications</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Sarabun', sans-serif; }</style>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto py-10 px-4">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">แอปพลิเคชันที่เชื่อมต่อ</h1>
            <a href="{{ route('user.dashboard') }}" class="text-indigo-600 hover:text-indigo-800">&larr; กลับหน้า Dashboard</a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            @if($apps->isEmpty())
                <div class="p-6 text-center text-gray-500">
                    ยังไม่มีแอปพลิเคชันที่เชื่อมต่อ
                </div>
            @else
                <ul class="divide-y divide-gray-200">
                    @foreach($apps as $clientId => $tokens)
                        @php
                            $latestToken = $tokens->first();
                            $sessionCount = $tokens->count();
                        @endphp
                    <li class="px-6 py-4 flex items-center justify-between hover:bg-gray-50">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">{{ $latestToken->client_name }}</h3>
                            <p class="text-sm text-gray-500">
                                ใช้งานล่าสุด: {{ \Carbon\Carbon::parse($latestToken->created_at)->format('d/m/Y H:i') }}
                            </p>
                            <p class="text-sm text-gray-500">
                                จำนวน Session ที่ใช้งานอยู่: <span class="font-semibold text-indigo-600">{{ $sessionCount }}</span>
                            </p>
                            <p class="text-sm text-gray-500">
                                สิทธิ์ (Scopes): {{ $latestToken->scopes ?: 'พื้นฐาน (Basic)' }}
                            </p>
                        </div>
                        <div>
                            <form action="{{ route('user.apps.revoke', $clientId) }}" method="POST" onsubmit="return confirm('คุณต้องการยกเลิกการเชื่อมต่อแอปนี้ใช่หรือไม่? (จะออกจากระบบทุก Session)');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 font-medium border border-red-200 hover:bg-red-50 px-3 py-1 rounded">
                                    ยกเลิกสิทธิ์ (Revoke All)
                                </button>
                            </form>
                        </div>
                    </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</body>
</html>
