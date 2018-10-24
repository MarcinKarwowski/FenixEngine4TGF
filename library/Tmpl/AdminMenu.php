<?php

/*
  +------------------------------------------------------------------------+
  | Fenix Engine                                                           |
  +------------------------------------------------------------------------+
  | Copyright (c) 2014-2015 Fenix Engine Team (http://e-fenix.info/)       |
  +------------------------------------------------------------------------+
  | Author: Marcin Karwowski <admin@e-fenix.info>                          |
  +------------------------------------------------------------------------+
 */

namespace App\Tmpl;

class AdminMenu extends \Phalcon\Mvc\User\Component
{
    /*
     * Index of the menu item is needed for permission system
     */
    public static $adminMenu = [
        'menu-header' => [
            'menu-dashboard'        => [
                'link' => '/admin',
                'ico'  => 'dashboard'
            ],
            'menu-config'           => [
                'link' => '/admin/configure',
                'ico' => 'cogs'
            ],
            'menu-template'           => [
                'link' => '/admin/template',
                'ico' => 'cogs'
            ],
            'menu-info'             => [
                'link'  => '/admin/info',
                'group' => 'menu-system_menu',
                'mico'  => 'medkit',
                'ico'   => 'info-circle'
            ],
            'menu-update'             => [
                'link'  => '/admin/check-update',
                'group' => 'menu-system_menu',
                'mico'  => 'medkit',
                'ico'   => 'info-circle'
            ],
            'menu-articles'         => [
                'link'  => '/admin/articles',
                'group' => 'menu-system_content',
                'mico'  => 'sticky-note-o',
                'ico'   => 'newspaper-o'
            ],
            'menu-wikipedia'        => [
                'link'  => '/admin/wikipedia',
                'group' => 'menu-system_content',
                'mico'  => 'sticky-note-o',
                'ico'   => 'university'
            ],
            'menu-media'            => [
                'link'  => '/admin/media',
                'group' => 'menu-system_content',
                'mico'  => 'sticky-note-o',
                'ico'   => 'picture-o'
            ],
            'chat-list'             => [
                'link'  => '/admin/chat',
                'group' => 'menu-system_content',
                'mico'  => 'sticky-note-o',
                'ico'   => 'comments'
            ],
            'users-list'            => [
                'link' => '/admin/users',
                'ico'  => 'users'
            ],
            'menu-notify'           => [
                'link' => '/admin/notify',
                'ico'  => 'sticky-note-o'
            ],
            'menu-menu_permissions' => [
                'link' => '/admin/permissions',
                'ico'  => 'bullhorn'
            ],
        ],
        'game-panel' => [
            'panel-game_configuration' => [
                'link' => '/admin/game/panel',
                'ico' => 'cogs'
            ],
            'panel-chacreator' => [
                'link' => '/admin/game/panel/creator',
                'group' => 'panel-menu_builder',
                'mico' => 'link',
                'ico' => 'link'
            ],
            'panel-items' => [
                'link' => '/admin/game/panel/items',
                'group' => 'panel-menu_builder',
                'mico' => 'link',
                'ico' => 'link'
            ],
            'panel-map' => [
                'link' => '/admin/game/panel/map',
                'group' => 'panel-menu_builder',
                'mico' => 'link',
                'ico' => 'link'
            ],
            'panel-locations' => [
                'link' => '/admin/game/panel/locations',
                'group' => 'panel-menu_builder',
                'mico' => 'link',
                'ico' => 'link'
            ],
            'panel-session_pd' => [
                'link' => '/admin/game/panel/pd',
                'group' => 'panel-menu_manage',
                'mico' => 'link',
                'ico' => 'link'
            ],
        ]
    ];

    public static function get()
    {
        return self::$adminMenu;
    }

    /*
     * @var (array) $arrMenu
     */
    public static function set($arrMenu = [])
    {
        // merge configs
        self::$adminMenu = array_replace_recursive(self::$adminMenu, $arrMenu);
    }
}
