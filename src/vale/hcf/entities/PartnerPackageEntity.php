<?php

declare(strict_types = 1);

namespace vale\hcf\entities;
use muqsit\invmenu\InvMenu;
use muqsit\invmenu\MenuIds;
use pocketmine\entity\Human;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\item\Item;
use pocketmine\level\Level;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\Player;
use vale\hcf\HCF;
use vale\hcf\manager\misc\IEManager;

class PartnerPackageEntity extends Human {

	const NAMETAG = "§l§a* NEW *  \n\n\n§r§6Partner Crates \n§r§7Left click for Rewards \n§r§7Right Click to Open \n\n\n§r§7store.ourhcfserver.net!";


	const NETWORK_ID =  1;
	/**
	 * BaseEntity constructor.
	 *
	 * @param Level $level
	 * @param CompoundTag $nbt
	 * @param Player $player
	 *
	 */
	public function __construct(Level $level, CompoundTag $nbt, Player $player) {
		$manager = new IEManager(HCF::getInstance(), "meezoid.png");
		$this->setSkin($manager->skin);
		parent::__construct($level, $nbt);
		$this->setMaxHealth(4);
		$this->setNameTag(self::getNPCName());
		$this->setNameTagAlwaysVisible(true);
		$this->setHealth(4);
		$this->setScale(1);
		$this->yaw = $player->getYaw();
		$this->getInventory()->setItemInHand(Item::get(Item::ENDER_CHEST, 0, 1));
		$this->setCanSaveWithChunk(true);
	}

	/**
	 * @return string
	 */
	public function getName(): string {
		return "OJ2EJE[2";
	}

	/**
	 * @param EntityDamageEvent $event
	 * @priority NORMAL
	 */

	public function attack(EntityDamageEvent $event) : void{
		if($event instanceof EntityDamageByEntityEvent){
			$entity = $event->getEntity();
			$damager = $event->getDamager();
			if($damager instanceof Player){
				$event->setCancelled();
				$damager->sendMessage("TEST");
				$menu = InvMenu::create(MenuIds::TYPE_DOUBLE_CHEST);

			}
		}
	}


	public static function getNPCName(): string{
		$line = [
			"§l§a* NEW * ",
			str_repeat(" ", 3),
			"\n§r§6Partner Crates",
			"\n§r§fLeft click for Rewards",
			"\n§r§fRight Click to Open",
			str_repeat(" ", 2),
			"\n§r§fstore.ourhcfserver.net!!\n\n",
		];
		#foreach($val as $line){

		return ($line[0] . "\n" . $line[1] . $line[2] . $line[3] . $line[4] . "\n" . $line[5] . $line[6] . "\n\n\n");
		# }
	}
}



