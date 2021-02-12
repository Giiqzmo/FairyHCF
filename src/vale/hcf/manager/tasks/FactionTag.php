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
    public function __construct()
    {
    }

    public function onRun(int $currentTick)
    {
        $faction = new FactionLoader(HCF::getInstance());
        foreach (Server::getInstance()->getOnlinePlayers() as $onlinePlayer) {
            $fn = $faction->getPlayerFaction($onlinePlayer->getName());
            $fn2 = $faction->getPlayerFaction($onlinePlayer->getName());
            if ($faction->getPlayerFaction($onlinePlayer->getName()) === $faction->getPlayerFaction($onlinePlayer->getName())) {
                $onlinePlayer->setNameTag(TextFormat::GREEN . $onlinePlayer->getName() . "\n" . TextFormat::YELLOW . $faction->getPlayerFaction($onlinePlayer->getName()));
            }
            if (!$faction->isInFaction($onlinePlayer->getName())) {
                $onlinePlayer->setNameTag(TextFormat::RED . "NA" . $onlinePlayer->getName());

            } else {
                $onlinePlayer->setNameTag(TextFormat::RED . $onlinePlayer->getName() . "\n" . TextFormat::YELLOW . $faction->getPlayerFaction($onlinePlayer->getName()));
            }
        }
    }
}