<?php


namespace turtledev\MoneyGen\Event;

use turtledev\MoneyGen\MoneyGen;
use pocketmine\Player;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerRespawnEvent;


class onPlayerDeath implements Listener {
	public function __construct(MoneyGen $mg){
		parent::__construct($mg);
	}

    public generateItem(Inventory $inv,Item $i,$name,StringTag $srtag){
        $i->setCustomName($name);
        $i->setNamedTag($srtag);
        $inv->addItem($i);
    }

    public function onDeath(PlayerDeathEvent $p){
        $player = $p->getPlayer();
        $playerinv = $player->getInventory();
        $item = Item::get(399,0,1);
        if($playerinv->contains($item) and $item->getName() == $this->itemNameGen1){
            $this->generateItem($playerinv,$item,$this->itemNameGen1,new StringTag("Gen","1"));
        }
    }


}