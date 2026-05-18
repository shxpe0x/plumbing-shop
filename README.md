# Практика: интернет-магазин сантехники

Учебный проект (колледж) — разработка интернет-магазина сантехники по варианту 13.

## Содержимое репозитория

- [`.kiro/`](./.kiro/) — спецификация фичи `plumbing-shop-website` (requirements / design / tasks).
- [`plumbing-shop/`](./plumbing-shop/) — Laravel 11 + MySQL 8 приложение (Blade + TailwindCSS + Alpine.js).

## Стек

- PHP 8.2+, Laravel 11
- MySQL 8
- Blade, TailwindCSS 3, Alpine.js, Vite
- Laravel Breeze (auth-скаффолд)

## Запуск

```bash
cd plumbing-shop
composer install
npm install
cp .env.example .env
php artisan key:generate
# настроить креды БД в .env
php artisan migrate
npm run build
php artisan serve
```

## Сроки

04.05.2026 – 24.05.2026 (учебный практический модуль).

## Особенности учебного проекта

- Реальная отправка email и SMS не реализуется.
- Восстановление пароля — через секретный вопрос/ответ.
- Платёжные шлюзы не подключаются; способ оплаты фиксируется в БД (наличные/карта при получении, банковский перевод).
