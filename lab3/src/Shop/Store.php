<?php
declare(strict_types=1);

namespace Lab3\Shop;

use DomainException;
use Lab3\Support\Contracts\Journaled;
use Lab3\Support\State;

/**
 * Состояние магазина в сессии: каталог, корзина, история заказов и журнал.
 */
final class Store
{
    private const JOURNAL_LIMIT = 40;

    /** @return Product[] */
    public static function products(): array
    {
        return State::remember('products', static fn (): array => [
            new Book('book-php', 'PHP для начинающих', 1490, 'Иван Иванов', 384),
            new Book('book-patterns', 'Паттерны проектирования', 2390, 'Э. Гамма и др.', 416),
            new Book('book-refactoring', 'Рефакторинг', 2190, 'Мартин Фаулер', 448),
            new Electronic('el-phone', 'Смартфон Honor 200', 32990, 'Honor', 24),
            new Electronic('el-headphones', 'Наушники ProMax', 6490, 'SoundMax', 12),
            new Electronic('el-watch', 'Умные часы Pulse S', 8990, 'Pulse', 12),
        ]);
    }

    public static function find(string $id): Product
    {
        foreach (self::products() as $product) {
            if ($product->getId() === $id) {
                return $product;
            }
        }

        throw new DomainException('Такого товара нет в каталоге.');
    }

    public static function cart(): Cart
    {
        return State::remember('cart', static fn (): Cart => new Cart());
    }

    /** @return array<int, array{number: int, total: float, method: string, items: int, at: int}> */
    public static function orders(): array
    {
        return State::remember('orders', static fn (): array => []);
    }

    public static function registerOrder(float $total, string $method, int $items): int
    {
        $orders = self::orders();
        $number = count($orders) + 1001;

        array_unshift($orders, [
            'number' => $number,
            'total'  => $total,
            'method' => $method,
            'items'  => $items,
            'at'     => time(),
        ]);
        State::put('orders', array_slice($orders, 0, 5));

        return $number;
    }

    /** @return array<int, array{at: int, text: string}> новые записи сверху */
    public static function journal(): array
    {
        return State::remember('journal', static fn (): array => []);
    }

    public static function note(string $text): void
    {
        self::append([['at' => time(), 'text' => $text]]);
    }

    /** Забирает записи у объекта (товар, платёж) и переносит их в общий журнал. */
    public static function collect(Journaled $source): void
    {
        self::append($source->releaseLog());
    }

    private static function append(array $entries): void
    {
        if ($entries === []) {
            return;
        }

        $journal = array_merge(array_reverse($entries), self::journal());
        State::put('journal', array_slice($journal, 0, self::JOURNAL_LIMIT));
    }
}
