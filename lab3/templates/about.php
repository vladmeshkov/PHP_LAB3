<?php /** @var array{name: string, group: string, variant: int} $author */ ?>
<div class="about">
    <header class="about__hero">
        <p class="about__eyebrow">Лабораторная работа №3</p>
        <h1>Объекты, наследование, интерфейсы</h1>
        <p class="about__lead">
            Небольшое приложение на PHP, в котором одни и те же принципы ООП показаны на двух
            предметных областях: интернет-магазине и спортивных матчах.
        </p>
    </header>

    <section class="about__grid">
        <article class="about__card">
            <h2>Магазин</h2>
            <ul>
                <li><code>Product</code> — абстрактный родитель с ценой и скидкой;</li>
                <li><code>Book</code> и <code>Electronic</code> — наследники со своими атрибутами и своим пределом скидки;</li>
                <li><code>Discountable</code> и <code>Payable</code> — интерфейсы скидки и оплаты;</li>
                <li><code>CreditCardPayment</code> и <code>PayPalPayment</code> — способы оплаты, у PayPal своя комиссия;</li>
                <li><code>Loggable</code> — трейт журнала событий.</li>
            </ul>
        </article>

        <article class="about__card">
            <h2>Спорт</h2>
            <ul>
                <li><code>Sport</code> — абстрактный класс матча со статусом и счётом;</li>
                <li><code>Football</code>, <code>Basketball</code>, <code>Tennis</code> — свои правила подсчёта очков;</li>
                <li><code>Playable</code> — интерфейс <code>startMatch()</code> и <code>scorePoint()</code>;</li>
                <li>методы <code>rules()</code>, <code>getPlayersCount()</code>, <code>info()</code>;</li>
                <li>счёт теннисного матча считается по геймам и сетам.</li>
            </ul>
        </article>

        <article class="about__card">
            <h2>Инкапсуляция</h2>
            <p>
                Состояние объектов закрыто: цена товара, счёт матча и данные карты доступны только
                через методы. Некорректные действия — скидка выше лимита, гол в не начатом матче —
                завершаются исключением <code>DomainException</code>, которое страница показывает пользователю.
            </p>
        </article>

        <article class="about__card">
            <h2>Тема оформления</h2>
            <p>
                Выбранная тема хранится в <code>$_SESSION</code> и подставляется в атрибут
                <code>data-theme</code> на сервере, поэтому страница сразу отображается в нужных цветах.
                Сами цвета задают CSS-переменные в <code>base.css</code>.
            </p>
        </article>
    </section>

    <footer class="about__author">
        <span><?= e($author['name']) ?></span>
        <span><?= e($author['group']) ?></span>
        <span>Вариант <?= e($author['variant']) ?></span>
    </footer>
</div>
