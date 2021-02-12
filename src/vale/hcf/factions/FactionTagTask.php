<?php


namespace vale\hcf\factions;


use pocketmine\Player;
use pocketmine\scheduler\Task;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use vale\hcf\factions\FactionLoader;
use vale\hcf\HCF;

class FactionTagTask extends Task
{

    /**
     * FactionTagTask constructor.
     * @param Player $player
     * @param mixed|string $facname
     */

    public $player;
    public string $facname;
    public function __construct(Player $player, string $facname){
    	$this->player =  $player;
    	$this->facname = $facname;
    }

    public function onRun(int $currentTick)
	{
		$onlinePlayers = Server::getInstance()->getOnlinePlayers();
		$mngr = new FactionLoader(HCF::getInstance());
		$faction = $mngr->getPlayerFaction($this->player->getName());
		$members = $mngr->getAllMembers($faction);
		foreach ($onlinePlayers as $onlinePlayer){
			if(in_array($onlinePlayers, $members)){
				$onlinePlayer->setNameTag(TextFormat::GREEN . $onlinePlayer->getName() . "\n" . TextFormat::YELLOW . $faction);
			}
			if(!$mngr->isInFaction($onlinePlayer)){
				$onlinePlayer->setNameTag(TextFormat::RED . "NA"  . "\n" .  $onlinePlayer->getName());
			}
			if(!in_array($onlinePlayer, $members)){
				$onlinePlayer->setNameTag(TextFormat::RED . $onlinePlayer->getName() . "\n" . TextFormat::YELLOW . $mngr->getPlayerFaction($onlinePlayer->getName()));

			}
		}
	}

}
