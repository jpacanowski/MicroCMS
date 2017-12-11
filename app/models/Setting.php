<?php

class Setting {

    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getSettings() {
        $this->db->query('SELECT * FROM settings');
        return $this->db->single();
    }

    public function updateSettings($data) {
        
        $this->db->query('  UPDATE
                                settings
                            SET
                                site_title          = :site_title,
                                site_tagline        = :site_tagline,
                                site_description    = :site_description,
                                site_keywords       = :site_keywords,
                                site_url            = :site_url,
                                posts_per_page      = :posts_per_page,
                                updated_at          = NOW()
                            WHERE
                                id = 1
        ');
        
        $this->db->bind(':site_title',          $data['site_title']);
        $this->db->bind(':site_tagline',        $data['site_tagline']);
        $this->db->bind(':site_description',    $data['site_description']);
        $this->db->bind(':site_keywords',       $data['site_keywords']);
        $this->db->bind(':site_url',            $data['site_url']);
        $this->db->bind(':posts_per_page',      $data['posts_per_page']);
        
        if($this->db->execute()) {
            return true;
        }
        else {
            return false;
        }
    }
}