<?php

class ArvoreDecisao {
	private $data;
	private $node;
	private $attrList;

	public function __construct($data, $attrList) {
		$this->data = $data;
		$this->attrList = $attrList;
		$this->node = array();
	}

	public function build() {

		$labelListCounter = labelCounter($this->data);

		if (count($labelListCounter) == 1) {
			return key($labelListCounter) . " Origem1";
		}

		if (empty($this->attrList)) {
			return labelCommon($labelListCounter) . " Origem2";
		}

		$bestAttr = findBestAttr($this->data, $this->attrList);

		$this->attrList = array_diff($this->attrList, [$bestAttr]);

		$subDatas = array();
		foreach ($this->data as $line) {
			$newLine = $line;
			unset($newLine[$bestAttr]);
			$subDatas[$line[$bestAttr]][] = $newLine;
		}

		foreach ($subDatas as $attr => $subData) {
			$arvore = new ArvoreDecisao($subData, $this->attrList);
			$this->node[$attr][] = $arvore->build();
		}

		return $this->node;
	}

	private function labelCounter($data) {
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

	private function labelCommon($labelListCounter) {
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

	private function findBestAttr($data, $attrList) {
		foreach ($attrList as $key => $value) {
			return $value;
		}
	}

}