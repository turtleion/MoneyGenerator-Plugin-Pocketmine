<?php


namespace turtledev\MoneyGen\Event;

use turtledev\MoneyGen\MoneyGen;
use pocketmine\Player;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerRespawnEvent;


class onPlayerDeath implements Listener {
    protected $data;
    protected $pname;

    public function __construct(MoneyGen $mg){
		parent::__construct($mg);
    }

    public generateItem(Inventory $inv,Item $i,$name,StringTag $srtag){
        $i->setCustomName($name);
        $i->setNamedTag($srtag);
        $inv->addItem($i);
    }

    public function onRespawn(PlayerRespawnEvent $rp){
        if($rp->getPlayer()->getName() == $this->pname){
            $this->generateItem($data[0],$data[1],$data[2],$data[3]);
        }
    }

    public function onDeath(PlayerDeathEvent $p){
        $player = $p->getPlayer();
        $playerinv = $player->getInventory();
        $item = Item::get(399,0,1);
        if($playerinv->contains($item) and $item->getName() == $this->itemNameGen1){
            $this->pname = $player->getName();
            $strtag = new StringTag("Gen","1");
            $this->data = [$playerinv, $item, $itemNameGen1,$strtag];
        }
    }


}
