<?php

namespace MCA7\AutoSellPE\provider;

use onebone\economyapi\EconomyAPI as EconomyAPI;
use pocketmine\player\Player;

class EconomyAPIProvider extends EconomyProvider
{

    public function getName(): string
    {
        return "EconomyAPI";
    }

    public function checkClass(): bool
    {
        if (class_exists(EconomyAPI::class))
            return true;
        return false;
    }

    public function addToMoney(Player $player, int $amount, array $labels): void
    {
        EconomyAPI::getInstance()->addMoney($player, $amount);
    }

}