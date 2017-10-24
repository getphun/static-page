CREATE TABLE IF NOT EXISTS `static_page` (
    `id` INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `user` INTEGER NOT NULL,
    `title` VARCHAR(100) NOT NULL,
    `slug` VARCHAR(100) NOT NULL UNIQUE,
    `content` TEXT,
    
    `meta_schema` VARCHAR(20),
    `meta_title` VARCHAR(100),
    `meta_description` TEXT,
    `meta_keywords` VARCHAR(200),
    
    `updated` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT IGNORE INTO `site_param` ( `name`, `type`, `group`, `value` ) VALUES
    ( 'static_page_index_enable', 4, 'Static Page', '0' ),
    ( 'static_page_index_meta_title', 1, 'Static Page', 'Pages' ),
    ( 'static_page_index_meta_description',  5, 'Static Page', 'List of our pages' ),
    ( 'static_page_index_meta_keywords', 1, 'Static Page', '' );