<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SSO Callback Test</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-2xl">
        <h1 class="text-2xl font-bold mb-4 text-green-600">Authorization Successful!</h1>
        <p class="mb-4">ได้รับ Authorization Code แล้ว:</p>
        <code class="block bg-gray-200 p-2 rounded mb-4 break-all">{{ $code }}</code>

        <div id="status" class="mb-4 text-gray-600">กำลังแลกเปลี่ยน Token...</div>

        <div id="result" class="hidden">
            <h2 class="text-xl font-bold mb-2">Access Token Response:</h2>
            <pre id="token-response" class="bg-gray-800 text-green-400 p-4 rounded overflow-auto max-h-60 text-sm mb-4"></pre>

            <div id="refresh-section" class="hidden mb-4">
                <h2 class="text-xl font-bold mb-2">Refresh Token Test:</h2>
                <button onclick="refreshToken()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    ลอง Refresh Token
                </button>
                <pre id="refresh-response" class="mt-2 bg-gray-800 text-yellow-400 p-4 rounded overflow-auto max-h-60 text-sm hidden"></pre>
            </div>

            <h2 class="text-xl font-bold mb-2">User Info (from /api/user):</h2>
            <pre id="user-response" class="bg-gray-800 text-blue-400 p-4 rounded overflow-auto max-h-60 text-sm"></pre>
        </div>

        <div id="error" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">Error!</strong>
            <span class="block sm:inline" id="error-message"></span>
        </div>
        
        <div class="mt-6">
            <a href="{{ route('sso.test.form') }}" class="text-blue-500 hover:underline">&larr; กลับไปหน้าทดสอบ</a>
        </div>
    </div>

    <script>
        const code = "{{ $code }}";
        const clientId = "{{ session('test_client_id') }}";
        const clientSecret = "{{ session('test_client_secret') }}";
        const redirectUri = "{{ route('sso.test.callback') }}";
        const tokenUrl = "{{ url('/oauth/token') }}";
        const userUrl = "{{ url('/api/user') }}";

        let currentRefreshToken = '';

        async function exchangeToken() {
            try {
                const response = await fetch(tokenUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        grant_type: 'authorization_code',
                        client_id: clientId,
                        client_secret: clientSecret,
                        redirect_uri: redirectUri,
                        code: code
                    })
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.error_description || data.message || 'Failed to exchange token');
                }

                document.getElementById('token-response').textContent = JSON.stringify(data, null, 2);
                
                if (data.refresh_token) {
                    currentRefreshToken = data.refresh_token;
                    document.getElementById('refresh-section').classList.remove('hidden');
                }

                // Get User Info
                const userResponse = await fetch(userUrl, {
                    headers: {
                        'Authorization': 'Bearer ' + data.access_token,
                        'Accept': 'application/json'
                    }
                });
                
                const userData = await userResponse.json();
                document.getElementById('user-response').textContent = JSON.stringify(userData, null, 2);
                
                document.getElementById('status').textContent = 'แลกเปลี่ยน Token สำเร็จ!';
                document.getElementById('status').className = 'mb-4 text-green-600 font-bold';
                document.getElementById('result').classList.remove('hidden');

            } catch (error) {
                document.getElementById('status').textContent = 'เกิดข้อผิดพลาด';
                document.getElementById('status').className = 'mb-4 text-red-600 font-bold';
                document.getElementById('error').classList.remove('hidden');
                document.getElementById('error-message').textContent = error.message;
                console.error(error);
            }
        }

        async function refreshToken() {
            try {
                const response = await fetch(tokenUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        grant_type: 'refresh_token',
                        client_id: clientId,
                        client_secret: clientSecret,
                        refresh_token: currentRefreshToken
                    })
                });

                const data = await response.json();
                document.getElementById('refresh-response').classList.remove('hidden');
                document.getElementById('refresh-response').textContent = JSON.stringify(data, null, 2);

                if (data.refresh_token) {
                    currentRefreshToken = data.refresh_token;
                }

            } catch (error) {
                alert('Refresh failed: ' + error.message);
            }
        }

        // Auto start exchange
        exchangeToken();
    </script>
</body>
</html>