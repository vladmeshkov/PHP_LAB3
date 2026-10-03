<?php
declare(strict_types=1);

namespace Lab3\Sport;

use DomainException;
use Lab3\Support\Contracts\Journaled;
use Lab3\Support\Loggable;

abstract class Sport implements Playable, Journaled
{
    use Loggable;

    public const HOME = 0;
    public const AWAY = 1;

    public const WAITING  = 'waiting';
    public const LIVE     = 'live';
    public const FINISHED = 'finished';

    public const NAME_MAX_LENGTH = 30;

    protected const PLAYERS_PER_SIDE = 1;

    protected string $status = self::WAITING;

    private bool $archived = false;

    /** @var int[] */
    protected array $score = [0, 0];

    public function __construct(
        protected string $homeName,
        protected string $awayName,
    ) {
    }

    abstract public function getTitle(): string;

    abstract public function rules(): string;

    /** Счёт в том виде, в каком его показывают на табло. */
    abstract public function getScoreLabel(): string;

    /** Крупное число на табло; по умолчанию совпадает с текстовым счётом. */
    public function getBoardScore(): string
    {
        return $this->getScoreLabel();
    }

    public function getPlayersCount(): int
    {
        return static::PLAYERS_PER_SIDE * 2;
    }

    /** Как называть участника в интерфейсе: «Команда» или «Игрок». */
    public function getSideNoun(): string
    {
        return 'Команда';
    }

    /**
     * Меняет названия сторон; пока матч не начался.
     */
    public function setSideNames(string $home, string $away): void
    {
        if ($this->status !== self::WAITING) {
            throw new DomainException('Названия можно менять только до начала матча.');
        }

        $home = self::cleanName($home);
        $away = self::cleanName($away);

        if (mb_strtolower($home) === mb_strtolower($away)) {
            throw new DomainException('Названия сторон должны различаться.');
        }

        $this->homeName = $home;
        $this->awayName = $away;
    }

    public function getSideName(int $side): string
    {
        return $side === self::HOME ? $this->homeName : $this->awayName;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function isLive(): bool
    {
        return $this->status === self::LIVE;
    }

    public function isFinished(): bool
    {
        return $this->status === self::FINISHED;
    }

    public function isArchived(): bool
    {
        return $this->archived;
    }

    public function markArchived(): void
    {
        $this->archived = true;
    }

    public function startMatch(): void
    {
        if ($this->status !== self::WAITING) {
            throw new DomainException('Матч уже начат или завершён.');
        }

        $this->status = self::LIVE;
        $this->log("Матч начался: {$this->homeName} — {$this->awayName}");
    }

    public function finishMatch(): void
    {
        $this->assertLive();

        $this->status = self::FINISHED;
        $winner = $this->getWinner();

        $this->log($winner === null
            ? "Матч окончен вничью, счёт {$this->getScoreLabel()}"
            : "Матч окончен, победитель — {$this->getSideName($winner)} ({$this->getScoreLabel()})");
    }

    /** Победитель определяется по основному счёту; null означает ничью. */
    public function getWinner(): ?int
    {
        return match (true) {
            $this->score[self::HOME] > $this->score[self::AWAY] => self::HOME,
            $this->score[self::AWAY] > $this->score[self::HOME] => self::AWAY,
            default => null,
        };
    }

    public function info(): string
    {
        $statusText = match ($this->status) {
            self::WAITING  => 'ожидание начала',
            self::LIVE     => 'идёт матч',
            self::FINISHED => 'завершён',
        };

        return sprintf(
            '%s: %s — %s, %s (%s)',
            $this->getTitle(),
            $this->homeName,
            $this->awayName,
            $this->getScoreLabel(),
            $statusText
        );
    }

    private static function cleanName(string $name): string
    {
        $name = trim(preg_replace('/\s+/u', ' ', $name) ?? '');
        $length = mb_strlen($name);

        if ($length < 2 || $length > self::NAME_MAX_LENGTH) {
            throw new DomainException('Название должно содержать от 2 до ' . self::NAME_MAX_LENGTH . ' символов.');
        }

        return $name;
    }

    protected function assertLive(): void
    {
        if ($this->status !== self::LIVE) {
            throw new DomainException('Матч не идёт: сначала нажмите «Начать матч».');
        }
    }

    protected function assertSide(int $side): void
    {
        if ($side !== self::HOME && $side !== self::AWAY) {
            throw new DomainException('Неизвестная сторона.');
        }
    }
}
