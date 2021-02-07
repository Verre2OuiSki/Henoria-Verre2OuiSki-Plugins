<?php

namespace HenoriaMod;

use HenoriaMod\Loaders\Commands;
use HenoriaMod\Loaders\Events;
use pocketmine\utils\TextFormat;
use pocketmine\plugin\PluginBase;


class Main extends PluginBase{

    private static $_instance;

    public function onLoad() : void {
        $this->getLogger()->info( TextFormat::DARK_PURPLE . $this->getFullName() . " a été chargé !" );
    }

    public function onEnable() : void{
        self::$_instance = $this;
        $this->saveResource( "currentKeyKey.yml" );

        Commands::Load();
        Events::Load();

        $this->getLogger()->info( TextFormat::DARK_GREEN . $this->getFullName() . " a été activé !" );
    }

    public function onDisable() : void{
        $this->getLogger()->info( TextFormat::DARK_RED . $this->getFullName() . " a été désactivé !" );
    }


    public static function getInstance(){
        return self::$_instance;
    }




}
