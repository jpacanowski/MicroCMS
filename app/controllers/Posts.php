<?php

class Posts extends Controller {

    public function __construct() {
        $this->postModel = $this->model('Post');
        $this->menuModel = $this->model('Navigation');
        $this->settingModel = $this->model('Setting');
    }

    public function index() {
        $posts = $this->postModel->getPosts();
        $menu = $this->menuModel->getNavigation();
        $settings = $this->settingModel->getSettings();
        $this->view('posts/index', [
            'posts' => $posts,
            'menu' => $menu,
            'cms' => $settings
        ]);
    }

    public function show($slug) {
        $post = $this->postModel->getPost($slug);
        $comments = $this->postModel->getPostComments($post->id);
        $settings = $this->settingModel->getSettings();
        $menu = $this->menuModel->getNavigation();
        $this->view('posts/single', [
            'post' => $post,
            'comments' => $comments,
            'cms' => $settings,
            'menu' => $menu
        ]);
    }

    public function tag($tag) {
        $posts = $this->postModel->getPostsByTag($tag);
        $settings = $this->settingModel->getSettings();
        $menu = $this->menuModel->getNavigation();
        $this->view('posts/index', [
            'posts' => $posts,
            'menu' => $menu,
            'cms' => $settings
        ]);
    }

    //public function edit($id) {
    //    $this->view('posts/edit');
    //}
}