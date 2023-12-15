<?php

require_once __DIR__ . '/vendor/autoload.php';
use Grid\Game;
use Grid\Player;

$playerName = readline("Введите имя игрока");
$botName = readline("Введите имя компьютера");

$playerUser = new Player($playerName);
$playerBot = new Player($botName);
$game = new Game($playerUser, $playerBot);

try {
    $game->start();
} catch (Exception $e) {
}
