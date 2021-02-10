<?php


namespace vale\hcf\factions;


use pocketmine\Player;
use pocketmine\scheduler\Task;
use vale\hcf\HCF;

class FriendlyFireTask extends Task
{
	public string $fac;

	public Player $player;

    public function __construct(Player $player, $fac){
    	$this->player = $player;
    	$this->fac = (string) $fac;
    }

    public function onRun(int $currentTick)
	{
		$mngr = HCF::getInstance()->getFactionManager();
		$fac = $mngr->getPlayerFaction($this->player->getName());
		$leader = $mngr->getFactionLeaders($fac);
		foreach ($mngr->getAllMembers($fac) as $member){
			array_push(FactionLoader::$friendlyFire, $member->getName());
		}
	}
}
