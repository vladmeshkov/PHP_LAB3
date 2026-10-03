<?php
declare(strict_types=1);

require __DIR__ . '/../src/bootstrap.php';

use Lab3\Sport\Arena;
use Lab3\Support\Flash;
use Lab3\Support\View;

$matches = Arena::matches();
$code = (string) ($_GET['sport'] ?? $_POST['sport'] ?? array_key_first($matches));
if (!isset($matches[$code])) {
    $code = array_key_first($matches);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $match = Arena::get($code);

        switch ($_POST['action'] ?? '') {
            case 'start':
                $match->startMatch();
                break;

            case 'score':
                $side = (int) ($_POST['side'] ?? -1);
                isset($_POST['points'])
                    ? $match->scorePoint($side, (int) $_POST['points'])
                    : $match->scorePoint($side);
                break;

            case 'finish':
                $match->finishMatch();
                break;

            case 'restart':
                Arena::restart($code);
                break;
        }
    } catch (DomainException $error) {
        Flash::add('error', $error->getMessage());
    }

    Arena::history();
    redirect('sport.php?sport=' . urlencode($code));
}

View::render('sport', [
    'title'   => 'Матчи',
    'section' => 'sport',
    'page'    => 'sport',
    'styles'  => ['sport'],
    'scripts' => [],
    'matches' => $matches,
    'code'    => $code,
    'match'   => $matches[$code],
]);
