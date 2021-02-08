<?php

declare(strict_types = 1);

namespace vale\hcf\manager;

use vale\hcf\HCF;

class KitManager {

	private $plugin;
  
	private $kits = [];
  
	private $data = [];

	  public function __construct(HCF $plugin) {
		  $this->plugin = $plugin;
	  }

	  public function getKitByName(string $kit) : ?Kit {
		  return $this->kits[$kit] ?? null;
	  }

	  public function getKits(): array {
	    //todo
    }

	  public function getCooldown(string $kit, string $player): int{
	    //todo
    }

    public function addToCooldown(string $kit, string $player, int $time): void{
      //todo
    }

    public function removeFromCooldown(string $kit, string $player): void{
	    //todo
    }

	  public function getCooldownPath(): string{
	    return HCF::getInstance()->getDataFolder() . "kit";
    }
}
