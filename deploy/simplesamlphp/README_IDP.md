คู่มือสั้นการตั้งค่า SimpleSAMLphp เป็น Identity Provider (IdP)

ภาพรวม
- ไฟล์นี้เป็นตัวอย่างการติดตั้ง SimpleSAMLphp เพื่อให้ IdHub ทำหน้าที่เป็น SAML IdP (ให้ Service Providers มาตรวจสอบผู้ใช้จาก IdHub)

สิ่งที่จะสร้าง
- Container SimpleSAMLphp (ตัวอย่างใน `docker-compose.yml`) ที่ให้บริการ Metadata, AssertionConsumerService (ACS), SingleLogout (SLS)

ขั้นตอนสรุป
1. เตรียมโดเมน/URL สำหรับ IdP (เช่น `https://saml-idp.example.com`) และชี้ DNS ไปยังเซิร์ฟเวอร์
2. สร้างไฟล์คอนฟิกใน `deploy/simplesamlphp/config/`
   - `config.php` : ตั้งค่า `baseurlpath`, `secretsalt`, อื่นๆ
   - `authsources.php` : ตั้งค่า authentication source เช่น `example-userpass` หรือ custom module เพื่อเรียก Laravel (ใช้ REST API หรือ DB)
3. สร้างคู่คีย์/ใบรับรองสำหรับ signing/encryption และวางไว้ใน `deploy/simplesamlphp/cert/` (`server.crt`, `server.key`)
4. สร้าง `metadata/saml20-idp-hosted.php` เพื่อประกาศ metadata ของ IdP (entityID, certificate, SingleSignOnService, SingleLogoutService)
5. ใส่ metadata ของ SPs ที่จะอนุญาตให้ล็อกอินใน `metadata/saml20-sp-remote.php`
6. เรียกใช้งานด้วย `docker compose up -d` (หรือ deploy ใน k8s/VM พร้อม TLS reverse-proxy)

ตัวอย่างการแมปกับ Laravel
- วิธีที่นิยม: ให้ SimpleSAMLphp ทำหน้าที่ Authentication (ask for username/password) โดยใช้ backend เป็น Laravel DB หรือ REST API:
  - Option A: ใน `authsources.php` ใช้ `sqlauth` หรือ `ldap` ถ้าต้องการเชื่อมตรงกับ DB/LDAP
  - Option B: เขียน custom PHP module สำหรับ SimpleSAMLphp ที่เรียก Laravel API endpoint `/saml/authenticate` เพื่อยืนยันผู้ใช้และดึง attributes

Attribute mapping (แนะนำ)
- NameID: `urn:oasis:names:tc:SAML:1.1:nameid-format:emailAddress` → `email`
- `urn:oid:2.5.4.42` (givenName) → `first_name`
- `urn:oid:2.5.4.4` (sn) → `last_name`
- `email` → `email`
- `roles` → `roles` (array)

ความปลอดภัยและปฏิบัติการ
- เก็บคีย์/secret ใน Secret Manager; อย่าเก็บคีย์ใน repo
- เปิด TLS (HTTPS) สำหรับ production, ใช้ reverse-proxy (nginx) และ HSTS
- ตั้งการหมุนใบรับรองและตรวจ clock-skew ใน SimpleSAMLphp

ทดสอบเบื้องต้น
1. รัน `docker compose up -d` ใน `deploy/simplesamlphp`
2. เข้าถึง Metadata: `http://localhost:8081/simplesaml/module.php/saml/sp/metadata.php/[SP-ENTITY-ID]` หรือ IdP metadata path ตาม config
3. ดาวน์โหลด metadata ของ IdP ส่งให้ SP และทดสอบ SSO flow

ไฟล์ตัวอย่างที่ต้องเตรียมใน repo นี้
- `deploy/simplesamlphp/docker-compose.yml` (ตัวอย่าง)
- `deploy/simplesamlphp/config/` (ต้องสร้างในเครื่อง operator)
- `deploy/simplesamlphp/metadata/` (ต้องใส่ metadata/keys)

ต้องการให้ผมทำต่อหรือไม่
- ผมสามารถ scaffold ตัวอย่าง `config.php`, `authsources.php`, และตัวอย่าง `saml20-idp-hosted.php` (แบบ non-prod) ให้ใน repo เพื่อเป็นจุดเริ่มต้น
- ผมยังสามารถสร้างตัวอย่าง custom auth module ที่เรียก Laravel REST API เพื่อยืนยันผู้ใช้ (ต้องเขียน PHP module สำหรับ SimpleSAMLphp)
