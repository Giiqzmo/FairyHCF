<?php

namespace vale\hcf\manager\tasks;

use pocketmine\Player;
use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat as C;
use vale\hcf\HCF;

class BroadcastTask extends Task
{

    /** @var HCF */
    public HCF $plugin;
    
    /** @var Player */
    public Player $player;
    
//    /** @var Messages */
//    public Array $messages = []
    
    public function __construct(HCF $plugin)
    {
        $this->plugin = $plugin;
    }

    public function onRun(int $currentTick)
    {
        //shit goes here
    }
}
