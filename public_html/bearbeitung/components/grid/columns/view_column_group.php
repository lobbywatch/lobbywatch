<?php

class ViewColumnGroup
{
    /**
     * @var string
     */
    private $caption;

    /**
     * @var ViewColumnGroup[]
     */
    private $children;

    /**
     * @param string            $caption
     * @param ViewColumnGroup[] $children
     */
    public function __construct($caption, array $children = array())
    {
        $this->caption = $caption;
        $this->children = $children;
    }

    /**
     * @return string
     */
    public function getCaption()
    {
        return $this->caption;
    }

    /**
     * @param ViewColumnGroup $child
     *
     * @return $this
     */
    public function add(ViewColumnGroup $child)
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * @return ViewColumnGroup[]
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @return AbstractViewColumn[]
     */
    public function getLeafs()
    {
        $result = array();

        foreach ($this->getChildren() as $child) {
            if ($child->getChildrenCount() > 0) {
                $result = array_merge($result, $child->getLeafs());
            } else {
                $result[] = $child;
            }
        }

        return $result;
    }

    /**
     * @return int
     */
    public function getChildrenCount()
    {
        return count($this->getChildren());
    }

    private function getEmptyChildren($mode = ColumnVisibility::LARGE_DESKTOP)
    {
        $result = array();
        foreach ($this->getChildren() as $child) {
            if ($child->getMinimalVisibility() <= $mode
                && $child->getChildrenCount() === 0
            ) {
                $result[] = $child;
            }
        }

        return $result;
    }

    /** @return ViewColumnGroup[] */
    private function getNonEmptyChildren($mode = ColumnVisibility::LARGE_DESKTOP)
    {
        $result = array();
        foreach ($this->getChildren() as $child) {
            if ($child->getMinimalVisibility() <= $mode
                && $child->getChildrenCount() > 0
            ) {
                $result[] = $child;
            }
        }

        return $result;
    }

    /**
     * @return int
     */
    public function getColSpan($mode = ColumnVisibility::LARGE_DESKTOP)
    {
        $result = count($this->getEmptyChildren($mode));
        foreach ($this->getNonEmptyChildren($mode) as $child) {
            $result += $child->getColSpan($mode);
        }

        return max(1, $result);
    }

    /**
     * @return int
     */
    public function getDepth()
    {
        $depth = 0;
        foreach ($this->getNonEmptyChildren() as $child) {
            $depth = max($depth, $child->getDepth());
        }
        return min(1, count($this->getChildren())) + $depth;
    }

    /**
     * @param int $depth
     * @param int $rootDepth
     *
     * @return ViewColumnGroup[]
     */
    public function getAtDepth($depth, $rootDepth = null)
    {
        if (is_null($rootDepth)) {
            $rootDepth = $this->getDepth();
        }

        $targetDepth = ($rootDepth - $depth);
        if ($targetDepth === $this->getDepth()) {
            return $this->getChildren();
        }

        if ($targetDepth > $this->getDepth() || $targetDepth < 1) {
            return array();
        }

        $children = array();
        foreach ($this->getChildren() as $child) {
            $children = array_merge($children, $child->getAtDepth($depth, $rootDepth));
        }

        return $children;
    }

    /**
     * @return int
     */
    public function getVisibilityMap()
    {
        $result = array(
            ColumnVisibility::PHONE => 0,
            ColumnVisibility::TABLET => 0,
            ColumnVisibility::DESKTOP => 0,
            ColumnVisibility::LARGE_DESKTOP => 0,
        );

        foreach ($this->children as $child) {
            $childMinimalVisibility = $child->getMinimalVisibility();
            foreach (array_keys($result) as $mode) {
                if ($mode >= $childMinimalVisibility) {
                    $result[$mode] += $child->getColSpan($mode);
                }
            }
        }

        return $result;
    }

    public function getMinimalVisibility()
    {
        $minVisibilities = array();
        foreach ($this->children as $child) {
            $minVisibilities[] = $child->getMinimalVisibility();
        }
        return ColumnVisibility::getMinimalVisibility($minVisibilities);
    }

    public function GetGridColumnClass()
    {
        $classes = array();

        $minimalVisibility = $this->getMinimalVisibility();
        if ($minimalVisibility > ColumnVisibility::PHONE) {
            $classes[] = 'hidden-xs';
        }
        if ($minimalVisibility > ColumnVisibility::TABLET) {
            $classes[] = 'hidden-sm';
        }
        if ($minimalVisibility > ColumnVisibility::DESKTOP) {
            $classes[] = 'hidden-md';
        }

        return implode(' ', $classes);
    }
}
