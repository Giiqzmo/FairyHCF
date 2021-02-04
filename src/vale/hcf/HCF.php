<?php

namespace vale\hcf;
#Will use sessions instead of this gay data saving method
#Please Put ALL UPDATES IN #READ.MD

use muqsit\invmenu\InvMenuHandler;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use SQLite3;
use vale\hcf\cmds\FactionCommand;
use vale\hcf\cmds\mod\BlacklistCommand;
use vale\hcf\cmds\mod\WarnCommand;
use vale\hcf\events\CrateListener;
use vale\hcf\events\PlayerListener;
use vale\hcf\factions\FactionLoader;
use vale\hcf\manager\DataManager;
use vale\hcf\manager\DeathBanManager;
use vale\hcf\manager\tasks\DeathbanTask;

class HCF extends PluginBase
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
    
    public static Config $crateData;
    
    public static SQLite3 $factionData;
    
    public static FactionLoader $factionManager;
    /** @var string[] $worlds */
    public array $worlds = ["test", "uh", "ok"];

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
        $this->initDatabase();
        $this->loadWorlds();
        $this->loadCommands();
        $this->initListeners();
        $this->getScheduler()->scheduleRepeatingTask(new DeathbanTask($this), 20);
    }

    function initDatabase()
    {
        self::$factionManager = new FactionLoader($this);
        @mkdir($this->getDataFolder() . "lives");
        @mkdir($this->getDataFolder() . "deaths");
        @mkdir($this->getDataFolder() . "crates");
        @mkdir($this->getDataFolder() . "warns");
        @mkdir($this->getDataFolder() . "kills");
        self::$lives = new Config($this->getDataFolder() . "lives/" . "lives.yml");
        self::$deaths = new Config($this->getDataFolder() . "deaths/" . "deaths.yml");
        self::$kills = new Config($this->getDataFolder() . "kills/" . "kills.yml");
        self::$warns = new Config($this->getDataFolder() . "warns/" . "warns.yml");
        self::$crateData = new Config($this->getDataFolder() . "crates/" . "CrateData.yml");
        self::$deathBannedPlayers = new Config($this->getDataFolder() . "deathbannedplayers.yml");
        self::$blacklistedPlayers = new Config($this->getDataFolder() . "blacklistedplayers.yml");
        self::$dataManager = new DataManager($this);
        self::$deathBanManager = new DeathBanManager();
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
        ]);

    }

    function initListeners()
    {
        new PlayerListener($this);
        new CrateListener($this);
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

    public function getLivesData(): Config
    {
        return self::$lives;
    }

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

    public function getFactionManager(): FactionLoader
    {
        return self::$factionManager;
    }

    public function secondsToTime(int $secs)
    {
        $s = $secs % 60;
        $m = floor(($secs % 3600) / 60);
        $h = floor(($secs % 86400) / 3600);
        $d = floor(($secs % 2592000) / 86400);
        $M = floor($secs / 2592000);

        return "$d days $h hours $m minutes $s seconds";
    }

    public function getFactionData(): SQLite3
    {
        return self::$factionData;
    }
}
