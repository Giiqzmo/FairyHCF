<?php

namespace vale\hcf\items;

use pocketmine\item\Item;
use pocketmine\nbt\tag\StringTag;
use pocketmine\Player;

class ItemManager
{

    public function initItems()
    {
        //todo implement registering
    }

    public function addHCFItem(Player $player, string $id, int $amount = 1)
    {
        switch ($id) {
            case "PartnerPackage":
                $partnerpackage = Item::get(Item::ENDER_CHEST, 0, $amount);
                $partnerpackage->setCustomName("");
                $partnerpackage->setLore([
                    '',
                    '',
                ]);
                $partnerpackage->getNamedTag()->setTag(new StringTag("partnerpackage"));
                $player->getInventory()->addItem($partnerpackage);
                break;
        }
    }
}
