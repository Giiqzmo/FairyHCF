<?php

namespace vale\hcf\manager;

use vale\hcf\HCF;

class KitManager {

	private $plugin;
  
	private $kits = [];
  
	private $data = [];

	public function __construct(HCF $plugin) {
		$this->plugin = $plugin;
		if(file_exists($this->getCooldownPath() . DIRECTORY_SEPARATOR . "cooldown.json")){
            		$this->data = json_decode(file_get_contents($this->getCooldownPath() . DIRECTORY_SEPARATOR . "cooldown.json"), true);
        	}
	}

	public function getKitByName(string $kit) : ?Kit {
		return $this->kits[$kit] ?? null;
	}

	public function getKits(): array {
		return $this->kits;
    	}

	public function getCooldown(string $kit, string $player): int{
		return isset($this->data[$kit][$player]) ? $this->data[$kit][$player] : 0;
    	}

    	public function addToCooldown(string $kit, string $player, int $time): void{
      		$this->data[$kit][$player] = time();
    	}

    	public function removeFromCooldown(string $kit, string $player): void{
	    	if(isset($this->data[$kit][$player]))
        	unset($this->data[$kit][$player]);
    	}

	public function getCooldownPath(): string{
	    	return HCF::getInstance()->getDataFolder() . "kit";
    	}
	
	//add a save() function?
}
