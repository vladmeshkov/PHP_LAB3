<?php
declare(strict_types=1);

namespace Lab3\Sport;

use DomainException;
use Lab3\Support\State;

/**
 * Реестр матчей, живущих в сессии. Ключ — короткий код вида спорта.
 */
final class Arena
{
    /** @return array<string, Sport> */
    public static function matches(): array
    {
        return State::remember('matches', static fn (): array => [
            'football'   => new Football('Спартак', 'Зенит'),
            'basketball' => new Basketball('Лейкерс', 'Селтикс'),
            'tennis'     => new Tennis('Медведев', 'Синнер'),
        ]);
    }

    public static function get(string $code): Sport
    {
        return self::matches()[$code] ?? throw new DomainException('Неизвестный вид спорта.');
    }

    public static function replace(string $code, Sport $fresh): void
    {
        $matches = self::matches();
        $matches[$code] = $fresh;
        State::put('matches', $matches);
    }

    /** Новый матч того же вида спорта с теми же участниками. */
    public static function restart(string $code): void
    {
        $old = self::get($code);
        self::replace($code, new $old($old->getSideName(Sport::HOME), $old->getSideName(Sport::AWAY)));
    }
}
