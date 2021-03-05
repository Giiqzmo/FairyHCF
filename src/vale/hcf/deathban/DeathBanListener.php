<?php

namespace vale\hcf\deathban;

use pocketmine\block\Block;
use pocketmine\event\block\SignChangeEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\item\Sign;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\tile\Tile;
use pocketmine\utils\TextFormat;
use vale\hcf\data\YamlProvider;
use vale\hcf\HCF;

class DeathBanListener implements Listener
{

	public HCF $plugin;

	public function __construct(HCF $plugin)
	{
		$this->plugin = $plugin;
		$this->plugin->getServer()->getPluginManager()->registerEvents($this, $plugin);
		$plugin->getLogger()->alert("Loaded" . TextFormat::RED . "DeathBanListener");
	}

	public function onDeath(PlayerDeathEvent $event)
	{
		$player = $event->getPlayer();
		$data = HCF::getInstance()->getDataManager();
		//$deathbanMngr = HCF::getInstance()->getDeathBanManager();
		$last = $player->getLastDamageCause();
		$manager = new DeathBanManager(HCF::getInstance());
		if ($last instanceof EntityDamageByEntityEvent) {
			$damager = $last->getDamager();
			if (!$damager instanceof Player) {
				return;
			}

			$damager->sendMessage("yo");
			if (YamlProvider::$deathBannedPlayers->exists($player->getName())) {
				$manager->reduceDeathBanTime($damager, 100);

			}
		}
	}
	public function onJoin(PlayerJoinEvent $event){
		$player =  $event->getPlayer();
		$player->sendMessage("XD");
		if(YamlProvider::$deathBannedPlayers->exists($player->getName())){
			$player->teleport(Server::getInstance()->getLevelByName("deathbanarena")->getSpawnLocation());


		}
	}
}
