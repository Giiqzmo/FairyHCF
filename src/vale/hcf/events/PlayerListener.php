<?php

namespace vale\hcf\events;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use vale\hcf\data\YamlProvider;
use vale\hcf\factions\FactionLoader;
use vale\hcf\HCF;
use vale\hcf\manager\DataManager;
use vale\hcf\manager\tasks\FactionTag;
use vale\hcf\manager\tasks\ScoreboardTask;

class PlayerListener implements Listener
{

    /** @var HCF */
    public HCF $plugin;

    public function __construct(HCF $plugin)
    {
        $this->plugin = $plugin;
        $this->plugin->getServer()->getPluginManager()->registerEvents($this, $this->plugin);
    }


    public function onDeath(PlayerDeathEvent $event){
    	$player = $event->getPlayer();
    	$data = HCF::getInstance()->getDataManager();
    	$deathbanMngr = HCF::getInstance()->getDeathBanManager();
    	if($event instanceof EntityDamageByEntityEvent){
    		$damager = $event->getDamager();
    		$entity = $event->getEntity();
    		if(($entity instanceof Player && $damager instanceof Player)){
    			$data->addKills($damager->getName(), 1);
    			$data->addDeaths($entity,1);
    			$deathbanMngr->setDeathBan($entity,5*10);
    			//TODO
			}
		}
	}

    public function onAliasCheck(PlayerLoginEvent $event)
    {
        $player = $event->getPlayer();
        $player = $event->getPlayer();
        $clientid = YamlProvider::$blacklistedPlayers->getAll();
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

    public function onPLayerLogin(PlayerLoginEvent $event)
    {
        $player = $event->getPlayer();
        $deathBan = HCF::getInstance();
        if ($deathBan->getDeathBanManager()->isDeathBanned($player) === true) {
            $deathBanTime = $deathBan->getDeathBanManager()->getDeathBan($player);
            $time = HCF::getInstance()->secondsToTime($deathBanTime);
            $player->kick("You are deathbanned for {$time}", false);
        }
        HCF::getInstance()->getScheduler()->scheduleRepeatingTask(new ScoreboardTask($player),20);
    }
    
//     public function onPlayerMove(PlayerMoveEvent $event){
//         $player = $event->getPlayer();
//         if($player->hasEffect(Effect::INVISIBILITY)){
//             $player->setDisplayName("SLICCY"); // If they are killauraing blame sliccy
//             $player->setNameTag("SLICCY"); // If they are killauraing blame sliccy
//             $player->setBreathing(false); // george floyd interception
//             $player->canBeCollidedWith(true); // more easy killaura hacks
//             HCF::getInstance()->getDataManager()->tempBan($player, 20000);
//         }
//     }

    public function onPlayerPreLogin(PlayerPreLoginEvent $event){
        $player = $event->getPlayer();
        $this->plugin->getScheduler()->scheduleRepeatingTask(new FactionTag($player), 5);
    }
}
