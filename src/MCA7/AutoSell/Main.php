<?php

declare(strict_types=1);

namespace MCA7\AutoSell;

use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\event\Listener;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use onebone\economyapi\EconomyAPI;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\block\Block;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\player\Player;

class Main extends PluginBase implements Listener 
{


    private $db;

    public function onEnable() : void 
    {
         $this->db = new Config($this->getDataFolder() . "players.yml");
         $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }


    public function onLoad() : void 
    {
        if(!$this->getConfig()->get('ver') === '1.0') {
            $this->getServer()->getLogger()->debug(
                TextFormat::RED . 'Invalid Config version - Update plugin or delete the old config file! Disabling plugin.'
            );
            $this->getServer()->getPluginManager()->getPlugin()->disable();
         }
    }


    public function onJoin(PlayerJoinEvent $event) : void 
    {

        $player = $event->getPlayer()->getName();

        if(!$this->db->getNested($player)) {

            $this->db->setNested($player, "off");
            $this->db->save();

        }
        
    }


    public function onCommand(CommandSender $sender, Command $cmd, string $lable, array $args) : bool 
    {
    
       $prefix = $this->getConfig()->get("prefix");

      if($cmd->getName() === 'autosell')
      {

            $player = $sender->getName();

            if(!($sender->hasPermission("autosell.command"))) {
                $sender->sendMessage($prefix . " " . TextFormat::RED . "You do not have the permission to use this command!");
                return true;
            }

            if(!($sender instanceof Player)) {
                $sender->sendMessage($prefix . " " . TextFormat::RED . "You can only use this command in-game!");
                return true;
            }

            if(!(isset($args[0]))) {
                $sender->sendMessage($prefix . " " . TextFormat::RED . "Usage: /autosell <on/off>");
                return true;
            }

                switch(strtolower($args[0])) 
                   {
                      case "on":

                        $this->db->setNested($player, "on");
                        $this->db->save();
                        $sender->sendMessage($prefix . " " . TextFormat::GREEN . "Toggled AutoSell! (Enabled)");
                        return true;

                      case "off":

                         $this->db->setNested($player, "off");
                         $this->db->save();
                         $sender->sendMessage($prefix . " " . TextFormat::RED . "Toggled AutoSell! (Disabled)");
                         return true;
                    }

      }

      return true;

    }


  /**
   * @priority MONITOR
   */

    public function onBreak(BlockBreakEvent $event) : void 
    {
        $player = $event->getPlayer();
        $name = $event->getPlayer()->getName();
        if(!($player->hasPermission("autosell.command"))) return;
        if($this->db->getNested($name) == "off") return;
        if($event->isCancelled()) {
            $player->sendTip(TextFormat::RED . "You cannot AutoSell protected blocks!");
            return;
        }
        if($player->isCreative()) {
            $player->sendTip(TextFormat::RED."You cannot AutoSell in Creative Mode!");
            return;
        }
        if(!in_array($player->getWorld()->getFolderName(), $this->getConfig()->get("worlds"))) {
            $player->sendTip(TextFormat::RED . "You cannot AutoSell in this world!");
            return;
        }
       
            $con = $this->getConfig()->getAll();
            $item = $event->getBlock()->getId();
            $itemname = $event->getBlock()->getName();

            if(!(isset($con[$item]))) {

                $player->sendTip(TextFormat::RED . "This block cannot be AutoSold!");

                } else {

                    $price = (int)$this->getConfig()->get($item);
                    EconomyAPI::getInstance()->addMoney($name, $price);
                    $player->sendTip(
                    TextFormat::GREEN . "Sold" . TextFormat::AQUA . " " . $itemname ."(s)". TextFormat::GREEN ." for" . TextFormat::YELLOW ." $" . $price
                    );
                    $event->setDrops([]);
                                    
                }


      }

}