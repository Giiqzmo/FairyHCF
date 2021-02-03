<?php

namespace vale\hcf;
#Will use sessions instead of this gay data saving method
#Please Put ALL UPDATES IN #READ.MD

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\Config;
use vale\hcf\cmds\mod\BlacklistCommand;
use vale\hcf\cmds\mod\WarnCommand;
use vale\hcf\events\PlayerListener;
use vale\hcf\manager\DeathBanManager;
use vale\hcf\manager\DataManager;
use vale\hcf\manager\tasks\DeathbanTask;

class HCF extends PluginBase implements Listener
{
	/** @var Config $warns */
	public static Config $warns;
	/** @var Config $lives */
	public static Config $lives;
	/** @var Config $deaths */
	public static Config $deaths;
	/** @var Config $kills */
	public static Config $kills;
	/** @var HCF $instance */
	public static HCF $instance;
	/** @var DataManager $dataManager */
	public static DataManager $dataManager;
	public static Config $blacklistedPlayers;
	/** @var Config $deathBannedPlayers */
	public static Config $deathBannedPlayers;
	public static DeathBanManager $deathBanManager;
    public static PlayerListener $playerListener;
    /** @var string[] $worlds */
	public array $worlds = ["test", "uh", "ok"];

	public function onEnable(): void
	{
		self::$instance = $this;
		$this->initDatabase();
		$this->loadWorlds();
		$this->loadCommands();
		$this->initListeners();
        // someone move this i dont fell like doing it
        $this->getScheduler()->scheduleRepeatingTask(new DeathbanTask($this), 20);
	}

	function loadCommands()
	{
		$map = $this->getServer()->getCommandMap();
		$map->registerAll("HCF", [
			new BlacklistCommand($this),
			new WarnCommand($this),
		]);

	}

	function loadWorlds()
	{
		$wrldcount = count($this->worlds);
		for ($i = 0; $i < $wrldcount; $i++) {
			$this->getServer()->loadLevel($this->worlds[$i]);
			$this->getServer()->getLogger()->info("Preparing the worlds " . (string)$this->worlds[$i]);
		}
	}

	function initDatabase()
	{
		self::$lives = new Config($this->getDataFolder() . "lives.yml");
		self::$deaths = new Config($this->getDataFolder() . "deaths.yml");
		self::$kills = new Config($this->getDataFolder() . "kills.yml");
		self::$warns = new Config($this->getDataFolder() . "warns.yml");
		self::$deathBannedPlayers = new Config($this->getDataFolder(). "deathbannedplayers.yml");
		self::$blacklistedPlayers = new Config($this->getDataFolder() . "blacklistedplayers.yml");
		self::$dataManager = new DataManager($this);
		self::$deathBanManager = new DeathBanManager();
	}

	function initListeners(){
		new PlayerListener($this);
	}

	public function getWarnData(): Config
	{
		return self::$warns;
	}

	public function getKillsData(): Config
	{
		return self::$kills;
	}

	public function getDeathsData(): Config
	{
		return self::$deaths;
	}


	/**
	 * @return Config
	 */

	public function getLivesData(): Config{
		return self::$lives;
	}

	/**
	 * @return Config
	 */
	public function getDeathBannedData(): Config
	{
		return self::$deathBannedPlayers;
	}

	public function getDeathBanManager(): DeathBanManager
	{
		return self::$deathBanManager;
	}

	public function getDataManager(): DataManager
	{
		return self::$dataManager;
	}

	public static function getInstance(): HCF
	{
		return self::$instance;
	}

	public function secondsToTime(int $secs) {
#from php.net
		$s = $secs%60;
		$m = floor(($secs%3600)/60);
		$h = floor(($secs%86400)/3600);
		$d = floor(($secs%2592000)/86400);
		$M = floor($secs/2592000);

		return "$d days $h hours $m minutes $s seconds";
	}
}
