# راهنمای نصب پروژه مدیریت تسک‌ها

این سند مراحل نصب و راه‌اندازی پروژه مدیریت تسک‌ها را شرح می‌دهد. فرض بر این است که پروژه از یک مخزن Git کلون شده است و نیازی به نصب اولیه Laravel نیست.

## پیش‌نیازها
- PHP >= 8.1
- Composer
- Node.js و npm
- یک دیتابیس (MySQL یا SQLite)

## مراحل نصب

1. **کلون کردن پروژه**
   پروژه را از مخزن Git کلون کنید:
   ```bash
   git clone <repository-url>
   cd <project-directory>

2. **نصب پیشنیاز php**

  نصب وابستگی‌های PHP وابستگی‌های پروژه را با استفاده از Composer نصب کنید:
composer install

3. **نصب وابستگی‌های جاوااسکریپت**
 نصب وابستگی‌های جاوااسکریپت پکیج‌های فرانت‌اند (Inertia.js و Vue.js) را با npm نصب کنید:
npm install

4. **تنظیمات فایل محیطی**
 تنظیم فایل محیطی (.env) فایل .env.example را کپی کرده و به .env تغییر نام دهید:
cp .env.example .env
فایل .env را باز کرده و تنظیمات دیتابیس را وارد کنید. به عنوان مثال:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_management
DB_USERNAME=root
DB_PASSWORD=

5. **تولید کلید برنامه**
php artisan key:generate

6. **ساخت جداول**
 اجرای Migrationها جداول دیتابیس را با اجرای Migrationها ایجاد کنید:
php artisan migrate

7. **ساخت محیط اسکریپت**
کامپایل فایل‌های فرانت‌اند فایل‌های جاوااسکریپت و CSS را با Vite کامپایل کنید:
npm run dev

8. برای محیط Production:
npm run build
پروژه در آدرس http://localhost:8000 قابل دسترسی خواهد بود.

## آماده سازی فایل تست
9.  آماده‌سازی برای تست‌ها فایل .env.testing را برای تست‌ها ایجاد کنید:
APP_ENV=testing
DB_CONNECTION=sqlite
DB_DATABASE=:memory:
SESSION_DRIVER=database
CACHE_STORE=array
QUEUE_CONNECTION=sync

سپس Migrationها را برای تست اجرا کنید:
php artisan migrate --database=sqlite
