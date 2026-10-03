<?php
use Lab3\Support\Money;
?>
<div class="page-head">
    <h1>Журнал магазина</h1>
    <p class="muted">Все скидки и платежи, записанные трейтом Loggable. Хранится последние 40 записей.</p>
</div>

<div class="shop shop--journal">
    <section class="panel">
        <h2>События</h2>
<?php if (!$journal): ?>
        <p class="muted">Пока пусто. Примените скидку или оплатите заказ в каталоге.</p>
<?php else: ?>
        <ol class="journal journal--full">
<?php foreach ($journal as $entry): ?>
            <li><time><?= e(date('d.m H:i:s', $entry['at'])) ?></time> <?= e($entry['text']) ?></li>
<?php endforeach; ?>
        </ol>
<?php endif; ?>
    </section>

    <section class="panel">
        <h2>Последние заказы</h2>
<?php if (!$orders): ?>
        <p class="muted">Заказов ещё не было.</p>
<?php else: ?>
        <ul class="orders">
<?php foreach ($orders as $order): ?>
            <li>
                <span>№<?= e($order['number']) ?> · <?= e($order['method']) ?> · <?= e($order['items']) ?> шт.<br>
                    <small class="muted"><?= e(date('d.m H:i', $order['at'])) ?></small></span>
                <strong><?= e(Money::format($order['total'])) ?></strong>
            </li>
<?php endforeach; ?>
        </ul>
<?php endif; ?>
    </section>
</div>
