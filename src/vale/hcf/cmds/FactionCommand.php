<?php

namespace vale\hcf\cmds;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;
use pocketmine\Server;
use vale\hcf\factions\FactionLoader;
use vale\hcf\HCF;

class FactionCommand extends PluginCommand
{

    public function __construct(HCF $plugin)
    {
        parent::__construct("f", $plugin);
        $this->setDescription("Factions Command");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player) {
            if (!isset($args[0])) {
                $sender->sendMessage("/f help");
                return false;
            }
            if (isset($args[0])) {
                switch ($args[0]) {
                    case "Help":
                    case "HELP":
                    case "help":
                        break;

                    case "create":
                    case "CREATE":
                        if (!isset($args[1])) {
                            return false;
                        }
                        if (!is_string($args[1])) {
                            $sender->sendMessage("/f create <faction>");
                        } else {
                            HCF::getInstance()->getFactionManager()->createFaction($args[1], $sender);
                            HCF::getInstance()->getServer()->broadcastMessage("");
                        }
                        break;

                    case "delete":
                    case "DELETE":
                    case "Delete":
                        if (HCF::getInstance()->getFactionManager()->isInFaction($sender) && HCF::getInstance()->getFactionManager()->isFactionLeader($sender)) {
                            HCF::getInstance()->getFactionManager()->deleteFaction(HCF::$factionManager->getPlayerFaction($sender));
                        }
                        Server::getInstance()->broadcastMessage("");
                        $sender->sendMessage("");
                        break;

                    case "Who":
                    case "who":
                    case "WHO":
                        if (!isset($args[1])) {
                            $sender->sendMessage("Provide a fac name");
                            return false;
                        }
                        if (!HCF::getInstance()->getFactionManager()->playerFactionExists($args[1])) {
                            $sender->sendMessage("Please enter a valid faction name");
                            return false;
                        } elseif (HCF::getInstance()->getFactionManager()->playerFactionExists($args[1])) {
                            $mgr = new FactionLoader(HCF::getInstance());
                            $dtr = $mgr->getFactionDTR($args[1]);
                            $home = $mgr->getHome($args[1]);
                            $name = $mgr->getFacByString($args[1]);
                            $sender->sendMessage($name . "FACTION INFO");
                            $sender->sendMessage("DTR:" . $dtr);
                            $sender->sendMessage("Home" . $home);
                        }
                        break;

					case "INFO":
					case "Info":
					case "info":

						$mgr = HCF::getInstance()->getFactionManager();
						if($mgr->isInFaction($sender)) {
							$fac = $mgr->getPlayerFaction($sender);
							$dtr = $mgr->getFactionDTR($fac);
							$sender->sendMessage("Name {$fac}");
							$sender->sendMessage("DTR {$dtr}");

						}else{
							$sender->sendMessage("not in a fac coooon");
						}
						break;

                    case "invite":
                    case "INVITE":
                    case "Invite":
			$mgr = new FactionLoader(HCF::getInstance());
                        if (!isset($args[1])) {
                            $sender->sendMessage("Provide a playername");
                            return false;
                        }
                        if($sender->getName() === $args[1]){
                            $sender->sendMessage("You cannot invite yourself");
                        }

                        if($mgr->getPlayerFaction($args[1]) === $mgr->getPlayerFaction($sender)){
                        	$sender->sendMessage("Player is already in Faction");
                        	return  false;
						}
                        if ($mgr->isInFaction($sender) && $mgr->isFactionLeader($sender) or $mgr->isFactionCoLeader($sender) or $mgr->isFactionCaptain($sender)) {
                           $mgr->addInvite($args[1], $mgr->getPlayerFaction($sender), $sender);
                           $sender->sendMessage("Invited". (string)$args[1]);
                        }
                        break;
					case "chat":
					case "CHAT":
					case "Chat":
			                case "c":
						if(!isset($args[1])){
							$sender->sendMessage("/f chat (faction) (public)");
							return false;
						}

						if(strtolower($args[1] === "public" or "p" or "pc")) {
							$mgr = new FactionLoader(HCF::getInstance());
							if (isset($mgr->factionChat[$sender->getName()])) {
								unset($mgr->factionChat[$sender->getName()]);
								$sender->sendMessage("Faction Chat Disabled");
							}
							if (strtolower($args[1]) === "faction" or "f" or "fc") {
								if (!isset($mgr->factionChat[$sender->getName()])) {
									array_push($mgr->factionChat, $sender->getName());
									$sender->sendMessage("Faction Chat Enabled");
								}
							}
						}
						break;
					case "FRIENDLYFIRE":
					case "friendlyfire":
					case "ff":
					case "FriendlyFire":
						/*
						$mgr = HCF::getInstance()->getFactionManager();
						foreach ($mgr->getMembers() as $member){
							if($member instanceof Player){
								if(!$mgr->hasFriendlyFireEnabled($member))
								$mgr->setFriendlyFire($member,"on");
							}else{
								$mgr->setFriendlyFire($member, "off");
							}
						}*/
						#cant do this coz new hasnt made a fricking get members func
                        break;

                    case "test":
                        $mgr = HCF::getInstance()->getFactionManager();
                        foreach($mgr->getMembers($mgr->getPlayerFaction($sender)) as $member){
                            $sender->sendMessage($member);
                        }
                }
            }
        }
        return true;
    }

}
