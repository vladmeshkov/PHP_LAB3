<?php
declare(strict_types=1);

namespace Lab3\Sport;

final class Tennis extends Sport
{
    private const SETS_TO_WIN = 2;
    private const POINT_NAMES = [0 => '0', 1 => '15', 2 => '30', 3 => '40'];

    /** Очки в текущем гейме. */
    private array $points = [0, 0];

    /** Выигранные геймы в текущем сете. Основной счёт ($score) здесь — выигранные сеты. */
    private array $games = [0, 0];

    public function getTitle(): string
    {
        return 'Теннис';
    }

    public function getSideNoun(): string
    {
        return 'Игрок';
    }

    public function rules(): string
    {
        return 'Одиночный матч до двух выигранных сетов. Очки в гейме идут 15, 30, 40; при 40:40 '
            . 'нужно преимущество в два очка. Сет выигрывается при 6 геймах с разницей в два гейма '
            . '(при 6:6 разыгрывается решающий гейм до 7:6).';
    }

    public function startMatch(): void
    {
        parent::startMatch();
        $this->log('Игроки вышли на корт, первая подача разыграна.');
    }

    public function scorePoint(int $side, int $value = 1): void
    {
        $this->assertLive();
        $this->assertSide($side);

        $this->points[$side]++;
        $this->log("Очко: {$this->getSideName($side)}");

        if (!$this->isGameWon($side)) {
            return;
        }

        $this->points = [0, 0];
        $this->games[$side]++;
        $this->log("Гейм: {$this->getSideName($side)}, геймов в сете {$this->games[self::HOME]}:{$this->games[self::AWAY]}");

        if (!$this->isSetWon($side)) {
            return;
        }

        $this->score[$side]++;
        $this->games = [0, 0];
        $this->log("Сет: {$this->getSideName($side)}, по сетам {$this->score[self::HOME]}:{$this->score[self::AWAY]}");

        if ($this->score[$side] === self::SETS_TO_WIN) {
            $this->finishMatch();
        }
    }

    public function getScoreLabel(): string
    {
        return "сеты {$this->score[self::HOME]}:{$this->score[self::AWAY]}";
    }

    public function getBoardScore(): string
    {
        return "{$this->score[self::HOME]} : {$this->score[self::AWAY]}";
    }

    public function info(): string
    {
        $details = $this->isLive()
            ? sprintf('; геймы %d:%d, гейм %s', $this->games[self::HOME], $this->games[self::AWAY], $this->getGameLabel())
            : '';

        return parent::info() . $details;
    }

    public function getGamesLabel(): string
    {
        return "{$this->games[self::HOME]} : {$this->games[self::AWAY]}";
    }

    public function getGameLabel(): string
    {
        [$home, $away] = $this->points;

        if ($home >= 3 && $away >= 3) {
            return match (true) {
                $home === $away => '40:40',
                $home > $away   => 'преимущество ' . $this->homeName,
                default         => 'преимущество ' . $this->awayName,
            };
        }

        return self::POINT_NAMES[$home] . ':' . self::POINT_NAMES[$away];
    }

    private function isGameWon(int $side): bool
    {
        $mine = $this->points[$side];
        $other = $this->points[1 - $side];

        return $mine >= 4 && $mine - $other >= 2;
    }

    private function isSetWon(int $side): bool
    {
        $mine = $this->games[$side];
        $other = $this->games[1 - $side];

        return ($mine >= 6 && $mine - $other >= 2) || $mine === 7;
    }
}
