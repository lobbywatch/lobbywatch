<?php

interface ColumnInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getCaption();

    /**
     * @param Renderer $renderer
     *
     * @return mixed
     */
    public function getDisplayValue(Renderer $renderer);

    /**
     * @param Grid $grid
     */
    public function SetGrid(Grid $grid);

    /**
     * @return array
     */
    public function getViewData();
}
