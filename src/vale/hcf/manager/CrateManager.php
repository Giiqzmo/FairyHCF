<?php

namespace vale\hcf\crates;

use muqsit\invmenu\InvMenu;
use muqsit\invmenu\MenuIds;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\utils\Config;

class CrateManager{

	public function __construct(){

	} 

	public function cratePreview(Player $player, string $crateType){
		switch ($crateType){
			case "Fairy":
				$fairyPreview = InvMenu::create(MenuIds::TYPE_CHEST);
				$fairyPreview->setName("FairyPreview");
				$fairyPreview->readonly(true);
				$fairyPreview->send($player);
				break;
			case "Candy":
				$candyPreview = InvMenu::create(MenuIds::TYPE_CHEST);
				$candyPreview->setName("CandyPreview");
				$candyPreview->readonly(true);
				$candyPreview->send($player);
				break;
			case "Vale":
				break;
			case "Jory":
				break;
			case "Newcoolboys":
				break;
		}
	}

	public function sendFairyCrateRewards(Player $player){
		$chance = mt_rand(1,4);
		switch ($chance){
			case 1:
				$diamondBlocks = Item::get(Item::DIAMOND_BLOCK, 0 , mt_rand(1,32));
				if(!$player->getInventory()->canAddItem($diamondBlocks)){
					$player->getLevel()->dropItem(new Vector3($player->getX(), $player->getY(), $player->getZ()),$diamondBlocks);
				}else{
					$player->getInventory()->addItem($diamondBlocks);
				}
				break;
			case 2:
				$goldBlocks = Item::get(Item::GOLD_BLOCK, 0 , mt_rand(1,32));
				if(!$player->getInventory()->canAddItem($goldBlocks)){
					$player->getLevel()->dropItem(new Vector3($player->getX(), $player->getY(), $player->getZ()),$goldBlocks);
				}else{
					$player->getInventory()->addItem($goldBlocks);
				}
				break;
			case 3:
				$CoalBlocks = Item::get(Item::COAL_BLOCK, 0 , mt_rand(1,32));
				if(!$player->getInventory()->canAddItem($CoalBlocks)){
					$player->getLevel()->dropItem(new Vector3($player->getX(), $player->getY(), $player->getZ()),$CoalBlocks);
				}else{
					$player->getInventory()->addItem($CoalBlocks);
				}
				break;

			case 4:
				$godApple = Item::get(Item::ENCHANTED_GOLDEN_APPLE, 0 , mt_rand(1,2));
				$godApple->setCustomName("§r§dEnchanted Notch Apple");
				if(!$player->getInventory()->canAddItem($godApple)){
					$player->getLevel()->dropItem(new Vector3($player->getX(), $player->getY(), $player->getZ()),$godApple);
				}else{
					$player->getInventory()->addItem($godApple);
				}
				break;
		}
	}
}
