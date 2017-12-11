<?php

class Comments extends Controller {

    public function __construct() {
        $this->commentModel = $this->model('Comment');
    }

    public function submit($postId) {

        if($_SERVER['REQUEST_METHOD'] == 'POST') {

            $content = trim($_POST['content']);
            $content = strip_tags($content);
            $content = stripslashes($content);

            $data = [
                'post_id' => $postId,
                'user_id' => $_SESSION['user_id'],
                'content' => $content,
                'content_err' => '',
            ];

            if(empty($data['content'])) {
                $data['content_err'] = 'Please enter the message';
            }

            if(empty($data['content_err'])) {
                $this->commentModel->addComment($data);
                header('Location: '.$_SERVER['HTTP_REFERER'].'#comments');
            }
            else {
                header('Location: '.$_SERVER['HTTP_REFERER'].'#comments');
            }
        }
    }
}