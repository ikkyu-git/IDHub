<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ข้อกำหนดการใช้งาน (Terms of Service)</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Sarabun', sans-serif; }</style>
</head>
<body class="bg-gray-50 text-gray-800">
    <div class="max-w-4xl mx-auto px-4 py-12">
        <div class="bg-white shadow-lg rounded-xl p-8 md:p-12">
            <h1 class="text-3xl font-bold text-indigo-700 mb-6">ข้อกำหนดการใช้งาน</h1>
            <p class="text-gray-500 text-sm mb-8">อัปเดตล่าสุดเมื่อ: {{ date('d/m/Y') }}</p>

            <div class="space-y-6 text-gray-700 leading-relaxed">
                <section>
                    <h2 class="text-xl font-semibold text-gray-900 mb-2">1. การยอมรับข้อกำหนด</h2>
                    <p>การเข้าถึงและใช้งานระบบ Single Sign-On (SSO) นี้ ถือว่าคุณยอมรับและตกลงที่จะปฏิบัติตามข้อกำหนดและเงื่อนไขเหล่านี้ หากคุณไม่เห็นด้วย โปรดระงับการใช้งานทันที</p>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-gray-900 mb-2">2. บัญชีผู้ใช้</h2>
                    <ul class="list-disc list-inside ml-4 space-y-1">
                        <li>คุณต้องรับผิดชอบในการรักษาความลับของรหัสผ่านและบัญชีของคุณ</li>
                        <li>คุณตกลงที่จะแจ้งให้เราทราบทันทีหากมีการใช้บัญชีของคุณโดยไม่ได้รับอนุญาต</li>
                        <li>เราขอสงวนสิทธิ์ในการระงับหรือยกเลิกบัญชีของคุณหากพบการละเมิดข้อกำหนด</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-gray-900 mb-2">3. การใช้งานที่เหมาะสม</h2>
                    <p>คุณตกลงที่จะไม่ใช้บริการนี้เพื่อ:</p>
                    <ul class="list-disc list-inside ml-4 space-y-1">
                        <li>กระทำการใดๆ ที่ผิดกฎหมายหรือละเมิดสิทธิ์ของผู้อื่น</li>
                        <li>พยายามเจาะระบบ (Hacking) หรือรบกวนการทำงานของเซิร์ฟเวอร์</li>
                        <li>แอบอ้างเป็นบุคคลอื่นหรือบิดเบือนข้อมูลตัวตน</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-gray-900 mb-2">4. ทรัพย์สินทางปัญญา</h2>
                    <p>เนื้อหา โลโก้ และซอฟต์แวร์ทั้งหมดในระบบนี้เป็นทรัพย์สินของเราหรือผู้ให้อนุญาต ห้ามคัดลอก ดัดแปลง หรือเผยแพร่โดยไม่ได้รับอนุญาตเป็นลายลักษณ์อักษร</p>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-gray-900 mb-2">5. การจำกัดความรับผิด</h2>
                    <p>บริการนี้ให้บริการ "ตามสภาพ" (As Is) เราไม่รับประกันว่าบริการจะไม่มีข้อผิดพลาดหรือหยุดชะงัก และจะไม่รับผิดชอบต่อความเสียหายใดๆ ที่เกิดจากการใช้งานบริการนี้</p>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-gray-900 mb-2">6. การเปลี่ยนแปลงข้อกำหนด</h2>
                    <p>เราอาจปรับปรุงข้อกำหนดเหล่านี้ได้ตลอดเวลา การใช้งานบริการต่อหลังจากมีการเปลี่ยนแปลงถือว่าคุณยอมรับข้อกำหนดใหม่แล้ว</p>
                </section>
            </div>

            <div class="mt-12 pt-8 border-t border-gray-200 text-center">
                <a href="{{ url('/') }}" class="text-indigo-600 font-medium hover:text-indigo-800">กลับสู่หน้าหลัก</a>
            </div>
        </div>
    </div>
</body>
</html>
