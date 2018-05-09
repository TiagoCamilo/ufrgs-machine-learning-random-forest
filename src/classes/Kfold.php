<?php

class Kfold {
	private $data;
	private $nFolds;
	private $folds;

	public function __construct($data, $nFolds) {
		$this->data = $data;
		$this->nFolds = $nFolds;
		$this->folds = [];
		$this->processFold();
	}

	public function getFolds() {
		return $this->folds;
	}

	private function processFold() {
		$indexLabel = count($this->data[0]) - 1;

		foreach ($this->data as $key => $value) {
			$listByLabel[$value[$indexLabel]][] = $value;
		}

		$i = 0;
		foreach ($listByLabel as $label => $subData) {
			foreach ($subData as $line) {
				$this->folds[$i][] = $line;
				$i++;
				if ($i == $this->nFolds) {
					$i = 0;
				}
			}
		}
		return true;
	}

}