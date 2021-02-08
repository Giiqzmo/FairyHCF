<?php
namespace vale\hcf\cmds\mod;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\item\Item;
use pocketmine\Player;
use vale\hcf\HCF;

class CrateBlockCommand extends PluginCommand implements PluginIdentifiableCommand
{

    public $plugin;

    public function __construct(HCF $plugin)
    {
        $this->plugin = $plugin;
        parent::__construct("crateblock", $plugin);
        parent::setDescription("get a crate block");

    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player && $sender->isOp()) {
            if (!isset($args[0])) {
                $sender->sendMessage("Please run the command /crate types");
                return false;
            }
            if (isset($args[0])) {
                switch ($args[0]) {
                    case "Types":
                        $sender->sendMessage("§r§7***§r§c§lCRATE TYPES§r§7***");
                        foreach ($this->sendCrateTypes() as $crateType) {
                            $sender->sendMessage(implode((array)""), $crateType);
                        }
                        break;

                    case "fairy":
                    case "FAIRY":
                    case "Fairy":
                        $fairyCrate = Item::get(Item::CHEST);
                        $fairyCrate->setCustomName("§r§dFairy Crate");
                        $fairyCrate->setLore([
                            '§r§7Right Click to place the §r§dFairy Crate',
                            '§r§7 §r§§§lWARNING:: §r§7You can only place this once'
                        ]);
                        $sender->getInventory()->addItem($fairyCrate);
                        break;
                    case "slyph":
                    case "SLYPH":
                    case "Slyph":
                        $slyphCrate = Item::get(Item::CHEST);
                        $slyphCrate->setCustomName("§r§6Slyph Crate");
                        $slyphCrate->setLore([
                            '§r§7Right Click to place the §r§dSlyph Crate',
                            '§r§7 §r§§§lWARNING:: §r§7You can only place this once'
                        ]);
                        $sender->getInventory()->addItem($slyphCrate);
                        break;
                }
            }
        }
        return true;
    }

    public function sendCrateTypes(): array
    {
        $message = [
            "§r§dFairy §r§7Crate §r§7((FAIRY)",
            "§r§cCandy §r§7Crate §r§7((CANDY))",
            "§r§6Slyph §r§7Crate §r§7((SYLPH))",
        ];
        return $message;
    }
}
