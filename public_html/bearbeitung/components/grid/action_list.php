<?php

class ActionList {

    const POSITION_LEFT = 'left';
    const POSITION_RIGHT = 'right';

    /**
     * @var string
     */
    private $position;

    /**
     * @var string
     */
    private $caption;

    /**
     * @var BaseRowOperation[]
     */
    private $operations = array();

    /**
     * Actions constructor.
     */
    public function __construct()
    {
        $this->setPosition(ActionList::POSITION_LEFT);
        $this->setCaption('Actions');
    }

    /**
     * @return mixed
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param mixed $position
     *
     * @return $this
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * @return string
     */
    public function getCaption()
    {
        return $this->caption;
    }

    /**
     * @param string $caption
     *
     * @return $this
     */
    public function setCaption($caption)
    {
        $this->caption = $caption;

        return $this;
    }

    /**
     * @return BaseRowOperation[]
     */
    public function getOperations()
    {
        return $this->operations;
    }

    /**
     * @param BaseRowOperation $operation
     *
     * @return $this
     */
    public function addOperation($operation)
    {
        $this->operations[] = $operation;

        return $this;
    }

    public function getViewData()
    {
        if (sizeof($this->getOperations()) == 0) {
            return false;
        }

        return array(
            'PositionIsLeft' => ActionList::POSITION_LEFT == $this->getPosition(),
            'PositionIsRight' => ActionList::POSITION_RIGHT == $this->getPosition(),
            'Caption' => $this->getCaption(),
            'Operations' => $this->getOperations()
        );
    }

    public function hasEditOperation() {
        foreach ($this->operations as $operation) {
            if ($operation->isEditOperation()) {
                return true;
            }
        }
        return false;
    }
}
