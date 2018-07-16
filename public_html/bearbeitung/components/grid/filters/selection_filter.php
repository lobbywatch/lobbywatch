<?php

class SelectionOperator {
    const SELECTED = 'selected';
    const UNSELECTED = 'unselected';
}

class SelectionFilter {
    /** @var SelectionOperator */
    private $condition = SelectionOperator::SELECTED;
    /** @var array */
    private $selectedKeys = array();
    /** @var  Captions */
    private $localizer;

    /** @param Captions $localizer*/
    public function __construct($localizer) {
        $this->localizer = $localizer;
    }

    /** @param string $value */
    public function setCondition($value) {
        $this->condition = $value;
    }

    /** @return string */
    public function getCondition() {
        return $this->condition;
    }

    /** @param array $value */
    public function setSelectedKeys(array $value) {
        $this->selectedKeys = $value;
    }

    /** @return array */
    public function getSelectedKeys() {
        return $this->selectedKeys;
    }

    /** @return string */
    public function toString() {
        if ($this->condition == SelectionOperator::SELECTED) {
            return $this->localizer->GetMessageString('OnlySelectedRecordsAreShown');
        } else {
            return $this->localizer->GetMessageString('OnlyUnselectedRecordsAreShown');
        }
    }

    /** @return bool */
    public function isActive() {
        return count($this->selectedKeys) > 0;
    }

    /** @return array */
    public function serialize() {
        return array(
            'condition' => $this->condition,
            'keys' => $this->selectedKeys
        );
    }

    /** @param array $serializedArray */
    public function deserialize(array $serializedArray) {
        if (isset($serializedArray['condition'])) {
            $this->condition = $serializedArray['condition'];
        }
        if (isset($serializedArray['keys']) && is_array($serializedArray['keys'])) {
            $this->selectedKeys = $serializedArray['keys'];
        }
    }

    /** @param Dataset $dataset */
    public function applyFilter($dataset) {
        if ($this->condition == SelectionOperator::SELECTED) {
            $dataset->applyFilterBasedOnPrimaryKeyValuesSet($this->selectedKeys);
        } elseif ($this->condition == SelectionOperator::UNSELECTED) {
            $dataset->applyFilterBasedOnPrimaryKeyValuesSet($this->selectedKeys, true);
        }
    }

}
