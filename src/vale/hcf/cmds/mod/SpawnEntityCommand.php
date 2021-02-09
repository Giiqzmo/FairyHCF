<?php

namespace vale\hcf\cmds\mod;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\entity\Entity;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\plugin\Plugin;
use vale\hcf\entities\PartnerPackageEntity;
use vale\hcf\HCF;

class SpawnEntityCommand extends PluginCommand{

	public function __construct(string $name, HCF $owner)
	{
		parent::__construct($name, $owner);
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args)
	{
		if ($sender instanceof Player) {
			if (!isset($args[0])) {
				$sender->sendMessage("/spawnentity <name>");
				return false;
			}
			switch ($args[0]) {
				case "partner":
					$bot = new PartnerPackageEntity($sender->getLevel(), Entity::createBaseNBT($sender->asVector3()));
					$bot->spawnToAll();
					break;
			}
		}
		return true;
	}

}
