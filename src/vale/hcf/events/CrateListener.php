<?php

namespace vale\hcf\events;

use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\Listener;
use vale\hcf\data\YamlProvider;
use vale\hcf\HCF;

class CrateListener implements Listener
{

	public HCF $plugin;

	public function __construct(HCF $plugin)
	{
		$this->plugin = $plugin;
		$this->plugin->getServer()->getPluginManager()->registerEvents($this, $plugin);
	}

	public function onBlockPlace(BlockPlaceEvent $event){
		$player =  $event->getPlayer();
	}
}
