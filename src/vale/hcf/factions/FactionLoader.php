<?php

namespace vale\hcf\factions;

use pocketmine\event\Listener;
use pocketmine\math\Vector3;
use pocketmine\Player;
use SQLite3;
use vale\hcf\HCF;

class FactionLoader
{
    /** @var SQLite3 $factionData */
    public SQLite3 $factionData;

    /** @var HCF $plugin */
    public HCF $plugin;

    /** @var array $factionChat */
    public array $factionChat = [];

    public $dtrFreeze;

    /** @var array $friendlyFire */
    public array $friendlyFire = [];

    public function __construct(HCF $plugin)
    {
        $this->plugin = $plugin;
        $this->factionData = new SQLite3($this->plugin->getDataFolder() . "factionData.db");
        $this->factionData->exec("CREATE TABLE IF NOT EXISTS faction (player TEXT PRIMARY KEY COLLATE NOCASE, factionname TEXT, rank TEXT);");
        $this->factionData->exec("CREATE TABLE IF NOT EXISTS home (faction TEXT PRIMARY KEY, x INT, y INT, z INT, world TEXT);");
        $this->factionData->exec("CREATE TABLE IF NOT EXISTS description (faction TEXT PRIMARY KEY, description INT)");
        $this->factionData->exec("CREATE TABLE IF NOT EXISTS dtr (faction TEXT PRIMARY KEY, dtr DOUBLE);");
        $this->factionData->exec("CREATE TABLE IF NOT EXISTS balance (faction TEXT PRIMARY KEY, balance INT)");
    }


    public function isInFaction($player): bool
    {
        $faction = $this->factionData->query("SELECT factionname from faction WHERE player = '$player'");
        $factionArray = $faction->fetchArray(SQLITE3_ASSOC);
        if ($factionArray === null) {
            return false;
        }
        return true;
    }

    public function getPlayerFaction($player)
    {
        $faction = $this->factionData->query("SELECT factionname FROM faction WHERE player = '$player'");
        $factionArray = $faction->fetchArray(SQLITE3_BOTH);
        return $factionArray['factionname'];
    }

    public function setHome(string $faction, Vector3 $x, Vector3 $y, Vector3 $z, string $world)
    {
        $faction = $this->factionData->prepare("INSERT OR REPLACE INTO home (faction, x, y, z, world) VALUES (:faction, :x, :y, :z, :world)");
        $faction->bindValue(":faction", $faction);
        $faction->bindValue(":x", $x);
        $faction->bindValue(":y", $y);
        $faction->bindValue(":z", $z);
        $faction->bindValue(":world", $world);
        $faction->execute();
    }

    public function getHome(string $faction)
    {
        if ($this->playerFactionExists($faction)) {
            $faction = $this->factionData->query("SELECT * FROM home WHERE faction = '$faction' ");
            $factionArray = $faction->fetchArray(SQLITE3_ASSOC);
            if (empty($factionArray)) {
                return "Not set";
            }
            return $factionArray;
        }
        return null;
    }

    public function deleteHome(string $faction, Vector3 $x, Vector3 $y, Vector3 $z, string $world)
    {
        $faction = $this->factionData->prepare("DELETE FROM home WHERE (faction = :faction, x = :x, y = :y, z = :z, world = :world)");
        $faction->bindValue(":faction", $faction);
        $faction->bindValue(":x", $x);
        $faction->bindValue(":y", $y);
        $faction->bindValue(":z", $z);
        $faction->bindValue(":world", $world);
    }

    public function getFactionDTR(string $faction): int
    {
        $faction = $this->factionData->query("SELECT dtr FROM dtr WHERE faction = '$faction';");
        $factionArray = $faction->fetchArray(SQLITE3_ASSOC);
        return $factionArray['dtr'];
    }

    public function setDTR(string $name, int $amount)
    {
        $faction = $this->factionData->prepare("INSERT OR REPLACE INTO dtr (faction, dtr) VALUES (:faction, :dtr);");
        $faction->bindValue(":faction", $name);
        $faction->bindValue(":dtr", $amount);
        $faction->execute();
    }

    public function addDTR(string $faction, int $amount)
    {
        $faction = $this->factionData->prepare("INSERT OR REPLACE INTO dtr (faction, dtr) VALUES (:faction, :dtr);");
        $faction->bindValue(":faction", $faction);
        $faction->bindValue(":dtr", $this->getFactionDTR($faction) + $amount);
        $faction->execute();
    }

    public function removeDTR(string $faction, int $amount)
    {
        $faction = $this->factionData->prepare("INSERT OR REPLACE INTO dtr (faction, dtr) VALUES (:faction, :dtr);");
        $faction->bindValue(":faction", $faction);
        $faction->bindValue(":dtr", $this->getFactionDTR($faction) - $amount);
        $faction->execute();
    }

    public function isFactionLeader($player): bool
    {
        $faction = $this->factionData->query("SELECT rank FROM faction WHERE player ='$player';");
        $factionArray = $faction->fetchArray(SQLITE3_ASSOC);
        $factionRank = $factionArray["rank"];
        if ($factionRank === "Leader") {
            return true;
        } else {
            return false;
        }
    }

    public function isFactionCoLeader($player): bool
    {
        $faction = $this->factionData->query("SELECT rank FROM faction WHERE player ='$player';");
        $factionArray = $faction->fetchArray(SQLITE3_ASSOC);
        $factionRank = $factionArray["rank"];
        if ($factionRank === "Co-Leader") {
            return true;
        } else {
            return false;
        }
    }

    public function isFactionCaptain($player): bool
    {
        $faction = $this->factionData->query("SELECT rank FROM faction WHERE player ='$player';");
        $factionArray = $faction->fetchArray(SQLITE3_ASSOC);
        $factionRank = $factionArray["rank"];
        if ($factionRank === "Captain") {
            return true;
        } else {
            return false;
        }
    }

    public function isFactionMember($player): bool
    {
        $faction = $this->factionData->query("SELECT rank FROM faction WHERE player ='$player';");
        $factionArray = $faction->fetchArray(SQLITE3_ASSOC);
        $factionRank = $factionArray["rank"];
        if ($factionRank === "Member") {
            return true;
        } else {
            return false;
        }
    }

    public function createFaction(string $name, $player)
    {
        $faction = $this->factionData->prepare("INSERT OR REPLACE INTO faction (player, factionname, rank) VALUES (:player, :factionname, :rank)");
        $faction->bindValue(":player", $player->getName());
        $faction->bindValue(":factionname", $name);
        $faction->bindValue(":rank", "Leader");
        $faction->execute();
        $this->setDTR($name, 1.05);
    }

    public function deleteFaction(string $name, $player)
    {
        $faction = $this->factionData->prepare("DELETE faction (player, factionname, rank) VALUES (:player, :factionname, :rank)");
        $faction->bindValue(":player", $player->getName());
        $faction->bindValue(":factionname", $name);
        $faction->bindValue(":rank", "Leader");
        $faction->execute();
    }

    public function playerFactionExists(string $faction): bool
    {
        $factionName = strtolower($faction);
        $faction = $this->factionData->query("SELECT player FROM faction WHERE lower(factionname) = '$factionName';");
        $factionArray = $faction->fetchArray(SQLITE3_ASSOC);
        if (empty($factionArray)) {
            return false;
        }
        return true;
    }

    public function getFacByString(string $faction)
    {
        $factionName = strtolower($faction);
        if ($this->playerFactionExists($factionName)) {
            return $factionName;
        }
        return false;
    }

    public function getAllMembers(string $faction): array
    {
        $players = [];
        $faction = $this->factionData->query("SELECT player FROM faction WHERE factionname = '$faction';");
        while ($factionArray = $faction->fetchArray(SQLITE3_ASSOC)) {
            $players[] = $factionArray["player"];
        }
        return $players;
    }


    public function getFactionLeaders(string $factionName){
        $test = $this->getAllMembers($factionName);
        $list = [];
        foreach($test as $tests){
            if($this->isFactionLeader($tests)){
                $list[] = $tests;
            }
        }
        return count($list) < 1 ? "None" : implode(", ", $list);
    }

    public function getFactionCoLeader(string $factionName){
        $test = $this->getAllMembers($factionName);
        $list = [];
        foreach($test as $tests){
            if($this->isFactionCoLeader($tests)){
                $list[] = $tests;
            }
        }
        return count($list) < 1 ? "None" : implode(", ", $list);
    }

    public function getFactionCaptains(string $factionName){
        $test = $this->getAllMembers($factionName);
        $list = [];
        foreach($test as $tests){
            if($this->isFactionCaptain($tests)){
                $list[] = $tests;
            }
        }
        return count($list) < 1 ? "None" : implode(", ", $list);
    }

    public function getFactionMembers(string $factionName){
        $test = $this->getAllMembers($factionName);
        $list = [];
        foreach($test as $tests){
            if($this->isFactionMember($tests)){
                $list[] = $tests;
            }
        }
        return count($list) < 1 ? "None" : implode(", ", $list);
    }

    public function addMember(string $factionName, $player){
        $faction = $this->factionData->prepare("INSERT or REPLACE INTO faction (player, factionname, rank) VALUES (:player, :factionname, :rank)");
        $faction->bindValue(":player", $player);
        $faction->bindValue(":factionname", $factionName);
        $faction->bindValue(":rank", "Member");
        $faction->execute();
    }

    public function hasFriendlyFireEnabled(Player $player): bool
    {
        return isset($this->friendlyFire[$player->getName()]);
    }


	public function showFactionPlayersTag(Player $player)
		{
			foreach ($this->plugin->getServer()->getOnlinePlayers() as $onlinePlayer) {
				if ($this->isInFaction($onlinePlayer)) {
					$fac = $this->getPlayerFaction($player->getName());
					foreach ($this->getAllMembers($fac) as $facMember) {
						$pk = new SetActorDataPacket();
						$pk->metadata = [Entity::DATA_NAMETAG => [Entity::DATA_TYPE_STRING, "§a" . $facMember->getName()]];
						$pk->entityRuntimeId = $onlinePlayer->getId();
						$player->dataPacket($pk);
					}
				}else{
					$pk = new SetActorDataPacket();
					$pk->metadata = [Entity::DATA_NAMETAG => [Entity::DATA_TYPE_STRING, "§c" . $onlinePlayer->getName()]];
					$pk->entityRuntimeId = $onlinePlayer->getId();
					$player->dataPacket($pk);
				}
			}
		}
	public function setFriendlyFire(Player $player){
		if(isset($this->friendlyFire[$player->getName()])){
			unset($this->friendlyFire[$player->getName()]);
		}else{
			array_push($this->friendlyFire, $player->getName());
		}
	}
}
