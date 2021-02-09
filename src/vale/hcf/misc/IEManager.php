<?php

declare(strict_types = 1);

#   /\ /\___ _ __ __ _ ___
#   \ \ / / _ \ '__/ _`|/_\
#    \ V / __/ | | (_| |__/
#     \_/ \___ |_| \__,|\___
#                  |___/

namespace vale\hcf\manager\misc;


use pocketmine\entity\Skin;
use pocketmine\level\Level;
use pocketmine\level\Position;
use pocketmine\utils\TextFormat;
use vale\hcf\manager\misc\SkinConverter;
use vale\hcf\HCF;

class IEManager {


	/** @var Skin */
	public $skin;

	/** @var string */
	public $name;

	/** @var HCF*/
	private $plugin;

	/**
	 * Manager constructor.
	 *
	 * @param HCF $plugin
	 */
	public function __construct(HCF $plugin, string $path) {
		$this->plugin = $plugin;
		$this->path = $path;
		$this->init();
	}

	public function init(): void {
		#   $config = $this->plugin->getConfig();

		$path = $this->plugin->getDataFolder() . $this->path;
		$this->skin = SkinConverter::createSkin(SkinConverter::getSkinDataFromPNG($path));

	}
}
