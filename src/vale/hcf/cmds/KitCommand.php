<?php

namespace vale\hcf\cmds;

use pocketmine\command\PluginCommand;
use pocketmine\command\CommandSender;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\Player;
use pocketmine\item\Item;
use pocketmine\item\ItemIds;
use pocketmine\utils\TextFormat as C;

use vale\hcf\manager\KitManager;
use vale\hcf\HCF;

class KitCommand extends PluginCommand
{
    private $plugin;

    public function __construct(HCF $plugin)
    {
        parent::__construct("kit", $plugin);
        $this->setDescription("Kits Command");
    }
    
    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if($sender instanceof Player){
            if(count($args) == 0){
                $sender->sendMessage(TextFormat::RED . "Usage: /kit [Starter:Brewer:Builder:Miner:Bard:Archer:Diamond:Master]"); //Add More or less Kits idk what we'll have fully
            } else {
                switch(strtolower($args[0])){
                case "Starter":
                break;
                case "Brewer":
                break;
                case "Builder":
                break;
                case "Miner":
                break;
                case "Bard":
                break;
                case "Archer":
                break;
                case "Diamond":
                break;
                case "Master":
                break;
            }
        }
    }
}
