<?php

class InformationGain {
	private $data;
	private $attrList;

	public function __construct($data, $attrList) {
		$this->data = $data;
		$this->attrList = $attrList;
	}

	public function compute() {

		$labelListCounter = Util::labelCounter($this->data);

		$sizeData = count($this->data);
		$infoD = 0;
		foreach ($labelListCounter as $counter) {
			$infoD -= (($counter / $sizeData) * log(($counter / $sizeData), 2));

		}

		$infoDAttr = array();
		$lowerEntropy = 100;
		$value = 0;

		foreach ($this->attrList as $attr) {
			$counter = $this->attrCounter($attr);
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

		}

		return $bestAttr;
	}

	private function attrCounter($attrIndex) {
		$reverseLine = array_flip($this->data[0]);
		$labelIndex = array_pop($reverseLine);

		$labelListCounter = array();
		foreach ($this->data as $line) {
			if (isset($labelListCounter[$line[$attrIndex]][$line[$labelIndex]]) == false) {
				$labelListCounter[$line[$attrIndex]][$line[$labelIndex]] = 1;
				continue;
			}
			$labelListCounter[$line[$attrIndex]][$line[$labelIndex]] += 1;
		}
		return $labelListCounter;
	}

}