<?php

namespace vale\hcf\events;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\Server;
use vale\hcf\HCF;
use vale\hcf\manager\DataManager;

class PlayerListener implements Listener
{

    /** @var HCF */
    public HCF $plugin;

    public function __construct(HCF $plugin)
    {
        $this->plugin = $plugin;
        $this->plugin->getServer()->getPluginManager()->registerEvents($this, $this->plugin);
    }

    public function onPlayerDeath(PlayerDeathEvent $event)
    {
        $deathBan = HCF::getInstance();
        $player = $event->getPlayer();
        $deathBan->getDeathBanManager()->setDeathBan($player, 6000);
        $player->kick("You have been deathbanned for 60 minutes", false);
    }

	public function onAliasCheck(PlayerLoginEvent $event)
	{
		$player = $event->getPlayer();
		$player = $event->getPlayer();
		$clientid = HCF::$blacklistedPlayers->getAll();
		foreach ($clientid as $p => $id) {
			if ($player->getClientId() === $id) {
				$player->setBanned(true);
				$mngr = new DataManager(HCF::getInstance());
				$mngr->setBlacklisted($player);
				Server::getInstance()->getLogger()->info($player->getName() . "tried to join but is blacklisted");
				$event->setCancelled(true);
			}
		}
	}
    public function onPLayerLogin(PlayerLoginEvent $event){
        $player = $event->getPlayer();
        $deathBan = HCF::getInstance();
        if($deathBan->getDeathBanManager()->isDeathBanned($player) === true){
            $deathBanTime = $deathBan->getDeathBanManager()->getDeathBan($player);
            $time = HCF::getInstance()->secondsToTime($deathBanTime);
            $player->kick("You are deathbanned for {$time}", false);
        }
    }
}
