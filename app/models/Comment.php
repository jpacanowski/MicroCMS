<?php

class Comment {

    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getComment($id) {
        $this->db->query('SELECT * FROM comments WHERE id = :id');
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function getComments() {
        $this->db->query('  SELECT
                                comments.id AS commentId,
                                comments.content AS commentContent,
                                comments.created_at AS commentCreatedAt,
                                comments.approved AS commentApproved,
                                posts.title AS postTitle,
                                posts.slug AS postSlug,
                                users.username AS commentUsername
                            FROM
                                comments, posts, users
                            WHERE
                                comments.post_id = posts.id
                            AND
                                comments.user_id = users.id
                            ORDER BY
                                commentCreatedAt DESC
        ');
        return $this->db->resultSet();
    }

    public function getCommentsNumber() {
        $this->db->query('SELECT * FROM comments');
        $this->db->resultSet();
        return $this->db->rowCount();
    }

    public function addComment($data) {
        $this->db->query('  INSERT INTO comments SET
                            post_id = :post_id,
                            user_id = :user_id,
                            content = :content
        ');
        $this->db->bind('post_id', $data['post_id']);
        $this->db->bind('user_id', $data['user_id']);
        $this->db->bind('content', $data['content']);
        return $this->db->execute();
    }

    public function approveComment($id) {
        $this->db->query('UPDATE comments SET approved = 1 WHERE id = :id');
        $this->db->bind('id', $id);
        return $this->db->execute();
    }

    public function updateComment($data) {
        $this->db->query('UPDATE comments SET content = :content WHERE id = :id');
        $this->db->bind('id', $data['id']);
        $this->db->bind('content', $data['msg']);
        return $this->db->execute();
    }

    public function deleteComment($id) {
        $this->db->query('DELETE FROM comments WHERE id = :id');
        $this->db->bind('id', $id);
        return $this->db->execute();
    }
}