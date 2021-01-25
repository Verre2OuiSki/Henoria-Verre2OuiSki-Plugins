<?php

namespace HenoriaRanks;

use pocketmine\utils\TextFormat;
use pocketmine\plugin\PluginBase;


class Main extends PluginBase{

    private static $_instance;

    public function onLoad() : void {
        $this->getLogger()->info( TextFormat::DARK_PURPLE . $this->getFullName() . " a été chargé !" );
    }

    public function onEnable() : void{
        self::$_instance = $this;

        $this->getLogger()->info( TextFormat::DARK_GREEN . $this->getFullName() . " a été activé !" );
    }

    public function onDisable() : void{
        $this->getLogger()->info( TextFormat::DARK_RED . $this->getFullName() . " a été désactivé !" );
    }


    public static function getInstance(){
        return self::$_instance;
    }



}
