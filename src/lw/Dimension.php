<?php

namespace lw;
use pocketmine\network\mcpe\protocol\{ChangeDimensionPacket,PlayStatusPacket};
use pocketmine\scheduler\Task;
use lw\LuckyWars;
use pocketmine\plugin\Plugin;
use pocketmine\math\Vector3;
use pocketmine\{Player,Server};
class Dimension extends Task{
public function __construct(SkyWars $eid, PLayer $p){

		$this->pl = $eid;
		$this->p = $p;

	}

public function onRun(int $currentTick){
$pk = new PlayStatusPacket();
       $pk->status = 3;
       $this->p->dataPacket($pk);
}


}
