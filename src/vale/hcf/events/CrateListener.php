<?php

namespace vale\hcf\events;

use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\Listener;
use vale\hcf\data\YamlProvider;
use vale\hcf\HCF;

class CrateListener implements Listener
{

    public HCF $plugin;

    public function __construct(HCF $plugin)
    {
        $this->plugin = $plugin;
        $this->plugin->getServer()->getPluginManager()->registerEvents($this, $plugin);
    }

    public function onPlace(BlockPlaceEvent $ev)
    {
        $player = $ev->getPlayer();
        $block = $ev->getBlock();
        $hand = $player->getInventory()->getItemInHand();
        $level = (string)$block->getLevel()->getName();
        $l = $block->getLevel();
        $names = explode("\n", $hand->getCustomName());
        $data = YamlProvider::$crateData;
        $ev->setCancelled();
        $name = $block->getName();
        switch ($name) {
            case "Fairy":
                if (!$data->exists("FairyCrate")) {
                    $data->set("FairyCrate", [
                        "Name" => "FairyCrate",
                        "PosX" => $block->getX(),
                        "PosY" => $block->getY(),
                        "PosZ" => $block->getZ(),
                        "Level" => $level
                    ]);
                    $data->save();
                } else {
                    $ev->setCancelled();
                    $player->sendMessage("Block Already Placed");
                }
                break;
            case "Sylph":
                if (!$data->exists("SylphCrate")) {
                    $data->set("SylphCrate", [
                        "Name" => "SylphCrate",
                        "PosX" => $block->getX(),
                        "PosY" => $block->getY(),
                        "PosZ" => $block->getZ(),
                        "Level" => $level
                    ]);
                    $data->save();
                } else {
                    $ev->setCancelled();
                    $player->sendMessage("Block Already Placed");
                }
                break;    
                //todo add more
        }
    }

}
