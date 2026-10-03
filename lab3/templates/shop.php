<?php
use Lab3\Shop\Cart;
use Lab3\Shop\CartLine;
use Lab3\Shop\Product;
use Lab3\Support\Money;

/** @var Product[] $products */
/** @var Cart $cart */
?>
<div class="page-head">
    <h1>Каталог</h1>
    <p class="muted">Цены указаны в белорусских рублях (<?= e(Money::CURRENCY) ?>).</p>
</div>

<div class="shop">
    <section class="panel">
        <h2>Товары</h2>
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
                <form method="post" class="product__buy" data-validate>
                    <strong class="price"><?= e(Money::format($product->getPrice())) ?></strong>
                    <input type="hidden" name="product_id" value="<?= e($product->getId()) ?>">
                    <div class="buy-row">
                        <label class="qty">
                            <span class="visually-hidden">Количество</span>
                            <input type="number" name="quantity" value="1" min="1" max="<?= CartLine::MAX_QUANTITY ?>" step="1" required>
                        </label>
                        <button class="btn btn--primary" name="action" value="add">В корзину</button>
                    </div>
                </form>
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
                    <div class="cart__info">
                        <div class="cart__name"><?= e($item->getName()) ?></div>
                        <div class="muted"><?= e(Money::format($item->getPrice())) ?> за шт.</div>
                    </div>
                    <div class="cart__sum">
<?php if ($cart->getDiscount() > 0 && $cart->lineTotal($line) < $line->subtotal()): ?>
                        <s><?= e(Money::format($line->subtotal())) ?></s>
<?php endif; ?>
                        <strong><?= e(Money::format($cart->lineTotal($line))) ?></strong>
                    </div>
                    <form method="post" class="cart__qty" data-validate>
                        <input type="hidden" name="product_id" value="<?= e($item->getId()) ?>">
                        <input type="number" name="quantity" value="<?= e($line->getQuantity()) ?>" min="1" max="<?= CartLine::MAX_QUANTITY ?>" step="1" required aria-label="Количество">
                        <button class="btn btn--small" name="action" value="set_quantity">Обновить</button>
                        <button class="btn btn--small btn--danger" name="action" value="remove" formnovalidate>Убрать</button>
                    </form>
                </li>
<?php endforeach; ?>
            </ul>

<?php if ($cart->getPromoCode() === null): ?>
            <form method="post" class="promo" data-validate>
                <label class="field">Промокод
                    <span class="promo__row">
                        <input name="promo" placeholder="например, PROMO10" required maxlength="20" pattern="[A-Za-z]{2,10}[0-9]{1,4}" title="Буквы и цифры, например PROMO10" autocomplete="off">
                        <button class="btn" name="action" value="apply_promo">Применить</button>
                    </span>
                </label>
            </form>
<?php else: ?>
            <form method="post" class="promo promo--active">
                <span>Промокод <strong><?= e($cart->getPromoCode()) ?></strong> (−<?= e($cart->getDiscount()) ?>%)</span>
                <button class="btn btn--ghost btn--small" name="action" value="remove_promo">Убрать</button>
            </form>
<?php endif; ?>
<?php endif; ?>

            <dl class="totals">
<?php if ($cart->discountAmount() > 0): ?>
                <div><dt>Сумма</dt><dd><?= e(Money::format($cart->subtotal())) ?></dd></div>
                <div class="totals__discount"><dt>Скидка</dt><dd>−<?= e(Money::format($cart->discountAmount())) ?></dd></div>
<?php endif; ?>
                <div class="totals__sum"><dt>Итого</dt><dd><?= e(Money::format($cart->total())) ?></dd></div>
            </dl>

<?php if (!$cart->isEmpty()): ?>
            <form method="post" class="checkout" id="checkout" data-validate>
                <h3>Оплата</h3>
                <div class="segmented" role="radiogroup" aria-label="Способ оплаты">
                    <label><input type="radio" name="method" value="card"<?= $method === 'card' ? ' checked' : '' ?>><span>Карта</span></label>
                    <label><input type="radio" name="method" value="paypal"<?= $method === 'paypal' ? ' checked' : '' ?>><span>PayPal</span></label>
                </div>

                <fieldset class="pay-fields" data-method="card">
                    <label class="field">Номер карты
                        <input name="card_number" inputmode="numeric" autocomplete="off" placeholder="4242 4242 4242 4242" required maxlength="23" pattern="[0-9 ]{13,23}" title="От 13 до 19 цифр">
                    </label>
                    <label class="field">Держатель
                        <input name="card_holder" autocomplete="off" placeholder="IVAN IVANOV" required minlength="2" maxlength="40" pattern="[A-Za-zА-Яа-яЁё][A-Za-zА-Яа-яЁё .'\-]+" title="Только буквы">
                    </label>
                    <div class="field-row">
                        <label class="field">Срок
                            <input name="card_expiry" autocomplete="off" placeholder="ММ/ГГ" required maxlength="5" pattern="(0[1-9]|1[0-2])/[0-9]{2}" title="Формат ММ/ГГ">
                        </label>
                        <label class="field">CVV
                            <input name="card_cvv" inputmode="numeric" autocomplete="off" placeholder="123" required maxlength="3" pattern="[0-9]{3}" title="Три цифры" type="password">
                        </label>
                    </div>
                </fieldset>

                <fieldset class="pay-fields" data-method="paypal">
                    <label class="field">E-mail PayPal
                        <input name="paypal_email" type="email" autocomplete="off" placeholder="name@example.com" required>
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
    </aside>
</div>
