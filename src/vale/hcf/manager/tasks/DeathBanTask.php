<?php

namespace vale\hcf\manager\tasks;

use pocketmine\Player;
use pocketmine\scheduler\Task;
use pocketmine\Server;
use vale\hcf\data\YamlProvider;
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
			if (YamlProvider::$deathBannedPlayers->get($p) <= 1) {
				YamlProvider::$deathBannedPlayers->remove($p);
				YamlProvider::$deathBannedPlayers->save();

				} else {
					YamlProvider::$deathBannedPlayers->set($p, $time - 1);
					YamlProvider::$deathBannedPlayers->save();
					//todo lives
				}
			}
		}
	}

