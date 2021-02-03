<?php
namespace vale\hcf\events;

use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\Listener;
use vale\hcf\HCF;

class CrateListener implements Listener
{

	public HCF $plugin;

	public function __construct(HCF $plugin)
	{
		$this->plugin = $plugin;
		$this->plugin->getServer()->getPluginManager()->registerEvents($this, $plugin);
	}

	public function onPlace(BlockPlaceEvent $ev)
	{
		$player = $ev->getPlayer();
		$block = $ev->getBlock();
		$hand = $player->getInventory()->getItemInHand();
		//$config = DB::$cratedata;
		$level = $block->getLevel()->getName();
		$l = $block->getLevel();
		$names = explode("\n", $hand->getCustomName());
	}
}


