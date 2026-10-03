<?php
declare(strict_types=1);

namespace Lab3\Support\Contracts;

interface Journaled
{
    /** Возвращает накопленные записи и очищает собственный журнал. */
    public function releaseLog(): array;
}
