<?php

namespace lw\PacketSound;
use pocketmine\level\sound\GenericSound;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\LevelEventPacket;
class HitSound extends GenericSound{
public function __construct(Vector3 $pos, float $pitch = 0){

		parent::__construct($pos, LevelEventPacket::EVENT_SOUND_ARMOR_STAND_HIT, $pitch);

	}
}
