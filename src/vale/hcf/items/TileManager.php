<?php
declare(strict_types = 1);

namespace vale\hcf\items;

use pocketmine\tile\Tile as PMTile;
use vale\hcf\HCF;
use vale\hcf\items\tiles\BrewingStand;

abstract class TileManager extends PMTile {
	/** @var string */
	public const
		BEACON = "Beacon",
		MOB_SPAWNER = "MobSpawner",
		SHULKER_BOX = "ShulkerBox",
		HOPPER = "Hopper",
		JUKEBOX = "Jukebox",
		CAULDRON = "Cauldron";

	public static function init(){
		try {
			self::registerTile(BrewingStand::class);

			//self::registerTile(Jukebox::class);
		} catch(\ReflectionException $e){
			HCF::getInstance()->getLogger()->error($e); // stfu phpstorm
		}
	}
}
