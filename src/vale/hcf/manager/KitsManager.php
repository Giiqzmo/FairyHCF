<?php

namespace vale\hcf\manager;

use jojoe77777\FormAPI\FormAPI;
use jojoe77777\FormAPI\SimpleForm;
use pocketmine\Player;
use vale\hcf\HCF;

class KitsManager
{


	public HCF $plugin;

	public function __construct(HCF $plugin)
	{
		$this->plugin = $plugin;
	}


	public function sendKitsMainMenu(Player $player): void
	{
		$api = $this->plugin->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $api->createSimpleForm(function (Player $player, int $data = null) {
			$result = $data;
			if ($result === null) {
				return;
			}
			switch ($result) {

				case 0:
					$this->sendMasterKitMenu($player);
					break;


			}
		});
		$name = $player->getName();
		$form->setTitle("§r§8FairyHCF Gkits");
		$form->addButton("§r§5§lMaster §r§7§lKit" . "\n" . "§r§7Click to Select the kit");
		$form->sendToPlayer($player);
	}

	####################KITS############################

	public function sendMasterKitMenu(Player $player): void
	{
		$api = $this->plugin->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $api->createSimpleForm(function (Player $player, int $data = null) {
			$result = $data;
			if ($result === null) {
				return;
			}
			switch ($result) {

				case 0:
					if($player->hasPermission("hcf.kits.master.kit"))
					$player->sendMessage("Claimed");
					else{
						$this->noPermforKit($player);
					}
					break;

				case 1:
					$this->sendKitsMainMenu($player);
					break;


			}
		});
		$name = $player->getName();
		$form->setTitle("§r§8Click  'Continue' to use the kit.");
		$form->setContent("§r§fa fully enchanted §r§5§lMaster §r§7§lKit §r§fthe best kit on the server!" . "\n". "§r§7Purchase kits at §r§a§lstore.hcf.net !");
		$form->addButton("§r§7Continue");
		$form->addButton("§r§7Cancel");
		$form->sendToPlayer($player);
	}

	public function noPermforKit(Player $player): void
	{
		$api = $this->plugin->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $api->createSimpleForm(function (Player $player, int $data = null) {
			$result = $data;
			if ($result === null) {
				return;
			}
			switch ($result) {

				case 0:
                 $this->sendKitsMainMenu($player);
					break;

				case 1:
					break;


			}
		});
		$name = $player->getName();
		$form->setTitle("§r§8FairyHCF Gkits");
		$form->setContent("§r§cYou cannnot obtain this kit");
		$form->addButton("§r§7Back");
		$form->addButton("§r§7Exit");
		$form->sendToPlayer($player);
	}
}
