# IDHub - Identity & SSO Management System

NextAuth SSO System - ระบบจัดการผู้ใช้และ Single Sign-On

## คุณสมบัติ

- 🔐 **ระบบ Authentication** - Login, Register, Email Verification
- 👥 **การจัดการผู้ใช้** - CRUD Users, Roles & Permissions
- 🔑 **SSO Server** - OAuth 2.0 & OpenID Connect Support
- 📱 **Two-Factor Authentication** - 2FA with TOTP
- 📧 **Email Notifications** - ส่งอีเมลยืนยันและแจ้งเตือน
- 📊 **Audit Logs** - บันทึกการใช้งานระบบ
- 🎨 **Admin Dashboard** - หน้าจัดการระบบสำหรับ Admin
- 🔒 **Security Features** - Password History, Force Password Change, Account Suspension

## ความต้องการของระบบ

- PHP 8.2+
- Composer
- Node.js & NPM
- SQLite (หรือ MySQL/PostgreSQL)

## การติดตั้ง

```bash
# Clone repository
git clone https://github.com/ikkyu-git/IDHub.git
cd IDHub

# ติดตั้ง dependencies
composer install
npm install

# ตั้งค่า environment
cp .env.example .env
php artisan key:generate

# สร้าง database
touch database/database.sqlite

# Run migrations
php artisan migrate

# Build assets
npm run build

# Run development server
php artisan serve
```

## การใช้งาน

1. เข้าไปที่ `http://localhost:8000`
2. สร้าง Super Admin ที่ `/setup/admin`
3. เข้าสู่ระบบและเริ่มใช้งาน

## คู่มือการติดตั้งบน Production Server

ดูรายละเอียดได้ที่ [DEPLOYMENT.md](DEPLOYMENT.md)

## License

Open-sourced software licensed under the MIT license.


We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
<<<<<<< HEAD
=======
"# IDHub" 
>>>>>>> d66ffaac01d3989caabcf2834892fa06ed767c3f
