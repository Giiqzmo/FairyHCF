<?php

namespace vale\hcf\manager\tasks;

use pocketmine\Player;
use pocketmine\scheduler\Task;
use vale\hcf\data\YamlProvider;
use vale\hcf\factions\FactionLoader;
use vale\hcf\HCF;
use vale\hcf\manager\ScoreBoardManager;
use pocketmine\utils\TextFormat as TE;

class ScoreboardTask extends Task
{

	public Player $player;

	public string $faction;

	public $homeCoords;

	public int $dtr;

	public $api;

	public string $onlineMembers;

	public function __construct(Player $player)
	{
		$this->player = $player;
	}

	public function onRun(int $currentTick): void
	{
		$player = $this->player;
		if (!$player->isOnline()) {
			HCF::getInstance()->getScheduler()->cancelTask($this->getTaskId());
			return;
		}
		$factionManager = new FactionLoader(HCF::getInstance());
		$this->faction = $factionManager->getPlayerFaction($player->getName());
		$this->dtr = $factionManager->getFactionDTR($this->faction);
		$this->homeCoords = $factionManager->getHome($this->faction);

		$this->api = new ScoreBoardManager();
		$api = $this->api;
		$api->newScoreboard($player, "ScoreBoard", "§r§6Fairy | §r§eInferno Realm");
		if ($factionManager->isInFaction($player)) {
			$api->setLine($player, 1, "§r§7-----------------");
			$api->setLine($player, 2, "§6§lTeam§r§7: " . (string)$this->faction);
			$api->setLine($player, 3, "§6§lHQ§r§7: " . (string)$this->homeCoords);
			$api->setLine($player, 4, "§6§lDTR§r§7 §r§c" . $this->checkDtr($player));
		}
	}

	public function checkDtr(Player $player)
	{
		$factionManager = new FactionLoader(HCF::getInstance());
		$this->faction = $factionManager->getPlayerFaction($player->getName());
		$this->dtr = $factionManager->getFactionDTR($this->faction);
		$this->homeCoords = $factionManager->getHome($this->faction);
		$api = $this->api;
		if ($this->dtr <= 1.25) {
			$api->setLine($player, 4, "§6§lDTR§r§7: §r§c" . $this->dtr . "§r§c■ ");
		}
		if ($this->dtr <= 2.50 && $this->dtr > 1.25) {
			$api->setLine($player, 4, "§6§lDTR§r§7: §r§e" . $this->dtr . "§r§e■");
		}
		if ($this->dtr >= 3) {
			$api->setLine($player, 4, "§6§lDTR§r§7: §r§a" . $this->dtr . "§r§a■ ");
		}
	}
}
