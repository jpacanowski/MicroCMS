<?php

class Post {

    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getPosts() {
        $this->db->query('  SELECT
                                posts.title,
                                posts.slug,
                                posts.created_at,
                                posts.visits_count,
                                SUBSTRING_INDEX(posts.content, " ", 80) AS postContent,
                                users.firstname,
                                users.lastname
                            FROM posts
                            INNER JOIN users
                            ON posts.author_id = users.id
                            ORDER BY posts.created_at DESC
        ');
        return $this->db->resultSet();
    }

    public function getPostsByTag($tag) {
        $this->db->query('  SELECT
                                posts.title,
                                posts.slug,
                                posts.created_at,
                                posts.visits_count,
                                SUBSTRING_INDEX(posts.content, " ", 80) AS postContent,
                                users.firstname,
                                users.lastname
                            FROM posts
                            INNER JOIN users
                            ON posts.author_id = users.id
                            WHERE tags LIKE "%":tag"%"
                            ORDER BY posts.created_at DESC
        ');
        $this->db->bind('tag', $tag);
        return $this->db->resultSet();
    }

    public function Dashboard_getPosts() {
        $this->db->query('  SELECT
                                posts.id,
                                posts.slug,
                                posts.title,
                                posts.visits_count,
                                posts.created_at
                            FROM
                                posts
                            ORDER BY
                                posts.title ASC
        ');
        return $this->db->resultSet();
    }

    public function getPost($slug) {

        // Increment visits_count
        $this->db->query('UPDATE posts SET visits_count = visits_count + 1 WHERE slug = :slug');
        $this->db->bind(':slug', $slug);
        $this->db->execute();

        // Get the post
        $this->db->query('  SELECT
                                posts.id,
                                posts.title,
                                posts.content,
                                posts.tags,
                                posts.created_at,
                                posts.visits_count,
                                users.firstname,
                                users.lastname
                            FROM
                                posts, users
                            WHERE
                                users.id = posts.author_id
                            AND
                                posts.slug = :slug');

        $this->db->bind(':slug', $slug);
        return $this->db->single();
    }

    public function Dashboard_getPost($postId) {
        
        // Get the post
        $this->db->query('  SELECT
                                posts.id,
                                posts.title,
                                posts.content,
                                posts.tags
                            FROM
                                posts
                            WHERE
                                posts.id = :id');
        
        $this->db->bind(':id', $postId);
        return $this->db->single();
    }

    public function getPostComments($postId) {

        $this->db->query('  SELECT
                                users.username AS username,
                                comments.content AS content,
                                comments.created_at AS created_at
                            FROM
                                comments, users
                            WHERE
                                comments.post_id = :post_id
                            AND
                                comments.user_id = users.id');

        $this->db->bind(':post_id', $postId);
        return $this->db->resultSet();
    }

    public function getPostsNumber() {
        $this->db->query('SELECT * FROM posts');
        $this->db->resultSet();
        return $this->db->rowCount();
    }

    public function addPost($post) {

        $this->db->query('  INSERT INTO posts SET
                            title = :title,
                            slug = :slug,
                            tags = :tags,
                            content = :content,
                            author_id = :author_id,
                            created_at = NOW(),
                            updated_at = NOW()
        ');

        $this->db->bind(':title', $post['title']);
        $this->db->bind(':slug', $post['slug']);
        $this->db->bind(':tags', $post['tags']);
        $this->db->bind(':content', $post['content']);
        $this->db->bind(':author_id', $post['author_id']);

        $this->db->execute();

        // Let's get the ID of the post we've just inserted to the database
        $this->db->query('SELECT id FROM posts ORDER BY id DESC LIMIT 1');

        return $this->db->single();
    }

    public function updatePost($post) {
        
        $this->db->query('  UPDATE posts SET
                                title = :title,
                                content = :content,
                                slug = :slug,
                                tags = :tags,
                                updated_at = NOW()
                            WHERE
                                id = :id
        ');
        
        $this->db->bind(':title', $post['title']);
        $this->db->bind(':content', $post['content']);
        $this->db->bind(':slug', $post['slug']);
        $this->db->bind(':tags', $post['tags']);
        $this->db->bind(':id', $post['id']);
        
        return $this->db->execute();
    }

    public function deletePost($postId) {
        
        $this->db->query('DELETE FROM posts WHERE id = :id');
        $this->db->bind(':id', $postId);
        $this->db->execute();

        $this->db->query('DELETE FROM comments WHERE post_id = :id');
        $this->db->bind(':id', $postId);
        return $this->db->execute();
    }
}