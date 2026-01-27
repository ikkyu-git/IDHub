<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ประวัติการเข้าสู่ระบบ</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Sarabun', sans-serif; }</style>
</head>
<body class="bg-gray-50 text-gray-800">
    <div class="max-w-6xl mx-auto px-4 py-12">
        <div class="mb-6">
            <a href="{{ route('user.dashboard') }}" class="text-indigo-600 hover:text-indigo-800 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                กลับไปหน้า Dashboard
            </a>
        </div>

        <div class="bg-white shadow-lg rounded-xl p-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">ประวัติการเข้าสู่ระบบ</h1>
                    <p class="text-gray-500 text-sm mt-1">รายการกิจกรรมการเข้าสู่ระบบล่าสุดของคุณ</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">เวลา</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">กิจกรรม</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">อุปกรณ์ / เบราว์เซอร์</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">สถานะ</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($logs as $log)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $log->created_at->format('d/m/Y H:i:s') }}
                                <div class="text-xs text-gray-400">{{ $log->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                @if($log->action == 'login_success') เข้าสู่ระบบ
                                @elseif($log->action == 'login_failed') เข้าสู่ระบบไม่สำเร็จ
                                @elseif($log->action == 'logout') ออกจากระบบ
                                @elseif($log->action == 'login_locked_out') ถูกระงับการเข้าสู่ระบบชั่วคราว
                                @else {{ $log->action }}
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $log->ip_address }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate" title="{{ $log->details['user_agent'] ?? '-' }}">
                                {{ $log->details['user_agent'] ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($log->action == 'login_success')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">สำเร็จ</span>
                                @elseif($log->action == 'logout')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">ปกติ</span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">ล้มเหลว</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
</body>
</html>
