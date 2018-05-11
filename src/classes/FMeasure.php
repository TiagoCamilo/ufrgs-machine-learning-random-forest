<?php

class FMeasure {

	private $truePositive;
	private $trueNegative;
	private $falsePositive;
	private $falseNegative;
	private $F1Measure;
	private $rev;
	private $prec;

	public function __construct() {

		$this->truePositive = 0;
		$this->trueNegative = 0;
		$this->falsePositive = 0;
		$this->falseNegative = 0;
		$this->F1Measure = 0;
		$this->rev = 0;
		$this->prec = 0;

	}

	public function getTruePositive() {
		return $this->truePositive;
	}
	public function getTrueNegative() {
		return $this->trueNegative;
	}
	public function getFalsePositive() {
		return $this->falsePositive;
	}
	public function getFalseNegative() {
		return $this->falseNegative;
	}

	public function compute($instancia, $result, $positiveValue) {
		if ($instancia[count($instancia) - 1] == $result) {
			if ($result == $positiveValue) {
				$this->truePositive++;
			} else {
				$this->trueNegative++;

			}

		} else {
			if ($result == $positiveValue) {
				$this->falsePositive++;
			} else {
				$this->falseNegative++;
			}
		}
	}

	public function calcMeasure() {
		if (($this->truePositive + $this->falseNegative) === 0) {
			$this->rev = $this->truePositive;
		} else {
			$this->rev = $this->truePositive / ($this->truePositive + $this->falseNegative);

		}
		if (($this->truePositive + $this->falsePositive) === 0) {
			$this->prec = $this->truePositive;

		} else {
			$this->prec = $this->truePositive / ($this->truePositive + $this->falsePositive);
		}
		if (($this->prec + $this->rev) === 0) {
			$this->f1Measure = (2 * ($this->prec * $this->rev));

		} else {
			$this->f1Measure = (2 * ($this->prec * $this->rev)) / ($this->prec + $this->rev);
		}

		return $this->f1Measure;

	}
}
