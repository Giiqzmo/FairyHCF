<?php

namespace vale\hcf\manager;

use pocketmine\Player;
use vale\hcf\data\YamlProvider;
use vale\hcf\HCF;

class DeathBanManager
{

    public function setDeathBan(Player $player, int $value)
    {
        $data = HCF::getInstance()->getDeathBannedData();
        if (!$data->exists($player->getName())) {
            $data->set($player->getName(), (int)$value);
            $data->save();
        }
    }


    public function removeDeathBan(Player $player)
    {
        $data = HCF::getInstance()->getDeathBannedData();
        if ($data->exists($player->getName())) {
            $data->remove($player->getName());
        }

    }

    public function isDeathBanned(Player $player): bool
    {
        $data = HCF::getInstance()->getDeathBannedData();
        if ($data->exists($player->getName())) {
            return true;
        } else {
            return false;
        }
    }

    public function getDeathBan(Player $player)
    {
        $data = HCF::getInstance()->getDeathBannedData();
        if ($data->exists($player->getName())) {
            $time = (int)$data->get($player->getName());
            return (int)$time;
        } else {
            return (int)0;
        }
    }

    public function sendLives(Player $sender, Player $recipient, int $value): void
    {
    }

}
