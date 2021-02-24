<?php

namespace vale\hcf\manager\tasks;

use pocketmine\Player;
use pocketmine\scheduler\Task;
use pocketmine\Server;
use pocketmine\utils\TextFormat as C;
use vale\hcf\events\PlayerFactionTagEvent;
use vale\hcf\HCF;

class FactionTagTask extends Task
{

    /** @var HCF */
    public HCF $plugin;
    
    /** @var Player */
    public Player $player;
    
//    /** @var Messages */
//    public Array $messages = []
    
    public function __construct(Player $player)
    {
        $this->player = $player;
    }

    public function onRun(int $currentTick)
    {
   $player = $this->player;
      $ev = new PlayerFactionTagEvent($player);
      $ev->updateFactions($player);
      $ev->call();
    }
}
