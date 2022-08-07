<?php

namespace MCA7\AutoSell\provider;

use pocketmine\player\Player;

abstract class  EconomyProvider
{

    abstract public function getName(): string;

    abstract public function checkClass(): bool;

    abstract public function addToMoney(Player $player, int $amount, array $labels): void;

}