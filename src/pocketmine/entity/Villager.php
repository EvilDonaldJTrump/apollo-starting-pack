<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 *
*/

declare(strict_types=1);

namespace pocketmine\entity;

use pocketmine\nbt\tag\IntTag;
use pocketmine\network\mcpe\protocol\AddEntityPacket;
use pocketmine\Player;

class Villager extends Creature implements NPC, Ageable{
	const PROFESSION_FARMER = 0;
	const PROFESSION_LIBRARIAN = 1;
	const PROFESSION_PRIEST = 2;
	const PROFESSION_BLACKSMITH = 3;
	const PROFESSION_BUTCHER = 4;
	const PROFESSION_GENERIC = 5;

	const NETWORK_ID = 15;

	public $width = 0.6;
	public $length = 0.6;
	public $height = 1.8;

	public function getName() : string{
		return "Villager";
	}

	protected function initEntity(){
		parent::initEntity();
		if(!isset($this->namedtag->Profession)){
			$this->setProfession(self::PROFESSION_GENERIC);
		}
	}

	public function spawnTo(Player $player){
		$pk = new AddEntityPacket();
		$pk->entityRuntimeId = $this->getId();
		$pk->type = Villager::NETWORK_ID;
		$pk->position = $this->asVector3();
		$pk->motion = $this->getMotion();
		$pk->yaw = $this->yaw;
		$pk->pitch = $this->pitch;
		$pk->metadata = $this->dataProperties;
		$player->dataPacket($pk);

		parent::spawnTo($player);
	}

	/**
	 * Sets the villager profession
	 *
	 * @param int $profession
	 */
	public function setProfession(int $profession){
		$this->namedtag->Profession = new IntTag("Profession", $profession);
	}

	public function getProfession() : int{
		return (int) $this->namedtag["Profession"];
	}

	public function isBaby() : bool{
		return $this->getGenericFlag(self::DATA_FLAG_BABY);
	}
}