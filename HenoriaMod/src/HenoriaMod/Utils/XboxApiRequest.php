<?php

namespace HenoriaMod\Utils;

use HenoriaMod\Main;
use pocketmine\utils\Config;

class XboxApiRequest{

    private $plugin;

    private $gamertag;
    private $xuid;

    private $apiKeys;
    private $currentKeyKeyC;
    private $limitRequest = false;

    public function __construct( string $gamertag ){
        $this->gamertag = rawurlencode($gamertag);

        $this->plugin = Main::getInstance();

        $this->apiKeys = $this->plugin->getConfig()->get("xapiAccounts");
        $this->currentKeyKeyC = new Config( $this->plugin->getDataFolder() . "currentKeyKey.yml", Config::YAML );

        $this->apiRequest();
    }

    private function apiRequest(){

        $this->currentKeyKeyC->reload();
        $currentKeyKey = $this->currentKeyKeyC->get("currentKeyKey");

        $currentKeyTime = new \DateTime( $currentKeyKey[1] );
        $now = new \DateTime();
        $timeDiff = $now->diff( $currentKeyTime );

        if( $timeDiff->h >= 1 ){
            $this->currentKeyKeyC->set("currentKeyKey", [ 0, time() ]);
            $this->currentKeyKeyC->save();
            $currentKeyKey = $this->currentKeyKeyC->get("currentKeyKey");
        }

        $apiKey = $this->apiKeys[ $currentKeyKey[0] ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"https://xboxapi.com/v2/xuid/{$this->gamertag}");
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "X-AUTH: {$apiKey}",
        ]);
        curl_setopt( $ch, CURLOPT_HEADER, true );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $result = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);
        $header = (isset($info["header_size"]))?substr($result,0,$info["header_size"]):"";
        $this->xuid = (isset($info["header_size"]))?substr($result,$info["header_size"]):"";


        preg_match("%(?<=X-RateLimit-Remaining: ).*%i", $header, $requestsRemaining);

        if ( (int) $requestsRemaining[0] <= 1 ){

            $keysNumber = count( $this->apiKeys );

            if( $keysNumber-1 == $currentKeyKey[0] || $this->limitRequest ){
                $this->limitRequest = true;
            }else{
                $this->currentKeyKeyC->set("currentKeyKey", [ $currentKeyKey[0] + 1, time() ]);
                $this->currentKeyKeyC->save();
                $currentKeyKey = $this->currentKeyKeyC->get("currentKeyKey");
            }
        }
    }

    public function getXuid() : string {
        return $this->xuid;
    }

    public function isLimit() : bool {
        return $this->limitRequest;
    }


}