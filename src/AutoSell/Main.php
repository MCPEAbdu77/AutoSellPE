<?php

declare(strict_types=1);

namespace AutoSell;
//By MCA7 [Copyright (C) 2021]

use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\event\Listener;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\item\Item;
use onebone\economyapi\EconomyAPI;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\block\Block;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\Player;

class Main extends PluginBase implements Listener {

  public function onEnable() {

         @mkdir($this->getDataFolder());
         @mkdir($this->getDataFolder() . "players/");
         $this->saveDefaultConfig();
         $this->getResource("config.yml");
         $this->getServer()->getPluginManager()->registerEvents($this, $this);

  }
  public function onDisable() {

    $this->getLogger()->info("Plugin Disabled!");
  }

  public function onJoin(PlayerJoinEvent $event) {

    $player = $event->getPlayer();
    $this->db = new Config($this->getDataFolder() . "players/" . strtolower($player->getName()), Config::YAML, array(
      "autosell" => "off"
    ));
    $this->db;

  }

  public function onCommand(CommandSender $sender, Command $cmd, string $lable, array $args) : bool {

    switch($cmd->getName()){
      case "autosell":
      if($sender->hasPermission("autosell.command")){

       if($sender instanceof Player){
         if(isset($args[0])) {

         switch(strtolower($args[0])) {
           case "on":
           $this->db->set("autosell", "on");
           $this->db->save();
           $sender->sendMessage(TextFormat::GREEN . "Toggled AutoSell! (Enabled)");
           return true;
           break;
           case "off":
             $this->db->set("autosell", "off");
             $this->db->save();
             $sender->sendMessage(TextFormat::RED . "Toggled AutoSell! (Disabled)");
             return true;
             break;
           }
         } else {
           $sender->sendMessage(TextFormat::RED . "Usage: /autosell <on/off>");
         }
         }  else {
           $sender->sendMessage(TextFormat::RED . "You can only use this command in-game!");
         }
       } else {
         $sender->sendMessage(TextFormat::RED . "You do not have the permission to use this command!");
         return true;
       }
       return true;
     }
  return true;
}
 public function onBreak(BlockBreakEvent $event) {
   if($this->db->get("autosell") == "on") {
     if($event->getPlayer()->getLevel()->getName() == $this->getConfig()->get("world")) {
       $item = $event->getBlock()->getId();
       $itemname = $event->getBlock()->getName();
       $con = $this->getConfig()->getAll();
       If(!(isset($con[$item]))) {

            $event->getPlayer()->sendTip(TextFormat::RED . "This block cannot be AutoSold!");
            return true;
    } else {

      $price = $this->getConfig()->get($item);
      $ply = $event->getPlayer()->getName();
      $event->getDrops();
      $event->setDrops([]);
      EconomyAPI::getInstance()->addMoney($ply, (int)$price);
      $event->getPlayer()->sendTip(TextFormat::GREEN . "Sold" . TextFormat::AQUA . " " . $itemname . TextFormat::GREEN ." for" . TextFormat::YELLOW ." $" . $price);
      return true;
    }

    } else {
    $event->getPlayer()->sendTip(TextFormat::RED . "You cannot AutoSell in this world!");
    return true;
  }
  }
  }
}
