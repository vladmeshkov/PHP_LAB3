<?php
declare(strict_types=1);

require __DIR__ . '/../src/bootstrap.php';

use Lab3\Shop\Payment\CreditCardPayment;
use Lab3\Shop\Payment\PayPalPayment;
use Lab3\Shop\Store;
use Lab3\Support\Flash;
use Lab3\Support\View;

function handleCheckout(): void
{
    $cart = Store::cart();
    if ($cart->isEmpty()) {
        throw new DomainException('Корзина пуста.');
    }

    $method = $_POST['method'] ?? 'card';
    $payment = match ($method) {
        'paypal' => new PayPalPayment((string) ($_POST['paypal_email'] ?? '')),
        default  => new CreditCardPayment(
            (string) ($_POST['card_number'] ?? ''),
            (string) ($_POST['card_holder'] ?? ''),
            (string) ($_POST['card_expiry'] ?? ''),
            (string) ($_POST['card_cvv'] ?? ''),
        ),
    };

    $result = $payment->pay($cart->total());
    Store::collect($payment);
    Store::collect($cart);
    $_SESSION['checkout_method'] = $method === 'paypal' ? 'paypal' : 'card';

    if (!$result->isSuccessful()) {
        throw new DomainException($result->getMessage());
    }

    $number = Store::registerOrder($cart->total(), $payment->getMethodName(), $cart->countItems());
    $cart->clear();
    Flash::add('success', "Заказ №{$number} оплачен. {$result->getMessage()}");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        switch ($_POST['action'] ?? '') {
            case 'add':
                $product = Store::find((string) ($_POST['product_id'] ?? ''));
                Store::cart()->add($product, filter_var($_POST['quantity'] ?? 1, FILTER_VALIDATE_INT) ?: 0);
                Store::collect(Store::cart());
                break;

            case 'set_quantity':
                Store::cart()->setQuantity(
                    (string) ($_POST['product_id'] ?? ''),
                    filter_var($_POST['quantity'] ?? 0, FILTER_VALIDATE_INT) ?: 0
                );
                break;

            case 'remove':
                Store::cart()->remove((string) ($_POST['product_id'] ?? ''));
                break;

            case 'apply_promo':
                try {
                    Store::cart()->applyPromo((string) ($_POST['promo'] ?? ''));
                    Flash::add('success', 'Промокод применён.');
                } finally {
                    Store::collect(Store::cart());
                }
                break;

            case 'remove_promo':
                Store::cart()->removePromo();
                break;

            case 'clear_cart':
                Store::cart()->clear();
                Flash::add('info', 'Корзина очищена.');
                break;

            case 'checkout':
                handleCheckout();
                break;
        }
    } catch (DomainException $error) {
        Flash::add('error', $error->getMessage());
    }

    redirect();
}

View::render('shop', [
    'title'    => 'Каталог',
    'section'  => 'shop',
    'page'     => 'shop',
    'styles'   => ['shop'],
    'products' => Store::products(),
    'cart'     => Store::cart(),
    'method'   => $_SESSION['checkout_method'] ?? 'card',
]);
