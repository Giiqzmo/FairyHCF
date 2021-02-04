<?php

namespace vale\hcf\factions;

use pocketmine\math\Vector3;
use SQLite3;
use vale\hcf\HCF;

class FactionLoader
{


    public SQLite3 $factionData;
    public HCF $plugin;

    public function __construct(HCF $plugin)
    {
        $this->plugin = $plugin;
        $this->factionData = new SQLite3($this->plugin->getDataFolder() . "Faction.db");
        $this->factionData->exec("CREATE TABLE IF NOT EXISTS faction (player TEXT PRIMARY KEY COLLATE NOCASE, factionname TEXT, rank TEXT);");
        $this->factionData->exec("CREATE TABLE IF NOT EXISTS invite (player TEXT PRIMARY  KEY, faction TEXT, inviter TEXT, timestamp INT);");
        $this->factionData->exec("CREATE TABLE IF NOT EXISTS home (faction TEXT PRIMARY KEY, x INT, y INT, z INT, world TEXT);");
        $this->factionData->exec("CREATE TABLE IF NOT EXISTS description (faction TEXT PRIMARY KEY, description INT)");
        $this->factionData->exec("CREATE TABLE IF NOT EXISTS dtr (faction TEXT PRIMARY KEY, dtr INT);");
        $this->factionData->exec("CREATE TABLE IF NOT EXISTS balance (faction TEXT PRIMARY KEY, balance INT)");
    }


    public function isInFaction($player): bool
    {
        $player = $player->getName();
        $faction = $this->factionData->query("SELECT factionname from faction WHERE player = '$player'");
        $factionArray = $faction->fetchArray(SQLITE3_ASSOC);
        if ($factionArray === null) {
            return false;
        }
        return true;
    }

    public function getPlayerFaction($player)
    {
        $player = $player->getName();
        $faction = $this->factionData->query("SELECT factionname from faction WHERE player = '$player'");
        $factionArray = $faction->fetchArray(SQLITE3_ASSOC);
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
            return $factionArray;
        } else {
            return "Not set";
        }
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

    public function deleteHome(string $faction, Vector3 $x, Vector3 $y, Vector3 $z, string $world)
    {
        $faction = $this->factionData->prepare("DELETE FROM home WHERE (faction = :faction, x = :x, y = :y, z = :z, world = :world)");
        $faction->bindValue(":faction", $faction);
        $faction->bindValue(":x", $x);
        $faction->bindValue(":y", $y);
        $faction->bindValue(":z", $z);
        $faction->bindValue(":world", $world);
    }

    public function addDTR(string $faction, int $amount)
    {
        $faction = $this->factionData->prepare("INSERT OR REPLACE INTO dtr (faction, dtr) VALUES (:faction, :dtr);");
        $faction->bindValue(":faction", $faction);
        $faction->bindValue(":dtr", $this->getFactionDTR($faction) + $amount);
        $faction->execute();
    }

    public function getFactionDTR(string $faction)
    {
        $faction = $this->factionData->query("SELECT dtr FROM dtr WHERE faction = '$faction';");
        $factionArray = $faction->fetchArray(SQLITE3_ASSOC);
        return (int)$factionArray["dtr"];
    }

    public function removeDTR(string $faction, int $amount)
    {
        $faction = $this->factionData->prepare("INSERT OR REPLACE INTO dtr (faction, dtr) VALUES (:faction, :dtr);");
        $faction->bindValue(":faction", $faction);
        $faction->bindValue(":dtr", $this->getFactionDTR($faction) - $amount);
        $faction->execute();
    }

    public function isFactionLeader($player)
    {
        $player = $player->getName();
        $faction = $this->factionData->query("SELECT rank FROM faction WHERE player ='$player';");
        $factionArray = $faction->fetchArray(SQLITE3_ASSOC);
        if ($factionArray['rank'] === "Leader") {
            return true;
        }
        return false;
    }

    public function isFactionCoLeader($player)
    {
        $player = $player->getName();
        $faction = $this->factionData->query("SELECT rank FROM faction WHERE player ='$player';");
        $factionArray = $faction->fetchArray(SQLITE3_ASSOC);
        if ($factionArray['rank'] === "Co-Leader") {
            return true;
        }
        return false;
    }

    public function isFactionCaptain($player)
    {
        $player = $player->getName();
        $faction = $this->factionData->query("SELECT rank FROM faction WHERE player ='$player';");
        $factionArray = $faction->fetchArray(SQLITE3_ASSOC);
        if ($factionArray['rank'] === "Captain") {
            return true;
        }
        return false;
    }

    public function isFactionMember($player)
    {
        $player = $player->getName();
        $faction = $this->factionData->query("SELECT rank FROM faction WHERE player ='$player';");
        $factionArray = $faction->fetchArray(SQLITE3_ASSOC);
        $factionRank = $factionArray["rank"] == "Member";
        return $factionRank;
    }

    public function createFaction(string $name, $player)
    {
        $player = $player->getName();
        $faction = $this->factionData->prepare("INSERT OR REPLACE INTO faction (player, factionname, rank) VALUES (:player, :factionname, :rank)");
        $faction->bindValue(":player", $player);
        $faction->bindValue(":factionname", $name);
        $faction->bindValue(":rank", "Leader");
        $faction->execute();
        $this->setDTR($name, 1.12);
    }

    public function setDTR(string $name, int $amount)
    {
        $faction = $this->factionData->prepare("INSERT OR REPLACE INTO dtr (faction, dtr) VALUES (:faction, :dtr);");
        $faction->bindValue(":faction", $name);
        $faction->bindValue(":dtr", $amount);
        $faction->execute();
    }

    public function deleteFaction(string $faction)
    {
        $this->factionData->query("DELETE FROM faction WHERE factionname = '$faction'");
    }

    public function getFacByString(string $faction)
    {
        $factionName = strtolower($faction);
        if ($this->playerFactionExists($factionName)) {
            return $factionName;
        }
        return false;
    }

    public function addInvite($player, string $faction, $inviter)
    {
        $player = $player->getName();
        $inviter = $inviter->getName();
        $faction = $this->factionData->prepare("INSERT OR REPLACE INTO invite (player, faction, inviter, timestamp) VALUES (:player, :faction, :inviter, :timestamp )");
        $faction->bindValue(":player", $player);
        $faction->bindValue(":faction", $faction);
        $faction->bindValue(":inviter", $inviter);
        $faction->bindValue(":timestamp", time());
        $faction->execute();
    }

    public function acceptInvite($player)
    {
        $player = $player->getName();
        $faction = $this->factionData->query("SELECT * FROM invite WHERE player = '{$player}'");
        $factionArray = $faction->fetchArray(SQLITE3_ASSOC);
        if (empty($factionArray)) {
            $player->sendMessage("§cYou arent invited to any factions");
            return false;
        }
        $timeInvited = $factionArray['timestamp'];
        $currentTime = time();
        if ($currentTime - $timeInvited <= 60) {
            $factionName = $factionArray['factionname'];
            $faction = $this->factionData->prepare("INSERT OR REPLACE INTO faction (player, factionname, rank) VALUES (:player, :factionname, :rank)");
            $faction->bindValue(":player", $player);
            $faction->bindValue(":faction", $factionName);
            $faction->bindValue(":rank", "Member");
            $faction->execute();
            $this->factionData->query("DELETE FROM invite WHERE player = '{$player}'");
            $player->sendMessage("§eYou have successfully joined §a{$factionName}");
        } else {
            $player->sendMessage("§cThe invite you have received timed out.");
            $this->factionData->query("DELETE FROM invite WHERE player = '{$player}'");
        }
        return true;
    }

    public function declineInvite($player)
    {
        $player = $player->getName();
        $faction = $this->factionData->query("SELECT * FROM invite WHERE player = '{$player}'");
        $factionArray = $faction->fetchArray(SQLITE3_ASSOC);
        if (empty($factionArray)) {
            $player->sendMessage("§cYou arent invited to any factions");
            return false;
        }
        $timeInvited = $factionArray['timestamp'];
        $currentTime = time();
        if ($currentTime - $timeInvited <= 60) {
            $factionName = $factionArray['factionname'];
            $this->factionData->query("DELETE FROM invite WHERE player = '{$player()}'");
            $player->sendMessage("§eYou have successfully declined §a{$factionName}");
        } else {
            $player->sendMessage("§cThe invite you have received timed out.");
            $this->factionData->query("DELETE FROM invite WHERE player = '{$player()}'");
        }
        return true;
    }

}
