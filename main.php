<?php

include('game.php');

$playerName = readline("Введите имя игрока");
$botName = readline("Введите имя компьютера");

$playerUser = new Player($playerName);
$playerBot = new Player($botName);
$game = new Game($playerUser, $playerBot);

$game->start();