<?php

namespace MCA7\AutoSellPE\provider;

use NhanAZ\SimpleEconomy\Main as SimpleEconomy;
use pocketmine\player\Player;

class SimpleEconomyProvider extends EconomyProvider
{

    public function getName(): string
    {
        return "SimpleEconomy";
    }

    public function checkClass(): bool
    {
        if (class_exists(SimpleEconomy::class))
            return true;
        return false;
    }

    public function addToMoney(Player $player, int $amount, array $labels): void
    {
        SimpleEconomy::getInstance()->addMoney($player, $amount);
    }

}