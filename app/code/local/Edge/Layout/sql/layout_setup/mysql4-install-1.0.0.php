<?php

$this->startSetup();
// Layout
$this->run("

    CREATE TABLE IF NOT EXISTS {$this->getTable('layout/layout_groups')} (
        `id_group` int(11) primary key NOT NULL auto_increment,
        `name` text NULL DEFAULT NULL,
        `description` text NULL DEFAULT NULL,
        `sort_order` int(11) NOT NULL DEFAULT '0'
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8; ");

$this->run("CREATE TABLE IF NOT EXISTS {$this->getTable('layout/layout_elements')} (
        `id_element` int(11) primary key NOT NULL auto_increment,
        `fk_group` int(11) unsigned NOT NULL,
        `type` ENUM('top','slideshow','banners','bottom_banners') NOT NULL DEFAULT 'banners',
        `title` text NULL DEFAULT NULL,
        `description` text NULL DEFAULT NULL,
        `link` text NULL DEFAULT NULL,
        `link_text` text NULL DEFAULT NULL,
        `image` text NULL DEFAULT NULL,
        `overlay_style` ENUM('full','circle') NULL DEFAULT NULL,
        `overlay_colour` text NULL DEFAULT NULL,
        `sort_order` int(11) NOT NULL DEFAULT '0',
        CONSTRAINT `FK_fk_group` FOREIGN KEY (`fk_group`) REFERENCES {$this->getTable('layout/layout_groups')} (`id_group`) ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");
$this->endSetup();