<?php

declare(strict_types = 1);

namespace vale\hcf\entities;
use muqsit\invmenu\InvMenu;
use muqsit\invmenu\MenuIds;
use pocketmine\entity\Human;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\inventory\transaction\action\SlotChangeAction;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\Item;
use pocketmine\level\Level;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\Player;
use vale\hcf\HCF;
use vale\hcf\misc\IEManager;

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
	public function __construct(Level $level, CompoundTag $nbt) {
		$manager = new IEManager(HCF::getInstance(), "meezoid.png");
		$this->setSkin($manager->skin);
		parent::__construct($level, $nbt);
		$this->setMaxHealth(4);
		$this->setNameTag(self::getNPCName());
		$this->setNameTagAlwaysVisible(true);
		$this->setHealth(4);
		$this->setScale(1);
		$this->yaw = $this->getYaw();
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

	public function attack(EntityDamageEvent $event) : void
	{
		if ($event instanceof EntityDamageByEntityEvent) {
			$entity = $event->getEntity();
			$damager = $event->getDamager();
			if ($damager instanceof Player) {
				$event->setCancelled();
				$damager->sendMessage("TEST");
				$menu = InvMenu::create(MenuIds::TYPE_DOUBLE_CHEST);
				$menu->readonly(true);
				$inv = $menu->getInventory();
				$oargne = Item::get(241, 1, 1);
				$slots = [0, 1, 9, 7, 17, 8, 52, 53, 44, 45, 46, 36];
				foreach ($slots as $slotID) {
					$inv->setItem($slotID, $oargne);
				}
				$vale = Item::get(397, 5, 1);
				$vale->getNamedTag()->setTag(new StringTag("vale"));
				$vale->setCustomName("§r§7[§r§k§c§leje§r§cVales Crate§r§k§c§leje§r§7]");
				$vale->setLore([
					'§r§7You can redeem this key at the §r§cVales Crate',
					'§r§7at spawn Left click to view the crate rewards'
				]);
				$jory = Item::get(397, 4, 1);
				$jory->getNamedTag()->setTag(new StringTag("jory"));
				$jory->setCustomName("§r§7[§r§k§e§leje§r§eJorys Crate§r§k§e§leje§r§7]");
				$jory->setLore([
					'§r§7You can redeem this key at the §r§eJorys Crate',
					'§r§7at spawn Left click to view the crate rewards'
				]);
				$new = Item::get(397, 3, 1);
				$new->getNamedTag()->setTag(new StringTag("new"));
				$new->setCustomName("§r§7[§r§k§b§leje§r§bNewcoolboys Crate§r§k§b§leje§r§7]");
				$new->setLore([
					'§r§7You can redeem this key at the §r§bNewcoolboys Crate',
					'§r§7at spawn Left click to view the crate rewards'
				]);
				$inv->setItem(11, $vale);
				$inv->setItem(12, $jory);
				$inv->setItem(13, $new);
				$menu->send($damager);
				$menu->setListener(function (Player $player, Item $itemClicked, Item $itemClickedWith, SlotChangeAction $action) {
					if ($action->getSourceItem()->getNamedTag()->hasTag("vale")) {
						$menu = InvMenu::create(MenuIds::TYPE_CHEST);
						$menu->setName("vale");
						$helm = Item::get(Item::DIAMOND_HELMET,0,1);
						$helm->setCustomName("§r§cVales Helmet");
						$helm->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantmentByName("protection"),3));
						$helm->setLore([
							'§r§cFire Resistance II',
							'§r§cRecover I'
						]);

						$chestplate = Item::get(Item::DIAMOND_CHESTPLATE);
						$chestplate->setCustomName("§r§cVales Chestplate");
						$chestplate->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantmentByName("protection"),3));
						$legg = Item::get(Item::DIAMOND_LEGGINGS,0,1);
						$legg->setCustomName("§r§cVales Leggings");
						$legg->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantmentByName("protection"),3));
						$legg->setLore([
							'§r§cNutrition II',
						]);
						$boots = Item::get(Item::DIAMOND_BOOTS,0,1);
						$boots->setCustomName("§r§cVales Boots");
						$boots->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantmentByName("protection"),3));
						$boots->setLore([
							'§r§cSpeed II',
						]);
						$sword = Item::get(Item::DIAMOND_SWORD,0,1);
						$sword->setCustomName("§r§cVales Sword");
						$sword->setLore([
							'§r§cGlutony II',
							'§r§cRecover II',
						]);
						$pick = Item::get(Item::DIAMOND_PICKAXE,0,1);
						$pick->setCustomName("§r§cVales PICK");
						$pick->setLore([
							'§r§cExperience II',

						]);

						$menu->readonly(true);
						$menu->getInventory()->setItem(0, $helm);
						$menu->getInventory()->setItem(1,$chestplate);
						$menu->getInventory()->setItem(2, $legg);
						$menu->getInventory()->setItem(3, $boots);
						$menu->getInventory()->setItem(4,$sword);
						$menu->getInventory()->setItem(5,$pick);
						$menu->getInventory()->setItem(6, Item::get(Item::DIAMOND_BLOCK, 0, 32));
						$menu->getInventory()->setItem(7, Item::get(Item::GOLD_BLOCK, 0, 32));
						$menu->getInventory()->setItem(8, Item::get(Item::IRON_BLOCK, 0, 32));
						$menu->getInventory()->setItem(9, Item::get(Item::REDSTONE_BLOCK, 0, 32));
						$menu->getInventory()->setItem(10, Item::get(Item::ENCHANTED_GOLDEN_APPLE, 0, 2));
						$menu->getInventory()->setItem(11, Item::get(Item::GOLDEN_APPLE, 0, 15));
						$menu->getInventory()->setItem(12, Item::get(Item::PAPER, 0, 3)->setCustomName("§r§4**§r§cLIVES§r§4**"));
						$menu->send($player);
					}
					if ($action->getSourceItem()->getNamedTag()->hasTag("jory")) {
						$menu = InvMenu::create(MenuIds::TYPE_CHEST);
						$menu->setName("jory");
						$menu->readonly(true);
						$menu->send($player);
					}
					if ($action->getSourceItem()->getNamedTag()->hasTag("new")) {
						$menu = InvMenu::create(MenuIds::TYPE_CHEST);
						$menu->setName("new");
						$menu->readonly(true);
						$menu->send($player);
					}
				});
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

	public function entityBaseTick(int $tickDiff = 1): bool
	{
		return parent::entityBaseTick($tickDiff); // TODO: Change the autogenerated stub
	}
}



