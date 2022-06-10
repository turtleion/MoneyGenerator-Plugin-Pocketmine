<?php


namespace turtledev\MoneyGen\Event;

use turtledev\MoneyGen\MoneyGen;
use pocketmine\Player;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerQuitEvent;

class onPlayerDisconnect implements Listener {
	public function __construct(MoneyGen $mg){
		parent::__construct($mg);
	}

	public function onQuit(PlayerQuitEvent $event){
        $playername = $event->getPlayer()->getName();
        $getTime = $this->getTimer()[$playername];
        $res = $db->query("INSERT INTO data VALUES ('$playername',$getTime)");
        if(!$res instanceof \SQLite3Result){
            $adminPlayer = [];
            forech($this->getServer()->getOnlinePlayers() as $player){
                if($player->hasPermission("core.admin")){
                    array_push($adminPlayer, $player->getName());
                }
            }
            for($i = 0; i < count($adminPlayer); $i++){
                $this->getServer()->getPlayerByName($adminPlayer[i])->sendMessage("[*DEBUG*] Player " . $playername . " has disconnected but the timer is does'nt successfully saved!");
            }

        }
    }
}