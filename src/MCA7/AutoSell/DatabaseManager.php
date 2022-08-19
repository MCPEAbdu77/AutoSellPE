<?php

declare(strict_types=1);

namespace MCA7\AutoSell;

use SQLite3;
use pocketmine\player\Player;

class DatabaseManager
{

	private $main;

	public function __construct(Main $main)
	{
		$this->main = $main;
	}


	public function openConnnection(): void
	{
		$this->main->db = new \SQLite3($this->main->getDataFolder() . 'players.db');
		$this->main->db->exec('CREATE TABLE IF NOT EXISTS PLAYERS (player TEXT PRIMARY KEY NOT NULL, mode BOOLEAN);');
	}


	public function addPlayer(Player $player): void
	{
		$name = $player->getName();
		$prep = $this->main->db->prepare('INSERT INTO PLAYERS (player, mode) VALUES (:name, :false);');
		$prep->bindValue(':name', $name);
		$prep->bindValue(':false', false);
		$prep->execute();
	}


	public function getPlayer(Player $player): bool
	{
		$name = $player->getName();
		$prep = $this->main->db->prepare('SELECT player FROM PLAYERS WHERE player = :player;');
		$prep->bindValue(':player', $name);
		$r = $prep->execute();
		while ($row = $r->fetchArray()) {
			if (count($row) > 0)
				return true;
		}
		return false;
	}


	public function setPlayerMode(Player $player, string $args): void
	{
		$name = $player->getName();
		$mode = match ($args) {
			'on' => true,
			'off' => false,
			default => false
		};
		$prep = $this->main->db->prepare('UPDATE PLAYERS SET mode = :mode WHERE player = :name;');
		$prep->bindValue(':mode', $mode);
		$prep->bindValue(':name', $name);
		$prep->execute();
	}


	public function getAllPlayers(): void
	{
		$prep = $this->main->db->query('SELECT * FROM PLAYERS;');
		while ($row = $prep->fetchArray()) {
			$this->main->players[$row['player']] = match ($row['mode']) {
				1 => true,
				0 => false,
				default => false
			};
		}

	}

}
