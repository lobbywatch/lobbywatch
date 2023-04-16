<?php

class Navigation implements IteratorAggregate, Countable
{
    /**
     * @var array
     */
    private $navigation = array();

    /**
     * @var Page
     */
    private $page;

    /**
     * @param Page $page
     */
    public function __construct(Page $page)
    {
        $this->page = $page;
    }

    /**
     * @param string     $title
     * @param string     $url
     * @param Navigation $siblings
     * @return Navigation
     */
    public function append($title, $url = null, Navigation $siblings = null)
    {
        $this->navigation[] = array(
            'title' => $title,
            'url' => $url,
            'siblings' => $siblings,
        );

        return $this;
    }

    public function prepend($title, $url = null, Navigation $siblings = null)
    {
        array_unshift($this->navigation, array(
            'title' => $title,
            'url' => $url,
            'siblings' => $siblings,
        ));

        return $this;
    }

    /**
     * @param Renderer $renderer
     */
    public function Accept(Renderer $renderer)
    {
        $renderer->RenderNavigation($this);
    }

    /**
     * @return Page
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * {@inheritdoc}
     */
    #[\ReturnTypeWillChange]
    public function getIterator()
    {
        return new ArrayIterator($this->navigation);
    }

    /**
     * {@inheritdoc}
     */
    #[\ReturnTypeWillChange]
    public function count()
    {
        return count($this->navigation);
    }
}
