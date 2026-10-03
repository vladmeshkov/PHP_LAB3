<?php
use Lab3\Shop\Product;
use Lab3\Support\Money;

/** @var Product[] $products */
?>
<div class="page-head">
    <h1>Каталог</h1>
    <p class="muted">Product → Book, Electronic · интерфейсы Discountable и Payable · трейт Loggable</p>
</div>

<div class="shop">
    <section class="panel">
        <h2>Каталог</h2>
        <ul class="catalog">
<?php foreach ($products as $product): ?>
            <li class="product">
                <div class="product__body">
                    <span class="badge"><?= e($product->getCategory()) ?></span>
                    <h3 class="product__name"><?= e($product->getName()) ?></h3>
                    <dl class="attrs">
<?php foreach ($product->getAttributes() as $label => $value): ?>
                        <div><dt><?= e($label) ?></dt><dd><?= e($value) ?></dd></div>
<?php endforeach; ?>
                    </dl>
                </div>
                <div class="product__buy">
                    <div class="price<?= $product->hasDiscount() ? ' price--sale' : '' ?>">
<?php if ($product->hasDiscount()): ?>
                        <s><?= e(Money::format($product->getBasePrice())) ?></s>
                        <span class="price__tag">−<?= e($product->getDiscount()) ?>%</span>
<?php endif; ?>
                        <strong><?= e(Money::format($product->getPrice())) ?></strong>
                    </div>
                    <div class="actions">
                        <form method="post">
                            <input type="hidden" name="product_id" value="<?= e($product->getId()) ?>">
                            <button class="btn btn--primary" name="action" value="add">В корзину</button>
                        </form>
                        <form method="post" class="actions__discounts">
                            <input type="hidden" name="product_id" value="<?= e($product->getId()) ?>">
                            <input type="hidden" name="action" value="discount">
                            <button class="btn" name="percent" value="10">−10%</button>
                            <button class="btn" name="percent" value="20">−20%</button>
                            <button class="btn btn--ghost" name="percent" value="0">Сбросить</button>
                        </form>
                    </div>
                </div>
            </li>
<?php endforeach; ?>
        </ul>
    </section>

    <aside class="side">
        <section class="panel">
            <h2>Корзина<?php if (!$cart->isEmpty()): ?> <span class="counter"><?= e($cart->countItems()) ?></span><?php endif; ?></h2>
<?php if ($cart->isEmpty()): ?>
            <p class="muted">Пока пусто. Добавьте товар из каталога.</p>
<?php else: ?>
            <ul class="cart">
<?php foreach ($cart->getLines() as $line): $item = $line->getProduct(); ?>
                <li class="cart__line">
                    <div>
                        <div class="cart__name"><?= e($item->getName()) ?></div>
                        <div class="muted"><?= e($line->getQuantity()) ?> × <?= e(Money::format($item->getPrice())) ?></div>
                    </div>
                    <form method="post">
                        <input type="hidden" name="product_id" value="<?= e($item->getId()) ?>">
                        <button class="btn btn--danger btn--small" name="action" value="remove">Убрать</button>
                    </form>
                </li>
<?php endforeach; ?>
            </ul>
<?php endif; ?>
            <div class="total">
                <span>Итого</span>
                <strong><?= e(Money::format($cart->total())) ?></strong>
            </div>
<?php if (!$cart->isEmpty()): ?>
            <form method="post" class="checkout" id="checkout">
                <h3>Оплата</h3>
                <div class="segmented" role="radiogroup" aria-label="Способ оплаты">
                    <label><input type="radio" name="method" value="card"<?= $method === 'card' ? ' checked' : '' ?>><span>Карта</span></label>
                    <label><input type="radio" name="method" value="paypal"<?= $method === 'paypal' ? ' checked' : '' ?>><span>PayPal</span></label>
                </div>

                <fieldset class="pay-fields" data-method="card">
                    <label class="field">Номер карты
                        <input name="card_number" inputmode="numeric" autocomplete="off" placeholder="4242 4242 4242 4242">
                    </label>
                    <label class="field">Держатель
                        <input name="card_holder" autocomplete="off" placeholder="IVAN IVANOV">
                    </label>
                    <div class="field-row">
                        <label class="field">Срок
                            <input name="card_expiry" autocomplete="off" placeholder="ММ/ГГ" maxlength="5">
                        </label>
                        <label class="field">CVV
                            <input name="card_cvv" inputmode="numeric" autocomplete="off" placeholder="123" maxlength="3" type="password">
                        </label>
                    </div>
                </fieldset>

                <fieldset class="pay-fields" data-method="paypal">
                    <label class="field">E-mail PayPal
                        <input name="paypal_email" type="email" autocomplete="off" placeholder="name@example.com">
                    </label>
                    <p class="muted">Комиссия PayPal — 2,9% от суммы заказа.</p>
                </fieldset>

                <button class="btn btn--primary btn--wide" name="action" value="checkout">Оплатить</button>
            </form>
            <form method="post">
                <button class="btn btn--ghost btn--wide" name="action" value="clear_cart">Очистить корзину</button>
            </form>
<?php endif; ?>
        </section>

<?php if ($orders): ?>
        <section class="panel">
            <h2>Заказы</h2>
            <ul class="orders">
<?php foreach ($orders as $order): ?>
                <li>
                    <span>№<?= e($order['number']) ?> · <?= e($order['method']) ?></span>
                    <strong><?= e(Money::format($order['total'])) ?></strong>
                </li>
<?php endforeach; ?>
            </ul>
        </section>
<?php endif; ?>

        <section class="panel">
            <h2>Журнал событий</h2>
<?php if (!$journal): ?>
            <p class="muted">Здесь появятся скидки и платежи.</p>
<?php else: ?>
            <ol class="journal">
<?php foreach (array_slice($journal, 0, 8) as $entry): ?>
                <li><time><?= e(date('H:i:s', $entry['at'])) ?></time> <?= e($entry['text']) ?></li>
<?php endforeach; ?>
            </ol>
<?php endif; ?>
            <p class="more"><a href="journal.php">Весь журнал и заказы</a></p>
        </section>
    </aside>
</div>
