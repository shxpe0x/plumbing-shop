# 🛁 Plumbing Shop — интернет-магазин сантехники

[![Laravel](https://img.shields.io/badge/Laravel-11-FF2D20?logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?logo=php&logoColor=white)](https://www.php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8-4479A1?logo=mysql&logoColor=white)](https://www.mysql.com)
[![Tailwind](https://img.shields.io/badge/TailwindCSS-3-38B2AC?logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
[![License: MIT](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

Учебный проект (колледж) — интернет-магазин сантехники по варианту 13 учебной практики.
Стек: **Laravel 11 + MySQL 8 + Blade + TailwindCSS + Alpine.js**.

---

## 📋 Содержание

- [Описание проекта](#-описание-проекта)
- [Структура репозитория](#-структура-репозитория)
- [Стек технологий](#-стек-технологий)
- [Быстрый старт](#-быстрый-старт)
- [Прогресс реализации](#-прогресс-реализации)
- [Git-флоу](#-git-флоу)
- [Документы спецификации](#-документы-спецификации)
- [Особенности учебного проекта](#-особенности-учебного-проекта)
- [Лицензия](#-лицензия)

---

## 📖 Описание проекта

Интернет-магазин сантехники для рынка Российской Федерации с публичной витриной
и административной панелью. Проект разрабатывается по методологии spec-driven
development: сначала формализованы требования и технический дизайн, затем
по чек-листу задач реализуется код.

**Сроки разработки:** 04.05.2026 — 24.05.2026.

---

## 🗂 Структура репозитория

```
.
├── .kiro/specs/plumbing-shop-website/   # Спецификация фичи
│   ├── requirements.md                  # 26 требований в формате EARS
│   ├── design.md                        # Технический дизайн + Mermaid + correctness properties
│   └── tasks.md                         # План реализации (22 группы, 114 задач)
├── plumbing-shop/                       # Laravel-приложение
│   ├── app/
│   ├── config/
│   ├── database/
│   ├── resources/
│   │   ├── views/layouts/
│   │   │   ├── public.blade.php
│   │   │   └── admin.blade.php
│   │   ├── css/app.css
│   │   └── js/app.js
│   ├── routes/
│   ├── tests/
│   ├── tailwind.config.js
│   ├── composer.json
│   ├── package.json
│   └── .env.example
├── .github/
│   ├── workflows/ci.yml                 # GitHub Actions CI
│   ├── pull_request_template.md
│   └── ISSUE_TEMPLATE/
├── CONTRIBUTING.md                      # Git-флоу и правила работы
├── LICENSE                              # MIT
└── README.md
```

---

## 🛠 Стек технологий

| Слой | Технология | Версия |
|------|-----------|--------|
| Backend | Laravel | 11 |
| База данных | MySQL | 8 |
| Шаблоны | Blade | — |
| CSS | TailwindCSS | 3 |
| JS-микрофреймворк | Alpine.js | 3 |
| Сборка | Vite | 5 |
| Auth-скаффолд | Laravel Breeze | 2 |
| Изображения | Intervention Image | 3 |
| Sitemap | spatie/laravel-sitemap | 8 |
| Slug-генерация | cocur/slugify | 4 |
| Тесты | PHPUnit + Eris (PBT) | 10/1 |
| PHP | 8.2+ |

---

## ⚡ Быстрый старт

### Требования
- PHP **8.2+** с расширениями `mbstring`, `pdo_mysql`, `gd`, `xml`, `bcmath`, `intl`
- Composer 2
- Node.js 18+ и npm
- MySQL 8

### Установка

```bash
git clone https://github.com/shxpe0x/plumbing-shop.git
cd plumbing-shop/plumbing-shop

composer install
npm install

cp .env.example .env
php artisan key:generate

# Создать БД и прописать креды в .env
mysql -u root -p -e "CREATE DATABASE plumbing_shop CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

php artisan migrate
php artisan storage:link

npm run build
php artisan serve
```

Откройте http://localhost:8000

### Запуск тестов

```bash
php artisan test
php artisan test --testsuite=Property   # property-based тесты
```

---

## 📊 Прогресс реализации

| # | Группа | Статус |
|---|--------|--------|
| 1 | Каркас проекта и базовая инфраструктура | 🟢 в работе |
| 2 | Миграции и Eloquent-модели | ⚪ |
| 3 | Аутентификация и восстановление пароля | ⚪ |
| 5 | Сервисный слой | ⚪ |
| 7–10 | Публичная часть | ⚪ |
| 12–14 | Административная панель | ⚪ |
| 16 | Безопасность | ⚪ |
| 17 | SEO, sitemap, robots.txt | ⚪ |
| 18 | Производительность и адаптивность | ⚪ |
| 19 | Тестирование (PHPUnit + Eris) | ⚪ |
| 21 | Сидеры и демо-данные | ⚪ |

Полный чек-лист — в [`.kiro/specs/plumbing-shop-website/tasks.md`](./.kiro/specs/plumbing-shop-website/tasks.md).

---

## 🌳 Git-флоу

Проект использует упрощённый GitHub Flow с двумя долгоживущими ветками:

- **`main`** — стабильная ветка, отражает состояние, готовое к демонстрации.
- **`develop`** — рабочая ветка, в неё льются feature-ветки.
- **`feature/<id>-<краткое-описание>`** — ветки под отдельные задачи спецификации.

Подробности и правила оформления PR — в [CONTRIBUTING.md](./CONTRIBUTING.md).

---

## 📄 Документы спецификации

- [Требования](./.kiro/specs/plumbing-shop-website/requirements.md) — 26 требований в формате EARS, на русском языке.
- [Технический дизайн](./.kiro/specs/plumbing-shop-website/design.md) — архитектура, схема БД (ERD), маршрутизация, сервисный слой, sequence-диаграммы, 15 свойств корректности для PBT.
- [Задачи](./.kiro/specs/plumbing-shop-website/tasks.md) — план реализации с привязкой к Req X.Y и Property N.

---

## 🎓 Особенности учебного проекта

- ❌ Реальная отправка email и SMS **не реализуется** (учебные ограничения).
- 🔑 Восстановление пароля — через секретный вопрос/ответ (без писем).
- 💳 Платёжные шлюзы не подключаются. Способы оплаты: наличные/карта при получении, банковский перевод — фиксируются в БД.
- 🇷🇺 Регион РФ, валюта рубль (₽), интерфейс на русском, часовой пояс `Europe/Moscow`.

---

## 📝 Лицензия

[MIT](./LICENSE) © 2026 shxpe0x
