<?php /** @var array $company */ ?>
<div class="company">
    <header class="company__hero">
        <p class="company__eyebrow">О компании</p>
        <h1><?= e($company['name']) ?></h1>
        <p class="company__slogan"><?= e($company['slogan']) ?></p>
    </header>

    <section class="company__facts" aria-label="Компания в цифрах">
<?php foreach ($company['facts'] as [$value, $label]): ?>
        <div class="fact">
            <strong><?= e($value) ?></strong>
            <span><?= e($label) ?></span>
        </div>
<?php endforeach; ?>
    </section>

    <section class="company__story">
        <h2>Чем мы занимаемся</h2>
<?php foreach ($company['about'] as $paragraph): ?>
        <p><?= e($paragraph) ?></p>
<?php endforeach; ?>
    </section>

    <section class="company__values">
<?php foreach ($company['values'] as $title => $text): ?>
        <article>
            <h3><?= e($title) ?></h3>
            <p><?= e($text) ?></p>
        </article>
<?php endforeach; ?>
    </section>

    <section class="company__contacts">
        <h2>Контакты</h2>
        <dl>
<?php foreach ($company['contacts'] as $label => $value): ?>
            <div><dt><?= e($label) ?></dt><dd><?= e($value) ?></dd></div>
<?php endforeach; ?>
        </dl>
        <p class="company__legal"><?= e($company['legal']) ?></p>
    </section>
</div>
