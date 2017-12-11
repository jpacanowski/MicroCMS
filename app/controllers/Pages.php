<?php

class Pages extends Controller {

    public function __construct() {
        $this->pageModel = $this->model('Page');
        $this->menuModel = $this->model('Navigation');
    }

    public function show($slug) {
        $page = $this->pageModel->getPage($slug);
        $menu = $this->menuModel->getNavigation();
        $this->view('pages/single', [
            'page' => $page,
            'menu' => $menu
        ]);
    }
}