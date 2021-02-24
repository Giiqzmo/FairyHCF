<?php

namespace vale\hcf\events;

use pocketmine\event\Cancellable;
use pocketmine\event\player\PlayerEvent;
use pocketmine\network\mcpe\protocol\AddEntityPacket;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use vale\hcf\factions\FactionLoader;
use vale\hcf\HCF;
use pocketmine\network\mcpe\protocol\AddActorPacket;
use pocketmine\entity\Entity;

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

	public function updateFactions(Player $player)
	{
		$player = $this->getPlayer();
		$factionmanager = new FactionLoader(HCF::getInstance());
		$faction = $factionmanager->getPlayerFaction($player->getName());
		foreach ($factionmanager->getAllMembers($faction) as $member) {
			 $mem = Server::getInstance()->getPlayerExact($member);
			if ($mem != null) {
				$name = $mem->getName();
				$faction = TextFormat::YELLOW . $factionmanager->getPlayerFaction($mem->getName());
				$player->sendData($mem, [Entity::DATA_NAMETAG => [Entity::DATA_TYPE_STRING, TextFormat::GREEN . "$name" . "\n" . "$faction"]]);
			}
		}
	}
}
