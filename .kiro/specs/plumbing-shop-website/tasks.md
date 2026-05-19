# Implementation Plan: интернет-магазин сантехники

## Overview

План разбивает реализацию на инкрементальные шаги: сначала каркас Laravel 11, миграции и базовые сервисы, затем публичная часть, после — административная панель, в конце — безопасность, SEO, производительность, тестирование и наполнение демо-данными. Каждая задача ссылается на конкретные подтребования из `requirements.md` (Req X.Y), а property-тесты — на свойства из раздела «Correctness Properties» в `design.md` (Property N).

Стек реализации фиксирован дизайном: **Laravel 11 + MySQL 8 + Blade + TailwindCSS 3 + Alpine.js + Intervention Image v3 + Eris (PBT) + PHPUnit 11**. Денежные значения хранятся в копейках (`bigint`), форматируются через `App\Support\Money` и хелпер `format_price()`.

Подзадачи, помеченные `*`, относятся к тестам и являются опциональными: их можно пропустить для ускорения MVP, но рекомендуется выполнять для критичных доменов (корзина, оформление, скидки, поиск, формат денег).

## Tasks

- [x] 1. Каркас проекта и базовая инфраструктура
  - [x] 1.1 Инициализировать Laravel 11 + Breeze (Blade-стек) и зафиксировать зависимости
    - Создать новый проект `laravel/laravel:^11`, установить `laravel/breeze` (stack: blade), `tailwindcss`, `alpinejs`, `intervention/image:^3`, `cocur/slugify`, `spatie/laravel-sitemap`, `giorgiosironi/eris` (require-dev)
    - Запустить `php artisan breeze:install blade`, выполнить миграции по умолчанию
    - Настроить `.env.example` (DB MySQL 8, `APP_LOCALE=ru`, `APP_TIMEZONE=Europe/Moscow`, `SESSION_DRIVER=database`, `SESSION_LIFETIME=1440`)
    - Настроить `config/app.php`: `locale=ru`, `fallback_locale=ru`, `timezone=Europe/Moscow`
    - Настроить `config/hashing.php`: bcrypt `rounds=12`
    - _Requirements: 7.5, 22.1, 26.1, 26.3_

  - [x] 1.2 Подключить TailwindCSS, Alpine.js и базовые layout-шаблоны
    - Сконфигурировать `tailwind.config.js` (контент: `resources/views/**/*.blade.php`), брейкпоинты sm/md/lg/xl/2xl покрывают 320–1920 px
    - Создать `resources/views/layouts/public.blade.php` и `resources/views/layouts/admin.blade.php` с семантическими `<header>/<nav>/<main>/<footer>` и одной `<h1>`
    - Подключить Alpine.js в `resources/js/app.js`, инициализировать в layout
    - _Requirements: 23.3, 25.1, 25.3, 25.6_

  - [x] 1.3 Создать языковые файлы и хелперы локализации
    - Создать `resources/lang/ru/{auth,validation,pagination,passwords,app}.php` и `resources/lang/ru.json`
    - Реализовать middleware `LogMissingTranslations`, регистрирующий отсутствующие ключи в `storage/logs/missing-translations.log`
    - Зарегистрировать макросы `Carbon::macro('toRu')` (`d.m.Y`) и `Carbon::macro('toRuDateTime')` (`d.m.Y H:i`) в `AppServiceProvider`
    - _Requirements: 26.1, 26.3, 26.4_

  - [x] 1.4 Реализовать класс `App\Support\Money` и хелпер `format_price`
    - Хранение `kopecks: int`, методы `plus/minus/multipliedBy/format`, фабрики `fromRubles`/`fromKopecks`
    - Хелпер `format_price(Money $m): string` форматирует как «1 234,56 ₽» (неразрывный пробел `\u{00A0}` как разделитель тысяч и перед символом ₽)
    - _Requirements: 26.2, 4.1, 5.2_

  - [x]* 1.5 Property-тест формата денежных сумм
    - **Property 13: Round-trip формата денежных сумм**
    - **Validates: Requirements 26.2, 4.1, 5.2**
    - В `tests/Property/MoneyFormatPropertyTest.php` сгенерировать `m ∈ [0; 999_999_999]` копеек, проверить шаблон, наличие неразрывного пробела, ровно 2 цифры после запятой, окончание на `\u{00A0}₽`, round-trip `parse_price(format_price(Money(m))) === m`

  - [x] 1.6 Создать структуру каталогов и базовые namespace-ы
    - `app/Http/Controllers/{Public,Auth,Admin}`, `app/Http/Middleware`, `app/Http/Requests`, `app/Services`, `app/Policies`, `app/View/Components`, `app/Support`, `app/Exceptions/Domain`
    - Заглушки `routes/web.php` и `routes/admin.php` (последний подключается с префиксом `/admin`)

- [ ] 2. Миграции и Eloquent-модели
  - [ ] 2.1 Миграции пользователей и аутентификации
    - Расширить таблицу `users` полями `name`, `phone`, `role` enum, `status` enum, `secret_question`, `secret_answer_hash`
    - Индексы: `UNIQUE(email)`, `INDEX(role)`, `INDEX(status)`
    - Создать модель `User` с `$casts`, скоупами `active()`, `admins()`
    - _Requirements: 7.1, 7.2, 21.2, 21.4_

  - [ ] 2.2 Миграции каталога: категории, бренды, материалы, цвета, типы установки, товары
    - Таблицы `categories` (с `parent_id`, `level`, `is_popular`, `slug` UNIQUE, `UNIQUE(parent_id, name)`), `brands`, `materials`, `colors`, `installation_types`
    - Таблица `products` с `price_kopecks bigint`, `stock`, `popularity_score`, мета-полями, индексами и FULLTEXT(`name`,`sku`)
    - Pivot-таблицы `product_material`, `product_color`, `product_installation_type` с FK ON DELETE CASCADE
    - Eloquent-модели с отношениями `belongsTo`, `hasMany`, `belongsToMany`
    - _Requirements: 2.1, 2.2, 3.1, 4.1, 15.1, 15.6, 15.8_

  - [ ] 2.3 Миграции изображений и характеристик товара
    - Таблицы `product_images` (5 полей путей, `alt`, `sort_order`, `is_primary`) и `product_attributes` (`UNIQUE(product_id, name)`)
    - Модели `ProductImage`, `ProductAttribute`
    - _Requirements: 15.4, 15.5, 15.8, 23.7, 24.2_

  - [ ] 2.4 Миграции корзин, заказов, истории статусов и избранного
    - Таблицы `carts` (с `user_id` UNIQUE NULL, `session_id` UNIQUE NULL, `expires_at`), `cart_items` (`UNIQUE(cart_id, product_id)`)
    - Таблицы `orders` (с уникальным человекочитаемым `number`, snapshot-полями покупателя, `subtotal_kopecks`, `delivery_cost_kopecks`, `total_kopecks`, статусом enum), `order_items` (snapshot названия/sku/цены), `order_status_history`
    - Таблица `favorites` с `UNIQUE(user_id, product_id)`
    - Модели `Cart`, `CartItem`, `Order`, `OrderItem`, `OrderStatusHistory`, `Favorite`
    - _Requirements: 5.1, 5.8, 6.3, 8.5, 8.7, 17.1, 17.4_

  - [ ] 2.5 Миграции акций, баннеров и «товара дня»
    - Таблица `promotions` с `discount_type` enum, `discount_percent` 1–99, `discount_fixed_kopecks`, CHECK на даты, флаг `is_product_of_day`
    - Pivot `promotion_product` (PK составной)
    - Таблица `banners` (поля `link_url`, `promotion_id`, `category_id`, `starts_at`, `ends_at`, `sort_order`, `is_active`)
    - Модели `Promotion`, `Banner` с скоупами `active()` (по периоду и флагу)
    - _Requirements: 1.1, 1.4, 16.1, 16.4, 16.5_

  - [ ] 2.6 Миграции контента: страницы, статьи блога, отзывы
    - Таблица `static_pages` (key enum about/delivery/warranty/contacts UNIQUE)
    - Таблица `blog_articles` с `status` enum draft/published, `published_at`, мета-полями
    - Таблица `reviews` со `status` enum pending/approved/rejected, `rating` 1–5, FK на товар (NULL = отзыв о магазине)
    - Модели `StaticPage`, `BlogArticle`, `Review`
    - _Requirements: 12.3, 13.1, 13.3, 14.1, 14.5, 18.1, 18.2_

  - [ ] 2.7 Миграции настроек, способов доставки и оплаты, регионов и тарифов
    - Таблица `shop_settings` (singleton, JSON-поля `phones`, `emails`, флаг `chat_enabled`)
    - Таблицы `delivery_methods`, `payment_methods` (UNIQUE имя, `is_enabled`)
    - Таблицы `regions` (UNIQUE имя), `delivery_tariffs` (`UNIQUE(delivery_method_id, region_id)`, `cost_kopecks`)
    - Модели и связи
    - _Requirements: 11.1, 12.2, 19.1, 19.3, 19.4_

  - [ ] 2.8 Миграции обратного звонка, чата и аналитики
    - Таблица `callback_requests` (`name`, `phone`, статус new/processed)
    - Таблицы `chat_sessions` (`visitor_token` UNIQUE, статус open/closed) и `chat_messages` (sender enum, `body` ≤2000)
    - Таблица `page_views` (`session_id`, `url`, `viewed_at`) с индексами
    - Модели `CallbackRequest`, `ChatSession`, `ChatMessage`, `PageView`
    - _Requirements: 10.3, 11.3, 11.4, 20.3_

  - [ ] 2.9 Фабрики моделей для тестов
    - `database/factories/*Factory.php` для всех основных сущностей (User, Category, Brand, Material, Color, InstallationType, Product, ProductImage, Cart, CartItem, Order, OrderItem, Promotion, Banner, BlogArticle, StaticPage, Review, DeliveryMethod, PaymentMethod, Region, DeliveryTariff, CallbackRequest, ChatSession, ChatMessage)
    - В фабриках использовать русские названия и валидные значения по требованиям

- [ ] 3. Аутентификация и восстановление пароля
  - [ ] 3.1 Расширить регистрацию полями секретного вопроса
    - `RegisterRequest`: правила email RFC 5322 5–254, пароль 8–128 с буквой и цифрой, вопрос 5–200, ответ 2–100
    - `RegisterController@store`: создать пользователя, хешировать пароль и `mb_strtolower(trim($answer))` через `Hash::make`, роль `customer`, статус `active`
    - Сообщение об уже занятом email: «Пользователь с таким email уже зарегистрирован»
    - _Requirements: 7.1, 7.2, 7.3, 7.4, 22.1_

  - [ ]* 3.2 Property-тест валидации регистрации и уникальности email
    - **Property 12: Валидация регистрации и уникальность email**
    - **Validates: Requirements 7.2, 7.3, 7.4, 22.1**
    - Eris-генераторы для всех полей; проверить, что валидные комбинации создают активного customer-а с bcrypt-хешами; уже занятый email отклоняется; невалидные входы отклоняются с указанием поля

  - [ ] 3.3 Реализовать вход с rate-limit и блокировкой учётной записи
    - `LoginRequest` + `LoginController@store`: throttle 5/15 мин на пару `email|ip` и 5/60 c на IP через `RateLimiter::for('login', ...)` в `AppServiceProvider::boot`
    - Проверка `status='blocked'` и сообщение «Учётная запись заблокирована»
    - При успехе — сессия 24 ч, редирект в `/account`
    - При ошибке — общее сообщение «Неверный email или пароль» (без раскрытия, какое поле неверно)
    - _Requirements: 7.5, 7.6, 7.7, 21.5, 22.9_

  - [ ] 3.4 Реализовать выход и middleware `EnsureUserNotBlocked`
    - `LoginController@destroy`: завершить сессию, редирект на `/`
    - Middleware `EnsureUserNotBlocked` (глобально для web): при `status='blocked'` — `Auth::logout()` + редирект на `/login`
    - _Requirements: 7.8, 21.5, 21.7_

  - [ ] 3.5 Реализовать трёхшаговое восстановление пароля по секретному вопросу
    - `PasswordRecoveryController`: `show` (форма ввода email), `question` (показ секретного вопроса по email), `reset` (проверка ответа + смена пароля)
    - `reset`: при успехе — обновить хеш пароля, инвалидировать все сессии пользователя (`DB::table('sessions')->where('user_id', ...)->delete()` или `Auth::logoutOtherDevices`)
    - Все ошибки — обобщённые: «Неверные данные восстановления» / «Новый пароль не соответствует требованиям», без раскрытия факта существования учётной записи
    - _Requirements: 7.9, 7.10, 7.11_

  - [ ]* 3.6 Property-тест round-trip восстановления пароля
    - **Property 11: Round-trip восстановления пароля**
    - **Validates: Requirements 7.9, 7.10, 7.11**
    - Сгенерировать пользователя с известным `secret_answer`, выполнить `reset` с верным ответом и валидным `p_new`, проверить: `login(email, p_new) = success`, `login(email, p_old) = failure`, прежние сессии инвалидированы; для неверных входов — пароль не меняется

  - [ ] 3.7 Реализовать `RoleMiddleware` и базовые политики доступа
    - Middleware `role:admin|content_manager`, возвращает 403 при несовпадении или отсутствии аутентификации
    - Регистрация в `bootstrap/app.php` (Laravel 11)
    - _Requirements: 21.3, 22.6, 22.7_

  - [ ]* 3.8 Property-тест защиты административной области и инварианта последнего админа
    - **Property 10: Защита административной области и инвариант последнего администратора**
    - **Validates: Requirements 21.3, 21.5, 21.6, 21.7, 21.8, 22.6, 22.7**
    - Проверить: для пользователей не-admin/blocked любой `GET /admin/*` → 403 без изменений данных; в любой момент `count(active admins) ≥ 1`; попытки нарушить инвариант отклоняются

- [ ] 4. Чекпоинт безопасности и аутентификации
  - Ensure all tests pass, ask the user if questions arise.

- [ ] 5. Сервисный слой: корзина, заказы, акции, поиск, доставка, изображения
  - [ ] 5.1 Реализовать `CartService` (гостевая и пользовательская корзина)
    - `getCart(?User, Request)`: для гостя — поиск/создание `Cart` по cookie `cart_token` (UUID, HttpOnly, Secure, SameSite=Lax, 14 дней); для пользователя — по `user_id`
    - `add($cart, $productId, $quantity)`: если позиция есть — `quantity = min(qty + $quantity, stock)`, иначе создать; отказ при `stock = 0` с сообщением о недоступности
    - `updateQuantity($cart, $itemId, $q)`: `q ≤ 0` → удалить позицию; `q > stock` → ограничить остатком + уведомление
    - `remove`, `clear`, `totals` (subtotal/discount/total в `Money`)
    - `mergeGuestCart($guest, $user)`: суммирование позиций с проверкой остатка
    - _Requirements: 5.1, 5.3, 5.4, 5.5, 5.6, 5.7, 5.8_

  - [ ]* 5.2 Property-тест инвариантов корзины
    - **Property 1: Инварианты корзины**
    - **Validates: Requirements 5.1, 5.3, 5.4, 5.5, 5.6, 4.5, 4.6**
    - Eris-генераторы последовательностей операций `add/update/remove`, проверить после каждой: `line_total = unit_price * quantity`, `quantity ∈ [1, stock]`, `subtotal = Σ line_total`, `q ≤ 0` удаляет позицию, `q > stock` ограничивается до `stock`

  - [ ] 5.3 Реализовать `PromotionService` (включая «товар дня»)
    - `activeForProduct(Product, ?Carbon)`: SELECT акций, охватывающих товар, по периоду, выбор с наибольшей скидкой детерминированно
    - `priceFor(Product, ?Carbon)`: для процентной — `round(price * (1 - p/100), 2)` (в копейках), для фиксированной — `price - fixed`, инвариант `> 0`
    - `activeAtMoment(Carbon)`: список активных акций
    - `productOfDay()`: товар с активной пометкой `is_product_of_day`
    - `setProductOfDay(Promotion)`: в транзакции снять флаг с предыдущего и установить на новом
    - _Requirements: 16.1, 16.2, 16.3, 16.4, 16.5_

  - [ ]* 5.4 Property-тест корректности применения скидки
    - **Property 4: Корректность применения скидки**
    - **Validates: Requirements 16.1, 16.2, 16.3**
    - Сгенерировать `(p.price, a.discount_type, a.percent/fixed, t)`, проверить: в активном периоде `priceFor` положительна, ≤ price, округлена до 2 знаков; формула для percent/fixed; вне периода `priceFor = price`; при нескольких активных акциях — детерминированный выбор и `final ≤ min(вариантов)`

  - [ ]* 5.5 Property-тест уникальности «товара дня»
    - **Property 15: Уникальность «товара дня»**
    - **Validates: Requirements 16.4, 16.5**
    - Последовательность `setProductOfDay(p_i)`, после каждой проверять: ровно один товар с активной пометкой, и это `p_i`

  - [ ] 5.6 Реализовать `OrderService` (создание, отмена, смена статуса)
    - `create(Cart, CheckoutData)`: `DB::transaction` с `SELECT ... FOR UPDATE` по всем `product_id` из корзины; перепроверить остатки; при недостатке — `InsufficientStockException` (откат); цены берутся из `PromotionService::priceFor`; стоимость доставки — из `DeliveryService`; INSERT `orders`/`order_items`/`order_status_history` (NULL → placed); UPDATE остатков; очистка корзины
    - `cancelByCustomer(Order, User)`: только для `placed`/`processing`; в транзакции вернуть остатки и записать переход; иначе — `OrderCancellationNotAllowedException`
    - `changeStatus(Order, $status, User $admin)`: валидация перечня, запись в историю
    - `calculateTotal(Cart, DeliveryMethod, ?Region, ?Promotion)`: предварительный расчёт без сохранения
    - Генерация номера: `ORD-{YYYY}-{seq6}` UNIQUE
    - _Requirements: 6.3, 6.4, 6.5, 6.6, 8.5, 8.6, 17.4, 17.5_

  - [ ]* 5.7 Property-тест атомарности оформления заказа
    - **Property 2: Атомарность оформления заказа и сохранение остатков**
    - **Validates: Requirements 6.3, 6.4, 6.5, 6.6**
    - Eris: при валидной корзине `qty_i ≤ stock_i` — создан ровно 1 заказ `placed`, `stock_i' = stock_i - qty_i`, корзина пуста, `total = Σ unit_price*qty + delivery_cost`; при `qty_i > stock_i` — заказы/остатки/корзина не меняются

  - [ ]* 5.8 Property-тест round-trip остатков при отмене заказа
    - **Property 3: Round-trip остатков при отмене заказа**
    - **Validates: Requirements 8.5, 8.6, 17.4**
    - Создать заказ → отменить со статусом placed/processing → остатки восстановлены, `status = cancelled`, переход записан в историю; для статусов shipped/delivered/cancelled отмена отклоняется

  - [ ] 5.9 Реализовать `SearchService`
    - SQL-запрос `LOWER(name) LIKE LOWER(?) OR LOWER(sku) LIKE ... OR LOWER(brands.name) LIKE ...` с `JOIN brands`
    - Сортировка результатов: точное совпадение → префиксное (`LIKE 'q%'`) → подстрочное (`LIKE '%q%'`); внутри группы — по `popularity_score DESC`
    - Валидация: `trim`, `mb_strlen ∈ [1, 100]`; пустой запрос → пользователю сообщение «Введите поисковый запрос»; >100 символов → ошибка длины
    - Постраничный возврат `LengthAwarePaginator` (per page 24)
    - _Requirements: 9.1, 9.2, 9.3, 9.5_

  - [ ]* 5.10 Property-тест полноты и релевантности поиска
    - **Property 9: Полнота и релевантности поиска**
    - **Validates: Requirements 9.1, 9.2**
    - Для произвольного товара и подстроки одного из полей `{name, sku, brand.name}` (без учёта регистра) — товар присутствует в результатах; порядок: точное → префиксное → подстрочное

  - [ ] 5.11 Реализовать `DeliveryService`
    - `tariffFor(DeliveryMethod, Region)`: поиск тарифа в `delivery_tariffs`
    - `calculateCost(DeliveryMethod, ?Region)`: тариф или 0 (если регион не задан и дефолтного тарифа нет — отдельная политика)
    - _Requirements: 6.6, 19.4_

  - [ ] 5.12 Реализовать `ImageService` на Intervention Image v3
    - `storeProductImage(Product, UploadedFile)`: валидация размера ≤10 МБ и MIME ∈ {jpeg, png, webp}, лимит ≤10 на товар; сохранить оригинал и сгенерировать варианты
    - `generateVariants($source)`: ресайз до 300 px и 1200 px (с сохранением пропорций), сохранить WebP и JPEG, качество 75–85
    - `deleteVariants($image)`: удалить все 5 файлов
    - Хранение в `storage/app/public/products/{productId}/{uuid}.*`, симлинк через `php artisan storage:link`
    - Авто-формирование `alt` ≤125 символов из `product.name`
    - _Requirements: 15.4, 15.5, 23.7, 24.2, 24.3, 24.5, 24.6_

- [ ] 6. Чекпоинт сервисного слоя
  - Ensure all tests pass, ask the user if questions arise.

- [ ] 7. Публичная часть — навигация, главная и информационные страницы
  - [ ] 7.1 Реализовать главное меню и базовый layout публичной части
    - В `layouts/public.blade.php`: `<header>` с логотипом и горизонтальным меню («Главная», «Каталог товаров», «О нас», «Доставка и оплата», «Гарантия и возврат», «Контакты», «Блог», «Отзывы»), на ширине <768 px — «гамбургер»-меню на Alpine.js
    - Минимальная область нажатия 44×44 px на мобильных
    - Кнопка «Консультация / Обратный звонок» во всех макетах
    - _Requirements: 1.2, 1.3, 10.1, 25.3, 25.4, 25.6_

  - [ ] 7.2 Реализовать `HomeController` и главную страницу
    - Сборка блоков: актуальные предложения (4–12), новинки (4–12, ≤30 дней), активные акции (1–8), баннеры (1–5), популярные категории (4–12, `is_popular=true`), товар дня
    - Пустые блоки скрываются; страница не ломается
    - Обработка недоступной целевой страницы баннера/меню — сообщение об ошибке + сохранение на главной
    - _Requirements: 1.1, 1.4, 1.5, 1.6, 16.5_

  - [ ] 7.3 Реализовать `PageController` для статических страниц
    - Маршруты `/about`, `/delivery`, `/warranty`, `/contacts` отображают `static_pages` по ключу
    - Страница «Контакты» подтягивает из `shop_settings` 1–5 телефонов, 1–3 email, адрес, режим работы; пустые поля скрываются, если все поля категории пусты — сообщение об отсутствии данных
    - 404-страница «Возврат на главную» при отсутствии записи
    - _Requirements: 12.1, 12.2, 12.3, 12.4, 12.5_

- [ ] 8. Публичная часть — каталог, карточка товара, поиск
  - [ ] 8.1 Реализовать `CatalogController` и список товаров категории
    - `index()`: корень каталога; `show($slug)`: страница категории с подкатегориями
    - `ProductRepository::filtered($filters, $sort, $page)`: товары категории + всех её потомков, postraничный вывод по 24, по умолчанию сорт «по наличию, затем по наименованию по возрастанию»
    - Сообщение «Товаров нет» при пустой категории; 404 при несуществующем slug
    - _Requirements: 2.1, 2.2, 2.3, 2.4, 2.5, 2.6, 2.7_

  - [ ] 8.2 Реализовать панель фильтров и сортировки в каталоге
    - Фильтры: цена (min/max ∈ [0, 9 999 999], шаг 1 ₽), бренд, материал, цвет, тип установки, наличие
    - Сортировки: «по популярности» (default), «по новизне», «по цене ↑», «по цене ↓»
    - Логика AND между фильтрами разных категорий
    - Валидация: `price_min > price_max`, отрицательные, нечисловые → сообщение об ошибке + сохранение прежнего списка
    - Сохранение фильтров и сортировки в query string и сессии в пределах каталога
    - _Requirements: 3.1, 3.2, 3.3, 3.4, 3.5, 3.6, 3.7_

  - [ ]* 8.3 Property-тест AND-композиции фильтров
    - **Property 5: AND-композиция фильтров каталога**
    - **Validates: Requirements 3.1, 3.2, 3.3**
    - Сгенерировать набор товаров и набор фильтров; результат `filter(P, F)` равен пересечению результатов отдельных фильтров; невалидные параметры цены отклоняются и список не меняется

  - [ ]* 8.4 Property-тест монотонности и стабильности сортировки
    - **Property 6: Монотонность и стабильность сортировки**
    - **Validates: Requirements 3.5, 3.6, 3.7, 2.3, 2.4**
    - Для каждого варианта `s` — соседние элементы упорядочены по ключу; `set(sort(filter(P,F),s)) = set(filter(P,F))`

  - [ ]* 8.5 Property-тест иерархии категорий
    - **Property 7: Иерархия категорий**
    - **Validates: Requirements 2.3, 2.4, 2.2**
    - Для дерева глубины 1–3 — товар категории-потомка присутствует в листинге каждого предка; листинг подкатегории содержит только товары этой подкатегории и её потомков

  - [ ] 8.6 Реализовать `ProductController@show` (карточка товара)
    - Галерея фотографий (`<picture>` с WebP+JPEG), наименование (≤255), артикул, бренд, цена с учётом активной акции (зачёркнутая исходная + итоговая), признак наличия, характеристики, описание (≤5000)
    - Поле количества: значение по умолчанию 1, диапазон `[1, stock]`; при `stock=0` — «Нет в наличии», кнопка и поле неактивны
    - Клик по миниатюре переключает основное изображение
    - 404 при отсутствии товара
    - JSON-LD `Product` (см. задачу 14.4)
    - _Requirements: 4.1, 4.2, 4.3, 4.4, 4.7, 16.2, 23.6, 24.5_

  - [ ] 8.7 Реализовать `SearchController` и страницу результатов
    - `GET /search?q=...` через `SearchService`
    - Пагинация по 24, сортировка по релевантности
    - Сообщения: «Введите поисковый запрос» (пусто), «По вашему запросу ничего не найдено», ошибка превышения 100 символов
    - _Requirements: 9.1, 9.2, 9.3, 9.4, 9.5_

  - [ ]* 8.8 Property-тест монотонности пагинации
    - **Property 8: Монотонность пагинации**
    - **Validates: Requirements 2.3, 2.4, 8.1, 13.1, 17.1, 18.3, 21.1**
    - Для произвольного упорядоченного `S` и `perPage=k`: все промежуточные страницы по `k`, последняя ≤ k и не пуста, объединение страниц = S без дубликатов, порядок сохраняется

- [ ] 9. Публичная часть — корзина, оформление заказа, личный кабинет
  - [ ] 9.1 Реализовать `CartController` и страницу корзины
    - Маршруты: GET `/cart`, POST `/cart/add`, PATCH `/cart/{itemId}`, DELETE `/cart/{itemId}`
    - Виджет корзины на Alpine.js (счётчик в шапке)
    - Отображение: миниатюра, наименование, цена/ед., количество, стоимость позиции, итог
    - Сообщения о превышении остатка с указанием максимума
    - _Requirements: 4.5, 4.6, 5.1, 5.2, 5.3, 5.4, 5.5, 5.6, 5.7_

  - [ ] 9.2 Реализовать `CheckoutController` и форму оформления
    - `show()`: форма с полями ФИО (2–150), телефон (10–15 цифр, формат +7/8 + 10 цифр), email (≤254, RFC), адрес (5–500), способ доставки (из активных), способ оплаты (из активных), регион (опц.), комментарий (≤1000)
    - `recalculate(Request)`: AJAX-пересчёт суммы при смене способа доставки/региона за ≤2 c
    - `store(CheckoutRequest)`: валидация и вызов `OrderService::create` в транзакции; при ошибках валидации — 422 с сообщениями по полям и сохранением `old()`; при недостатке остатков — 422 со списком товаров и доступным количеством
    - `success($number)`: страница подтверждения (номер, состав, итог)
    - _Requirements: 6.1, 6.2, 6.3, 6.4, 6.5, 6.6_

  - [ ] 9.3 Реализовать `AccountController` (личный кабинет)
    - `index()`: список заказов авторизованного пользователя, ORDER BY `created_at DESC`, по 20 на странице, поля: номер, дата, итог, статус (маппинг placed→Оформлен, processing→В обработке и т.д.)
    - `order($id)`: детали заказа (состав, адрес, способ оплаты, статус); политика — только владелец
    - `cancel($id)`: подтверждение + `OrderService::cancelByCustomer`; кнопка активна только для placed/processing
    - Пустой список → сообщение «У вас пока нет заказов»
    - Неавторизованный → редирект на `/login`
    - _Requirements: 8.1, 8.2, 8.3, 8.4, 8.5, 8.6, 8.8, 8.9_

  - [ ] 9.4 Реализовать `FavoriteController` (избранное)
    - `index()`: список избранного авторизованного покупателя
    - `toggle($productId)`: добавить/удалить, лимит 200 → 422 «Превышен лимит избранных товаров»; запрет дубликатов на уровне `UNIQUE(user_id, product_id)`
    - Пустой список → сообщение
    - _Requirements: 8.7, 8.9_

  - [ ]* 9.5 Feature-тест слияния гостевой корзины при входе
    - При успешном `POST /login` гостевая корзина из cookie `cart_token` объединяется с пользовательской: позиции суммируются с ограничением остатка
    - _Requirements: 5.8, 7.5_

- [ ] 10. Публичная часть — блог, отзывы, обратный звонок, чат
  - [ ] 10.1 Реализовать раздел «Блог»
    - `BlogController@index`: список опубликованных статей, ORDER BY `published_at DESC`, по 12 на странице, поля: заголовок, анонс (≤300), дата ДД.ММ.ГГГГ, миниатюра
    - `BlogController@show($slug)`: показ только статусов `published`; 404 для draft/удалённых
    - Пустой раздел → сообщение «Статей пока нет»
    - _Requirements: 13.1, 13.2, 13.3, 13.4, 13.5_

  - [ ] 10.2 Реализовать страницу отзывов и форму нового отзыва
    - `ReviewController@index`: одобренные отзывы по убыванию даты
    - `ReviewController@store`: только для авторизованных; валидация `body` 10–2000, `rating` 1–5; статус `pending`; подтверждение «Отзыв отправлен на модерацию»
    - Неавторизованный → редирект на `/login`
    - Schema.org `Review` в блоке отзывов о товаре (см. задачу 14.4)
    - _Requirements: 14.1, 14.2, 14.3, 14.4, 14.5, 14.6, 23.6_

  - [ ] 10.3 Реализовать форму обратного звонка
    - Модальное окно на Alpine.js на всех публичных страницах
    - `CallbackController@store`: валидация имени (2–50) и телефона (формат +7/8 + 10 цифр); сохранение в `callback_requests`; ответ «Спасибо, мы перезвоним вам в ближайшее время»
    - При сбое БД — 503 + сохранение введённых данных в полях + предложение повторить
    - _Requirements: 10.1, 10.2, 10.3, 10.4, 10.5, 10.6_

  - [ ] 10.4 Реализовать виджет онлайн-чата
    - Условный рендер по `shop_settings.chat_enabled`; виджет на всех публичных страницах
    - `ChatController@store`: валидация длины сообщения (1–2000), сохранение в `chat_messages`, отображение с timestamp
    - Гостевая идентификация через cookie `chat_visitor_token` (UUID)
    - _Requirements: 11.1, 11.2, 11.3, 11.4, 11.5_

- [ ] 11. Чекпоинт публичной части
  - Ensure all tests pass, ask the user if questions arise.

- [ ] 12. Административная панель — структура и управление каталогом
  - [ ] 12.1 Реализовать layout админ-панели и `DashboardController`
    - `routes/admin.php` с middleware `auth` + `role:admin`/`role:admin|content_manager`
    - `layouts/admin.blade.php` с боковым меню разделов
    - Dashboard со сводкой: новые заказы, новые отзывы на модерации, новые обратные звонки
    - _Requirements: 21.3, 22.6, 22.7_

  - [ ] 12.2 Реализовать CRUD товаров (`Admin\ProductController`)
    - `index/create/store/edit/update/destroy`; `StoreProductRequest`/`UpdateProductRequest` с валидацией: наименование 1–200, существующая категория, цена `[0,01; 9 999 999,99]` (вход в рублях, конверсия в копейки), остаток `[0; 999 999]`
    - При ошибке — `old()` сохраняется, сообщение по полю
    - Удаление — с подтверждением
    - _Requirements: 15.1, 15.2, 15.3_

  - [ ] 12.3 Реализовать управление изображениями товара (`Admin\ProductImageController`)
    - `store`: загрузка через `ImageService::storeProductImage`, мульти-загрузка, проверка лимитов 10 МБ/10 файлов
    - `destroy`: удаление с очисткой всех вариантов
    - Установка primary-изображения
    - _Requirements: 15.4, 15.5, 24.2, 24.3, 24.6_

  - [ ] 12.4 Реализовать управление характеристиками товара
    - Динамическая форма характеристик (Alpine.js): до 50 атрибутов на товар, имя 1–100, значение 1–500
    - Валидация уникальности имени атрибута в пределах товара
    - _Requirements: 15.8_

  - [ ] 12.5 Реализовать CRUD категорий (`Admin\CategoryController`)
    - Создание/переименование/перемещение/удаление; контроль глубины 2–3 уровня; `UNIQUE(parent_id, name)`
    - При попытке удалить категорию с товарами или подкатегориями — 422 «Категория не пуста»
    - _Requirements: 2.1, 2.2, 15.6, 15.7_

- [ ] 13. Административная панель — акции, баннеры, заказы
  - [ ] 13.1 Реализовать CRUD акций (`Admin\PromotionController`)
    - Поля: товары (1–1000), тип скидки (percent 1–99 / fixed 1₽–price-1₽), даты (`ends_at > starts_at`)
    - Валидация: при недопустимых значениях/датах — 422 без сохранения
    - `setProductOfDay($id)` — снятие флага с предыдущего в транзакции
    - _Requirements: 16.1, 16.3, 16.4_

  - [ ] 13.2 Реализовать CRUD баннеров (`Admin\BannerController`)
    - Поля: заголовок, изображение, ссылка, опц. `promotion_id`/`category_id`, период активности, sort_order
    - 1–5 активных одновременно (мягкое ограничение в выборке для главной)
    - _Requirements: 1.1, 1.4_

  - [ ] 13.3 Реализовать управление заказами (`Admin\OrderController`)
    - `index`: пагинация по 50, сортировки по дате/сумме, фильтр по статусу, поля: номер, дата ISO 8601, ФИО, сумма, статус
    - `show($id)`: полный заказ, история статусов
    - `updateStatus(UpdateOrderStatusRequest)`: валидация enum {Новый/В обработке/Отправлен/Выполнен/Отменён}; запись в `order_status_history` с user_id и timestamp
    - Невалидный статус → 422 без изменения
    - _Requirements: 17.1, 17.2, 17.3, 17.4, 17.5_

- [ ] 14. Административная панель — контент, настройки, регионы, пользователи, отчёты
  - [ ] 14.1 Реализовать управление статическими страницами и блогом
    - `Admin\PageController`: редактирование 4 страниц (about/delivery/warranty/contacts), заголовок 1–200, контент 1–50 000
    - `Admin\ArticleController`: CRUD статей блога, статусы draft/published, `published_at`, мета-поля
    - При ошибках валидации — сохранение предыдущей версии
    - _Requirements: 18.1, 18.2, 18.6_

  - [ ] 14.2 Реализовать модерацию отзывов
    - `Admin\ReviewController@index`: статус `pending`, ORDER BY `created_at DESC`, пагинация по 20
    - `approve/reject`: смена статуса в течение 3 c; при ошибке — сохранение предыдущего статуса
    - _Requirements: 18.3, 18.4, 18.5, 18.7_

  - [ ] 14.3 Реализовать настройки магазина и способы доставки/оплаты
    - `Admin\SettingController@edit/update`: телефоны (1–5, по 5–20 символов), email (1–5, RFC, ≤254), адрес (≤500), режим работы (≤500), флаг `chat_enabled`
    - `Admin\DeliveryMethodController`/`Admin\PaymentMethodController`: CRUD, имя 1–100, `is_enabled`, изменения применяются к публичной части за ≤5 c
    - При отключении способа — он исключается из выбора при оформлении, ранее оформленные заказы не меняются
    - _Requirements: 19.1, 19.2, 19.3, 19.6_

  - [ ] 14.4 Реализовать управление регионами и тарифами доставки
    - `Admin\RegionController`: CRUD регионов (имя 1–100, UNIQUE)
    - `Admin\DeliveryTariffController`: CRUD пар «регион + способ доставки», тариф `[0,00; 1 000 000,00]` ₽ с точностью до 2 знаков
    - Валидация: вне диапазона или >2 знаков → отказ + сообщение
    - _Requirements: 19.4, 19.5_

  - [ ] 14.5 Реализовать управление пользователями (`Admin\UserController`)
    - `index`: пагинация по 20, ORDER BY `created_at DESC`, поля: email, дата, роль, статус
    - Назначение роли (Покупатель/Контент-менеджер/Администратор) с подтверждением
    - Блокировка/разблокировка с подтверждением; завершение всех сессий заблокированного за ≤5 c
    - Защита: запрет действий над собственной учётной записью; запрет блокировки/смены роли последнего активного администратора (`LastAdministratorException`)
    - _Requirements: 21.1, 21.2, 21.3, 21.4, 21.5, 21.6, 21.7, 21.8_

  - [ ] 14.6 Реализовать обратные звонки и админ-чат
    - `Admin\CallbackController`: список заявок, смена статуса new/processed
    - `Admin\ChatController`: список открытых сессий, отправка ответов оператора
    - _Requirements: 10.3, 11.4_

  - [ ] 14.7 Реализовать отчёты (`Admin\ReportController`)
    - `sales`: разрезы по товарам/категориям/периодам с гранулярностью день/неделя/месяц; интервал 1–366 дней
    - `traffic`: уникальные посетители (по `session_id` в пределах суток) и просмотры за интервал
    - `users`: количество новых регистраций, среднее заказов/активный пользователь (округление до 2 знаков)
    - `conversion`: orders/visitors × 100% (округление до 2 знаков); при `visitors=0` — «нет данных»
    - Валидация интервала: `start ≤ end`, `≤366 дней`, `end ≤ today` → иначе 422
    - _Requirements: 20.1, 20.2, 20.3, 20.4, 20.5, 20.6_

  - [ ] 14.8 Реализовать middleware `TrackPageView` и команду очистки
    - `TrackPageView` логирует GET-запросы публичных страниц в `page_views` (исключая статику и `/admin/*`)
    - Artisan-команда `cart:purge-expired` для очистки гостевых корзин старше 14 дней (через scheduler)
    - _Requirements: 5.8, 20.3_

- [ ] 15. Чекпоинт админ-панели
  - Ensure all tests pass, ask the user if questions arise.

- [ ] 16. Безопасность и защита от типовых атак
  - [ ] 16.1 Включить и проверить CSRF/HTTPS-механизмы
    - `VerifyCsrfToken` глобально для web-группы; директива `@csrf` во всех формах
    - Middleware `force.https` + `URL::forceScheme('https')` в production
    - Запретить `{!! !!}` в публичных шаблонах (lint-правило/code-review check)
    - _Requirements: 22.3, 22.4, 22.5, 22.8_

  - [ ] 16.2 Зафиксировать политики и проверки авторизации
    - Policies: `ProductPolicy`, `OrderPolicy`, `ArticlePolicy`, `ReviewPolicy`, `UserPolicy`
    - Проверка прав до изменения данных; 403 для отказа
    - _Requirements: 21.3, 22.6, 22.7_

  - [ ] 16.3 Реализовать доменные исключения и глобальный Handler
    - `App\Exceptions\Domain\{InsufficientStockException, OrderCancellationNotAllowedException, LastAdministratorException, InvalidPromotionException, RateLimitedException}`
    - Глобальный `Handler` мапит исключения на HTTP-коды (419/422/429/403/404/503) и сообщения
    - Логирование 5xx и финансовых операций с маскированием PII
    - _Requirements: 6.4, 8.6, 10.6, 21.5, 21.8, 22.5, 22.7, 22.9_

- [ ] 17. SEO, sitemap и robots.txt
  - [ ] 17.1 Реализовать генерацию ЧПУ
    - Хелпер `generateSlug(string)` через `cocur/slugify` (транслитерация кириллицы), регэксп `^[a-z0-9](?:[a-z0-9-]{0,198}[a-z0-9])?$`, длина ≤200
    - Поля `slug` UNIQUE у `Product`, `Category`, `BlogArticle`; ручная правка в админке
    - _Requirements: 23.1_

  - [ ] 17.2 Реализовать формирование мета-тегов и семантической разметки
    - Дефолтная генерация `meta_title` (10–60) и `meta_description` (50–160) из названия/описания
    - Blade-компоненты `<x-meta>` и базовый layout с одним `<h1>` и обязательными `<header>/<nav>/<main>/<footer>`
    - _Requirements: 23.2, 23.3_

  - [ ] 17.3 Реализовать sitemap.xml и robots.txt
    - Artisan-команда `sitemap:generate` через `spatie/laravel-sitemap` (категории, товары, статьи, статические страницы); запуск планировщиком ежечасно
    - `public/robots.txt`: разрешает публичные разделы, запрещает `/admin`, `/cart`, `/checkout`, `/account`
    - Маршрут `/sitemap.xml` отдаёт сгенерированный файл
    - _Requirements: 23.4, 23.5, 23.8_

  - [ ] 17.4 Реализовать Schema.org JSON-LD
    - На карточке товара: `Product` с `name`, `image`, `description`, `sku`, `offers.price`, `offers.priceCurrency=RUB`, `offers.availability`
    - В блоке отзывов о товаре: `Review` с `author`, `reviewRating`, `reviewBody`
    - Атрибут `alt` главного изображения = `mb_substr(product.name, 0, 125)`
    - _Requirements: 23.6, 23.7_

  - [ ]* 17.5 Property-тест SEO-инвариантов публичных страниц
    - **Property 14: SEO-инварианты публичных страниц**
    - **Validates: Requirements 23.1, 23.3, 23.6, 23.7**
    - Eris: `slug` соответствует регэкспу и ≤200; рендер содержит ровно один `<h1>` и `<header>/<main>/<footer>`; JSON-LD `Product` имеет все обязательные непустые поля и `priceCurrency=RUB`; `alt` главного изображения 1–125 и содержит подстроку имени товара (или его усечения)

- [ ] 18. Производительность и адаптивность
  - [ ] 18.1 Настроить кеш-заголовки и оптимизацию статики
    - Конфигурация `Cache-Control: public, max-age=604800` для статики (Nginx-конфиг или middleware для отдаваемых файлов через PHP)
    - Минимизация JS/CSS через Vite production build
    - _Requirements: 24.4_

  - [ ] 18.2 Настроить отдачу изображений `<picture>` и WebP-fallback
    - Blade-компонент `<x-product-image>` рендерит `<picture><source type="image/webp"><img src="...jpg">`
    - Проверка `ImageService` — реальная конверсия в WebP с качеством 75–85
    - _Requirements: 24.3, 24.5_

  - [ ] 18.3 Адаптивная вёрстка 320–1920 px
    - Tailwind-классы для брейкпоинтов; «гамбургер»-меню при <768 px
    - Минимальный размер touch-целей 44×44 px
    - Баннер «Рекомендуем обновить браузер» для устаревших браузеров (через UA-парсер на стороне сервера)
    - _Requirements: 25.1, 25.3, 25.4, 25.5, 25.6_

- [ ] 19. Тестирование (PHPUnit + Eris)
  - [ ] 19.1 Подготовить инфраструктуру тестирования
    - Установить `giorgiosironi/eris` (require-dev), создать каталоги `tests/Feature/{Public,Auth,Admin}`, `tests/Unit/{Services,Support}`, `tests/Property/`
    - Настроить `phpunit.xml`: testsuite `Property`, переменная `ERIS_SEED` (для CI — фикс, локально — рандом)
    - Настроить минимум 100 итераций (`->withMaxSize(100)` или `->iterations(100)`)
    - Подключить `RefreshDatabase` к Feature-тестам
    - Все property-тесты должны быть выполнены с тегом-комментарием формата `Feature: plumbing-shop-website, Property N: ...` для трассируемости

  - [ ] 19.2 Feature-тесты публичной части
    - `Public/{HomeTest, CatalogTest, ProductTest, CartTest, CheckoutTest, SearchTest, AccountTest, BlogTest, ReviewTest, CallbackTest, ChatTest, PageTest}.php`
    - Покрыть happy-path и ключевые ошибки валидации
    - _Requirements: 1, 2, 3, 4, 5, 6, 8, 9, 10, 11, 12, 13, 14_

  - [ ] 19.3 Feature-тесты аутентификации
    - `Auth/{LoginTest, RegisterTest, PasswordRecoveryTest}.php`: успех, неверные credentials, rate-limit, заблокированный пользователь, восстановление по секретному вопросу
    - _Requirements: 7, 21.5, 22.9_

  - [ ] 19.4 Feature-тесты админки
    - `Admin/{ProductCrudTest, CategoryCrudTest, PromotionTest, OrderManagementTest, ReviewModerationTest, UserManagementTest, ReportTest, SettingTest, DeliveryTariffTest}.php`
    - Включить проверки 403 для не-админов, защиты последнего админа, валидаций и подтверждений
    - _Requirements: 15, 16, 17, 18, 19, 20, 21_

  - [ ] 19.5 Unit-тесты сервисов и Money
    - `Unit/Services/{CartServiceTest, OrderServiceTest, PromotionServiceTest, PriceFormatterTest, SearchServiceTest, DeliveryServiceTest}.php`
    - `Unit/Support/MoneyTest.php`
    - _Requirements: 5, 6, 9, 16, 26.2_

  - [ ] 19.6 Smoke-тесты SEO и инфраструктуры
    - Тест маршрута `/sitemap.xml` (валидный XML, наличие категорий/товаров/статей)
    - Тест файла `/robots.txt` (наличие правил для `/admin`, `/cart`, `/checkout`, `/account`)
    - Тест JSON-LD на карточке товара
    - _Requirements: 23.4, 23.5, 23.6, 23.8_

- [ ] 20. Чекпоинт тестирования
  - Ensure all tests pass, ask the user if questions arise.

- [ ] 21. Финальная подготовка: сидеры, демо-данные, документация
  - [ ] 21.1 Реализовать базовые сидеры справочников
    - `RolesSeeder`/`AdminSeeder`: создаёт администратора по умолчанию (email/пароль из `.env.example`)
    - `CategoriesSeeder`: 10 категорий верхнего уровня из Req 2.1, по 2–3 подкатегории на каждую
    - `BrandsSeeder`, `MaterialsSeeder`, `ColorsSeeder`, `InstallationTypesSeeder`
    - `DeliveryMethodsSeeder` (3 способа), `PaymentMethodsSeeder` (Наличные при получении, Карта при получении, Банковский перевод), `RegionsSeeder` (3 региона), `DeliveryTariffsSeeder`
    - `ShopSettingsSeeder` (контакты, режим работы, `chat_enabled=true`)
    - `StaticPagesSeeder` (about/delivery/warranty/contacts с контентом-заглушками)
    - _Requirements: 2.1, 12.2, 12.3, 19.1, 19.3, 19.4_

  - [ ] 21.2 Реализовать демо-сидер `DemoSeeder`
    - 20–40 товаров на подкатегорию (через фабрики), часть с акциями (percent и fixed), 1 «товар дня»
    - 3–5 баннеров, 3 опубликованные статьи блога, 5 одобренных и 2 ожидающих модерации отзывов
    - 5 примеров оформленных заказов в разных статусах для проверки личного кабинета и админки
    - 3 заявки на обратный звонок, 2 чат-сессии
    - _Requirements: 1.1, 13.1, 14.1, 16.1, 16.4, 17.1_

  - [ ] 21.3 Подготовить README и инструкцию по запуску
    - `README.md`: установка PHP 8.3+, MySQL 8, composer, npm; шаги: `composer install`, `npm install`, `cp .env.example .env`, `php artisan key:generate`, `php artisan migrate --seed`, `php artisan storage:link`, `npm run build`, `php artisan serve`
    - Инструкция по запуску тестов: `php artisan test`, `php artisan test --testsuite=Property`
    - Учётные данные демо-админа

  - [ ] 21.4 Финальный smoke-проход
    - Прогнать миграции с нуля, накатить сидеры, открыть главную, каталог, карточку товара, оформить тестовый заказ, отменить заказ из ЛК, отправить отзыв, оформить обратный звонок, войти как админ, изменить статус заказа, одобрить отзыв
    - Этот шаг выполняется через автоматизированные feature-тесты (без ручного запуска приложения)
    - _Requirements: 1, 2, 4, 5, 6, 8, 10, 14, 17, 18_

- [ ] 22. Финальный чекпоинт
  - Ensure all tests pass, ask the user if questions arise.

## Notes

- Подзадачи, помеченные `*`, — тесты (unit/feature/property), являются опциональными и могут быть пропущены для ускорения MVP. Для критичных доменов (корзина, оформление, скидки, отмена заказа, формат денег, поиск, фильтрация, пагинация, SEO) рекомендуется выполнять property-тесты.
- Каждая задача ссылается на конкретные подтребования (`Req X.Y`); каждый property-тест ссылается на свойство из `design.md` и проверяемые требования.
- Чекпоинты (задачи 4, 6, 11, 15, 20, 22) — точки сверки: «Ensure all tests pass, ask the user if questions arise.»
- Property-тесты выполняются через Eris (`giorgiosironi/eris`) с минимум 100 итераций; контр-примеры shrink-аются автоматически. Для PHP-кода это эквивалент QuickCheck.
- Все денежные значения хранятся в копейках (`bigint`) и форматируются только при выводе через `format_price()` (Property 13).
- Учебные ограничения: реальная отправка email/SMS не реализуется, платёжные шлюзы не подключаются — способ оплаты фиксируется в БД, расчёт при получении.
- Сроки разработки: 04.05.2026 – 24.05.2026 (≈3 недели). План разбит так, чтобы критичные части публичной витрины (главная, каталог, корзина, оформление, ЛК) были готовы к середине периода, админка и финальная отделка — во второй половине.

## Task Dependency Graph

```json
{
  "waves": [
    { "id": 0, "tasks": ["1.1", "1.2", "1.3", "1.4", "1.6"] },
    { "id": 1, "tasks": ["1.5", "2.1", "2.2"] },
    { "id": 2, "tasks": ["2.3", "2.4", "2.5", "2.6", "2.7", "2.8"] },
    { "id": 3, "tasks": ["2.9", "3.1", "3.3", "3.4", "3.7"] },
    { "id": 4, "tasks": ["3.2", "3.5", "3.6", "3.8"] },
    { "id": 5, "tasks": ["5.1", "5.3", "5.6", "5.9", "5.11", "5.12"] },
    { "id": 6, "tasks": ["5.2", "5.4", "5.5", "5.7", "5.8", "5.10"] },
    { "id": 7, "tasks": ["7.1", "7.2", "7.3", "8.1", "8.6", "8.7", "12.1"] },
    { "id": 8, "tasks": ["8.2", "8.3", "8.4", "8.5", "8.8", "9.1", "9.4", "10.1", "10.2", "10.3", "10.4"] },
    { "id": 9, "tasks": ["9.2", "9.3", "9.5", "12.2", "12.5", "13.1", "13.2", "14.1", "14.2"] },
    { "id": 10, "tasks": ["12.3", "12.4", "13.3", "14.3", "14.4", "14.5", "14.6", "14.7", "14.8"] },
    { "id": 11, "tasks": ["16.1", "16.2", "16.3", "17.1", "17.2", "17.3", "17.4", "18.1", "18.2", "18.3"] },
    { "id": 12, "tasks": ["17.5", "19.1"] },
    { "id": 13, "tasks": ["19.2", "19.3", "19.4", "19.5", "19.6"] },
    { "id": 14, "tasks": ["21.1"] },
    { "id": 15, "tasks": ["21.2", "21.3"] },
    { "id": 16, "tasks": ["21.4"] }
  ]
}
```
