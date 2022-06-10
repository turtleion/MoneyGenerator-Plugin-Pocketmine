<?php


namespace turtledev\MoneyGen\Event;

use turtledev\MoneyGen\MoneyGen;
use pocketmine\Player;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;

class onPlayerInteractItem implements Listener {
    protected $timer = [];
	public function __construct(MoneyGen $mg){
		parent::__construct($mg);
        $timer = $mg->getTimer();
	}

    public function onClick(PlayerInteractEvent $ev){
            $player = $ev->getPlayer();
            $item = $ev->getItem();

            if($item->getNamedTag()->hasTag("Gen")){
                    // if interact
                    if($timer[$player->getName()] == null || $timer[$player->getName()] <= 0){
                            $timer += [$player->getName() => 5];
                            $parentTag = $item->getNamedTag()->getString("Gen");
                            if($parentTag == "1")){
                                    $p->sendMessage("Interact 5 More Times to Unlock Generator");
                                    EconomyAPI::getInstance()->addMoney($player,2500);
                            }
                    }else{
                            $bforeTimer = $timer[$player->getName()];
                            unset($timer[$player->getName()];
                            $timer += [$timer->getName() => $bforeTimer--];

                    }
                    $this->setTimer($timer);
            }


    }
}