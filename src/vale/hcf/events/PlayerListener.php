<?php

namespace vale\hcf\events;

use pocketmine\entity\Entity;
use pocketmine\entity\Human;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\event\server\DataPacketSendEvent;
use pocketmine\level\Position;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\AddActorPacket;
use pocketmine\network\mcpe\protocol\LoginPacket;
use pocketmine\network\mcpe\protocol\ProtocolInfo;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use vale\hcf\data\YamlProvider;
use vale\hcf\deathban\DeathBanManager;
use vale\hcf\factions\FactionLoader;
use vale\hcf\HCF;
use vale\hcf\manager\DataManager;
use vale\hcf\manager\tasks\FactionTagTask;
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


	public function onRespawn(PlayerRespawnEvent $event){
		$player = $event->getPlayer();
		$mng = new DeathBanManager(HCF::getInstance());
			$level = Server::getInstance()->getLevelByName("deathbanarena");
			$player->teleport(Server::getInstance()->getLevelByName("deathbanarena")->getSpawnLocation());
		}



	public function onDeath(PlayerDeathEvent $event)
	{
		      $player = $event->getEntity();
			if ($player instanceof Player) {
				$mnr = new DeathBanManager(HCF::getInstance());
				$mnr->setDeathBan($player, 20);

			}
		}

    public function onPLayerLogin(PlayerLoginEvent $event)
    {
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

        HCF::getInstance()->getScheduler()->scheduleRepeatingTask(new ScoreboardTask($player),20);
		$this->plugin->getScheduler()->scheduleRepeatingTask(new FactionTagTask($player),20);

	}

    public function chatFormat(PlayerChatEvent $event){
        $player = $event->getPlayer();
        $faction = $this->plugin->getFactionManager()->getPlayerFaction($player->getName());
        $message = $event->getMessage();
        $isInFaction = $this->plugin->getFactionManager()->isInFaction($player->getName());
        if($isInFaction){
            $event->setFormat("§6[§e{$faction}§6] §f{$player->getName()}§7: §f{$message}");
        }else{
            $event->setFormat("§f{$player->getName()}§7: §f{$message}");
        }
    }
}
