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
                $sender->sendMessage(TextFormat::RED . "Usage: /kit [Starter:Brewer:Builder:Miner:Rogue:Bard:Archer:Diamond:Master]"); //Add More or less Kits idk what we'll have fully
            } else {
                switch(strtolower($args[0])){
                case "Starter":
                        //set up voting shit here
                break;
                case "Brewer":
                        if(HCF::getKitManager()->getCooldown("Brewer", $sender) == 0){
                            if($sender->hasPermission("hcfkit.brewer")){
                                
                                //define armor enchants names,
                                $helmet = Item::get(ItemIds::IRON_HELMET)->setCustomName(C::RESET . C::YELLOW . "Brewer Kit")->addEnchantment(Enchantment::getEnchantment(Enchantment::PROTECTION)->setLevel(2));
                                $chestplate = Item::get(ItemIds::IRON_CHESTPLATE)->setCustomName(C::RESET . C::YELLOW . "Brewer Kit")->addEnchantment(Enchantment::getEnchantment(Enchantment::PROTECTION)->setLevel(2));
                                $leggings = Item::get(ItemIds::IRON_LEGGINGS)->setCustomName(C::RESET . C::YELLOW . "Brewer Kit")->addEnchantment(Enchantment::getEnchantment(Enchantment::PROTECTION)->setLevel(2));
                                $boots = Item::get(ItemIds::IRON_BOOTS)->setCustomName(C::RESET . C::YELLOW . "Brewer Kit")->addEnchantment(Enchantment::getEnchantment(Enchantment::PROTECTION)->setLevel(2));

                                //add items
                                $sender->getInventory()->addItem($helmet);
                                $sender->getInventory()->addItem($chestplate);
                                $sender->getInventory()->addItem($leggings);
                                $sender->getInventory()->addItem($boots);
                                $sender->getInventory()->addItem(Item::get(ItemIds::STEAK, 0, 64));
                                $sender->getInventory()->addItem(Item::get(ItemIds::BLAZE_ROD, 0, 128));
                                $sender->getInventory()->addItem(Item::get(ItemIds::NETHER_WART, 0, 384));
                                $sender->getInventory()->addItem(Item::get(ItemIds::SUGAR, 0, 192));
                                $sender->getInventory()->addItem(Item::get(ItemIds::GUNPOWDER, 0, 192));
                                $sender->getInventory()->addItem(Item::get(ItemIds::GLOWSTONE_DUST, 0, 192));
                                $sender->getInventory()->addItem(Item::get(ItemIds::MAGMA_CREAM, 0, 192));
                                $sender->getInventory()->addItem(Item::get(ItemIds::FERMENTED_SPIDER_EYE, 0, 192));
                                //todo add if no space drop on ground checks
                            }
                            return true;
                        }                
                break;
                case "Builder":
                break;
                case "Miner":
                break;
                case "Rogue":
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
