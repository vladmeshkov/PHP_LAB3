<?php
use Lab3\Sport\Basketball;
use Lab3\Sport\Sport;
use Lab3\Sport\Tennis;

/** @var Sport[] $matches */
/** @var Sport $match */

$statusText = [
    Sport::WAITING  => 'Ожидание начала',
    Sport::LIVE     => 'Идёт матч',
    Sport::FINISHED => 'Матч завершён',
][$match->getStatus()];

$scoreLabel = $match instanceof Tennis ? 'Очко' : 'Гол';
$winner = $match->isFinished() ? $match->getWinner() : null;
?>
<div class="page-head">
    <h1>Спортивные матчи</h1>
    <p class="muted">Sport → Football, Basketball, Tennis · интерфейс Playable · трейт Loggable</p>
</div>

<nav class="tabs" aria-label="Вид спорта">
<?php foreach ($matches as $key => $item): ?>
    <a href="sport.php?sport=<?= e($key) ?>"<?= $key === $code ? ' aria-current="page"' : '' ?>><?= e($item->getTitle()) ?></a>
<?php endforeach; ?>
</nav>

<div class="match">
    <section class="panel scoreboard">
        <div class="status status--<?= e($match->getStatus()) ?>"><?= e($statusText) ?></div>

        <div class="scoreboard__row">
            <div class="team<?= $winner === Sport::HOME ? ' team--winner' : '' ?>"><?= e($match->getSideName(Sport::HOME)) ?></div>
            <div class="scoreboard__score"><?= e($match->getBoardScore()) ?></div>
            <div class="team<?= $winner === Sport::AWAY ? ' team--winner' : '' ?>"><?= e($match->getSideName(Sport::AWAY)) ?></div>
        </div>

<?php if ($match instanceof Tennis): ?>
        <p class="scoreboard__sub">
            Счёт по сетам
<?php if ($match->isLive()): ?>
            · геймы <strong><?= e($match->getGamesLabel()) ?></strong>
            · в текущем гейме <strong><?= e($match->getGameLabel()) ?></strong>
<?php endif; ?>
        </p>
<?php endif; ?>

<?php if ($match->isLive()): ?>
        <div class="controls">
<?php foreach ([Sport::HOME, Sport::AWAY] as $side): ?>
            <form method="post" class="controls__side">
                <input type="hidden" name="sport" value="<?= e($code) ?>">
                <input type="hidden" name="action" value="score">
                <input type="hidden" name="side" value="<?= $side ?>">
                <span class="controls__label"><?= e($match->getSideName($side)) ?></span>
<?php if ($match instanceof Basketball): ?>
<?php foreach ([1, 2, 3] as $points): ?>
                <button class="btn btn--primary" name="points" value="<?= $points ?>">+<?= $points ?></button>
<?php endforeach; ?>
<?php else: ?>
                <button class="btn btn--primary"><?= e($scoreLabel) ?></button>
<?php endif; ?>
            </form>
<?php endforeach; ?>
        </div>
<?php endif; ?>

        <form method="post" class="lifecycle">
            <input type="hidden" name="sport" value="<?= e($code) ?>">
<?php if ($match->getStatus() === Sport::WAITING): ?>
            <button class="btn btn--primary" name="action" value="start">Начать матч</button>
<?php elseif ($match->isLive()): ?>
            <button class="btn" name="action" value="finish">Завершить матч</button>
<?php endif; ?>
<?php if ($match->getStatus() !== Sport::WAITING): ?>
            <button class="btn btn--ghost" name="action" value="restart"><?= $match->isFinished() ? 'Новый матч' : 'Сбросить матч' ?></button>
<?php endif; ?>
        </form>
    </section>

    <aside class="side">
        <section class="panel">
            <h2><?= e($match->getTitle()) ?></h2>
            <dl class="facts">
                <div><dt>Игроков на поле</dt><dd><?= e($match->getPlayersCount()) ?></dd></div>
                <div><dt>Сводка</dt><dd><?= e($match->info()) ?></dd></div>
            </dl>
            <h3>Правила</h3>
            <p class="rules"><?= e($match->rules()) ?></p>
        </section>

        <section class="panel">
            <h2>Ход матча</h2>
<?php $events = array_reverse($match->getLog()); ?>
<?php if (!$events): ?>
            <p class="muted">Событий пока нет.</p>
<?php else: ?>
            <ol class="journal">
<?php foreach (array_slice($events, 0, 10) as $entry): ?>
                <li><time><?= e(date('H:i:s', $entry['at'])) ?></time> <?= e($entry['text']) ?></li>
<?php endforeach; ?>
            </ol>
<?php endif; ?>
        </section>
    </aside>
</div>
