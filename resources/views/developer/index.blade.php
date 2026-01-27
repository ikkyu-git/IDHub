<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Developer Documentation - SSO</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Sarabun', sans-serif; }
        pre { background-color: #1f2937; color: #e5e7eb; padding: 1rem; border-radius: 0.5rem; overflow-x: auto; }
        code { font-family: 'Courier New', Courier, monospace; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">

    <nav class="bg-white shadow mb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <span class="font-bold text-xl text-indigo-600">SSO Developers</span>
                    </div>
                </div>
                <div class="flex items-center">
                    <a href="{{ url('/') }}" class="text-gray-500 hover:text-gray-700">กลับหน้าหลัก</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <!-- Sidebar -->
            <div class="md:col-span-1">
                <div class="bg-white shadow rounded-lg p-4 sticky top-6">
                    <h3 class="font-bold text-lg mb-4">สารบัญ</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#introduction" class="text-indigo-600 hover:underline">บทนำ (Introduction)</a></li>
                        <li><a href="#discovery" class="text-indigo-600 hover:underline">Discovery Endpoint</a></li>
                        <li><a href="#authorization" class="text-indigo-600 hover:underline">Authorization Endpoint</a></li>
                        <li><a href="#token" class="text-indigo-600 hover:underline">Token Endpoint</a></li>
                        <li><a href="#userinfo" class="text-indigo-600 hover:underline">UserInfo Endpoint</a></li>
                        <li><a href="#introspection" class="text-indigo-600 hover:underline">Introspection Endpoint</a></li>
                        <li><a href="#revocation" class="text-indigo-600 hover:underline">Revocation Endpoint</a></li>
                        <li><a href="#logout" class="text-indigo-600 hover:underline">End Session (Logout)</a></li>
                        <li><a href="#jwks" class="text-indigo-600 hover:underline">JWKS Endpoint</a></li>
                        <li><a href="#pkce" class="text-indigo-600 hover:underline">PKCE Flow</a></li>
                    </ul>
                </div>
            </div>

            <!-- Content -->
            <div class="md:col-span-3 space-y-8">
                
                <section id="introduction" class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-2xl font-bold mb-4">บทนำ</h2>
                    <p class="mb-4">ยินดีต้อนรับสู่คู่มือนักพัฒนา ระบบ Single Sign-On (SSO) นี้รองรับมาตรฐาน <strong>OAuth 2.0</strong> และ <strong>OpenID Connect (OIDC) Core 1.0</strong></p>
                    <p>คุณสามารถใช้ Library มาตรฐานเช่น NextAuth.js, AppAuth, หรือ Passport.js เชื่อมต่อได้ทันที</p>
                    <p class="mt-2 text-sm text-gray-600">รองรับฟีเจอร์: Authorization Code Flow, PKCE, Refresh Token, UserInfo, Introspection, Revocation, Back-Channel Logout (Session Management)</p>
                </section>

                <section id="discovery" class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-2xl font-bold mb-4">1. Discovery Endpoint</h2>
                    <p class="mb-4">ใช้สำหรับ Auto-configuration (แนะนำให้ใช้)</p>
                    <div class="mb-2"><span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-bold">GET</span> <code class="text-sm">{{ route('oauth.discovery') }}</code></div>
                    <div class="mb-2"><span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-bold">GET</span> <code class="text-sm">{{ route('oauth.webfinger') }}</code> (WebFinger)</div>
                    <pre>
{
  "issuer": "{{ url('/') }}",
  "authorization_endpoint": "{{ route('oauth.authorize') }}",
  "token_endpoint": "{{ route('oauth.token') }}",
  "userinfo_endpoint": "{{ route('oauth.userinfo') }}",
  "end_session_endpoint": "{{ route('oauth.logout') }}",
  "revocation_endpoint": "{{ route('oauth.revoke') }}",
  "introspection_endpoint": "{{ route('oauth.introspect') }}",
  "jwks_uri": "{{ route('oauth.jwks') }}",
  ...
}</pre>
                </section>

                <section id="authorization" class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-2xl font-bold mb-4">2. Authorization Endpoint</h2>
                    <p class="mb-4">พาผู้ใช้มาที่ URL นี้เพื่อเริ่มกระบวนการ Login</p>
                    <div class="mb-2"><span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-bold">GET</span> <code class="text-sm">{{ route('oauth.authorize') }}</code></div>
                    
                    <h4 class="font-bold mt-4 mb-2">Parameters:</h4>
                    <ul class="list-disc list-inside text-sm space-y-1 ml-4">
                        <li><code>client_id</code> (Required): Client ID ของคุณ</li>
                        <li><code>redirect_uri</code> (Required): URL ที่จะให้ส่ง Code กลับไป</li>
                        <li><code>response_type</code> (Required): ต้องเป็น <code>code</code></li>
                        <li><code>scope</code> (Optional): เช่น <code>openid profile email offline_access roles</code></li>
                        <li><code>state</code> (Recommended): ค่าสุ่มเพื่อป้องกัน CSRF</li>
                        <li><code>nonce</code> (Optional): ค่าสุ่มเพื่อป้องกัน Replay Attack (จะอยู่ใน ID Token)</li>
                        <li><code>prompt</code> (Optional): <code>none</code> (เช็ค Login แบบเงียบๆ), <code>login</code> (บังคับ Login ใหม่)</li>
                        <li><code>code_challenge</code> (Optional): สำหรับ PKCE</li>
                        <li><code>code_challenge_method</code> (Optional): <code>S256</code> หรือ <code>plain</code></li>
                    </ul>
                </section>

                <section id="token" class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-2xl font-bold mb-4">3. Token Endpoint</h2>
                    <p class="mb-4">นำ Authorization Code มาแลกเป็น Access Token</p>
                    <div class="mb-2"><span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs font-bold">POST</span> <code class="text-sm">{{ route('oauth.token') }}</code></div>
                    
                    <h4 class="font-bold mt-4 mb-2">Body (x-www-form-urlencoded หรือ JSON):</h4>
                    <ul class="list-disc list-inside text-sm space-y-1 ml-4">
                        <li><code>grant_type</code> (Required): <code>authorization_code</code></li>
                        <li><code>client_id</code> (Required)</li>
                        <li><code>client_secret</code> (Required)</li>
                        <li><code>code</code> (Required): Code ที่ได้จากขั้นตอนก่อนหน้า</li>
                        <li><code>redirect_uri</code> (Required): ต้องตรงกับตอนขอ Code</li>
                        <li><code>code_verifier</code> (Required if PKCE used): รหัสลับที่ใช้สร้าง code_challenge</li>
                    </ul>
                </section>

                <section id="userinfo" class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-2xl font-bold mb-4">4. UserInfo Endpoint</h2>
                    <p class="mb-4">ดึงข้อมูลผู้ใช้ปัจจุบัน (Claims จะขึ้นอยู่กับ Scope ที่ขอ)</p>
                    <div class="mb-2"><span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-bold">GET</span> <code class="text-sm">{{ route('oauth.userinfo') }}</code></div>
                    
                    <h4 class="font-bold mt-4 mb-2">Headers:</h4>
                    <ul class="list-disc list-inside text-sm space-y-1 ml-4">
                        <li><code>Authorization</code>: <code>Bearer {access_token}</code></li>
                    </ul>
                    
                    <h4 class="font-bold mt-4 mb-2">Response Example:</h4>
                    <pre>
                    {
                        "sub": "uuid...",
                        "name": "John Doe",             // ถ้าขอ scope profile
                        "given_name": "John",           // แยกชื่อ (first name)
                        "family_name": "Doe",           // แยกนามสกุล (last name)
                        "preferred_username": "johnd",  // username (preferred)
                        "username": "johnd",            // (app-specific)
                        "first_name": "John",           // (app-specific)
                        "last_name": "Doe",             // (app-specific)
                        "email": "john@...",            // ถ้าขอ scope email
                        "email_verified": true,           // ถ้าขอ scope email
                        "roles": ["admin", ...]         // ถ้าขอ scope roles
                    }
                    </pre>
                </section>

                <section id="introspection" class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-2xl font-bold mb-4">5. Introspection Endpoint (RFC 7662)</h2>
                    <p class="mb-4">ตรวจสอบสถานะของ Token (สำหรับ Resource Server)</p>
                    <div class="mb-2"><span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs font-bold">POST</span> <code class="text-sm">{{ route('oauth.introspect') }}</code></div>
                    
                    <h4 class="font-bold mt-4 mb-2">Body:</h4>
                    <ul class="list-disc list-inside text-sm space-y-1 ml-4">
                        <li><code>token</code> (Required): Token ที่ต้องการตรวจสอบ</li>
                        <li><code>client_id</code> & <code>client_secret</code> (Required): สำหรับยืนยันตัวตนผู้ตรวจสอบ</li>
                    </ul>
                </section>

                <section id="revocation" class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-2xl font-bold mb-4">6. Revocation Endpoint (RFC 7009)</h2>
                    <p class="mb-4">ยกเลิกการใช้งาน Token</p>
                    <div class="mb-2"><span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs font-bold">POST</span> <code class="text-sm">{{ route('oauth.revoke') }}</code></div>
                    
                    <h4 class="font-bold mt-4 mb-2">Body:</h4>
                    <ul class="list-disc list-inside text-sm space-y-1 ml-4">
                        <li><code>token</code> (Required)</li>
                        <li><code>token_type_hint</code> (Optional): <code>access_token</code> หรือ <code>refresh_token</code></li>
                    </ul>
                </section>

                <section id="logout" class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-2xl font-bold mb-4">7. End Session Endpoint (Logout)</h2>
                    <p class="mb-4">ออกจากระบบ SSO</p>
                    <div class="mb-2"><span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-bold">GET</span> <code class="text-sm">{{ route('oauth.logout') }}</code></div>
                    
                    <h4 class="font-bold mt-4 mb-2">Parameters:</h4>
                    <ul class="list-disc list-inside text-sm space-y-1 ml-4">
                        <li><code>id_token_hint</code> (Recommended): ID Token เดิมเพื่อยืนยันตัวตน</li>
                        <li><code>post_logout_redirect_uri</code> (Optional): URL ที่จะให้กลับไปหลัง Logout (ต้องลงทะเบียนไว้)</li>
                        <li><code>state</code> (Optional): ส่งกลับไปพร้อม redirect uri</li>
                    </ul>
                </section>

                <section id="jwks" class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-2xl font-bold mb-4">8. JWKS Endpoint</h2>
                    <p class="mb-4">Public Keys สำหรับตรวจสอบ Signature ของ ID Token (RS256)</p>
                    <div class="mb-2"><span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-bold">GET</span> <code class="text-sm">{{ route('oauth.jwks') }}</code></div>
                </section>

                <section id="pkce" class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-2xl font-bold mb-4">PKCE Flow (แนะนำสำหรับ Mobile/SPA)</h2>
                    <p class="mb-4">หากคุณพัฒนา Mobile App หรือ Single Page Application ควรใช้ PKCE เพื่อความปลอดภัย</p>
                    <ol class="list-decimal list-inside text-sm space-y-2 ml-4">
                        <li>สร้าง <code>code_verifier</code> (Random String 43-128 chars)</li>
                        <li>สร้าง <code>code_challenge</code> = Base64Url(SHA256(code_verifier))</li>
                        <li>ส่ง <code>code_challenge</code> ไปตอน Authorize</li>
                        <li>ส่ง <code>code_verifier</code> ไปตอนแลก Token</li>
                    </ol>
                </section>

            </div>
        </div>
    </div>
</body>
</html>
