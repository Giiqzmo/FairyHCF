<?php

namespace vale\hcf\manager\tasks;

use pocketmine\Player;
use vale\hcf\HCF;
use pocketmine\scheduler\Task;
use vale\hcf\manager\SotwManager;

class StartOfTheWorldTask extends Task {

	/**
	 * StartOfTheWorldTask Constructor.
	 * @param Int $time
	 */
	public function __construct(Int $time = 60){
		SotwManager::setTime($time);
	}

	/**
	 * @param Int $currentTick
	 * @return void
	 */
	public function onRun(Int $currentTick) : void {
		if(!SotwManager::isEnable()){
			HCF::getInstance()->getScheduler()->cancelTask($this->getTaskId());
			return;
		}
		if(SotwManager::getTime() === 0){
			SotwManager::setEnable(false);
			HCF::getInstance()->getScheduler()->cancelTask($this->getTaskId());
		}else{
			SotwManager::setTime(SotwManager::getTime() - 1);
		}
	}
}
