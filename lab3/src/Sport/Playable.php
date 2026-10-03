<?php
declare(strict_types=1);

namespace Lab3\Sport;

interface Playable
{
    public function startMatch(): void;

    /**
     * Засчитывает очко стороне (Sport::HOME или Sport::AWAY).
     * Для видов спорта с разной ценой очка value задаёт её.
     */
    public function scorePoint(int $side, int $value = 1): void;
}
