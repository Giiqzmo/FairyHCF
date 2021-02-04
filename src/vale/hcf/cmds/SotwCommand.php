<?php

namespace vale\hcf\cmds;

use pocketmine\command\ConsoleCommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;
use pocketmine\Server;
use vale\hcf\HCF;

class SotwCommand extends PluginCommand
{

	public $plugin;

	public function __construct(HCF $plugin)
	{
		$this->plugin = $plugin;
		parent::__construct("sotw", $plugin);
	}

	public function execute(\pocketmine\command\CommandSender $sender, string $commandLabel, array $args)
	{
		if (!isset($args[0])) {
			$sender->sendMessage("/sotw <time>");
			return false;
		}
		if(!is_numeric($args[0])){
			$sender->sendMessage("Second Argument must be of int not instance of string");
			return false;
		}
		if (is_numeric($args[0])) {
			$sender->sendMessage("Started Sotw for ". HCF::getInstance()->secondsToTime($args[0]));
		}
		return true;
	}
}
