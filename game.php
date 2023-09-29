<?php
include 'player.php';
class Game {
    private Player $playerUser;
    private Player $playerBot;

    public function __construct(Player $playerUser, Player $playerBot) {
        $this->playerUser = $playerUser;
        $this->playerBot = $playerBot;
    }

    private function getMove(Player $player): int {
        if ($player === $this->playerUser) {
            return (int)readline();
        }
        return rand(1, 2);
    }

    public function turn(Player $player) {
        $turn = true;
        $stones = [];
        while ($turn) {
            echo "Ходит {$player->Name}. Хотите ли вы взять камень? (1-да,2-нет)\n";
            switch ($this->getMove($player)) {
                case 1:
                    echo "{$player->Name} берет камень.\n";
                    usleep(500000); // Sleep for 500 milliseconds (0.5 seconds)
                    $pickedStone = $player->pickStone();
                    $stones[] = $pickedStone;
                    if (!$this->checkTable($player, $stones, $pickedStone)) {
                        $turn = false;
                        break;
                    }
                    switch ($pickedStone->Name) {
                        case "Изумруд":
                            $this->emeraldGame($player, $stones);
                            $turn = false;
                            break;
                        case "Рубин":
                            foreach ($stones as $s) {
                                $player->addStoneToBag($s);
                            }
                            $stones = [];
                            $this->rubyGame();
                            $turn = false;
                            break;
                    }
                    break;
                case 2:
                    echo "{$player->Name} заканчивает ход.\n";
                    $turn = false;
                    break;
            }
        }
        foreach ($stones as $s) {
            if (endsWith($s->Name, "самородок")) {
                $player->addStoneToTable($s);
            }
            else {
                $player->addStoneToBag($s);
            }
        }
        $this->checkWin();
        echo "------------------------\nСтатистика.\n{$this->playerUser->Name}: {$this->playerUser->getGoldCount()}\n{$this->playerBot->Name}:{$this->playerBot->getGoldCount()}\n---------------------------\n";
    }

    private function checkTable(Player $player, array $stones, Stone $pickedStone): bool {
        $bulyzhnikCount = 0;
        foreach ($stones as $stone) {
            if ($stone->Name === $pickedStone->Name && endsWith($stone->Name, "булыжник")) {
                $bulyzhnikCount++;
            }
        }
        if ($bulyzhnikCount === 2) {
            echo "Ход завершен! Вы подобрали второй булыжник\n";
            foreach ($stones as $s) {
                $player->addStoneToBag($s);
            }
            $stones = array();
            return false;
        }
        return true;
    }

    private function checkWin(): bool {
        return $this->playerUser->getGoldCount() < 5 && $this->playerBot->getGoldCount() < 5;
    }

    public function start() {
        while ($this->checkWin()) {
            $this->turn($this->playerUser);
            if ($this->checkWin()) { 
                break;
            }
            $this->turn($this->playerBot);
        }

        echo "Конец игры. Очки.\n{$this->playerUser->Name}: {$this->playerUser->getGoldCount()}\n{$this->playerBot->Name}:{$this->playerBot->getGoldCount()}\n";
    }

    private function emeraldGame(Player $player, array $stones) {
        $stoneF = $player->pickStone();
        $stones[] = $stoneF;
        $stoneS = $player->pickStone();
        $stones[] = $stoneS;

        if (!$this->checkTable($player, $stones, $stoneF) && !$this->checkTable($player, $stones, $stoneS)) {
            return;
        }

        echo "{$player->Name} успешно завершил ход с изумрудом. Булыжники положили другому игроку в мешок\n";
        foreach ($stones as $s) {
            if (!endsWith($s->Name, "самородок")) {
                if ($player === $this->playerUser) {
                    $this->playerBot->addStoneToBag($s);
                } else {
                    $this->playerUser->addStoneToBag($s);
                }
            } else {
                $player->addStoneToTable($s);
            }
        }
    }

    private function rubyByOrder(Player $player, array $stones, Stone $stone) {
        if (endsWith($stone->Name,"самородок")) {
            echo "ЗОЛОТО! {$player->Name} достал золотой самородок. Игра с рубином закончена\n";
            $key = array_search($stone, $stones);
            if ($key !== false) {
                unset($stones[$key]);
            }
            $player->addStoneToTable($stone);
            return true;
        }
        return false;
    }

    private function rubyGame() {
        $stonesUser = [];
        $stonesBot = [];
        while (true) {
            $first = $this->playerUser->pickStone();
            $stonesUser[] = $first;
            if ($this->rubyByOrder($this->playerUser, $stonesUser, $first)) {
                break;
            }
            $second = $this->playerBot->pickStone();
            $stonesBot[] = $second;
            if ($this->rubyByOrder($this->playerBot, $stonesBot, $second)) {
                break;
            }
        }

        foreach ($stonesUser as $s) {
            $this->playerUser->addStoneToBag($s);
        }
        foreach ($stonesBot as $s) {
            $this->playerUser->addStoneToBag($s);
        }
    }
}