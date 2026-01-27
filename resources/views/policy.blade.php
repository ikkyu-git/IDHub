<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>นโยบายความเป็นส่วนตัว (Privacy Policy)</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Sarabun', sans-serif; }</style>
</head>
<body class="bg-gray-50 text-gray-800">
    <div class="max-w-4xl mx-auto px-4 py-12">
        <div class="bg-white shadow-lg rounded-xl p-8 md:p-12">
            <h1 class="text-3xl font-bold text-indigo-700 mb-6">นโยบายความเป็นส่วนตัว</h1>
            <p class="text-gray-500 text-sm mb-8">อัปเดตล่าสุดเมื่อ: {{ date('d/m/Y') }}</p>

            <div class="space-y-6 text-gray-700 leading-relaxed">
                <section>
                    <h2 class="text-xl font-semibold text-gray-900 mb-2">1. บทนำ</h2>
                    <p>เราให้ความสำคัญกับความเป็นส่วนตัวของคุณ นโยบายนี้อธิบายถึงวิธีการที่เราเก็บรวบรวม ใช้ และเปิดเผยข้อมูลส่วนบุคคลของคุณเมื่อคุณใช้งานระบบ Single Sign-On (SSO) ของเรา</p>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-gray-900 mb-2">2. ข้อมูลที่เราเก็บรวบรวม</h2>
                    <ul class="list-disc list-inside ml-4 space-y-1">
                        <li><strong>ข้อมูลบัญชี:</strong> ชื่อ, อีเมล, รหัสผ่าน (ที่เข้ารหัสแล้ว), และรูปโปรไฟล์</li>
                        <li><strong>ข้อมูลการใช้งาน:</strong> ประวัติการเข้าสู่ระบบ, IP Address, และ User Agent</li>
                        <li><strong>ข้อมูลจากบริการภายนอก:</strong> หากคุณล็อกอินผ่าน Google หรือ Facebook เราจะได้รับข้อมูลพื้นฐานตามที่คุณอนุญาต</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-gray-900 mb-2">3. การใช้ข้อมูลของคุณ</h2>
                    <p>เราใช้ข้อมูลของคุณเพื่อ:</p>
                    <ul class="list-disc list-inside ml-4 space-y-1">
                        <li>ยืนยันตัวตนและให้บริการเข้าสู่ระบบ (Authentication)</li>
                        <li>รักษาความปลอดภัยของบัญชีและตรวจสอบกิจกรรมที่น่าสงสัย</li>
                        <li>ปรับปรุงประสบการณ์การใช้งานและพัฒนาบริการของเรา</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-gray-900 mb-2">4. การเปิดเผยข้อมูล</h2>
                    <p>เราจะไม่ขายหรือให้เช่าข้อมูลส่วนบุคคลของคุณแก่บุคคลที่สาม เราอาจเปิดเผยข้อมูลเฉพาะในกรณีที่:</p>
                    <ul class="list-disc list-inside ml-4 space-y-1">
                        <li>ได้รับความยินยอมจากคุณ (เช่น การอนุญาตให้แอปพลิเคชันภายนอกเข้าถึงข้อมูลผ่าน OAuth)</li>
                        <li>จำเป็นต้องปฏิบัติตามกฎหมายหรือคำสั่งศาล</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-gray-900 mb-2">5. ความปลอดภัยของข้อมูล</h2>
                    <p>เราใช้มาตรการรักษาความปลอดภัยตามมาตรฐานอุตสาหกรรม (เช่น การเข้ารหัส SSL/TLS, การแฮชรหัสผ่าน) เพื่อปกป้องข้อมูลของคุณจากการเข้าถึงโดยไม่ได้รับอนุญาต</p>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-gray-900 mb-2">6. สิทธิ์ของคุณ</h2>
                    <p>คุณมีสิทธิ์ในการเข้าถึง แก้ไข หรือลบข้อมูลส่วนบุคคลของคุณ คุณสามารถจัดการข้อมูลได้ผ่านหน้า Dashboard หรือติดต่อเราหากต้องการความช่วยเหลือเพิ่มเติม</p>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-gray-900 mb-2">7. การติดต่อเรา</h2>
                    <p>หากคุณมีคำถามเกี่ยวกับนโยบายความเป็นส่วนตัวนี้ โปรดติดต่อผู้ดูแลระบบที่ <a href="mailto:admin@example.com" class="text-indigo-600 hover:underline">admin@example.com</a></p>
                </section>
            </div>

            <div class="mt-12 pt-8 border-t border-gray-200 text-center">
                <a href="{{ url('/') }}" class="text-indigo-600 font-medium hover:text-indigo-800">กลับสู่หน้าหลัก</a>
            </div>
        </div>
    </div>
</body>
</html>
