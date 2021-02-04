<?php
namespace vale\hcf\cmds;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\Player;
use pocketmine\plugin\Plugin;
use pocketmine\Server;
use vale\hcf\factions\FactionLoader;
use vale\hcf\HCF;

class FactionCommand extends PluginCommand{

	public function __construct(HCF $plugin){
		parent::__construct("f", $plugin);
		$this->setDescription("Factions Command");
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args)
	{
		if($sender instanceof Player){
			if(!isset($args[0])){
				$sender->sendMessage("/f help");
				return false;
			}
			if(isset($args[0])){
				switch ($args[0]){
					case "Help":
					case "HELP":
					case "help":
						break;

					case "create":
					case "CREATE":
						if(!isset($args[1])){
							return false;
						}
						if(!is_string($args[1])){
							$sender->sendMessage("/f create <faction>");
						}else{
							HCF::getInstance()->getFactionManager()->createFaction($args[1], $sender);
							HCF::getInstance()->getServer()->broadcastMessage("");
						}
						break;

					case "delete":
					case "DELETE":
					case "Delete":
						if(HCF::getInstance()->getFactionManager()->isInFaction($sender) && HCF::getInstance()->getFactionManager()->isFactionLeader($sender)){
							HCF::getInstance()->getFactionManager()->deleteFaction($sender, HCF::$factionManager->getPlayerFaction($sender));
						}
						Server::getInstance()->broadcastMessage("");
						$sender->sendMessage("");
						break;

					case "info":
					case "INFO":
					case "Info":
						if(!isset($args[1])){
							$sender->sendMessage("Provide a fac name");
							return false;
						}
						if(!HCF::getInstance()->getFactionManager()->playerFactionExists($args[1])) {
							$sender->sendMessage("Please enter a valid faction name");
							return false;
						}elseif (HCF::getInstance()->getFactionManager()->playerFactionExists($args[1])){
							$mgr = new FactionLoader(HCF::getInstance());
							$dtr = $mgr->getFactionDTR($args[1]);
							$home = $mgr->getHome($args[1]);
							$name = $mgr->getFacByString($args[1]);
							$sender->sendMessage($name . "FACTION INFO");
							$sender->sendMessage("DTR:". $dtr);
							$sender->sendMessage("Home". $home);
						}
						break;
				}
			}
		}
		return true;
	}

}
