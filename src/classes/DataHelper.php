<?php

class DataHelper {
	public const FIRST_LINE = 0;

	private function getAttrIndexLabel($data) {
		$reverseLine = array_flip($data[DataHelper::FIRST_LINE]);
		return array_pop($reverseLine);
	}

	public static function labelCounter($data) {
		$labelIndex = DataHelper::getAttrIndexLabel($data);

		return DataHelper::attrNominalCounter($data, $labelIndex);
	}

	public static function labelMostCommonValue($data) {
		$labelIndex = DataHelper::getAttrIndexLabel($data);

		return DataHelper::attrMostCommonValue($data, $labelIndex);
	}

	public static function attrMostCommonValue($data, $attrIndex) {
		$attrCounter = DataHelper::labelCounter($data, $attrIndex);

		$valueMostCommon = 0;
		$maxValue = 0;
		foreach ($attrCounter as $value => $counter) {
			if ($counter > $maxValue) {
				$valueMostCommon = $value;
				$maxValue = $counter;
			}
		}
		return $valueMostCommon;
	}

	public static function attrCounter($data, $attrIndex) {
		//Se nao é valor numerico(continuo)
		if (is_numeric($data[DataHelper::FIRST_LINE][$attrIndex]) == false) {
			return DataHelper::attrNominalCounter($data, $attrIndex);
		} else {
			return DataHelper::attrContinuousCounter($data, $attrIndex);
		}
	}

	private static function attrNominalCounter($data, $attrIndex) {
		$attrListCounter = array();
		foreach ($data as $line) {
			@$attrListCounter[$line[$attrIndex]] += 1;
		}
		return $attrListCounter;
	}

	public static function attrContinuousCounter($data, $attrIndex) {
		$cutValue = DataHelper::cutValue($data, $attrIndex);

		$attrListCounter = array();
		foreach ($data as $line) {
			if ($line[$attrIndex] > $cutValue) {
				@$attrListCounter[">"][$line[$labelIndex]] += 1;
			} else {
				@$attrListCounter["<="][$line[$labelIndex]] += 1;
			}
		}

		return $attrListCounter;
	}

	public static function attrCounterWithLabel($data, $attrIndex) {
		//Se nao é valor numerico(continuo)
		if (is_numeric($data[DataHelper::FIRST_LINE][$attrIndex]) == false) {
			return DataHelper::attrNominalCounterWithLabel($data, $attrIndex);
		} else {
			return DataHelper::attrContinuousCounterWithLabel($data, $attrIndex);
		}
	}

	private static function attrNominalCounterWithLabel($data, $attrIndex) {
		$labelIndex = DataHelper::getAttrIndexLabel($data);

		$attrListCounter = array();
		foreach ($data as $line) {
			@$attrListCounter[$line[$attrIndex]][$line[$labelIndex]] += 1;
		}
		return $attrListCounter;
	}

	public static function attrContinuousCounterWithLabel($data, $attrIndex) {
		$labelIndex = DataHelper::getAttrIndexLabel($data);

		$cutValue = DataHelper::cutValue($data, $attrIndex);

		$attrListCounter = array();
		foreach ($data as $line) {
			if ($line[$attrIndex] > $cutValue) {
				@$attrListCounter[">"][$line[$labelIndex]] += 1;
			} else {
				@$attrListCounter["<="][$line[$labelIndex]] += 1;
			}
		}

		return $attrListCounter;
	}

	public static function cutValue($data, $attrIndex) {
		$somatorio = 0;
		foreach ($data as $line) {
			$somatorio += $line[$attrIndex];
		}
		$cutValue = $somatorio / count($data);

		return $cutValue;
	}

	public static function squareAttr($attrList) {
		$square = sqrt(count($attrList));
		shuffle($attrList);
		$returnList = [];
		for ($i = 0; $i <= $square; $i++) {
			$returnList[] = array_shift($attrList);
		}
		return $returnList;
	}

}