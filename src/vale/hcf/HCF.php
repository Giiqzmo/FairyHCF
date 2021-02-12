<?php

namespace vale\hcf;

use pocketmine\utils\Random;
use SQLite3;
use muqsit\invmenu\InvMenuHandler;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use vale\hcf\cmds\{
	FactionCommand, SotwCommand
};

use vale\hcf\cmds\mod\{BlacklistCommand, SpawnEntityCommand, WarnCommand, CrateBlockCommand};
use vale\hcf\events\{
	CrateListener, PlayerListener
};
use vale\hcf\factions\{
	FactionLoader, FactionListener
};
use vale\hcf\manager\{DataManager, DeathBanManager, CrateManager, EntityManager, RanksManager, ScoreBoardManager, SotwManager};
use vale\hcf\manager\tasks\{DeathbanTask, BroadcastTask, FactionTag};
use vale\hcf\data\YamlProvider;
use vale\hcf\entities\PartnerPackageEntity;
use vale\hcf\items\inventory\BrewingManager;
use vale\hcf\items\ItemManager;
use vale\hcf\items\TileManager;

class HCF extends PluginBase
{

	/** @var HCF $instance */
	public static HCF $instance;

	public static SQLite3 $factionData;

	public static FactionLoader $factionManager;

     /** @var DataManager  $dataManager */
	public static DataManager $dataManager;
	public static $brewingStandsEnabled = true;

	/** @var string[] $worlds */
	public array $worlds = ["test", "uh", "ok"];

	/** @var BrewingManager */
	private $brewingManager = null;

	public static function getInstance(): HCF
	{
		return self::$instance;
	}

	public function onEnable(): void
	{
		if (!InvMenuHandler::isRegistered()) {
			InvMenuHandler::register($this);
		}
		self::$instance = $this;
		YamlProvider:: __initiateRegistration();
		self::$dataManager = new DataManager($this);
		EntityManager::registerEntites();
		ItemManager::initItems();
		TileManager::init();
		$this->brewingManager = new BrewingManager();
		$this->brewingManager->init();
		$this->initFactions();
		$this->loadWorlds();
		$this->loadCommands();
		$this->initListeners();
		$this->getScheduler()->scheduleRepeatingTask(new DeathbanTask($this), 20);
		$this->getScheduler()->scheduleRepeatingTask(new BroadcastTask($this),20);
	}

	function initFactions()
	{
		self::$factionManager = new FactionLoader($this);
		new FactionListener($this);
	}

	function loadWorlds()
	{
		$wrldcount = count($this->worlds);
		for ($i = 0; $i < $wrldcount; $i++) {
			$this->getServer()->loadLevel($this->worlds[$i]);
			$this->getServer()->getLogger()->info("Preparing the worlds " . (string)$this->worlds[$i]);
		}
	}

	function loadCommands()
	{
		$map = $this->getServer()->getCommandMap();
		$map->registerAll("HCF", [
			new BlacklistCommand($this),
			new WarnCommand($this),
			new FactionCommand($this),
			new SotwCommand(),
			new SpawnEntityCommand("spawnentity",$this)
		]);

	}

	function initListeners()
	{
		new PlayerListener($this);
		new CrateListener($this);
	}

	public function getDeathBannedData(): Config
	{
		return YamlProvider::$deathBannedPlayers;
	}

	public function getDeathBanManager(): DeathBanManager
	{
		return YamlProvider::$deathBanManager;
	}


	public function getDataManager(): DataManager{
		return self::$dataManager;
	}


	public function getFactionManager(): FactionLoader
	{
		return self::$factionManager;
	}


	public static function getTimeToFullString(Int $time) : String {
		return gmdate("H:i:s", $time);
	}

	public function getBrewingManager(): BrewingManager{
		return $this->brewingManager;
	}


	public function onDisable()
	{

	}

}
