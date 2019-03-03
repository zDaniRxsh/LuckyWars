<?php

namespace lw\Entity;
use pocketmine\event\Listener;
use pocketmine\{Server,Player};
use pocketmine\utils\Config;
use pocketmine\math\{Vector2,Vector3};
use lw\LuckyWars;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\entity\Entity;
use lw\Entity\{LWNPC,GanadorNPC};
use pocketmine\level\Level;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\network\mcpe\protocol\MoveEntityAbsolutePacket;
class EventsNPCLW implements Listener{

public function __construct(LuckyWars $plugin){

$plugin->getServer()->getPluginManager()->registerEvents($this, $plugin);
$this->db = $plugin;
}
public function dano(EntityDamageEvent $evento){
if($evento->getEntity() instanceof LWNPC){
	$evento->setCancelled(true);
$this->db->system($evento->getDamager());
}
if($evento->getEntity() instanceof GanadorNPC){
	$evento->setCancelled(true);
}
}



}
