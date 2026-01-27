<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 5px; }
        .header { background-color: #f8f9fa; padding: 15px; text-align: center; border-bottom: 1px solid #eee; }
        .content { padding: 20px 0; }
        .alert { color: #d9534f; font-weight: bold; }
        .details { background-color: #f9f9f9; padding: 15px; border-radius: 4px; margin: 15px 0; }
        .footer { font-size: 12px; color: #999; text-align: center; margin-top: 20px; border-top: 1px solid #eee; padding-top: 10px; }
        .button { display: inline-block; padding: 10px 20px; background-color: #d9534f; color: white; text-decoration: none; border-radius: 4px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>แจ้งเตือนการเข้าสู่ระบบจากอุปกรณ์ใหม่</h2>
        </div>
        <div class="content">
            <p>สวัสดีคุณ {{ $user->name }},</p>
            <p>เราตรวจพบการเข้าสู่ระบบบัญชีของคุณจากอุปกรณ์ที่ไม่เคยใช้งานมาก่อน</p>
            
            <div class="details">
                <p><strong>เวลา:</strong> {{ $time }}</p>
                <p><strong>อุปกรณ์:</strong> {{ $userAgent }}</p>
                <p><strong>IP Address:</strong> {{ $ipAddress }}</p>
            </div>

            <p>หากนี่คือคุณ คุณสามารถเพิกเฉยต่ออีเมลฉบับนี้ได้</p>
            <p class="alert">หากนี่ไม่ใช่คุณ กรุณาเปลี่ยนรหัสผ่านทันที</p>
            
            <div style="text-align: center; margin-top: 20px;">
                <a href="{{ route('login.page') }}" class="button">ตรวจสอบบัญชีของคุณ</a>
            </div>
        </div>
        <div class="footer">
            <p>อีเมลนี้เป็นการแจ้งเตือนอัตโนมัติเพื่อความปลอดภัยของบัญชีคุณ</p>
        </div>
    </div>
</body>
</html>
