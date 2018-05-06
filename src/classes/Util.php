<?php

class Util {
	public const FIRST_LINE = 0;

	public static function labelCounter($data) {
		$reverseLine = array_flip($data[0]);
		$labelIndex = array_pop($reverseLine);

		$labelListCounter = array();
		foreach ($data as $line) {
			if (isset($labelListCounter[$line[$labelIndex]]) == false) {
				$labelListCounter[$line[$labelIndex]] = 1;
				continue;
			}
			$labelListCounter[$line[$labelIndex]] += 1;
		}
		return $labelListCounter;
	}

	public static function labelMostCommon($labelListCounter) {
		$labelBigger = 0;
		$maxValue = 0;
		foreach ($labelListCounter as $label => $counter) {
			if ($counter > $maxValue) {
				$labelBigger = $label;
				$maxValue = $counter;
			}
		}
		return $labelBigger;
	}

	public static function getCutValue($data, $attrIndex) {
		$somatorio = 0;
		foreach ($data as $line) {
			$somatorio += $line[$attrIndex];
		}
		$cutValue = $somatorio / count($data);

		return $cutValue;
	}

	public static function getSquareAttr($attrList) {
		$square = sqrt(count($attrList));
		shuffle($attrList);
		$returnList = [];
		for ($i = 0; $i <= $square; $i++) {
			$returnList[] = array_shift($attrList);
		}
		return $returnList;
	}

	public static function arrayRecursiveDiff($aArray1, $aArray2) {
		$aReturn = array();

		foreach ($aArray1 as $mKey => $mValue) {
			if (array_key_exists($mKey, $aArray2)) {
				if (is_array($mValue)) {
					$aRecursiveDiff = Util::arrayRecursiveDiff($mValue, $aArray2[$mKey]);
					if (count($aRecursiveDiff)) {$aReturn[$mKey] = $aRecursiveDiff;}
				} else {
					if ($mValue != $aArray2[$mKey]) {
						$aReturn[$mKey] = $mValue;
					}
				}
			} else {
				$aReturn[$mKey] = $mValue;
			}
		}
		return $aReturn;
	}

}