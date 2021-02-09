<?php

namespace vale\hcf\manager;

use vale\hcf\entities\PartnerPackageEntity;
use pocketmine\entity\Entity;

class EntityManager{

	public static function registerEntites(){
		Entity::registerEntity(PartnerPackageEntity::class, true);
	}

}
