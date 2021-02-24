<?php

namespace vale\hcf\deathban;

use pocketmine\level\Position;
use pocketmine\math\Vector3;
use vale\hcf\deathban\DeathBanArena;
use vale\hcf\HCF;

class DeathBanManager{

	public $arenas = [];

	public $plugin;

	public function __construct(HCF $plugin){
		$this->plugin = $plugin;
	}


	public function initArena(DeathBanArena $arena){
		$this->arenas[] = $arena;

	}
}
