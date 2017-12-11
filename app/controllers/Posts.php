<?php

class Posts extends Controller {

    public function __construct() {
        $this->postModel = $this->model('Post');
        $this->menuModel = $this->model('Navigation');
    }

    public function index() {
        $posts = $this->postModel->getPosts();
        $menu = $this->menuModel->getNavigation();
        $this->view('posts/index', [
            'posts' => $posts,
            'menu' => $menu
        ]);
    }

    public function show($slug) {
        $post = $this->postModel->getPost($slug);
        $comments = $this->postModel->getPostComments($post->id);
        $menu = $this->menuModel->getNavigation();
        $this->view('posts/single', [
            'post' => $post,
            'comments' => $comments,
            'menu' => $menu
        ]);
    }

    public function tag($tag) {
        $posts = $this->postModel->getPostsByTag($tag);
        $menu = $this->menuModel->getNavigation();
        $this->view('posts/index', [
            'posts' => $posts,
            'menu' => $menu
        ]);
    }

    //public function edit($id) {
    //    $this->view('posts/edit');
    //}
}