<?php

declare(strict_types=1);

namespace MCA7\AutoSell;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;
use pocketmine\utils\Config;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\player\Player;
use MCA7\AutoSell\DatabaseManager;
use MCA7\AutoSell\provider\BedrockEconomyProvider;
use MCA7\AutoSell\provider\CapitalEconomyProvider;
use MCA7\AutoSell\provider\EconomyAPIProvider;
use MCA7\AutoSell\provider\EconomyProvider;

/*
    Credits to cosmicnebula200 for multiple economy provider integration
    https://github.com/cosmicnebula200/SellMe
*/

class Main extends PluginBase implements Listener
{

	public $db;
	public $con;
	public $players = [];
	private $blocks = [];
	private $prices;

	/** @var EconomyProvider|null */
	private ?EconomyProvider $economyProvider;

	public function onEnable(): void
	{
		$this->db = new DatabaseManager($this);
		$this->con = new DatabaseManager($this);
		$this->db->openConnnection();
		$this->prices = new Config($this->getDataFolder() . "prices.yml");
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->blocks = $this->prices->getAll();
		$this->con->getAllPlayers();
		$this->economyProvider = match (strtolower($this->getConfig()->get('economy-provider'))) {
			"bedrockeconomy" => new BedrockEconomyProvider(),
			"capital" => new CapitalEconomyProvider($this),
			"economyapi" => new EconomyAPIProvider(),
			default => null
		};

		if ($this->economyProvider == null) {
			$this->yeet($this->getConfig()->get('economy-provider'));
			return;
		}
		if (!$this->economyProvider->checkClass()) {
			$this->yeet($this->economyProvider->getName());
			return;
		}
	}


	/**
	 * @return EconomyProvider|null
	 */

	public function getEconomyProvider(): ?EconomyProvider
	{
		return $this->economyProvider;
	}


	private function yeet(string $name): void
	{
		$this->getServer()->getLogger()->error("The respected class for the Economy Provider $name has not been found");
		$this->getServer()->getPluginManager()->disablePlugin($this);
	}


	public function onLoad(): void
	{
		if ($this->getConfig()->get('ver') === false || $this->getConfig()->get('ver') !== 1.2) {
			$this->saveDefaultConfig();
			$this->getServer()->getLogger()->critical(
				TextFormat::RED . 'Invalid Config version - Update plugin or delete the old config file! Disabling plugin.'
			);
			$this->getServer()->getPluginManager()->disablePlugin($this);
		}
	}


	public function onDisable(): void
	{
		foreach ($this->blocks as $block) {
			unset($block);
		}
		foreach ($this->players as $player) {
			unset($player);
		}
	}


	public function onJoin(PlayerJoinEvent $event): void
	{
		$player = $event->getPlayer();
		if (!$this->con->getPlayer($player)) {
			$this->con->addPlayer($player);
			$this->con->getAllPlayers();
		}
	}


	public function onCommand(CommandSender $sender, Command $cmd, string $lable, array $args): bool
	{

		$prefix = $this->getConfig()->get("prefix");

		if ($cmd->getName() === 'autosell') {

			if (!($sender->hasPermission("autosell.command"))) {
				$sender->sendMessage($prefix . " " . TextFormat::RED . "You do not have the permission to use this command!");
				return true;
			}

			if (!($sender instanceof Player)) {
				$sender->sendMessage($prefix . " " . TextFormat::RED . "You can only use this command in-game!");
				return true;
			}

			if (!(isset($args[0]))) {
				$sender->sendMessage($prefix . " " . TextFormat::RED . "Usage: /autosell < on | off | add | remove | view >");
				return true;
			}

			if (!in_array(strtolower($args[0]), ['on', 'off', 'view', 'add', 'remove'])) {
				$sender->sendMessage($prefix . " " . TextFormat::RED . "Invalid argument! Usage: /autosell < on | off | add | remove | view >");
				return true;
			}

			switch (strtolower($args[0])) {
				case "on":

					$this->con->setPlayerMode($sender, 'on');
					$this->con->getAllPlayers();
					$sender->sendMessage($prefix . " " . TextFormat::GREEN . "Toggled AutoSell! (Enabled)");
					return true;

				case "off":

					$this->con->setPlayerMode($sender, 'off');
					$this->con->getAllPlayers();
					$sender->sendMessage($prefix . " " . TextFormat::RED . "Toggled AutoSell! (Disabled)");
					return true;

				case "add":

					if (!$sender->hasPermission('autosell.command.add')) {
						$sender->sendMessage($prefix . " " . TextFormat::RED . "You do not have the permission to use this sub-command!");
						return true;
					}
					if (count($args) < 2 || (!(is_numeric($args[1])))) {
						$sender->sendMessage($prefix . " " . TextFormat::RED . "Usage: /autosell add <price>");
						return true;
					}
					$check = $sender->getInventory()->getItemInHand();
					$block = $sender->getInventory()->getItemInHand()->getName();
					if ($check->isNull() || get_class($check) === 'pocketmine\item\TieredTool') {
						$sender->sendMessage($prefix . " " . "Invalid block! Hold a block in hand and execute the command again.");
						return true;
					}
					$sellprice = $args[1];
					if ($this->prices->getNested($block)) {
						$this->prices->removeNested($block);
						$this->prices->setNested($block, $sellprice);
						$this->prices->save();
						$sender->sendMessage($prefix . " " . TextFormat::GREEN . "Updated sell price to" . TextFormat::WHITE . "$" . $sellprice);
						$this->blocks = $this->prices->getAll();
						return true;
					}
					$this->prices->setNested($block, $sellprice);
					$sender->sendMessage($prefix . " " . TextFormat::GREEN . "Added block/item successfully!");
					$this->prices->save();
					$this->blocks = $this->prices->getAll();
					return true;

				case "remove":

					if (!$sender->hasPermission('autosell.command.remove')) {
						$sender->sendMessage($prefix . " " . TextFormat::RED . "You do not have the permission to use this sub-command!");
						return true;
					}
					$item = $sender->getInventory()->getItemInHand()->getName();
					if ($this->prices->getNested($item)) {
						$this->prices->removeNested($item);
						$sender->sendMessage($prefix . " " . TextFormat::GREEN . "Removed item/block successfully!");
						$this->prices->save();
						$this->blocks = $this->prices->getAll();
					} else {
						$sender->sendMessage($prefix . " " . TextFormat::RED . $item . " has not been added before.");
					}
					return true;

				case "view":
					$sender->sendMessage(TextFormat::YELLOW . "-- VIEWING PRICES FOR AUTOSELL --");
					foreach ($this->blocks as $key => $value) {
						$sender->sendMessage(TextFormat::BLUE . $key . TextFormat::WHITE . " - $" . $value);
					}

			}
		}

		return true;

	}

	/**
	 * @priority MONITOR
	 */

	public function onBreak(BlockBreakEvent $event): void
	{
		$player = $event->getPlayer();
		$name = $event->getPlayer()->getName();
		if (!($player->hasPermission("autosell.command"))) return;
		if (!$this->players[$name] || $event->getDrops() === []) return;
		if ($event->isCancelled()) {
			$player->sendTip(TextFormat::RED . "You cannot AutoSell protected blocks!");
			return;
		}
		if ($player->isCreative()) {
			$player->sendTip(TextFormat::RED . "You cannot AutoSell in creative mode!");
			return;
		}
		if (is_array($this->getConfig()->get("worlds")) && !in_array($player->getWorld()->getFolderName(), $this->getConfig()->get("worlds"))) {
			Exception: 
			$player->sendTip(TextFormat::RED . "You cannot AutoSell in this world!");
			return;
		} elseif ($player->getWorld()->getFolderName() !== $this->getConfig()->get("worlds")) {
			goto Exception;
		}
		$count = 0;
		foreach ($event->getDrops() as $drop) {
			if (isset($this->blocks[$drop->getName()])) {
				$count += $drop->getCount();
			}
		}
		if (isset($this->blocks[$drop->getName()])) {
			$event->setDrops([]);
			$itemname = $drop->getName();
			$price = (int)$this->blocks[$itemname] * $count;
			$player->sendTip(
				TextFormat::GREEN . "Sold" . TextFormat::AQUA . " " . $itemname .
				TextFormat::YELLOW . " x" . $count . TextFormat::GREEN . " for" . TextFormat::YELLOW . " $" . $price
			);
			$this->getEconomyProvider()->addToMoney($player, $price, [
				"item" => $itemname,
				"amount" => $count,
			]);

		}

	}

}
