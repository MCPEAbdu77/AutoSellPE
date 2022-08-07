<?php

namespace MCA7\AutoSell\provider;

use cooldogedev\BedrockEconomy\api\BedrockEconomyAPI;
use cooldogedev\BedrockEconomy\BedrockEconomy;
use pocketmine\player\Player;

class BedrockEconomyProvider extends EconomyProvider
{

    public function getName(): string
    {
        return "BedrockEconomy";
    }

    public function checkClass(): bool
    {
        if (class_exists(BedrockEconomy::class))
            return true;
        return false;
    }

    public function addToMoney(Player $player, int $amount, array $labels): void
    {
        BedrockEconomyAPI::legacy()->addToPlayerBalance($player->getName(), $amount);
    }

}