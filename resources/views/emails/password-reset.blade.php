@component('emails.layout', ['title' => 'Reset your password'])
<p>สวัสดี {{ $user->name ?? 'ผู้ใช้' }},</p>

<p>คุณได้ร้องขอการรีเซ็ตรหัสผ่าน คลิกที่ปุ่มด้านล่างเพื่อเปลี่ยนรหัสผ่านของคุณ:</p>

<p style="text-align:center;"><a class="cta" href="{{ $url }}">รีเซ็ตรหัสผ่าน</a></p>

<p>หากคุณไม่ได้ร้องขอ กรุณาปฏิเสธอีเมลนี้</p>

@endcomponent
