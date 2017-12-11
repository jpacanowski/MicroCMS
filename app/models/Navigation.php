<?php

class Navigation {

    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getNavigation() {
        $this->db->query('  SELECT text, url
                            FROM navigation
                            WHERE active = 1
                            ORDER BY position ASC
        ');
        return $this->db->resultSet();
    }

    public function Dashboard_getNavigation() {
        $this->db->query('  SELECT *
                            FROM navigation
                            ORDER BY position ASC
        ');
        return $this->db->resultSet();
    }

    public function getNavigationLink($id) {
        $this->db->query('  SELECT *
                            FROM navigation
                            WHERE id = :id
        ');
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function storeNavigationLink($item) {

        // Get the number of links
        $this->db->query('  SELECT MAX(position) AS position
                            FROM navigation
        ');
        $link = $this->db->single();
        $link->position++;

        // Insert the link into the DB
        $this->db->query('  INSERT INTO navigation SET
                            text = :text, url = :url, position = :position
        ');
        $this->db->bind('text', $item['link_text']);
        $this->db->bind('url', $item['link_url']);
        $this->db->bind('position', $link->position);
        return $this->db->execute();
    }

    public function updateNavigationLink($data) {
        $this->db->query('  UPDATE navigation SET
                            text = :link_text,
                            url = :link_url
                            WHERE id = :link_id');
        $this->db->bind('link_id', $data['link_id']);
        $this->db->bind('link_text', $data['link_text']);
        $this->db->bind('link_url', $data['link_url']);
        return $this->db->execute();
    }

    public function disableNavigationLink($itemId) {
        $this->db->query('UPDATE navigation SET active = 0 WHERE id = :id');
        $this->db->bind('id', $itemId);
        return $this->db->execute();
    }

    public function enableNavigationLink($itemId) {
        $this->db->query('UPDATE navigation SET active = 1 WHERE id = :id');
        $this->db->bind('id', $itemId);
        return $this->db->execute();
    }

    public function deleteNavigationLink($itemId) {
        $this->db->query('DELETE FROM navigation WHERE id = :id');
        $this->db->bind('id', $itemId);
        return $this->db->execute();
    }
}