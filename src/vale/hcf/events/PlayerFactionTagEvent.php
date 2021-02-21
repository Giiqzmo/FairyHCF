<?php

namespace vale\hcf\events;

use pocketmine\event\Cancellable;
use pocketmine\event\player\PlayerEvent;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use vale\hcf\factions\FactionLoader;
use vale\hcf\HCF;

class PlayerFactionTagEvent extends PlayerEvent implements Cancellable
{

	public $player;

	public string $faction;

	public function __construct(Player $player)
	{
		$this->player = $player;
	}


	/**
	 * @param Player $player
	 * @return string
	 */
	public function getFaction(Player $player): string
	{
		return HCF::getInstance()->getFactionManager()->getPlayerFaction($player->getName());
	}

	/**
	 * @return Player
	 */
	public function getPlayer(): Player
	{
		return $this->player;
	}

	public function updateFactions()
	{
		$facs = [];
		$player = $this->getPlayer();
		$faction = new FactionLoader(HCF::getInstance());
		$fn = $faction->getPlayerFaction($player->getName());
		foreach (Server::getInstance()->getOnlinePlayers() as $onlinePlayer) {
			if (in_array($onlinePlayer->getName(), $faction->getAllMembers($fn))) {
				$player->setNameTag(TextFormat::GREEN . $player->getName()) . "\n" . TextFormat::YELLOW . $faction->getPlayerFaction($player->getName());
			} else {
				$player->setNameTag(TextFormat::RED . $player->getName());
			}
		}
	}
}
