<?php

namespace vale\hcf\cmds\mod;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use vale\hcf\HCF;

class WarnCommand extends PluginCommand
{

    public $plugin;

    public function __construct(HCF $plugin)
    {
        $this->plugin = $plugin;
        parent::__construct("warn", $plugin);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void
    {
        if (!$sender instanceof Player) {
            return;
        }
        if (!isset($args[1])) {
            return;
        }
        if (!$p = Server::getInstance()->getPlayer($args[0])) {
            $sender->sendMessage("Player needa be online nig");
            return;
        }
        if (!isset($args[1])) {
            $sender->sendMessage("Provide a reason");
            return;
        }
        if (!is_string($args[1])) {
            $sender->sendMessage("Please provide a ban reason");
            return;
        }

        $target = $p;
        $name = array_shift($args);
        $reason = strval(implode(" ", $args));
        Server::getInstance()->broadcastMessage(TextFormat::RESET . TextFormat::RED . $name . " was " . TextFormat::DARK_RED . " Warned By " . TextFormat::RED . $sender->getName() . TextFormat::RED . " for " . TextFormat::DARK_RED . $reason);
        HCF::getInstance()->getDataManager()->addWarn($p, 1);
    }
}
