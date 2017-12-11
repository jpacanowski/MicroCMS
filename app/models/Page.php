<?php

class Page {

    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getPages() {
        $this->db->query('SELECT * FROM pages');
        return $this->db->resultSet();
    }

    public function Dashboard_getPages() {
        $this->db->query('  SELECT
                                pages.id,
                                pages.slug,
                                pages.title,
                                pages.visits_count,
                                pages.created_at
                            FROM
                                pages
                            ORDER BY
                                pages.title ASC
        ');
        return $this->db->resultSet();
    }

    public function getPage($slug) {

        // Increment visits_count
        $this->db->query('UPDATE pages SET visits_count = visits_count + 1 WHERE slug = :slug');
        $this->db->bind(':slug', $slug);
        $this->db->execute();

        // Get the page
        $this->db->query('  SELECT
                                pages.id,
                                pages.title,
                                pages.content,
                                pages.created_at,
                                pages.visits_count
                            FROM
                                pages
                            WHERE
                                pages.slug = :slug');

        $this->db->bind(':slug', $slug);
        return $this->db->single();
    }

    public function Dashboard_getPage($pageId) {
        
        // Get the post
        $this->db->query('  SELECT
                                pages.id,
                                pages.title,
                                pages.content
                            FROM
                                pages
                            WHERE
                                pages.id = :id');
        
        $this->db->bind(':id', $pageId);
        return $this->db->single();
    }

    public function addPage($page) {

        $this->db->query('  INSERT INTO pages SET
                            title = :title,
                            slug = :slug,
                            content = :content,
                            author_id = :author_id,
                            created_at = NOW(),
                            updated_at = NOW()
        ');

        $this->db->bind(':title', $page['title']);
        $this->db->bind(':slug', $page['slug']);
        $this->db->bind(':content', $page['content']);
        $this->db->bind(':author_id', $page['author_id']);

        $this->db->execute();

        // Let's get the ID of the post we've just inserted to the database
        $this->db->query('SELECT id FROM pages ORDER BY id DESC LIMIT 1');

        return $this->db->single();
    }

    public function updatePage($page) {
        
        $this->db->query('  UPDATE pages SET
                                title = :title,
                                content = :content,
                                updated_at = NOW()
                            WHERE
                                id = :id
        ');
        
        $this->db->bind(':title', $page['title']);
        $this->db->bind(':content', $page['content']);
        $this->db->bind(':id', $page['id']);
        
        return $this->db->execute();
    }

    public function deletePage($pageId) {
        
        $this->db->query('DELETE FROM pages WHERE id = :id');
        
        $this->db->bind(':id', $pageId);
        
        return $this->db->execute();
    }

    public function getPagesNumber() {
        $this->db->query('SELECT * FROM pages');
        $this->db->resultSet();
        return $this->db->rowCount();
    }
}