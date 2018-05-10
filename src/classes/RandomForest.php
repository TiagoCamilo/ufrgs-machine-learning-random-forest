<?php

class RandomForest {
	private $treeList;

	public function addTree($tree) {
		$this->treeList[] = $tree;
	}

	public function getTreeList() {
		return $this->treeList;
	}

}