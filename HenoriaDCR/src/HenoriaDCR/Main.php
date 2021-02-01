<?php

namespace HenoriaDCR;

use pocketmine\utils\TextFormat;
use pocketmine\plugin\PluginBase;


class Main extends PluginBase{

    public function onLoad() : void {

        $commands = $this->getConfig()->get("commands");

        $map = $this->getServer()->getCommandMap();
        foreach ($commands as $cmd) {
            $command = $map->getCommand($cmd);
            if ($command !== null) {
                $map->unregister($command);
            }
        }

        $this->getLogger()->info( TextFormat::DARK_PURPLE . $this->getFullName() . " a été chargé !" );
    }

    public function onEnable() : void{
        $this->getLogger()->info( TextFormat::DARK_GREEN . $this->getFullName() . " a été activé !" );
    }

    public function onDisable() : void{
        $this->getLogger()->info( TextFormat::DARK_RED . $this->getFullName() . " a été désactivé !" );
    }
}
