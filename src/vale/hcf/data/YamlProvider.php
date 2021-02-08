<?php

declare(strict_types = 1);

namespace vale\hcf\data;


use pocketmine\{Server, Player};
use pocketmine\utils\Config;
use vale\hcf\HCF;
use vale\hcf\manager\{
	DataManager, DeathBanManager, CrateManager, RanksManager
};


class YamlProvider
{

	/** @var Config $config * */
	public static $config;
	/** @var array $folders * */
	public static array $folders = ["kits", "lives", "deaths", "crates", "warns", "kills"];
	/** @var DataManager $dataManager */
	public static DataManager $dataManager;

	public static Config $blacklistedPlayers;

	/** @var Config $deathBannedPlayers */
	public static Config $deathBannedPlayers;
	/** @var Config $warns */
	public static Config $warns;

	public static Config $crateData;
	/** @var Config $lives */
	public static Config $lives;

	/** @var Config $deaths */
	public static Config $deaths;

	/** @var Config $kills */
	public static Config $kills;
	public static DeathBanManager $deathBanManager;


	public static function __initiateRegistration(): void
	{
		@mkdir(HCF::getInstance()->getDataFolder());
		foreach (self::$folders as $folder) {
			@mkdir(HCF::getInstance()->getDataFolder() . $folder);
		}
		self::$lives = new Config(HCF::getInstance()->getDataFolder() . "lives/" . "lives.yml");
		self::$deaths = new Config(HCF::getInstance()->getDataFolder() . "deaths/" . "deaths.yml");
		self::$kills = new Config(HCF::getInstance()->getDataFolder() . "kills/" . "kills.yml");
		self::$warns = new Config(HCF::getInstance()->getDataFolder() . "warns/" . "warns.yml");
		self::$deathBannedPlayers = new Config(HCF::getInstance()->getDataFolder() . "deathbannedplayers.yml");
		self::$blacklistedPlayers = new Config(HCF::getInstance()->getDataFolder() . "blacklistedplayers.yml");
		self::$dataManager = new DataManager(HCF::getInstance());
		self::$deathBanManager = new DeathBanManager();
		self::$config = new Config(HCF::getInstance()->getDataFolder() . "serverprefrences.yml", Config::YAML);
		HCF::getInstance()->saveResource("serverprefrences.yml");
	}
}
