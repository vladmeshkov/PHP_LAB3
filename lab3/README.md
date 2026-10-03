# Лабораторная работа №3 — вариант 5

ООП в PHP: классы, наследование, инкапсуляция, интерфейсы и трейты.
Одно веб-приложение, три страницы.

| Страница | Задание |
|---|---|
| `index.php` — Магазин | Задание 1 (HomeWork 1): интерфейс `Payable`, классы `CreditCardPayment` и `PayPalPayment` |
| `about.php` — О проекте | Задание 1 (HomeWork 2): страница «О нас» со своим CSS |
| `sport.php` — Спорт | Задание 2: `Sport` → `Football`, `Basketball`, `Tennis` |
| любая страница | Задание 1 (HomeWork 2): переключатель светлой/тёмной темы (CSS + сессия) |

## Запуск

Нужен PHP 8.0 или новее, Composer не требуется.

```
cd lab3
php -S localhost:8000 -t public
```

Открыть http://localhost:8000. Под XAMPP достаточно положить папку в `htdocs` и открыть `lab3/public/`.

Тестовые данные оплаты: карта `4242 4242 4242 4242`, срок в будущем (например `12/30`), CVV из трёх цифр;
PayPal — любой корректный e-mail.

## Структура

```
public/            точки входа и статика (css, js)
src/               классы (PSR-4, пространство имён Lab3\)
  Shop/            Product, Book, Electronic, Cart, Store
    Contracts/     Discountable, Payable
    Payment/       Payment, CreditCardPayment, PayPalPayment, PaymentResult
  Sport/           Sport, Football, Basketball, Tennis, Arena
  Support/         Loggable (трейт), State, Flash, Theme, View, Money
templates/         шаблоны страниц
```

## Где что реализовано

- Наследование: `Product` → `Book`, `Electronic`; `Payment` → `CreditCardPayment`, `PayPalPayment`; `Sport` → три вида спорта.
- Переопределение: `getInfo()`, `getAttributes()`, лимит скидки (`MAX_DISCOUNT`), комиссия `fee()` у PayPal,
  `startMatch()`, `scorePoint()`, `info()` у видов спорта (вызов `parent::`).
- Интерфейсы: `Discountable`, `Payable`, `Playable`, `Journaled`.
- Трейт: `Loggable` подключён к `Product`, `Payment` и `Sport`.
- Инкапсуляция: состояние закрыто (`private`/`protected`), изменяется только методами;
  недопустимые операции выбрасывают `DomainException`.
