<?php


namespace vale\hcf\manager;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\network\mcpe\protocol\LevelChunkPacket;
use pocketmine\Player;
use vale\hcf\HCF;

class DataManager implements Listener
{ 
	public $plugin;

	public function __construct(HCF $plugin)
	{
		$this->plugin = $plugin;
	}

	public function addKill(Player $player, int $value)
	{
		if ($this->KillsDataExist($player)) {
			HCF::getInstance()->getKillsData()->set($player->getName(), HCF::$kills->get($player->getName(), +(int)$value));
			HCF::getInstance()->getKillsData()->save();
		}
	}

	public function getKills(Player $player)
	{
		$kills = HCF::getInstance()->getKillsData()->get($player->getName());
		return $kills;
	}

	public function setKills(Player $player, int $value)
	{
		HCF::getInstance()->getKillsData()->set($player->getName(), (int)$value);
	}

	public function KillsDataExist(Player $player): bool
	{
		if (HCF::$kills->exists($player->getName())) {
			return true;
		} else {
			return false;
		}
	}

	public function listTopKills(Player $player)
	{
		$kills = HCF::getInstance()->getKillsData()->getAll();
		asort($kills);
		foreach ($kills as $p => $kill) {
			$i = 0;
			if ($i < 10 && $kills) {
				$i++;
				switch ($i) {
					case 1:
						#1
						break;
					case 2:
						#2
						break;
					case 3:
						#3
						break;
					case 4:
						#4:
						break;

				}
			}
		}
	}

	public function addDeath(Player $player, int $value)
	{
		if ($this->DeathsDataExist($player)) {
			HCF::getInstance()->getDeathsData()->set($player->getName(), HCF::$deaths->get($player->getName(), +(int)$value));
			HCF::getInstance()->getDeathsData()->save();
		}
	}

	public function getDeaths(Player $player)
	{
		$deaths = HCF::getInstance()->getDeathsData()->get($player->getName());
		return (int)$deaths;
	}

	public function setDeaths(Player $player, int $value)
	{
		HCF::getInstance()->getDeathsData()->set($player->getName(), (int)$value);
	}

	public function DeathsDataExist(Player $player): bool
	{
		if (HCF::$deaths->exists($player->getName())) {
			return true;
		} else {
			return false;
		}
	}

	public function listTopDeaths(Player $player)
	{
		$deaths = HCF::getInstance()->getDeathsData()->getAll();
		asort($deaths);
		foreach ($deaths as $p => $kill) {
			$i = 0;
			if ($i < 10 && $deaths) {
				$i++;
				switch ($i) {
					case 1:
						#1
						break;
					case 2:
						#2
						break;
					case 3:
						#3
						break;
					case 4:
						#4:
						break;

				}
			}
		}
	}

	public function setBlacklisted(Player $player)
	{
		$id = $player->getClientId();
		if (!HCF::$blacklistedPlayers->exists($player->getName())) {
			HCF::$blacklistedPlayers->set($player->getName(), $player->getClientId());
			$player->setBanned(true);
			HCF::$blacklistedPlayers->save();
		}
	}

	public function ipBan(Player $player)
	{
		//todo max u do this or jory
	}

	public function tempBan(Player $player, int $time)
	{

		//todo max u do this or jory
	}

	public function addWarn(Player $player, int $warns)
	{
		HCF::getInstance()->getWarnData()->set($player->getName(), HCF::$warns->get($player->getName()) +(int)$warns);
		HCF::$warns->save();
	}

	public function removeWarns(Player $player, int $warns)
	{
		$currentwarns = HCF::getInstance()->getWarnData()->get($player->getName());
		HCF::$warns->set((int)$currentwarns - (int)$warns);
		HCF::$warns->save();

	}

	public function getWarns(Player $player)
	{
		$warns = HCF::getInstance()->getWarnData()->get($player->getName());
		return (int)$warns;

	}

	/**
	 * @param Player $player
	 * @param int $lives
	 */
	public function setLives(Player $player, int $lives)
	{
		$data = HCF::$lives;
		$data->set($player->getName(), (int)$lives);
		$data->save();

	}

	/**
	 * @param Player $player
	 * @return int
	 */
	public function getLives(Player $player)
	{
		$data = HCF::$lives;
		$data->get($player->getName());
		return (int)$data;
	}

	/**
	 * @param Player $player
	 * @param int $value
	 */
	public function addLives(Player $player, int $value)
	{
		$currentLives = $this->getLives($player);
		$data = HCF::$lives;
		$data->set($player->getName(), $currentLives + (int)$value);
		$data->save();
	}

	/**
	 * @param Player $sender
	 * @param Player $recipient
	 * @param int $value
	 */
	public function sendLives(Player $sender, Player $recipient, int $value)
	{
		$data1 = HCF::$lives->get($sender->getName());
		$data2 = HCF::$lives->get($recipient->getName());
		if ($data1 >= 1) {
			$toset = HCF::$lives;
			$toset->set($recipient->getName(), $this->getLives($recipient) + $value);
			$toset->set($sender->getName(), $this->getLives($sender) - $value);
			$toset->save();
		}
	}

	public function softBan(Player $player)
	{
		$currentwarns = HCF::getInstance()->getWarnData()->get($player->getName());
		if ($currentwarns === 1) {
			$player->sendMessage("You have 1 warnings (3 remaning) Your warns will reset upon restart");
		}
		if ($currentwarns === 2) {
			$player->sendMessage("You have 2 warnings (1 remaning) Your warns will reset upon restart");
		}
		if ($currentwarns === 3) {
			$player->sendMessage("You will be banned shortly");
			$this->setBlacklisted($player);
			//implement messages
		}
	}

	public function unBlacklist(Player $player)
	{
		$blacklisted = HCF::$blacklistedPlayers;
		if ($blacklisted->exists($player->getName())) {
			$blacklisted->remove($player->getName());
			$blacklisted->save();
		}

	}

	public function crash(Player $player): void
	{
		$chunk = $player->getLevel()->getChunkAtPosition($player);
		$pk = LevelChunkPacket::withCache($chunk->getX(), $chunk->getZ(), 10000000, [], "");
		$player->sendDataPacket($pk);
	}
}
