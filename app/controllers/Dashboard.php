<?php

class Dashboard extends Controller {

    public function __construct() {

        if(!isUserLoggedIn()) {
            redirect('users/login');
        }

        $this->postModel = $this->model('Post');
        $this->pageModel = $this->model('Page');
        $this->userModel = $this->model('User');
        $this->commentModel = $this->model('Comment');
        $this->settingModel = $this->model('Setting');
        $this->menuModel = $this->model('Navigation');
    }

    public function index() {
        $this->view('dashboard/index', [
            'php_version' => substr(phpversion(), 0, 6),
            'posts_number' => $this->postModel->getPostsNumber(),
            'pages_number' => $this->pageModel->getPagesNumber(),
            'users_number' => $this->userModel->getUsersNumber(),
            'comments_number' => $this->commentModel->getCommentsNumber(),
        ]);
    }

    public function profile() {

        if($_SERVER['REQUEST_METHOD'] == 'GET') {
            $user = $this->userModel->getUser(getUserId());
            $this->view('dashboard/profile', ['user' => $user]);
        }
        elseif($_SERVER['REQUEST_METHOD'] == 'POST') {

            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            $data = [
                'username' => trim($_POST['username']),
                'firstname' => trim($_POST['firstname']),
                'lastname' => trim($_POST['lastname']),
                'email' => trim($_POST['email']),
                'user_id' => trim($_SESSION['user_id']),
            ];

            if($this->userModel->updateUserProfile($data)) {
                flash('profile_updated', 'Your profile has been updated');
                redirect('dashboard/profile');
            }
            else {
                die('Something went wrong');
            }
        }
    }

    public function posts() {
        $posts = $this->postModel->Dashboard_getPosts();
        $this->view('dashboard/posts', ['posts' => $posts]);
    }

    public function pages() {
        $pages = $this->pageModel->Dashboard_getPages();
        $this->view('dashboard/pages', ['pages' => $pages]);
    }

    public function post() {

        $args = func_num_args();

        if($_SERVER['REQUEST_METHOD'] == 'GET') {

            if($args === 1 && func_get_arg(0) === 'add') {
                $this->view('dashboard/add_post');
            }
            elseif($args === 2 && func_get_arg(0) === 'edit') {
                $postId = func_get_arg(1);
                $post = $this->postModel->Dashboard_getPost($postId);
                $this->view('dashboard/edit_post', ['post' => $post]);
            }
            else {
                redirect('dashboard');
            }
        }
        elseif($_SERVER['REQUEST_METHOD'] == 'POST') {

            if($args === 1 && func_get_arg(0) == 'store') {

                $data = [
                    'title' => trim($_POST['post_title']),
                    'content' => trim($_POST['post_content']),
                    'slug' => slugify(trim($_POST['post_title'])),
                    'tags' => trim($_POST['post_tags']),
                    'author_id' => $_SESSION['user_id'],
                    'title_err' => '',
                    'content_err' => '',
                ];
                
                // Validate
                if(empty($data['title'])) {
                    $data['title_err'] = 'Please enter post title';
                }
                if(empty($data['content'])) {
                    $data['content_err'] = 'Please enter post content';
                }
                
                if(empty($_POST['title_err']) && empty($_POST['content_err'])) {

                    if($post = $this->postModel->addPost($data)) {
                        flash('post_message', 'Post has been added');
                        redirect('dashboard/post/edit/'.$post->id);
                    }
                    else {
                        die('Something went wrong');
                    }
                }
                else {
                    $this->view('dashboard/add_post', $data);
                }
            }

            elseif($args === 2 && func_get_arg(0) == 'update') {
                
                $data = [
                    'id' => '',
                    'title' => trim($_POST['post_title']),
                    'content' => trim($_POST['post_content']),
                    'slug' => slugify(trim($_POST['post_title'])),
                    'tags' => trim($_POST['post_tags']),
                    'title_err' => '',
                    'content_err' => '',
                ];
                                
                // Validate
                if(empty($data['title'])) {
                    $data['title_err'] = 'Please enter post title';
                }
                if(empty($data['content'])) {
                    $data['content_err'] = 'Please enter post content';
                }
                                
                if(empty($_POST['title_err']) && empty($_POST['content_err'])) {
                
                    $data['id'] = func_get_arg(1);
                    
                    if($this->postModel->updatePost($data)) {
                        flash('post_message', 'Post has been updated');
                        redirect('dashboard/post/edit/'.$data['id']);
                    }
                    else {
                        die('Something went wrong');
                    }
                }
                else {
                    redirect('dashboard/post/edit/'.$data['id']);
                }
            }

            elseif($args === 2 && func_get_arg(0) == 'delete') {

                $postId = func_get_arg(1);

                if($this->postModel->deletePost($postId)) {
                    flash('post_message', 'Post has been deleted');
                    redirect('dashboard/posts');
                }
                else {
                    die('Something went wrong');
                }
            }
        }
    }

    public function page() {
        
        $args = func_num_args();

        if($_SERVER['REQUEST_METHOD'] == 'GET') {

            if($args === 1 && func_get_arg(0) === 'add') {
                $this->view('dashboard/add_page');
            }
            elseif($args === 2 && func_get_arg(0) === 'edit') {
                $postId = func_get_arg(1);
                $page = $this->pageModel->Dashboard_getPage($postId);
                $this->view('dashboard/edit_page', ['page' => $page]);
            }
            else {
                redirect('dashboard');
            }
        }
        elseif($_SERVER['REQUEST_METHOD'] == 'POST') {

            if($args === 1 && func_get_arg(0) == 'store') {

                $data = [
                    'title' => trim($_POST['page_title']),
                    'content' => trim($_POST['page_content']),
                    'slug' => slugify(trim($_POST['page_title'])),
                    'author_id' => $_SESSION['user_id'],
                    'title_err' => '',
                    'content_err' => '',
                ];
                
                // Validate
                if(empty($data['title'])) {
                    $data['title_err'] = 'Please enter page title';
                }
                if(empty($data['content'])) {
                    $data['content_err'] = 'Please enter page content';
                }
                
                if(empty($_POST['title_err']) && empty($_POST['content_err'])) {

                    if($page = $this->pageModel->addPage($data)) {
                        flash('page_message', 'Page has been added');
                        redirect('dashboard/page/edit/'.$page->id);
                    }
                    else {
                        die('Something went wrong');
                    }
                }
                else {
                    $this->view('dashboard/add_page', $data);
                }
            }

            elseif($args === 2 && func_get_arg(0) == 'update') {
                
                $data = [
                    'id' => '',
                    'title' => trim($_POST['page_title']),
                    'content' => trim($_POST['page_content']),
                    'title_err' => '',
                    'content_err' => '',
                ];
                                
                // Validate
                if(empty($data['title'])) {
                    $data['title_err'] = 'Please enter page title';
                }
                if(empty($data['content'])) {
                    $data['content_err'] = 'Please enter page content';
                }
                                
                if(empty($_POST['title_err']) && empty($_POST['content_err'])) {
                
                    $data['id'] = func_get_arg(1);
                    
                    if($this->pageModel->updatePage($data)) {
                        flash('page_message', 'Page has been updated');
                        redirect('dashboard/page/edit/'.$data['id']);
                    }
                    else {
                        die('Something went wrong');
                    }
                }
                else {
                    redirect('dashboard/page/edit/'.$data['id']);
                }
            }

            elseif($args === 2 && func_get_arg(0) == 'delete') {

                $pageId = func_get_arg(1);

                if($this->pageModel->deletePage($pageId)) {
                    flash('page_message', 'Page has been deleted');
                    redirect('dashboard/pages');
                }
                else {
                    die('Something went wrong');
                }
            }
        }
    }

    public function navigation($action = '', $itemId = '') {

        if($_SERVER['REQUEST_METHOD'] == 'GET') {

            if($action === 'add') {
                $this->view('dashboard/add_link');
            }
            elseif($action === 'edit') {
                $link = $this->menuModel->getNavigationLink($itemId);
                $this->view('dashboard/edit_link', ['link' => $link]);
            }
            else {
                $menu = $this->menuModel->Dashboard_getNavigation();
                $this->view('dashboard/navigation', ['menu' => $menu]);
            }
        }
        elseif($_SERVER['REQUEST_METHOD'] == 'POST') {

            if($action === 'store') {

                $data = [
                    'link_text' => trim($_POST['link_text']),
                    'link_url' => trim($_POST['link_url']),
                    'link_text_err' => '',
                    'link_url_err' => '',
                ];
    
                if(empty($data['link_text'])) {
                    $data['link_text_err'] = 'Please enter the link text';
                }
                if(empty($data['link_url'])) {
                    $data['link_url_err'] = 'Please enter the link URL';
                }
    
                if(empty($data['link_text_err']) && empty($data['link_url_err'])) {
    
                    if($this->menuModel->storeNavigationLink($data)) {
                        flash('navigation_message', 'Link have been added');
                        redirect('dashboard/navigation');
                    }
                    else {
                        die('Something went wrong');
                    }
                }
                else {
                    $this->view('dashboard/add_link', $data);
                }
            }
            elseif($action === 'update') {
                
                $data = [
                    'link_id' => $itemId,
                    'link_text' => trim($_POST['link_text']),
                    'link_url' => trim($_POST['link_url']),
                    'link_text_err' => '',
                    'link_url_err' => '',
                ];
    
                if(empty($data['link_text'])) {
                    $data['link_text_err'] = 'Please enter the link text';
                }
                if(empty($data['link_url'])) {
                    $data['link_url_err'] = 'Please enter the link URL';
                }
    
                if(empty($data['link_text_err']) && empty($data['link_url_err'])) {
    
                    if($this->menuModel->updateNavigationLink($data)) {
                        flash('navigation_message', 'Link have been updated');
                        redirect('dashboard/navigation');
                    }
                    else {
                        die('Something went wrong');
                    }
                }
                else {
                    $this->view('dashboard/edit_link', $data);
                }
            }
            elseif($action === 'disable') {
                if($this->menuModel->disableNavigationLink($itemId)) {
                    flash('navigation_message', 'Link have been disabled');
                    redirect('dashboard/navigation');
                }
            }
            elseif($action === 'enable') {
                if($this->menuModel->enableNavigationLink($itemId)) {
                    flash('navigation_message', 'Link have been enabled');
                    redirect('dashboard/navigation');
                }
            }
            elseif($action === 'delete') {
                if($this->menuModel->deleteNavigationLink($itemId)) {
                    flash('navigation_message', 'Link have been deleted');
                    redirect('dashboard/navigation');
                }
            }
        }
    }

    public function settings() {

        if($_SERVER['REQUEST_METHOD'] == 'POST') {

            $data = [
                'site_title'        => trim($_POST['site_title']),
                'site_tagline'      => trim($_POST['site_tagline']),
                'site_description'  => trim($_POST['site_description']),
                'site_keywords'     => trim($_POST['site_keywords']),
                'site_url'          => trim($_POST['site_url']),
                'posts_per_page'    => trim($_POST['posts_per_page']),
            ];

            if($this->settingModel->updateSettings($data)) {
                flash('settings_updated', 'Settings have been updated');
                redirect('dashboard/settings');
            }
            else {
                die('Something went wrong');
            }
        }
        elseif($_SERVER['REQUEST_METHOD'] == 'GET') {
            $settings = $this->settingModel->getSettings();
            $this->view('dashboard/settings', ['settings' => $settings]);
        }
    }

    // public function themes() {

    //     $themes = array();
    //     $dir = '../app/views/templates';
    //     $cdir = scandir($dir);

    //     foreach ($cdir as $key => $value) {
    //         if (!in_array($value, array(".", ".."))) {
    //             if (is_dir($dir . DIRECTORY_SEPARATOR . $value)) {
    //                 $themes[] = /* $dir . DIRECTORY_SEPARATOR . */ $value;
    //             }
    //             else {
    //                 $themes[] = $value;
    //             }
    //         }
    //     } 

    //     $this->view('dashboard/themes', ['themes' => $themes]);
    // }

    public function comments() {
        $comments = $this->commentModel->getComments();
        $this->view('dashboard/comments', ['comments' => $comments]);
    }

    public function comment($action, $id) {

        if($_SERVER['REQUEST_METHOD'] == 'POST') {

            if($action == 'delete') {
                if($this->commentModel->deleteComment($id)) {
                    flash('comment_message', 'Comment has been deleted');
                    redirect('dashboard/comments');
                }
            }
            elseif($action == 'update') {

                $msg = trim($_POST['comment']);
                $msg = strip_tags($msg);
                $msg = stripslashes($msg);
                
                $data = [
                    'id' => $id,
                    'msg' => $msg,
                ];

                if($this->commentModel->updateComment($data)) {
                    flash('comment_message', 'Comment has been updated');
                    redirect('dashboard/comments');
                }
            }
            elseif($action == 'approve') {
                if($this->commentModel->approveComment($id)) {
                    flash('comment_message', 'Comment has been approved');
                    redirect('dashboard/comments');
                }
            }
        }
        elseif($_SERVER['REQUEST_METHOD'] == 'GET') {

            if($action == 'edit') {
                $comment = $this->commentModel->getComment($id);
                $this->view('dashboard/edit_comment', [
                    'comment' => $comment
                ]);
            }
        }
    }

    public function users($action = '', $userId = '') {

        if($_SERVER['REQUEST_METHOD'] == 'GET') {
            if($action == '') {
                $users = $this->userModel->getUsers();
                $this->view('dashboard/users', ['users' => $users]);
            }
            elseif($action == 'create') {
                $this->view('dashboard/add_user');
            }
            elseif($action == 'edit') {
                $user = $this->userModel->getUser($userId);
                $this->view('dashboard/edit_user', ['user' => $user]);
            }
        }
        elseif($_SERVER['REQUEST_METHOD'] == 'POST') {

            if($action == 'store') {

                $data = [
                    'username' => trim($_POST['username']),
                    'firstname' => trim($_POST['firstname']),
                    'lastname' => trim($_POST['lastname']),
                    'email' => trim($_POST['email']),
                    'password' => trim($_POST['password']),
                    'role' => trim($_POST['role']),
                    'username_err' => '',
                    'password_err' => '',
                ];
                
                // Validate
                if(empty($data['username'])) {
                    $data['username_err'] = 'Please enter username';
                }
                if(empty($data['password'])) {
                    $data['password_err'] = 'Please enter password';
                }
                
                if(empty($_POST['username_err']) && empty($_POST['password_err'])) {

                    // Hash password
                    $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                    
                    if($user = $this->userModel->addUser($data)) {
                        flash('user_message', 'User has been added');
                        redirect('dashboard/users');
                    }
                    else {
                        die('Something went wrong');
                    }
                }
                else {
                    $this->view('dashboard/add_user', $data);
                }
            }
            elseif($action == 'update') {
                
                $data = [
                    'id' => $userId,
                    'username' => trim($_POST['username']),
                    'firstname' => trim($_POST['firstname']),
                    'lastname' => trim($_POST['lastname']),
                    'email' => trim($_POST['email']),
                    //'password' => trim($_POST['password']),
                    'role' => trim($_POST['role']),
                    'username_err' => '',
                    //'password_err' => '',
                ];
                
                // Validate
                if(empty($data['username'])) {
                    $data['username_err'] = 'Please enter username';
                }
                // if(empty($data['password'])) {
                //     $data['password_err'] = 'Please enter password';
                // }
                
                if(empty($_POST['username_err']) /* && empty($_POST['password_err']) */) {

                    // Hash password
                    // $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                    
                    if($user = $this->userModel->updateUser($data)) {
                        flash('user_message', 'User has been updated');
                        redirect('dashboard/users');
                    }
                    else {
                        die('Something went wrong');
                    }
                }
                else {
                    $this->view('dashboard/add_user', $data);
                }
            }
            elseif($action == 'delete') {
                if($this->userModel->deleteUser($userId)) {
                    flash('user_message', 'User has been deleted');
                    redirect('dashboard/users');
                }
            }
        }
    }

    public function about() {
        $this->view('dashboard/about');
    }
}