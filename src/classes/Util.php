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

	public function getCutValue($data, $attrIndex) {
		$somatorio = 0;
		foreach ($data as $line) {
			$somatorio += $line[$attrIndex];
		}
		$cutValue = $somatorio / count($data);

		return $cutValue;
	}

	public function getSquareAttr($attrList) {
		$square = sqrt(count($attrList));
		return array_rand($attrList, $square);
	}

}