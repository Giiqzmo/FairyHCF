<?php

namespace vale\hcf\deathban;

use pocketmine\level\Position;
use pocketmine\math\Vector3;
use pocketmine\Player;
use vale\hcf\data\YamlProvider;
use vale\hcf\deathban\DeathBanArena;
use vale\hcf\HCF;

class DeathBanManager
{

	public $arenas = [];

	public $plugin;
	public $dbconfig;

	public function __construct(HCF $plugin)
	{
		$this->plugin = $plugin;
		$this->dbconfig = YamlProvider::$deathBannedPlayers;
	}

	public function setDeathBan(Player $player, int $value){
		if(!$this->dbconfig->exists($player->getName())){
			$this->dbconfig->set($player->getName(), $value);
			$this->dbconfig->save();
		}
	}
	public function addTimeToDeathBan(Player $player, int $value){
		if($this->dbconfig->exists($player->getName())){
			$this->dbconfig->set($player->getName(), $this->getDeathBanTime($player) + $value);
			$this->dbconfig->save();
		}
	}

	public function reduceDeathBanTime(Player $player, int $value){
	if($this->dbconfig->exists($player->getName())){
		$this->dbconfig->set($player->getName(), $this->getDeathBanTime($player) - $value);
		$this->dbconfig->save();
		var_dump($this->getDeathBanTime($player));
	}
	}

	public function getDeathBanTime(Player $player): int{
		return $this->dbconfig->get($player->getName()) ?? 0;
	}
	public function unDeathBan(Player $player)
	{
		if ($this->dbconfig->exists($player->getName())) {
			$this->dbconfig->remove($player->getName());
			$this->dbconfig->save();
		}
	}

	public function isDeathBanned(Player $player){
		return $this->dbconfig->exists($player->getName());
	}
}
