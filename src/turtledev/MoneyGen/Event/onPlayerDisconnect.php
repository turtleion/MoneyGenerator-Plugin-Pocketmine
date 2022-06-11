<?php


namespace turtledev\MoneyGen\Event;

use turtledev\MoneyGen\MoneyGen;
use pocketmine\Player;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerQuitEvent;

class onPlayerDisconnect implements Listener {
	protected $plugin;
	public function __construct(MoneyGen $mg){
		$this->plugin = $mg;
	}

	public function onQuit(PlayerQuitEvent $event){
		if($this->plugin->getCD()[$event->getPlayer()->getName()] == null){
			$this->plugin->ignore();
		}else{
			
			$playername = $event->getPlayer()->getName();
     	   		$getTime = $this->plugin->getCD()[$playername];
    		    	$res = $this->plugin->db->query("INSERT INTO data VALUES ('$playername',$getTime)");
        		if(!$res instanceof \SQLite3Result){
            			$adminPlayer = [];
            			forech($this->plugin->getServer()->getOnlinePlayers() as $player){
                			if($player->isOp()){
                    			array_push($adminPlayer, $player->getName());
                		}
            		}
            		for($i = 0; i < count($adminPlayer); $i++){
                		$this->plugin->getServer()->getPlayerByName($adminPlayer[i])->sendMessage("[*DEBUG*] Player " . $playername . " has disconnected but the timer is does'nt successfully saved!");
            		}
		}

        }
    }
}
