# Contributing

Документ описывает Git-флоу и правила оформления изменений в репозитории.

## Ветки

| Ветка | Назначение | Стабильность |
|-------|-----------|--------------|
| `main` | Стабильная демо-ветка | Защищена, изменения только через PR из `develop` |
| `develop` | Рабочая ветка интеграции | Default-ветка; в неё льются `feature/*` |
| `feature/<id>-<slug>` | Ветка под одну задачу из `tasks.md` | Удаляется после слияния |
| `fix/<slug>` | Срочное исправление | Удаляется после слияния |

## Соглашение об именовании feature-веток

Формат: `feature/<task-id>-<краткое-описание-латиницей>`

Примеры:
- `feature/1.1-laravel-scaffold`
- `feature/1.2-tailwind-layouts`
- `feature/2.1-user-migration`
- `feature/5.1-cart-service`

`task-id` — номер подзадачи из [`tasks.md`](./.kiro/specs/plumbing-shop-website/tasks.md).

## Поток работы

```
feature/x.y-...  →  develop  →  main
       ↑              ↑
   PR + review    PR (релиз)
```

1. Создаём feature-ветку от `develop`:
   ```bash
   git checkout develop
   git pull
   git checkout -b feature/1.3-localization
   ```
2. Выполняем задачу, делаем коммиты по правилам ниже.
3. Пушим ветку:
   ```bash
   git push -u origin feature/1.3-localization
   ```
4. Открываем Pull Request `feature/1.3-localization → develop`.
5. После слияния — удаляем feature-ветку.
6. Когда в `develop` накопилась стабильная порция изменений, открывается PR `develop → main` (релиз).

## Сообщения коммитов (Conventional Commits)

Формат: `<type>(<scope>): <description>`

**Типы:**
- `feat` — новая функциональность
- `fix` — исправление бага
- `docs` — изменение документации
- `style` — форматирование без логических изменений
- `refactor` — рефакторинг без новой функциональности
- `test` — добавление/изменение тестов
- `chore` — служебные изменения (конфиги, зависимости)

**Примеры:**
```
feat(cart): add CartService with quantity validation
fix(auth): handle blocked user on login
docs(readme): add quick start section
test(cart): property-based test for cart invariants
chore(deps): bump intervention/image to 3.5
```

Тело коммита (опционально) — на русском, с указанием Req-номеров:
```
feat(catalog): add filter panel

- price/brand/material/color/installation/in_stock filters
- AND-композиция между фильтрами разных категорий

Req: 3.1, 3.2, 3.3
Task: 8.2
```

## Требования к Pull Request

- **Заголовок:** `[<task-id>] <краткое описание>` (например, `[1.3] Локализация и хелперы`).
- **Описание:** ссылка на пункт `tasks.md`, перечень изменений, скриншоты UI (если применимо).
- **Чек-лист в описании:**
  - [ ] Код собирается (`npm run build`)
  - [ ] Тесты проходят (`php artisan test`)
  - [ ] Обновлены затронутые разделы документации
  - [ ] Привязка к Req X.Y указана в коммитах

## Запуск проверок локально перед PR

```bash
cd plumbing-shop

# Линтер
./vendor/bin/pint --test

# Тесты
php artisan test

# Сборка фронта
npm run build
```

## Стиль кода

- PHP: PSR-12 (Laravel Pint).
- Blade: 4 пробела, имена переменных и view-файлов — snake_case.
- JS: 4 пробела, ES modules.
- CSS: только Tailwind utility-классы, кастомный CSS — в `resources/css/app.css`.
- Все пользовательские строки — на русском, через языковые файлы (`resources/lang/ru/`).

## Безопасность

- ❌ Не коммитить `.env`, `*.key`, `auth.json`.
- ❌ Не коммитить личные данные и секреты.
- ✅ Для конфигурации новых интеграций обновлять `.env.example`.
