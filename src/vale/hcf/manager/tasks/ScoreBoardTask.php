<?php

namespace vale\hcf\manager\tasks;

use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\scheduler\Task;
use pocketmine\Server;
use vale\hcf\data\YamlProvider;
use vale\hcf\deathban\DeathBanManager;
use vale\hcf\events\PlayerFactionTagEvent;
use vale\hcf\HCF;
use vale\hcf\manager\{
	SotwManager, ScoreBoardManager
};
use pocketmine\utils\TextFormat as TE;
use vale\hcf\factions\FactionLoader;
use vale\hcf\items\ItemsListener;

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
		$api = new ScoreBoardManager();
		$facs = new FactionLoader(HCF::getInstance());
		$this->faction = $facs->getPlayerFaction($player->getName());
		$this->dtr =  $facs->getFactionDTR($this->faction);
		/** @var array */
		$scoreboard = [];
		if($player->getLevel() !== Server::getInstance()->getLevelByName("deathbanarena")) {
			if ($facs->isInFaction($player->getName())) {
				$scoreboard[] = "§6§lTeam§r§7: " . (string)$this->faction;
				if ($this->dtr <= 1.25) {
					$scoreboard[] = "§6§lDTR§r§7: §r§c" . $this->dtr . "§r§c■ ";
				}
				if ($this->dtr <= 2.50 && $this->dtr > 1.25) {
					$scoreboard[] = "§6§lDTR§r§7: §r§e" . $this->dtr . "§r§e■";
				}
				if ($this->dtr >= 3) {
					$scoreboard[] = "§6§lDTR§r§7: §r§a" . $this->dtr . "§r§a■ ";
				}
				$scoreboard[] = "§6§lHome§r§7: " . $facs->getHome($this->faction);
			}
		}
		if($player->getLevel() === Server::getInstance()->getLevelByName("deathbanarena")){
			$MNG = new DeathBanManager(HCF::getInstance());
			$scoreboard[] = "§r§c§lDeathban§r§c: " .  HCF::getTimeToFullString($MNG->getDeathBanTime($player));
			$scoreboard[] = "§r§6§lLives§r§6: " . 0;
		}

		if (SotwManager::isEnable()) {
			$scoreboard[] = "§r§6§lStart Of The World§r§7:§r§e " . HCF::getTimeToFullString(SotwManager::getTime());
		}
		if(isset(ItemsListener::$strengthItemCooldown[$player->getName()]) && ($cooldown = (time() - ItemsListener::$strengthItemCooldown[$player->getName()])) < 16) {

			$scoreboard[] = "§r§e§lStrength§r§e: §r§e0." . (16 - $cooldown) . "s";
		}

		if (count($scoreboard) >= 1) {
			$scoreboard[] = TE::GRAY . "------------------- ";
			$texting = [TE::GRAY . TE::GRAY . "------------------- "];
			$scoreboard = array_merge($texting, $scoreboard);
		} else {
			$api->removePrimary($player);
			return;
		}
		$api->newScoreboard($player, $player->getName(), "§r§6Fairy | §r§eInferno Realm");
		if ($api->getObjectiveName($player) !== null) {
			foreach ($scoreboard as $line => $key) {
				$api->remove($player, $scoreboard);
				$api->newScoreboard($player, $player->getName(), "§r§6Fairy | §r§eInferno Realm");
			}
		}
		foreach ($scoreboard as $line => $key) {
			$api->setLine($player, $line + 1, $key);
		}
	}
}
