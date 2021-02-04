<?php

namespace App\View\Components\Page;

use Illuminate\View\Component;

class PageHeader extends Component
{
    /**
     * The page title.
     *
     * @var string
     */
    public $title;

    /**
     * The page sub-title.
     *
     * @var string
     */
    public $subTitle;

    /**
     * The page breadcrumbs.
     *
     * @var string
     */
    public $breadcrumbs;
    
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($title, $subTitle, $breadcrumbs)
    {
        $this->title = $title;
        $this->subTitle = $subTitle;
        $this->breadcrumbs = $breadcrumbs;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.page.page-header');
    }
}
