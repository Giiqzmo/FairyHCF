<?php

namespace vale\hcf\items;

use pocketmine\block\BlockFactory;
use pocketmine\item\ItemFactory;
use vale\hcf\items\block\BrewingStand;
use vale\hcf\HCF;

class ItemManager{

	public static function initItems(){
		BlockFactory::registerBlock(new BrewingStand(), true);
	}
}
