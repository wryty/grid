<?php

include 'stone.php';
class Player {
    public string $Name;
    private array $bag;
    private array $table;

    public function __construct(string $name) {
        $this->Name = $name;
        $this->bag = [
            new Stone("Белый булыжник"),
            new Stone("Белый булыжник"),
            new Stone("Белый булыжник"),
            new Stone("Белый булыжник"),
            new Stone("Серый булыжник"),
            new Stone("Серый булыжник"),
            new Stone("Серый булыжник"),
            new Stone("Серый булыжник"),
            new Stone("Черный булыжник"),
            new Stone("Черный булыжник"),
            new Stone("Черный булыжник"),
            new Stone("Черный булыжник"),
            new Stone("Золотой самородок"),
            new Stone("Золотой самородок"),
            new Stone("Золотой самородок"),
            new Stone("Золотой самородок"),
            new Stone("Золотой самородок"),
            new Stone("Золотой самородок"),
            new Stone("Изумруд"),
            new Stone("Рубин"),
        ];
        $this->table = [];
    }

    public function getStoneCount(): int {
        return count($this->bag);
    }

    public function getGoldCount(): int {
        $goldCount = 0;
        foreach ($this->table as $stone) {
            if (endsWith($stone->Name, "самородок")) {
                $goldCount++;
            }
        }
        return $goldCount;
    }

    public function pickStone(): Stone {
        $randIndex = rand(0, count($this->bag) - 1);
        $stone = $this->bag[$randIndex];
        echo "{$this->Name} достал камень {$stone->Name}\n";
        array_splice($this->bag, $randIndex, 1);
        return $stone;
    }

    public function addStoneToBag(Stone $stone) {
        $this->bag[] = $stone;
    }

    public function addStoneToTable(Stone $stone) {
        //print_r($stone);
        $this->table[] = $stone;
        //print_r($this->table);
    }
}

function endsWith(string $haystack, string $needle) {
    $length = strlen($needle);
    if (!$length) {
        return true;
    }
    return substr($haystack, -$length) === $needle;
}