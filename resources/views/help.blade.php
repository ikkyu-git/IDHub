<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ศูนย์ช่วยเหลือ (Help Center)</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Sarabun', sans-serif; }</style>
</head>
<body class="bg-gray-50 text-gray-800">
    <div class="max-w-4xl mx-auto px-4 py-12">
        <div class="text-center mb-12">
            <h1 class="text-3xl font-bold text-indigo-700 mb-4">ศูนย์ช่วยเหลือ</h1>
            <p class="text-gray-600">คำถามที่พบบ่อยและวิธีการใช้งานระบบ SSO</p>
        </div>

        <div class="space-y-6">
            <!-- FAQ Item 1 -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">ฉันลืมรหัสผ่าน ต้องทำอย่างไร?</h3>
                    <p class="text-gray-600">คุณสามารถรีเซ็ตรหัสผ่านได้โดยคลิกที่ลิงก์ "ลืมรหัสผ่าน?" ในหน้าเข้าสู่ระบบ ระบบจะส่งลิงก์สำหรับตั้งรหัสผ่านใหม่ไปยังอีเมลของคุณ</p>
                </div>
            </div>

            <!-- FAQ Item 2 -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">SSO คืออะไร?</h3>
                    <p class="text-gray-600">SSO (Single Sign-On) คือระบบที่ช่วยให้คุณใช้บัญชีเดียวในการเข้าถึงแอปพลิเคชันต่างๆ ได้ โดยไม่ต้องจำรหัสผ่านหลายชุด</p>
                </div>
            </div>

            <!-- FAQ Item 3 -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">ฉันจะเปลี่ยนข้อมูลส่วนตัวได้อย่างไร?</h3>
                    <p class="text-gray-600">หลังจากเข้าสู่ระบบ ให้ไปที่เมนู "Dashboard" หรือ "โปรไฟล์" คุณสามารถแก้ไขชื่อ รูปภาพ และข้อมูลอื่นๆ ได้ที่นั่น</p>
                </div>
            </div>

            <!-- FAQ Item 4 -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">การเชื่อมต่อกับ Google/Facebook ปลอดภัยหรือไม่?</h3>
                    <p class="text-gray-600">ปลอดภัยมาก เราใช้มาตรฐาน OAuth 2.0 ในการเชื่อมต่อ เราจะได้รับเฉพาะข้อมูลพื้นฐาน (ชื่อ, อีเมล, รูปภาพ) และจะไม่ทราบรหัสผ่าน Google/Facebook ของคุณ</p>
                </div>
            </div>
        </div>

        <div class="mt-12 bg-indigo-50 rounded-xl p-8 text-center">
            <h3 class="text-xl font-bold text-indigo-800 mb-4">ยังต้องการความช่วยเหลือ?</h3>
            <p class="text-gray-600 mb-6">หากคุณไม่พบคำตอบที่ต้องการ สามารถติดต่อทีมสนับสนุนของเราได้</p>
            <a href="mailto:support@example.com" class="inline-block py-3 px-6 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg transition">
                ติดต่อทีมสนับสนุน
            </a>
        </div>

        <div class="mt-8 text-center">
            <a href="{{ url('/') }}" class="text-indigo-600 font-medium hover:text-indigo-800">กลับสู่หน้าหลัก</a>
        </div>
    </div>
</body>
</html>
