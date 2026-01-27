<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authorize Application</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Sarabun', sans-serif; }</style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen p-4">
    <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-md text-center">
        @if($client->logo_uri)
            <img src="{{ $client->logo_uri }}" alt="{{ $client->name }}" class="h-16 mx-auto mb-4 object-contain">
        @endif
        
        <h2 class="text-2xl font-bold text-gray-800 mb-4">คำขอเข้าถึงข้อมูล</h2>
        <p class="text-gray-600 mb-6">แอปพลิเคชัน <strong class="text-indigo-600">{{ $client->name }}</strong> ต้องการเข้าถึงข้อมูลของคุณ:</p>

        <div class="text-left bg-gray-50 p-4 rounded-lg mb-6 border border-gray-200">
            <ul class="space-y-2 text-sm text-gray-700">
                @php
                    $scopes = explode(' ', $request['scope'] ?? '');
                @endphp
                
                @if(in_array('openid', $scopes))
                    <li class="flex items-center"><span class="text-green-500 mr-2">✓</span> ยืนยันตัวตนของคุณ (OpenID)</li>
                @endif
                @if(in_array('profile', $scopes))
                    <li class="flex items-center"><span class="text-green-500 mr-2">✓</span> เข้าถึงข้อมูลส่วนตัว (ชื่อ, รูปภาพ)</li>
                @endif
                @if(in_array('email', $scopes))
                    <li class="flex items-center"><span class="text-green-500 mr-2">✓</span> เข้าถึงอีเมลของคุณ</li>
                @endif
                @if(in_array('roles', $scopes))
                    <li class="flex items-center"><span class="text-green-500 mr-2">✓</span> ดูสิทธิ์การใช้งาน (Roles)</li>
                @endif
                @if(in_array('offline_access', $scopes))
                    <li class="flex items-center"><span class="text-green-500 mr-2">✓</span> เข้าถึงข้อมูลเมื่อคุณไม่ได้ใช้งาน (Offline Access)</li>
                @endif
            </ul>
        </div>

        <form action="{{ route('oauth.approve') }}" method="POST" class="space-y-3">
            @csrf
            <input type="hidden" name="client_id" value="{{ $request['client_id'] }}">
            <input type="hidden" name="redirect_uri" value="{{ $request['redirect_uri'] }}">
            <input type="hidden" name="state" value="{{ $request['state'] ?? '' }}">
            <input type="hidden" name="scope" value="{{ $request['scope'] ?? '' }}">
            <input type="hidden" name="nonce" value="{{ $request['nonce'] ?? '' }}">
            <input type="hidden" name="code_challenge" value="{{ $request['code_challenge'] ?? '' }}">
            <input type="hidden" name="code_challenge_method" value="{{ $request['code_challenge_method'] ?? '' }}">

            <button type="submit" class="w-full py-3 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg">
                อนุญาต (Approve)
            </button>
        </form>

        <form action="{{ route('oauth.deny') }}" method="POST" class="mt-3">
            @csrf
            <input type="hidden" name="redirect_uri" value="{{ $request['redirect_uri'] }}">
            <input type="hidden" name="state" value="{{ $request['state'] ?? '' }}">
            
            <button type="submit" class="w-full py-3 px-4 bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold rounded-lg">
                ปฏิเสธ (Deny)
            </button>
        </form>

        @if($client->policy_uri || $client->tos_uri)
            <div class="mt-6 text-xs text-gray-500 space-x-2">
                @if($client->policy_uri)
                    <a href="{{ $client->policy_uri }}" target="_blank" class="underline hover:text-gray-700">นโยบายความเป็นส่วนตัว</a>
                @endif
                @if($client->policy_uri && $client->tos_uri)
                    <span>&bull;</span>
                @endif
                @if($client->tos_uri)
                    <a href="{{ $client->tos_uri }}" target="_blank" class="underline hover:text-gray-700">ข้อกำหนดการใช้งาน</a>
                @endif
            </div>
        @endif
    </div>
</body>
</html>
