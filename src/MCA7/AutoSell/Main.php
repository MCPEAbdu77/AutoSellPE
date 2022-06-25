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

class Main extends PluginBase implements Listener {


    private $db;

    public function onEnable():void 
    {
         $this->db = new Config($this->getDataFolder() . "players.yml");
         $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }


    public function onJoin(PlayerJoinEvent $event):void 
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

      switch($cmd->getName())
      {
         case "autosell":

            $player = $sender->getName();

            if($sender->hasPermission("autosell.command")){

              if($sender instanceof Player){

                if(isset($args[0])) {

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

                } else {

                      $sender->sendMessage($prefix . " " . TextFormat::RED . "Usage: /autosell <on/off>");
                }

              }  else {

                    $sender->sendMessage($prefix . " " . TextFormat::RED . "You can only use this command in-game!");
              }

            } else {

                 $sender->sendMessage($prefix . " " . TextFormat::RED . "You do not have the permission to use this command!");
            }
      }

      return true;

    }


    public function onDropPickup(BlockBreakEvent $event):void 
    {
        $name = $event->getPlayer()->getName();
        $con = $this->getConfig()->getAll();
        $item = $event->getBlock()->getId();

        if($event->getPlayer()->hasPermission("autosell.command")) {

          if($this->db->getNested($name) == "on") {

             if(in_array($event->getPlayer()->getWorld()->getFolderName(), $this->getConfig()->get("worlds"))) {

                if(isset($con[$item])) {

                    $event->setDrops([]);

                  }

                }

            }

        }

    }


    /**
     * @priority MONITOR
     * 
     */


    public function onBreak(BlockBreakEvent $event):void 
    {
          $name = $event->getPlayer()->getName();
          if($event->getPlayer()->hasPermission("autosell.command")){
              if($this->db->getNested($name) == "on") {
                  if(!$event->isCancelled()) {
                      if(!$event->getPlayer()->isCreative()) {
                          if(in_array($event->getPlayer()->getWorld()->getFolderName(), $this->getConfig()->get("worlds"))) {
       
                                $con = $this->getConfig()->getAll();
                                $item = $event->getBlock()->getId();
                                $itemname = $event->getBlock()->getName();

                                if(!(isset($con[$item]))) {

                                    $event->getPlayer()->sendTip(TextFormat::RED . "This block cannot be AutoSold!");

                                } else {

                                      $price = (int)$this->getConfig()->get($item);
                                      $ply = $event->getPlayer()->getName();
                                      EconomyAPI::getInstance()->addMoney($ply, (int)$price);
                                      $event->getPlayer()->sendTip(TextFormat::GREEN . "Sold" . TextFormat::AQUA . " " . $itemname ."(s)". TextFormat::GREEN ." for" . TextFormat::YELLOW ." $" . $price);
                                    
                                  }

                            } else {

                                  $event->getPlayer()->sendTip(TextFormat::RED . "You cannot AutoSell in this world!");
    
                              }

                        } else {

                              $event->getPlayer()->sendTip(TextFormat::RED."You cannot AutoSell in Creative Mode!");
  
                          }

                  } else {

                        $event->getPlayer()->sendTip(TextFormat::RED . "You cannot AutoSell protected blocks!");
      
                    }

                }

            }

      }

}