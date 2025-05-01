## تنظیمات اصلی داکر مانند db, app , webservice  با اجرای ایمیج اجرا میشوددستورات لازم جهت راه اندازی:

**1**  docker-compose up -d --build
**2**  docker exec -it laravel_app php artisan migrate
**3**  docker exec -it laravel_app php artisan key:generate
**4**  docker exec -it laravel_app npm run build


و با استفاده از آدرس 
 http://127.0.0.1:8000/
پروژه اجرا می شود!
