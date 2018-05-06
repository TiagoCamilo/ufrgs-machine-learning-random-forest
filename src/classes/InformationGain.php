<?php

class InformationGain {
	private $data;
	private $attrList;

	public function __construct($data, $attrList) {
		$this->data = $data;
		$this->attrList = $attrList;
	}

	public function compute() {

		$labelListCounter = DataHelper::labelCounter($this->data);

		$sizeData = count($this->data);
		$infoD = 0;

		foreach ($labelListCounter as $counter) {

			$infoD -= (($counter / $sizeData) * log(($counter / $sizeData), 2));
		}

		$infoDAttr = array();
		$lowerEntropy = 100;
		$value = 0;

		foreach ($this->attrList as $attr) {
			$counter = DataHelper::attrCounterWithLabel($this->data, $attr);

			$avgEntropy = 0;
			foreach ($counter as $key => $value) {
				$sizeAttr = 0;
				foreach ($value as $index) {
					$sizeAttr += $index;
				}

				$infoDAttr[$key] = 0;
				foreach ($value as $index) {
					$infoDAttr[$key] -= (($index / $sizeAttr) * log(($index / $sizeAttr), 2));
				}
				$avgEntropy += ($sizeAttr / $sizeData) * $infoDAttr[$key];
			}

			if ($avgEntropy < $lowerEntropy) {
				$lowerEntropy = $avgEntropy;
				$bestAttr = $attr;
			}

			$diff = $infoD - $avgEntropy;

		}

		return $bestAttr;
	}

}