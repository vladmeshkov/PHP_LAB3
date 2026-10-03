<div class="page-head">
    <h1>История матчей</h1>
    <p class="muted">Сюда попадают завершённые матчи. Откройте запись, чтобы увидеть ход игры.</p>
</div>

<?php if (!$history): ?>
<section class="panel">
    <p class="muted">Завершённых матчей пока нет. Начните матч на странице <a href="sport.php">«Матчи»</a> и завершите его.</p>
</section>
<?php else: ?>
<ul class="history">
<?php foreach ($history as $item): ?>
    <li class="panel history__item">
        <details>
            <summary>
                <span class="history__sport"><?= e($item['sport']) ?></span>
                <span class="history__teams"><?= e($item['home']) ?> — <?= e($item['away']) ?></span>
                <strong class="history__score"><?= e($item['score']) ?></strong>
                <span class="history__result"><?= $item['winner'] === null ? 'ничья' : 'победа: ' . e($item['winner']) ?></span>
                <time><?= e(date('d.m.Y H:i', $item['at'])) ?></time>
            </summary>
            <ol class="journal journal--full">
<?php foreach ($item['events'] as $entry): ?>
                <li><time><?= e(date('H:i:s', $entry['at'])) ?></time> <?= e($entry['text']) ?></li>
<?php endforeach; ?>
            </ol>
        </details>
    </li>
<?php endforeach; ?>
</ul>
<?php endif; ?>
