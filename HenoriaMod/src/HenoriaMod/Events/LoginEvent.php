<?php



namespace HenoriaMod\Events;

use HenoriaMod\Main;
use HenoriaMod\Utils\BanEntry;
use HenoriaMod\Utils\BanList;
use HenoriaMod\Utils\XboxApiRequest;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\utils\Config;

class LoginEvent implements Listener{

    public $plugin;

    private $apiKeys;
    private $currentKeyKeyC;
    private $limitRequest = true;

    public function __construct( Main $plugin ){
        $this->plugin = $plugin;

        $this->apiKeys = $this->plugin->getConfig()->get("xapiAccounts");
        $this->currentKeyKeyC = new Config( $this->plugin->getDataFolder() . "currentKeyKey.yml", Config::YAML );
    }


    public function onLogin( PlayerPreLoginEvent $event ) : void {


        $player = $event->getPlayer();

        $apiRequest = new XboxApiRequest( $player->getName() );

        if( $apiRequest->isLimit() ){
            $player->kick(
                $this->plugin->getConfig()->get("requestLimit"),
                false
            );
            $event->setCancelled();
        }

        $playerXuid = $apiRequest->getXuid();

        $banList = new BanList( $this->plugin );

        if( $banList->isBanned( $playerXuid ) ){

            $banEntry = $banList->getEntry( $playerXuid );

            if( !empty( $banEntry->getIp() ) ){

                $kickMessage = $this->plugin->getConfig()->get("playerTryJoin")["banIpMessage"];
                $player->kick(
                    str_replace(
                        [ "{staff}", "{reason}" ],
                        [ $banEntry->getSource(), $banEntry->getReason() ],
                        $kickMessage
                    ),
                    false
                );
                $event->setCancelled();
                return;
            }

            $unbanTime = $banEntry->getExpire();
            $now = new \DateTime();
            $timeRemain = $now->diff( $unbanTime );
            $banMessage = $this->plugin->getConfig()->get("playerTryJoin")["banMessage"];
            $banMessage = $this->setBanMessage( $banMessage, $timeRemain, $banEntry );

            $player->kick(
                $banMessage,
                false
            );
            $event->setCancelled();

        }

    }

    private function setBanMessage( $banMessage, \DateInterval $timeRemain, BanEntry $banEntry){

        if ( !empty( $timeRemain->y ) ){
            $banMessage = str_replace(
                [ "{staff}", "{time}", "{reason}" ],
                [
                    $banEntry->getSource(),
                    "{$timeRemain->y} an(s) et {$timeRemain->m} mois",
                    $banEntry->getReason()
                ],
                $banMessage
            );
        }elseif ( !empty( $timeRemain->m ) ){
            $banMessage = str_replace(
                [ "{staff}", "{time}", "{reason}" ],
                [
                    $banEntry->getSource(),
                    "{$timeRemain->m} mois et {$timeRemain->d} jour(s)",
                    $banEntry->getReason()
                ],
                $banMessage
            );
        }elseif ( !empty( $timeRemain->d ) ){
            $banMessage = str_replace(
                [ "{staff}", "{time}", "{reason}" ],
                [
                    $banEntry->getSource(),
                    "{$timeRemain->d} jour(s) et {$timeRemain->h} heure(s)",
                    $banEntry->getReason()
                ],
                $banMessage
            );
        }elseif ( !empty( $timeRemain->h ) ){
            $banMessage = str_replace(
                [ "{staff}", "{time}", "{reason}" ],
                [
                    $banEntry->getSource(),
                    "{$timeRemain->h} heure(s), {$timeRemain->i} minute(s) et {$timeRemain->s} seconde(s)",
                    $banEntry->getReason()
                ],
                $banMessage
            );
        }else{
            $banMessage = str_replace(
                [ "{staff}", "{time}", "{reason}" ],
                [
                    $banEntry->getSource(),
                    "{$timeRemain->i} minute(s) et {$timeRemain->s} seconde(s)",
                    $banEntry->getReason()
                ],
                $banMessage
            );
        }

        return $banMessage;

    }


}