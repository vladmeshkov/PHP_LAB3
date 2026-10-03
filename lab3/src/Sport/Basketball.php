<?php
declare(strict_types=1);

namespace Lab3\Sport;

use DomainException;

final class Basketball extends Sport
{
    protected const PLAYERS_PER_SIDE = 5;

    private const THROW_NAMES = [
        1 => 'штрафной бросок',
        2 => 'двухочковый бросок',
        3 => 'трёхочковый бросок',
    ];

    public function getTitle(): string
    {
        return 'Баскетбол';
    }

    public function rules(): string
    {
        return 'Четыре четверти по 10 минут, на площадке по 5 игроков от каждой команды. '
            . 'Штрафной бросок стоит 1 очко, попадание из-под трёхочковой дуги — 3, остальные — 2.';
    }

    public function startMatch(): void
    {
        parent::startMatch();
        $this->log('Спорный мяч разыгран.');
    }

    public function scorePoint(int $side, int $value = 2): void
    {
        $this->assertLive();
        $this->assertSide($side);

        if (!isset(self::THROW_NAMES[$value])) {
            throw new DomainException('В баскетболе бросок приносит 1, 2 или 3 очка.');
        }

        $this->score[$side] += $value;
        $this->log(sprintf(
            '%s: %s (+%d). Счёт %s',
            $this->getSideName($side),
            self::THROW_NAMES[$value],
            $value,
            $this->getScoreLabel()
        ));
    }

    public function getScoreLabel(): string
    {
        return "{$this->score[self::HOME]} : {$this->score[self::AWAY]}";
    }
}
