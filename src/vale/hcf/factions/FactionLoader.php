<?php
namespace vale\hcf\factions;

use pocketmine\entity\Entity;
use pocketmine\event\Listener;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\SetActorDataPacket;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
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
	public static array $friendlyFire = [];

	public function __construct(HCF $plugin)
	{
		$this->plugin = $plugin;
		$this->factionData = new SQLite3($this->plugin->getDataFolder() . "factionData.db");
		$this->factionData->exec("CREATE TABLE IF NOT EXISTS faction (player TEXT PRIMARY KEY COLLATE NOCASE, factionname TEXT, rank TEXT);");
		$this->factionData->exec("CREATE TABLE IF NOT EXISTS home (faction TEXT PRIMARY KEY, x INT, y INT, z INT, world TEXT);");
		$this->factionData->exec("CREATE TABLE IF NOT EXISTS description (faction TEXT PRIMARY KEY, description INT)");
		$this->factionData->exec("CREATE TABLE IF NOT EXISTS dtr (faction TEXT PRIMARY KEY, dtr NUMERIC);");
		$this->factionData->exec("CREATE TABLE IF NOT EXISTS balance (faction TEXT PRIMARY KEY, balance INT)");
	}

	public function getFaction(string $name): string
	{
		$result = $this->factionData->query("SELECT * FROM faction WHERE player = '$name';");
		$array = $result->fetchArray(SQLITE3_ASSOC);
		return $array["factionname"];
	}

	public function isInFaction(string $name): bool
	{

		$result = $this->factionData->query("SELECT * FROM faction WHERE player = '$name';");
		$array = $result->fetchArray(SQLITE3_ASSOC);
		return empty($array) == false;
	}

	public function getPlayerFaction($player)
	{
		$faction = $this->factionData->query("SELECT factionname FROM faction WHERE player = '$player'");
		$factionArray = $faction->fetchArray(SQLITE3_BOTH);
		return $factionArray['factionname'] ?? "N/A";
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
		return "Not Set";
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
		return $factionArray['dtr'] ?? 0;
	}

	public function setDTR(string $name, int $amount)
	{
		$faction = $this->factionData->prepare("INSERT OR REPLACE INTO dtr (faction, dtr) VALUES (:faction, :dtr);");
		$faction->bindValue(":faction", $name);
		$faction->bindValue(":dtr", $amount);
		$faction->execute();
	}

	public function addDTR(string $factionName, int $amount)
	{
		$faction = $this->factionData->prepare("INSERT OR REPLACE INTO dtr (faction, dtr) VALUES (:faction, :dtr);");
		$faction->bindValue(":faction", $factionName);
		$faction->bindValue(":dtr", $this->getFactionDTR($factionName) + $amount);
		$faction->execute();
	}

	public function removeDTR(string $factionName, int $amount)
	{
		$faction = $this->factionData->prepare("INSERT OR REPLACE INTO dtr (faction, dtr) VALUES (:faction, :dtr);");
		$faction->bindValue(":faction", $factionName);
		$faction->bindValue(":dtr", $this->getFactionDTR($factionName) - $amount);
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

	public function deleteFaction(string $name)
	{
		$this->factionData->query("DELETE FROM faction WHERE factionname = '$name'");
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


	public function getFactionLeaders(string $factionName)
	{
		$test = $this->getAllMembers($factionName);
		$list = [];
		foreach ($test as $tests) {
			if ($this->isFactionLeader($tests)) {
				$list[] = $tests;
			}
		}
		return count($list) < 1 ? "None" : implode(", ", $list);
	}

	public function getFactionCoLeader(string $factionName)
	{
		$test = $this->getAllMembers($factionName);
		$list = [];
		foreach ($test as $tests) {
			if ($this->isFactionCoLeader($tests)) {
				$list[] = $tests;
			}
		}
		return count($list) < 1 ? "None" : implode(", ", $list);
	}

	public function getFactionCaptains(string $factionName)
	{
		$test = $this->getAllMembers($factionName);
		$list = [];
		foreach ($test as $tests) {
			if ($this->isFactionCaptain($tests)) {
				$list[] = $tests;
			}
		}
		return count($list) < 1 ? "None" : implode(", ", $list);
	}

	public function getFactionMembers(string $factionName)
	{
		$test = $this->getAllMembers($factionName);
		$list = [];
		foreach ($test as $tests) {
			if ($this->isFactionMember($tests)) {
				$list[] = $tests;
			}
		}
		return count($list) < 1 ? "None" : implode(", ", $list);
	}

	public function addMember(string $factionName, $player)
	{
		$faction = $this->factionData->prepare("INSERT or REPLACE INTO faction (player, factionname, rank) VALUES (:player, :factionname, :rank)");
		$faction->bindValue(":player", $player);
		$faction->bindValue(":factionname", $factionName);
		$faction->bindValue(":rank", "Member");
		$faction->execute();
	}

	public function addInvite($player, string $faction, $inviter)
	{
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
		$faction = $this->factionData->query("SELECT * FROM invite WHERE player = '$player'");
		$factionArray = $faction->fetchArray(SQLITE3_ASSOC);
		if (empty($factionArray)) {
			$player->sendMessage("§cYou arent invited to any factions");
			return false;
		}
		return true;
	}

	public function declineInvite($player)
	{

		$faction = $this->factionData->query("SELECT * FROM invite WHERE player = '$player'");
		$factionArray = $faction->fetchArray(SQLITE3_ASSOC);
		if (empty($factionArray)) {
			$player->sendMessage("§cYou arent invited to any factions");
			return false;
		}
		$timeInvited = $factionArray['timestamp'];
		$currentTime = time();
		if ($currentTime - $timeInvited <= 60) {
			$factionName = $factionArray['factionname'];
			$this->factionData->query("DELETE FROM invite WHERE player = '$player'");
			$player->sendMessage("§eYou have successfully declined §a{$factionName}");
		} else {
			$player->sendMessage("§cThe invite you have received timed out.");
			$this->factionData->query("DELETE FROM invite WHERE player = '$player'");
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
			$this->factionData->query("DELETE FROM invite WHERE player = '$player'");
			$player->sendMessage("§eYou have successfully joined §a{$factionName}");
		} else {
			$player->sendMessage("§cThe invite you have received timed out.");
			$this->factionData->query("DELETE FROM invite WHERE player = '$player'");
		}
		return true;
	}

	public static function hasFriendlyFireEnabled(Player $player): bool
	{
		return isset(self::$friendlyFire[$player->getName()]);
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
			} else {
				$pk = new SetActorDataPacket();
				$pk->metadata = [Entity::DATA_NAMETAG => [Entity::DATA_TYPE_STRING, "§c" . $onlinePlayer->getName()]];
				$pk->entityRuntimeId = $onlinePlayer->getId();
				$player->dataPacket($pk);
			}
		}
	}

	public function setFriendlyFire(Player $player)
	{
		$fac = $this->getFacByString($player->getName());
		HCF::getInstance()->getScheduler()->scheduleRepeatingTask(new FriendlyFireTask($player, $fac), 20);
	}

	public function focus(Player $recipent, Player $target)
	{
		$name = $target->getName();
		$recipent->sendData($target, [Entity::DATA_NAMETAG => [Entity::DATA_TYPE_STRING, TextFormat::LIGHT_PURPLE . "{$name}" . "\n" . ""]]);
		$this->plugin->getScheduler()->scheduleRepeatingTask(new FactionFocusTask($recipent, $target),20);

	}
}
