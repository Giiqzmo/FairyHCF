<?php

namespace vale\hcf\manager\tasks;

use pocketmine\Player;
use pocketmine\scheduler\Task;
use vale\hcf\data\YamlProvider;
use vale\hcf\HCF;
use vale\hcf\manager\{
	SotwManager, ScoreBoardManager
};
use pocketmine\utils\TextFormat as TE;
use vale\hcf\factions\FactionLoader;

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

	public function onRun(Int $currentTick) : void {
		$player = $this->player;
		if(!$player->isOnline()){
			HCF::getInstance()->getScheduler()->cancelTask($this->getTaskId());
			return;
		}
		$api = new ScoreBoardManager();
		$facs = new FactionLoader(HCF::getInstance());
		$this->faction = $facs->getPlayerFaction($player->getName());
		/** @var array */
		$scoreboard = [];
		if($facs->isInFaction($player)){
			$scoreboard[] = "§6§lTeam§r§7: " . (string)$this->faction;
		}
		if(SotwManager::isEnable()){
			$scoreboard[] = "§r§6§lSOTW§r§7:§r§e " . HCF::getTimeToFullString(SotwManager::getTime());
		}

		if(count($scoreboard) >= 1){
			$scoreboard[] = TE::GRAY."------------------- ";
			$texting = [TE::GRAY.TE::GRAY."------------------- "];
			$scoreboard = array_merge($texting, $scoreboard);
		}else{
			$api->removePrimary($player);
			return;
		}
		$api->newScoreboard($player, $player->getName(), "§r§6Fairy | §r§eInferno Realm");
		if($api->getObjectiveName($player) !== null){
			foreach($scoreboard as $line => $key){
				$api->remove($player, $scoreboard);
				$api->newScoreboard($player, $player->getName(), "§r§6Fairy | §r§eInferno Realm");			}
		}
		foreach($scoreboard as $line => $key){
			$api->setLine($player, $line + 1, $key);
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
