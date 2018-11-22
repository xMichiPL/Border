<?php

namespace Border;

use pocketmine\plugin\PluginBase;

use pocketmine\event\Listener;

use pocketmine\level\Position;

use pocketmine\level\sound\EndermanTeleportSound;

use pocketmine\event\player\PlayerMoveEvent;

use Border\Main;

class Main extends PluginBase implements Listener {
    
    public function onEnable(){
        $this->getLogger()->info("Enabled.");
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		@mkdir($this->getDataFolder());
		$this->saveDefaultConfig();
		$this->getResource("config.yml");
    }

    public function onBorder(PlayerMoveEvent $event){
        $player = $event->getPlayer();
        if($player->getLevel()->getFolderName() === $this->getConfig()->get("level")){
            $distance = $this->getServer()->getDefaultLevel()->getSpawnLocation()->distance($player);
            if($distance >= $this->getConfig()->get("distance")){
				$player->setGamemode(0);
				$player->setAllowFlight(false);
				$player->getLevel()->addSound(new EndermanTeleportSound($player));
                $player->teleport($this->getServer()->getDefaultLevel()->getSafeSpawn());
                $player->sendMessage($this->getConfig()->get("message"));
            }
        }
    }

    public function onDisable(){
        $this->getLogger()->info("Disabled.");
    }
}