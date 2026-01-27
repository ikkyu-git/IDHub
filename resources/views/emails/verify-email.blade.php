@component('emails.layout', ['title' => 'Verify your email'])
<p>สวัสดี {{ $user->name ?? 'ผู้ใช้' }},</p>

<p>กรุณายืนยันอีเมลของคุณโดยคลิกปุ่มด้านล่าง:</p>

<p style="text-align:center;"><a class="cta" href="{{ $url }}">ยืนยันอีเมล</a></p>

<p>หากไม่ได้สร้างบัญชีนี้ โปรดเพิกเฉยอีเมลนี้</p>

@endcomponent
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Verify your email</title>
</head>
<body style="font-family: sans-serif; color: #111;">
  <h2>ยืนยันอีเมล</h2>
  <p>สวัสดี {{ $user->name }},</p>
  <p>กรุณาคลิกปุ่มด้านล่างเพื่อยืนยันอีเมลของคุณ:</p>
  <p>
    <a href="{{ $url }}" style="display:inline-block;padding:10px 16px;background:#4f46e5;color:#fff;border-radius:6px;text-decoration:none;">ยืนยันอีเมล</a>
  </p>
  <p>ถ้าคลิกไม่ได้ ให้คัดลอกลิงก์นี้แล้ววางในเบราว์เซอร์:</p>
  <p><small>{{ $url }}</small></p>
  <p>ขอบคุณ,<br/>ทีมงาน</p>
</body>
</html>