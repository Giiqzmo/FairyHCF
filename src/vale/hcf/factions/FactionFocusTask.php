<?php


namespace vale\hcf\factions;


use pocketmine\level\particle\FloatingTextParticle;
use pocketmine\level\Position;
use pocketmine\Player;
use pocketmine\scheduler\Task;
use pocketmine\Server;

class FactionFocusTask extends Task
{

	public Player $recipent;
	public Player $target;

    /**
     * FactionFocusTask constructor.
     * @param Player $recipent
     * @param Player $target
     */
    public function __construct(Player $recipent, Player $target){
    	$this->recipent = $recipent;
    	$this->target = $target;
    }

    public function onRun(int $currentTick)
	{
		if($this->target && $this->recipent != null){
			if($this->target !== $this->recipent){
				return;
			}

		}
	}
}
