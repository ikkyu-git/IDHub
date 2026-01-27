รายการตรวจสอบการปรับใช้ (Deployment Checklist) สำหรับ IdHub

1) เตรียมเซิร์ฟเวอร์
- ใช้ Ubuntu 20.04+ หรือระบบปฏิบัติการที่เทียบเท่า
- ติดตั้ง PHP 8.1+ พร้อมส่วนขยายที่จำเป็น: `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`
- ติดตั้ง Composer
- ติดตั้งเว็บเซิร์ฟเวอร์ (nginx หรือ apache) และ PHP-FPM
- เตรียมฐานข้อมูล (MySQL หรือ Postgres)

2) โคลนรีโพ
- `git clone <repo> /var/www/idhub`

3) กำหนดค่า environment
- คัดลอก `.env.production.example` เป็น `.env`
- ตั้งค่าตัวแปรที่จำเป็น: `APP_KEY`, `DB_*`, `MAIL_*`, `SENTRY_DSN`
- แนะนำให้ใช้ `QUEUE_CONNECTION=database` สำหรับการส่งอีเมลแบบคิว

4) ติดตั้ง dependencies
- `composer install --no-dev --optimize-autoloader`

5) รัน migrations และ (ถ้าต้องการ) seed
- `php artisan migrate --force`
- (ถ้าจำเป็น) `php artisan db:seed --class=ProductionSeeder`

6) แคชการตั้งค่า
- `php artisan config:cache`
- `php artisan route:cache`
- `php artisan view:cache`

7) ตั้งค่า workers สำหรับคิว
- ใช้ Supervisor หรือ systemd ในการจัดการ worker
- ตัวอย่าง Supervisor: `deploy/supervisor.conf`
- ตัวอย่าง systemd unit: `deploy/systemd-idhub-worker.service`

8) ตัวอย่างคำสั่ง Supervisor
- `sudo supervisorctl reread`
- `sudo supervisorctl update`
- `sudo supervisorctl start idhub-worker:*`

9) สิทธิ์ไฟล์
- ให้สิทธิ์โฟลเดอร์ storage และ bootstrap/cache ให้ผู้ใช้เว็บเซิร์ฟเวอร์:
- `chown -R www-data:www-data /var/www/idhub/storage /var/www/idhub/bootstrap/cache`

10) การตรวจสอบและมอนิเตอร์
- ตรวจสอบให้แน่ใจว่า `SENTRY_DSN` ถูกตั้งค่าใน `.env`
- ตั้งการแจ้งเตือนสำหรับคิวที่ค้าง (queue backlog) และงานที่ล้มเหลว (failed jobs)

11) การย้อนกลับ (Rollback)
- การย้อนกลับสามารถทำได้โดยใช้ Git (tag/rollback) และสำรองฐานข้อมูลไว้ก่อนปรับใช้

12) ตรวจสอบหลังปรับใช้ (Post-deploy checks)
- เข้าถึง `/health` และ `/health/ready` เพื่อยืนยันสถานะ
- ตรวจสอบบันทึกใน `storage/logs`
- ยืนยันว่าอีเมลที่อยู่ในคิวถูกประมวลผล (queued mails)

หมายเหตุการใช้งาน
- สคริปต์ช่วยปรับใช้ตัวอย่าง: `deploy/deploy.sh`
- ปรับค่าตามสภาพแวดล้อมของเซิร์ฟเวอร์จริงก่อนนำขึ้น production
