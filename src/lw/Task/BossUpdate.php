<?php
namespace lw\Task;
use pocketmine\{Server,Player};
use pocketmine\level\Level;
use pocketmine\scheduler\Task;
use pocketmine\utils\Config;
use pocketmine\math\{Vector2,Vector3};
use lw\LuckyWars;
use lw\Entity\{LWNPC};
use pocketmine\network\mcpe\protocol\MoveEntityAbsolutePacket;
class BossUpdate extends Task {


public function __construct(LuckyWars $eid){

		$this->plugin = $eid;

	}
public function onRun(int $currentTick){
	if(empty($this->plugin->getDataFolder()."Arenas/")) return;

	$scan = scandir($this->plugin->getDataFolder()."Arenas/");

	foreach($scan as $files){

	if($files !== ".." and $files !== "."){

	$name = str_replace(".yml", "", $files);

$this->plugin->sendBossBar($name);

}
}


}
}
