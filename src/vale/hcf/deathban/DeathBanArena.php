<?php

namespace vale\hcf\deathban;

use  pocketmine\level\Position;
use pocketmine\level\Level;
use pocketmine\Server;
use vale\hcf\data\YamlProvider;

class DeathBanArena
{

	public Position $spawnX;
	public Position $spawnY;
	public Position $spawnZ;
	public string $name;

	public string $world;

	public function __construct(string $name, Position $spawnX, Position $spawnY, Position $spawnZ, string $world)
	{
		$this->name = $name;
		$this->spawnX = $spawnX;
		$this->spawnY = $spawnY;
		$this->spawnZ = $spawnZ;
		$this->world = $world;
	}

	public function getWorld(): string
	{
		return $this->world ?? "deathban_arena";
	}

	public function setWorld(string $world): void
	{
		$this->world = $world;
	}

	public function setSpawnX(Position $spawnX): void
	{
		$this->spawnX = $spawnX;
	}


	public function getSpawnX(): Position
	{
		return $this->spawnX;
	}

	public function setSpawnY(Position $spawnY): void
	{
		$this->spawnY = $spawnY;
	}

	public function getSpawnY(): Position
	{
		return $this->spawnY;
	}

	public function setSpawnZ(Position $spawnZ): void
	{
		$this->spawnZ = $spawnZ;
	}

	public function getSpawnZ(): Position
	{
		return $this->spawnZ;
	}

	public function playersInArena(string $world) : int
	{
		$countworld = $world;
		$level = Server::getInstance()->getLevelByName($countworld);
		return count($level->getPlayers()) ?? 0;
	}
}
