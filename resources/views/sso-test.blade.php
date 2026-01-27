<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SSO Test Tool</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Sarabun', sans-serif; }</style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen p-4">
    <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-md border border-gray-200">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-indigo-600">ทดสอบระบบ SSO</h1>
            <p class="text-sm text-gray-500 mt-2">จำลองการทำงานของแอปพลิเคชันภายนอก (Client)</p>
        </div>
        
        <form action="{{ route('sso.test.start') }}" method="POST" class="space-y-5">
            @csrf
            <div class="bg-blue-50 p-4 rounded-lg border border-blue-100 text-sm text-blue-800 mb-4">
                <strong>วิธีใช้งาน:</strong>
                <ol class="list-decimal list-inside mt-1 space-y-1">
                    <li>ไปที่ Admin Console > SSO Clients</li>
                    <li>สร้าง Client ใหม่โดยใช้ Redirect URI ด้านล่าง</li>
                    <li>นำ Client ID และ Secret มากรอกที่นี่</li>
                </ol>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Client ID</label>
                <input type="text" name="client_id" value="xpNXLBh3UE" required class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm p-2.5 border" placeholder="เช่น 3">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Client Secret</label>
                <input type="text" name="client_secret" value="4AI7qwogHstCIC8yoSRBCdUv9dg32gM4V1OJ3RXW" required class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm p-2.5 border" placeholder="รหัสยาวๆ...">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Redirect URI (ต้องตรงกับที่ตั้งค่าไว้)</label>
                <div class="flex items-center">
                    <input type="text" value="{{ route('sso.test.callback') }}" readonly class="w-full rounded-l-lg border-gray-300 bg-gray-50 text-gray-500 text-xs p-2.5 border font-mono">
                    <button type="button" onclick="navigator.clipboard.writeText('{{ route('sso.test.callback') }}')" class="bg-gray-200 hover:bg-gray-300 border border-l-0 border-gray-300 rounded-r-lg px-3 py-2.5 text-xs font-medium text-gray-700">Copy</button>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Scopes (สิทธิ์ที่ต้องการขอ)</label>
                <div class="space-y-2 bg-gray-50 p-3 rounded-lg border border-gray-200">
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="scopes[]" value="openid" checked class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <span class="text-sm text-gray-700">openid</span>
                    </label>
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="scopes[]" value="email" checked class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <span class="text-sm text-gray-700">email (ดูอีเมล)</span>
                    </label>
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="scopes[]" value="profile" checked class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <span class="text-sm text-gray-700">profile </span>
                    </label>
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="scopes[]" value="admin" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <span class="text-sm text-gray-700">admin (เข้าถึงระบบ Admin)</span>
                    </label>
                </div>
            </div>

            <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                เริ่มทดสอบ Login
            </button>
        </form>
    </div>
</body>
</html>
