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

    /**
     * Переносит завершённые матчи в историю (каждый ровно один раз).
     *
     * @return array<int, array{sport: string, home: string, away: string, score: string, winner: ?string, at: int, events: array}>
     */
    public static function history(): array
    {
        foreach (self::matches() as $match) {
            if ($match->isFinished() && !$match->isArchived()) {
                $winner = $match->getWinner();
                $entry = [
                    'sport'  => $match->getTitle(),
                    'home'   => $match->getSideName(Sport::HOME),
                    'away'   => $match->getSideName(Sport::AWAY),
                    'score'  => $match->getBoardScore(),
                    'winner' => $winner === null ? null : $match->getSideName($winner),
                    'at'     => time(),
                    'events' => $match->getLog(),
                ];
                State::put('history', array_slice(
                    array_merge([$entry], State::remember('history', static fn (): array => [])),
                    0,
                    30
                ));
                $match->markArchived();
            }
        }

        return State::remember('history', static fn (): array => []);
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
