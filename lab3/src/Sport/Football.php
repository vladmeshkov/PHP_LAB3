<?php
declare(strict_types=1);

namespace Lab3\Sport;

final class Football extends Sport
{
    protected const PLAYERS_PER_SIDE = 11;

    public function getTitle(): string
    {
        return 'Футбол';
    }

    public function rules(): string
    {
        return 'Два тайма по 45 минут. Играют две команды по 11 человек, мяч нельзя трогать руками '
            . '(кроме вратаря). Каждый гол приносит команде одно очко, побеждает команда, забившая больше.';
    }

    public function startMatch(): void
    {
        parent::startMatch();
        $this->log('Судья дал стартовый свисток.');
    }

    public function scorePoint(int $side, int $value = 1): void
    {
        $this->assertLive();
        $this->assertSide($side);

        $this->score[$side]++;
        $this->log("Гол! {$this->getSideName($side)}. Счёт {$this->getScoreLabel()}");
    }

    public function getScoreLabel(): string
    {
        return "{$this->score[self::HOME]} : {$this->score[self::AWAY]}";
    }
}
