<?php

namespace vale\hcf\manager;

use jojoe77777\FormAPI\ModalForm;
use jojoe77777\FormAPI\SimpleForm;
use pocketmine\Player;
use pocketmine\Server;

class KitsManager
{

	public function sendMasterKitForm(Player $player)
	{
		$form = new ModalForm(function (Player $player, $data): void {
			if ($data === null) {
				return;
			}
			switch ($data) {
				case 1:
					$player->sendMessage("CLAIMED");
					break;

				case 2:
					$this->sendBaseKitForm($player);
					break;
			}
		});
		$form->setTitle("§6§lFairyHCF §r§7Gkits");
		$form->setContent("§r§fTEST");
		$form->setButton1("§r§6§lCLAIM");
		$form->setButton2("§r§c§lBACK");
		$form->sendToPlayer($player);
	}

	public function sendBaseKitForm(Player $player)
	{
		$form = new SimpleForm(function (Player $player, $data): void{
			if($data === null){
				return;
			}
			switch ($data){
				case 0:
					$this->sendMasterKitForm($player);
					break;
			}
		});
		$form->setTitle("§6§lFairyHCF §r§7Gkits");
		$form->setContent("§r§6§lSELECT §r§fa Kit!");
		$form->addButton("§r§4Master §r§7Kit", 0,1,0);
		$form->sendToPlayer($player);
	}
}
