<?php

namespace vale\hcf\items;

use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\item\Item;
use pocketmine\level\Position;
use pocketmine\level\sound\AnvilFallSound;
use pocketmine\math\Vector3;
use pocketmine\Player;
use vale\hcf\HCF;
use vale\hcf\misc\RewardsAPI;
use libs\utils\Utils;

class ItemsListener implements Listener
{

	public HCF $plugin;
	/** @var array $strengthItemCooldown */
	public static array $strengthItemCooldown = [];

	/** @var array $boneItemCooldown */
	public static array $boneItemCooldown = [];

	public $hits = 0;

	public function __construct(HCF $plugin)
	{
		$this->plugin = $plugin;
		$this->plugin->getServer()->getPluginManager()->registerEvents($this, $plugin);
	}

	public function onJoin(PlayerJoinEvent $event)
	{
		$player = $event->getPlayer();
		$item = Item::get(Item::ENDER_CHEST, 0, 10);
		$item->setCustomName("§r§d§lPartner Package §r§f(#1030)");
		$item->setLore([
			'§r§7Right click to open a partner package'
		]);
		$player->getInventory()->addItem($item);
	}

	public function onTouch(PlayerInteractEvent $event): void
	{
		$player = $event->getPlayer();
		$block = $event->getBlock();
		$inv = $player->getInventory();
		$hand = $inv->getItemInHand();
		$nbt = $hand->getNamedTag();
		$action = $event->getAction();
		if ($hand->getId() == 130 and $hand->getCustomName() == "§r§d§lPartner Package §r§f(#1030)") {
			Utils::addFireworks(new Position($player->x, $player->y + 2.5, $player->z, $player->getLevel()));
			$hand->setCount($hand->getCount() - 1);
			$inv->setItemInHand($hand);
			$event->setCancelled(true);
			$chance = rand(1, 5);
			switch ($chance) {
				case 1:
					RewardsAPI::giveReward($player, RewardsAPI::BONE);
					break;
				case 2:
					RewardsAPI::giveReward($player, RewardsAPI::NINJA_STAR);
					break;
				case 3:
					RewardsAPI::giveReward($player, RewardsAPI::BONE);
					break;
				case 4:
					RewardsAPI::giveReward($player, RewardsAPI::STRENGTH2);
					break;
				case 5:
					RewardsAPI::giveReward($player, RewardsAPI::STRENGTH2);
					break;
			}
		}
		if (($action == PlayerInteractEvent::RIGHT_CLICK_AIR || $action == PlayerInteractEvent::RIGHT_CLICK_BLOCK)) {
			if ($hand->getId() == 377 && $hand->getCustomName() == "§r§c§lStrength II") {
				$event->setCancelled();
				if (!isset(self::$strengthItemCooldown[$player->getName()])) {
					$hand->setCount($hand->getCount() - 1);
					$player->getInventory()->setItemInHand($hand);

					self::$strengthItemCooldown[$player->getName()] = time();
					$player->addEffect(new EffectInstance(Effect::getEffect(5), 8 * 20, 4));

				} else {
					if ((time() - self::$strengthItemCooldown[$player->getName()]) < 16) {
						$timer = time() - self::$strengthItemCooldown[$player->getName()];
						$player->sendMessage("§r§cYou are on cooldown for " . "§r§c§l{$timer}s");
						$player->getLevel()->addSound(new AnvilFallSound(new Vector3($player->getX(),), 2));
						return;
					} else {
						self::$strengthItemCooldown[$player->getName()] = time();
					}
				}
			}
		}
	}


	public function onPvp(EntityDamageByEntityEvent $event)
	{
		$player = $event->getDamager();
		$entity = $event->getEntity();
		if ($player instanceof Player && $entity instanceof Player) {
			$hand = $player->getInventory()->getItemInHand();
		}
	}



	public static function getPartnerItemCooldown(Player $player): int
	{
		if (in_array($player->getName(), self::$strengthItemCooldown)) {
			$timer = time() - self::$strengthItemCooldown[$player->getName()];

		}
		return $timer;
	}
}
