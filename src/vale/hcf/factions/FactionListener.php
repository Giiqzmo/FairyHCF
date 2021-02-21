<?php

namespace vale\hcf\factions;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\Player;
use pocketmine\Server;
use vale\hcf\events\PlayerFactionTagEvent;
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
            if (in_array($entity->getName(), $mngr->getAllMembers($damager->getName()))) {
                if (FactionLoader::hasFriendlyFireEnabled($entity) && FactionLoader::hasFriendlyFireEnabled($damager)) {
                    $event->setBaseDamage(0);
                } else {
                    $event->setCancelled(true);
                    $damager->sendMessage("You cannot attack your faction members");
                }
            }
        }
    }

    public function onPlayerJoin(PlayerJoinEvent $event)
    {
        $player = $event->getPlayer();
        $faction = new FactionLoader(HCF::getInstance());
        $event->setJoinMessage("");
        $playerFac = $faction->getPlayerFaction($player->getName());
        if ($faction->isInFaction($player->getName())) {
            foreach (Server::getInstance()->getOnlinePlayers() as $players) {
                if($faction->getPlayerFaction($players->getName()) == $playerFac){
                    $dtr = $faction->getFactionDTR($playerFac);
                    $message = "§l§6Member Online§r§7: §a{$player->getName()} \n";
                    $message .= "§l§6Faction DTR§r§7: §f{$dtr}";
                    $players->sendMessage($message);
                }
            }
        }
    }
}
