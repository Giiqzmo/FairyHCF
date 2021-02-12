<?php


namespace vale\hcf\manager\tasks;


use pocketmine\Player;
use pocketmine\scheduler\Task;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use vale\hcf\factions\FactionLoader;
use vale\hcf\HCF;

class FactionTag extends Task
{

    /** @var Player */
    public Player $player;

    /**
     * FactionTag constructor.
     * @param Player $player
     */
    public function  __construct(Player $player){
        $this->player = $player;
    }

    public function onRun(int $currentTick)
    {
        $player = $this->player;
        $faction = new FactionLoader(HCF::getInstance());
        $fn = $faction->getPlayerFaction($player->getName());
        $ts = $faction->isInFaction($fn);
        foreach (Server::getInstance()->getOnlinePlayers() as $onlinePlayer) {
            if (in_array($onlinePlayer->getName(), $faction->getAllMembers($fn))) {
                $player->setNameTag(TextFormat::GREEN . $player->getName() . "\n" . TextFormat::YELLOW . $fn);
            } else {
                $player->setNameTag(TextFormat::RED . $player->getName() . "\n" . TextFormat::YELLOW . $fn);
            }
            if($ts === false){
                $player->setNameTag(TextFormat::RED . $player->getName());
            }
        }
    }
}