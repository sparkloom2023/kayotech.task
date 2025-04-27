<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development/)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

```
kayotech
├─ .editorconfig
├─ app
│  ├─ Http
│  │  ├─ Controllers
│  │  │  ├─ Auth
│  │  │  │  ├─ AuthenticatedSessionController.php
│  │  │  │  ├─ ConfirmablePasswordController.php
│  │  │  │  ├─ EmailVerificationNotificationController.php
│  │  │  │  ├─ EmailVerificationPromptController.php
│  │  │  │  ├─ NewPasswordController.php
│  │  │  │  ├─ PasswordController.php
│  │  │  │  ├─ PasswordResetLinkController.php
│  │  │  │  ├─ RegisteredUserController.php
│  │  │  │  └─ VerifyEmailController.php
│  │  │  ├─ Controller.php
│  │  │  ├─ ProfileController.php
│  │  │  └─ TaskController.php
│  │  ├─ Middleware
│  │  │  └─ HandleInertiaRequests.php
│  │  └─ Requests
│  │     ├─ Auth
│  │     │  └─ LoginRequest.php
│  │     └─ ProfileUpdateRequest.php
│  ├─ Models
│  │  ├─ tasks.php
│  │  └─ User.php
│  └─ Providers
│     └─ AppServiceProvider.php
├─ artisan
├─ bootstrap
│  ├─ app.php
│  ├─ cache
│  │  ├─ config.php
│  │  ├─ events.php
│  │  ├─ packages.php
│  │  ├─ routes-v7.php
│  │  └─ services.php
│  └─ providers.php
├─ composer.json
├─ composer.lock
├─ config
│  ├─ app.php
│  ├─ auth.php
│  ├─ cache.php
│  ├─ database.php
│  ├─ filesystems.php
│  ├─ logging.php
│  ├─ mail.php
│  ├─ queue.php
│  ├─ services.php
│  └─ session.php
├─ database
│  ├─ database.sqlite
│  ├─ factories
│  │  └─ UserFactory.php
│  ├─ migrations
│  │  ├─ 0001_01_01_000000_create_users_table.php
│  │  ├─ 0001_01_01_000001_create_cache_table.php
│  │  ├─ 0001_01_01_000002_create_jobs_table.php
│  │  └─ 2025_04_27_204417_create_tasks_table.php
│  └─ seeders
│     └─ DatabaseSeeder.php
├─ jsconfig.json
├─ package-lock.json
├─ package.json
├─ phpunit.xml
├─ postcss.config.js
├─ public
│  ├─ .htaccess
│  ├─ favicon.ico
│  ├─ index.php
│  └─ robots.txt
├─ README.md
├─ resources
│  ├─ css
│  │  └─ app.css
│  ├─ js
│  │  ├─ app.js
│  │  ├─ bootstrap.js
│  │  ├─ Components
│  │  │  ├─ ApplicationLogo.vue
│  │  │  ├─ Checkbox.vue
│  │  │  ├─ DangerButton.vue
│  │  │  ├─ Dropdown.vue
│  │  │  ├─ DropdownLink.vue
│  │  │  ├─ InputError.vue
│  │  │  ├─ InputLabel.vue
│  │  │  ├─ Modal.vue
│  │  │  ├─ NavLink.vue
│  │  │  ├─ PrimaryButton.vue
│  │  │  ├─ ResponsiveNavLink.vue
│  │  │  ├─ SecondaryButton.vue
│  │  │  └─ TextInput.vue
│  │  ├─ Layouts
│  │  │  ├─ AuthenticatedLayout.vue
│  │  │  └─ GuestLayout.vue
│  │  └─ Pages
│  │     ├─ Auth
│  │     │  ├─ ConfirmPassword.vue
│  │     │  ├─ ForgotPassword.vue
│  │     │  ├─ Login.vue
│  │     │  ├─ Register.vue
│  │     │  ├─ ResetPassword.vue
│  │     │  └─ VerifyEmail.vue
│  │     ├─ Dashboard.vue
│  │     ├─ Profile
│  │     │  ├─ Edit.vue
│  │     │  └─ Partials
│  │     │     ├─ DeleteUserForm.vue
│  │     │     ├─ UpdatePasswordForm.vue
│  │     │     └─ UpdateProfileInformationForm.vue
│  │     ├─ Tasks
│  │     │  └─ Index.vue
│  │     └─ Welcome.vue
│  └─ views
│     └─ app.blade.php
├─ routes
│  ├─ auth.php
│  ├─ console.php
│  └─ web.php
├─ storage
│  ├─ app
│  │  ├─ private
│  │  └─ public
│  ├─ framework
│  │  ├─ cache
│  │  │  └─ data
│  │  ├─ sessions
│  │  ├─ testing
│  │  └─ views
│  │     ├─ 020829708d221262940caf6714dd43a4.php
│  │     ├─ 062400d2bb6ae26b4074adc9a5c5b269.php
│  │     ├─ 077c8a23bc82aec774cf1ea92a7f4589.php
│  │     ├─ 1103607a8d31e112894e1d96de7f4783.php
│  │     ├─ 1b7fae134bf450bbdc35ebef699365a2.php
│  │     ├─ 1c3551900bcf78ed1c6054b514024e12.php
│  │     ├─ 1d31a556fd871a8c2affed31dfd1f23c.php
│  │     ├─ 2445669b01926ae1bc822cdd7b85d4fb.php
│  │     ├─ 28327c9c14751c8752657ec013c36f44.php
│  │     ├─ 2df1b77cf57bf7e91f6871b7df78c341.php
│  │     ├─ 3471f541c52c3bdaae173a9ed801e66f.php
│  │     ├─ 46d7433a79511bf6245cc33d8d59aa6a.php
│  │     ├─ 4a83b23543b0d3def077302a6667294b.php
│  │     ├─ 5ebc02542a649027ef93abc1592e0e2b.php
│  │     ├─ 6599c48e521ad7233c63349223d35fa3.php
│  │     ├─ 688e62f753ff8d6aa4490611cb75ede5.php
│  │     ├─ 6d6484aab4909c526a18d4953d9241f2.php
│  │     ├─ 743ff8245c1f222781fd4e96d127e877.php
│  │     ├─ 76bf186351c3b8b503b0ce944fc58945.php
│  │     ├─ 8704d455f08518f9470e9f85973530fc.php
│  │     ├─ 88483a209a88e40dbb2e4d4a7fb27867.php
│  │     ├─ 8b5e1142e05097d767cafc5b808d5fe3.php
│  │     ├─ 911e7603d8e7d3b61381b688ef82aa21.php
│  │     ├─ 9131b0e18a049b7866903ce195ca0c6c.php
│  │     ├─ 9a5a3c88c37d4a07e0a2c70cc8e6d2d5.php
│  │     ├─ a46184c1977a0236eceb2b923b30ea48.php
│  │     ├─ a507d568566891f95081da0897f05566.php
│  │     ├─ a8360d134df2c49aa0a736909fd839f8.php
│  │     ├─ b09170430415f5795bc63a3a23ed0595.php
│  │     ├─ bcb9349423aacdd42d165d168500f513.php
│  │     ├─ c35cc464ad3d02c0f80516e38afb49a7.php
│  │     ├─ c4d8d87fcbf84d32a29365008f1e2052.php
│  │     ├─ d062e535e2812bf2074d023665869e97.php
│  │     ├─ dc24edaa6cee88152cc6bfe89c23389f.php
│  │     ├─ e31d7f037f175e978eb66c71827c37aa.php
│  │     ├─ f169473ee5367e54db5f05a08b1f049f.php
│  │     └─ fe6bea38f40a9394e54b6d3e8dfb71c2.php
│  └─ logs
│     └─ laravel.log
├─ tailwind.config.js
├─ tests
│  ├─ Feature
│  │  ├─ Auth
│  │  │  ├─ AuthenticationTest.php
│  │  │  ├─ EmailVerificationTest.php
│  │  │  ├─ PasswordConfirmationTest.php
│  │  │  ├─ PasswordResetTest.php
│  │  │  ├─ PasswordUpdateTest.php
│  │  │  └─ RegistrationTest.php
│  │  ├─ ExampleTest.php
│  │  └─ ProfileTest.php
│  ├─ Pest.php
│  ├─ TestCase.php
│  └─ Unit
│     └─ ExampleTest.php
└─ vite.config.js

```