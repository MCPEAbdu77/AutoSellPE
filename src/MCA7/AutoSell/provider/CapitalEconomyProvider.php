<?php

namespace MCA7\AutoSell\provider;

use MCA7\AutoSell\Main;
use pocketmine\player\Player;
use SOFe\Capital\Capital;
use SOFe\Capital\CapitalException;
use SOFe\Capital\LabelSet;
use SOFe\Capital\Schema\Complete;

class CapitalEconomyProvider extends EconomyProvider
{

    /** @var Complete */
    private Complete $selector;
    private $main;

    public function __construct(Main $main)
    {
        Capital::api("0.1.0", function(Capital $api) {
            $this->selector = $api->completeConfig($this->main->getConfig()->getNested("capital-settings.selector"));
        });
    }

    public function getName(): string
    {
        return "Capital";
    }

    public function checkClass(): bool
    {
        if (class_exists(Capital::class))
            return true;
        return false;
    }

    public function addToMoney(Player $player, int $amount, array $labels): void
    {
        Capital::api('0.1.0',
            function ($api) use ($player, $amount, $labels)
            {
                try {
                    yield from $api->addMoney(
                        "AutoSellPE",
                        $player,
                        $this->selector,
                        $amount,
                        new LabelSet($labels + ["reason" => "selling items"]),
                    );
                }catch (CapitalException){

                }
            });
    }

}
