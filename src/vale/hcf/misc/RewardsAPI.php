<?php

namespace vale\hcf\misc;

use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\StringTag;
use pocketmine\Player;
use libs\utils\Utils;

final class RewardsAPI
{

	const BONE = 1;
	const NINJA_STAR = 2;
	const STRENGTH2 = 3;
	const SNOWBALL = 4;
	const STICK = 5;
	const ROUGE_SWORD = 6;
	const Second_Chance = 7;
	const Helmet_Begone = 8;

	public static function giveReward(Player $player, int $id)
	{
		switch ($id) {
			case self::BONE:
				$bone = Item::get(Item::BONE, 0, mt_rand(1, 3))->
				setCustomName("§r§6§lVales §r§7Bone")->
				setLore([
					'§r§7Hit a player with this bone 3 times to prevent them from building',
				]);
				$bone->getNamedTag()->setTag(new StringTag("BonePartnerItem"));
				$player->getInventory()->addItem($bone);
				$player->sendMessage("§r§6§l* §r§7((x §r§61 §r§6§lVales §r§7Bone §r§7))");

				break;

			case self::NINJA_STAR:
				break;

			case self::STRENGTH2;
				$strength = Item::get(Item::BLAZE_POWDER, 0, mt_rand(1, 3))->
				setCustomName("§r§c§lStrength II")->
				setLore([
					'§r§7Tap this to §r§c§lRecieve §r§7Strength II for 5 seconds',
				]);
				$strength->getNamedTag()->setTag(new StringTag("BonePartnerItem"));

				$player->getInventory()->addItem($strength);
				$player->sendMessage("§r§6§l* §r§7((x §r§6{$strength->getCount()} §r§c§l{$strength->getCustomName()}§r§7))");

				break;
		}
	}
}
