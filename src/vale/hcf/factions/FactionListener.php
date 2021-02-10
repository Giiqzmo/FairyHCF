<?php

namespace vale\hcf\factions;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\Player;
use vale\hcf\HCF;

class FactionListener implements Listener
{

	public HCF $plugin;

	public function __construct(HCF $plugin)
	{
		$this->plugin = $plugin;
		$plugin->getServer()->getPluginManager()->registerEvents($this, $plugin);
	}

	/**
	 * @param EntityDamageByEntityEvent $event
	 */
	public function friendlyFire(EntityDamageByEntityEvent $event)
	{
		/** @var $entity Player */
		$entity = $event->getEntity();
		/** @var $damager Player */
		$damager = $event->getDamager();
		$mngr = HCF::getInstance()->getFactionManager();
		if ($entity instanceof Player && $damager instanceof Player) {
			if ($mngr->getPlayerFaction($entity->getName() === $mngr->getPlayerFaction($damager))) {
				if (FactionLoader::hasFriendlyFireEnabled($entity) && FactionLoader::hasFriendlyFireEnabled($damager)) {
					$event->setBaseDamage(0);
				} else {
					$event->setCancelled(true);
					$damager->sendMessage("You cannot attack your faction members");
				}
			}
		}
	}
}
