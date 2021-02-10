<?php

namespace vale\hcf\manager;

use vale\hcf\HCF;

class DataManager
{

	public \SQLite3 $database;


	public HCF $plugin;

	public function __construct(HCF $plugin)
	{
		$this->plugin = $plugin;
		$this->database = new \SQLite3($plugin->getDataFolder() . "data.db");
		$this->database->exec("CREATE TABLE IF NOT EXISTS kills(player TEXT PRIMARY KEY COLLATE NOCASE, kills INT)");
		$this->database->exec("CREATE TABLE IF NOT EXISTS deaths(player TEXT PRIMARY KEY COLLATE NOCASE, deaths INT)");
		$this->database->exec("CREATE TABLE IF NOT EXISTS warns(player TEXT PRIMARY KEY COLLATE NOCASE, warns INT)");
	}

	/**
	 * @param string $playername
	 * @return int|mixed
	 */

	public function getKills(string $playername)
	{
		$db = $this->database;
		$db = $db->query("SELECT player FROM kills WHERE player = '$playername';");
		$dbArray = $db->fetchArray(SQLITE3_ASSOC);
		return $dbArray["kills"] ?? 0;
	}

	/**
	 * @param string $playername
	 * @param int $value
	 */

	public function addKills(string $playername, int $value){
		$db = $this->database;
		$kills = $this->getKills($playername) + $value;
		$db = $db->prepare("INSERT OR REPLACE INTO kills (player, kills) VALUES ('$playername',  $kills);");
		$db->bindValue(":player", $playername);
		$db->bindValue(":kills", $this->getKills($playername) + $value);
		$db->execute();
	}

	/**
	 * @param string $playername
	 * @param int $value
	 */

	public function setKills(string $playername, int $value){
		$db = $this->database;
		$db->exec("UPDATE kills SET kills = '$value' WHERE player = '$playername'");
	}

	/**
	 * @param string $playername
	 * @return int|mixed
	 */

	public function getDeaths(string $playername)
	{
		$db = $this->database;
		$db = $db->query("SELECT player FROM deaths WHERE player = '$playername';");
		$dbArray = $db->fetchArray(SQLITE3_ASSOC);
		return $dbArray["deaths"] ?? 0;
	}

	/**
	 * @param string $playername
	 * @param int $value
	 */

	public function setDeaths(string $playername, int $value){
		$db = $this->database;
		$db->exec("UPDATE deaths SET deaths = '$value' WHERE player = '$playername'");
	}

	/**
	 * @param string $playername
	 * @param int $value
	 */
	public function addDeaths(string $playername, int $value){
		$db = $this->database;
		$deaths = $this->getDeaths($playername) + $value;
		$db = $db->prepare("INSERT OR REPLACE INTO deaths(player, deaths) VALUES ('$playername',  $deaths);");
		$db->bindValue(":player", $playername);
		$db->bindValue(":deaths", $this->getDeaths($playername) + $value);
		$db->execute();
	}

	/**
	 * @param string $playername
	 * @return int|mixed
	 */

	public function getWarns(string $playername)
	{
		$db = $this->database;
		$db = $db->query("SELECT player FROM warns WHERE player = '$playername';");
		$dbArray = $db->fetchArray(SQLITE3_ASSOC);
		return $dbArray["warns"] ?? 0;
	}

	/**
	 * @param string $playername
	 * @param int $value
	 */

	public function setWarns(string $playername, int $value){
		$db = $this->database;
		$db->exec("UPDATE warns SET warns = '$value' WHERE player = '$playername'");
	}

	/**
	 * @param string $playername
	 * @param int $value
	 */
	public function addWarns(string $playername, int $value){
		$db = $this->database;
		$warns = $this->getWarns($playername) + $value;
		$db = $db->prepare("INSERT OR REPLACE INTO warns (player, warns) VALUES ('$playername',  $warns);");
		$db->bindValue(":player", $playername);
		$db->bindValue(":warns", $this->getWarns($playername) + $value);
		$db->execute();
	}
}
