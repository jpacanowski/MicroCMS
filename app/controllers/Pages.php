<?php

class Pages extends Controller {

    public function __construct() {
        $this->pageModel = $this->model('Page');
        $this->menuModel = $this->model('Navigation');
        $this->settingModel = $this->model('Setting');
    }

    public function show($slug) {
        $page = $this->pageModel->getPage($slug);
        $menu = $this->menuModel->getNavigation();
        $settings = $this->settingModel->getSettings();
        $this->view('pages/single', [
            'page' => $page,
            'menu' => $menu,
            'cms' => $settings
        ]);
    }
}