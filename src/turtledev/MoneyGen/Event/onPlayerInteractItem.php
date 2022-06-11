<?php


namespace turtledev\MoneyGen\Event;

use turtledev\MoneyGen\MoneyGen;
use pocketmine\Player;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;

class onPlayerInteractItem implements Listener {
    protected $cd = [];
    public function __construct(MoneyGen $mg){
	   parent::__construct($mg);
           $cd = $mg->getCD();
    }

    public function onClick(PlayerInteractEvent $ev){
            $player = $ev->getPlayer();
            $item = $ev->getItem();

            if($item->getNamedTag()->hasTag("Gen")){
                    // if interact
                    if($cd[$player->getName()] == null || $cd[$player->getName()] <= 0){
                            $cd += [$player->getName() => 5];
                            $parentTag = $item->getNamedTag()->getString("Gen");
                            if($parentTag == "1")){
                                    $p->sendMessage("Interact 5 More Times to Unlock Generator");
                                    EconomyAPI::getInstance()->addMoney($player,2500);
                            }
                    }else{
                            $bfore = $cd[$player->getName()];
                            unset($cd[$player->getName()];
                            $cd += [$player->getName() => $bfore--];

                    }
                    $this->setCD($cd);
            }


    }
}
