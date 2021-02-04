<?php

namespace vale\hcf\events;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\Player;
use vale\hcf\HCF;

class FactionListener implements Listener
{

	public HCF $plugin;

	public function __construct(HCF $plugin)
	{
		$this->plugin = $plugin;
		$this->plugin->getServer()->getPluginManager()->registerEvents($this, $plugin);
	}

	public function onFactionChat(PlayerChatEvent $event)
	{
		$message = $event->getMessage();
		$player = $event->getPlayer();
		$onlineMembers = HCF::getInstance()->getServer()->getOnlinePlayers();
		$mgr = HCF::getInstance()->getFactionManager();
		$faction = $mgr->getPlayerFaction($player);
		foreach ($onlineMembers as $p) {
			if ($mgr->getPlayerFaction($p) === $mgr->getPlayerFaction($player)) {
				if ($mgr->hasFchatEnabled($player)) {
					$event->setMessage("FCHAT" . $message);
					$event->setCancelled();
				}
			}
		}
	}
	public function facProtection(EntityDamageByEntityEvent $event){
		$player = $event->getEntity();
		$damager = $event->getDamager();
		$mgnr = HCF::getInstance()->getFactionManager();
		if($player instanceof Player || $damager instanceof Player){
			$pfac = $mgnr->getPlayerFaction($player);
			$dfac = $mgnr->getPlayerFaction($damager);
			if($pfac === $dfac){
				$event->setCancelled();
			}elseif ($mgnr->hasFriendlyFireEnabled($player) && $mgnr->hasFriendlyFireEnabled($damager)){
				$event->setBaseDamage(0);
			}

		}
	}
}
