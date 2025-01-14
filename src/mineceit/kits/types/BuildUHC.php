<?php

declare(strict_types=1);

namespace mineceit\kits\types;

use mineceit\game\items\GoldenApple;
use mineceit\kits\DefaultKit;
use mineceit\kits\info\MiscKitInfo;
use mineceit\MineceitUtil;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;

class BuildUHC extends DefaultKit{

	/** @var MiscKitInfo */
	private $miscKitInfo;

	public function __construct(float $xkb = 0.4, float $ykb = 0.4, int $speed = 10){
		parent::__construct('BuildUHC', $xkb, $ykb, $speed);
		$this->miscKitInfo = new MiscKitInfo(
			'textures/items/bucket_lava.png',
			true,
			true,
			false,
			true
		);
	}

	/**
	 * @return MiscKitInfo
	 *
	 * Gets the misc kit information.
	 */
	public function getMiscKitInfo() : MiscKitInfo{
		return $this->miscKitInfo;
	}

	/**
	 * Initializes the items within the abstract kit.
	 */
	protected function initItems() : void{
		$e1 = new EnchantmentInstance(Enchantment::getEnchantment(0), 2);
		$e2 = new EnchantmentInstance(Enchantment::getEnchantment(17), 3);
		$e3 = new EnchantmentInstance(Enchantment::getEnchantment(2), 4);
		$e4 = new EnchantmentInstance(Enchantment::getEnchantment(9), 3);
		$e5 = new EnchantmentInstance(Enchantment::getEnchantment(19), 3);

		$this->items = [
			MineceitUtil::createItem(276, 0, 1, [$e4, $e2]),
			MineceitUtil::createItem(346),
			MineceitUtil::createItem(261, 0, 1, [$e5, $e2]),
			MineceitUtil::createItem(325, 10),
			MineceitUtil::createItem(325, 8),
			GoldenApple::create(false, 6),
			GoldenApple::create(true, 3),
			MineceitUtil::createItem(4, 0, 64),
			MineceitUtil::createItem(262, 0, 8),
			MineceitUtil::createItem(325, 10),
			MineceitUtil::createItem(325, 8),
			MineceitUtil::createItem(5, 2, 64),
			MineceitUtil::createItem(262, 0, 32),
			MineceitUtil::createItem(279),
			MineceitUtil::createItem(278)
		];

		$helmet = MineceitUtil::createItem(310, 0, 1, [$e1, $e4]);
		$chest = MineceitUtil::createItem(311, 0, 1, [$e1, $e4]);
		$legs = MineceitUtil::createItem(
			312,
			0,
			1,
			[$e1, $e4, $e3]
		);
		$boots = MineceitUtil::createItem(313, 0, 1, [$e1, $e4]);
		$this->armor = [$helmet, $chest, $legs, $boots];
	}
}
