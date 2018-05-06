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
		$minValue = null;
		$maxValue = null;
		foreach ($data as $line) {
			if ($line[$attrIndex] > $maxValue || $maxValue == null) {
				$maxValue = $line[$attrIndex];
			}

			if ($line[$attrIndex] < $minValue || $minValue == null) {
				$minValue = $line[$attrIndex];
			}
		}

		$amplitude = $maxValue - $minValue;
		$cutValue = $maxValue - ($amplitude / 2);

		return $cutValue;
	}

}