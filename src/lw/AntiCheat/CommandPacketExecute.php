<?php
namespace lw\AntiCheat;
use pocketmine\event\Listener;
use pocketmine\{Server,Player};
use pocketmine\utils\Config;
use pocketmine\math\{Vector2,Vector3};
use lw\LuckyWars;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
class CommandPacketExecute implements Listener{

public function __construct(LuckyWars $plugin){

$plugin->getServer()->getPluginManager()->registerEvents($this, $plugin);
$this->db = $plugin;
}

  public function Commands(PlayerCommandPreprocessEvent $event) {
       $cmd = explode(" ", strtolower($event->getMessage()));
$scan = scandir($this->db->getDataFolder()."Arenas/");
	foreach($scan as $files){
	if($files !== ".." and $files !== "."){
	$name = str_replace(".yml", "", $files);
	if($name == "") continue;
	$arena = new Config($this->db->getDataFolder()."Arenas/".$name.".yml", Config::YAML);
if($event->getPlayer()->getLevel()->getFolderName() == $arena->get("level")){
if($cmd[0] === "/gamemode"){
$event->setCancelled(true);
}

 if($cmd[0] === "/gm"){
$event->setCancelled(true);
}
 if($cmd[0] === "/fly"){
$event->setCancelled(true);
}
 if($cmd[0] === "/tp"){
$event->setCancelled(true);
}
if($cmd[0] === "/kick"){
$event->setCancelled(true);
}

if($cmd[0] === "/stop"){
$event->setCancelled(true);
}
if($cmd[0] === "/kill"){
$event->setCancelled(true);
}
if($cmd[0] === "/give"){
$event->setCancelled(true);
}
}
}
}
}

}
