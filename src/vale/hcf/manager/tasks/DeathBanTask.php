<?php

namespace vale\hcf\manager\tasks;

use pocketmine\Player;
use pocketmine\scheduler\Task;
use vale\hcf\HCF;

class DeathbanTask extends Task
{

    /** @var HCF */
    public HCF $plugin;
    /** @var Player */
    public Player $player;

    public function __construct(HCF $plugin)
    {
        $this->plugin = $plugin;
    }

    public function onRun(int $currentTick)
    {
        $deathbanned = HCF::getInstance()->getDeathBannedData()->getAll();
        foreach ($deathbanned as $p => $time) {
            if (HCF::$deathBannedPlayers->get($p) <= 1) {
                HCF::$deathBannedPlayers->remove($p);
                HCF::$deathBannedPlayers->save();

            } else {
                HCF::$deathBannedPlayers->set($p, $time - 1);
                HCF::$deathBannedPlayers->save();
                //todo lives
                echo "Revied {$deathbannedPlayers} ";
            }
        }
    }
}
