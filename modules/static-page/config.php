<?php
/**
 * static-page config file
 * @package static-page
 * @version 0.0.1
 * @upgrade true
 */

return [
    '__name' => 'static-page',
    '__version' => '0.0.1',
    '__git' => 'https://github.com/getphun/static-page',
    '__files' => [
        'modules/static-page/config.php'    => [ 'install', 'remove', 'update' ],
        'modules/static-page/_db'           => [ 'install', 'remove', 'update' ],
        'modules/static-page/model'         => [ 'install', 'remove', 'update' ],
        'modules/static-page/library'       => [ 'install', 'remove', 'update' ],
        'modules/static-page/meta'          => [ 'install', 'remove', 'update' ],
        'modules/static-page/event'         => [ 'install', 'remove' ],
        'modules/static-page/controller/RobotController.php'    => [ 'install', 'remove', 'update' ],
        'modules/static-page/controller/PageController.php'     => [ 'install', 'remove' ],
        'theme/site/static-page'            => [ 'install', 'remove' ]
    ],
    '__dependencies' => [
        'site-param',
        'formatter',
        'site',
        '/db-mysql',
        '/robot'
    ],
    '_services' => [],
    '_autoload' => [
        'classes' => [
            'StaticPage\\Model\\StaticPage'             => 'modules/static-page/model/StaticPage.php',
            'StaticPage\\Library\\Robot'                => 'modules/static-page/library/Robot.php',
            'StaticPage\\Meta\\Page'                    => 'modules/static-page/meta/Page.php',
            'StaticPage\\Controller\\RobotController'   => 'modules/static-page/controller/RobotController.php',
            'StaticPage\\Controller\\PageController'    => 'modules/static-page/controller/PageController.php',
            'StaticPage\\Event\\PageEvent'              => 'modules/static-page/event/PageEvent.php'
        ],
        'files' => []
    ],
    
    '_routes' => [
        'site' => [
            'siteStaticPageFeedXML' => [
                'rule' => '/page/feed.xml',
                'handler' => 'StaticPage\\Controller\\Robot::feedXml'
            ],
            'siteStaticPageFeedJSON' => [
                'rule' => '/page/feed.json',
                'handler' => 'StaticPage\\Controller\\Robot::feedJson'
            ],
            'siteStaticPageSingle' => [
                'rule' => '/page/:slug',
                'handler' => 'StaticPage\\Controller\\Page::single'
            ],
            'siteStaticPage' => [
                'rule' => '/page',
                'handler' => 'StaticPage\\Controller\\Page::index'
            ]
        ]
    ],
    
    'events' => [
        'static-page:created' => [
            'static-page' => 'StaticPage\\Event\\PageEvent::created'
        ],
        'static-page:updated' => [
            'static-page' => 'StaticPage\\Event\\PageEvent::updated'
        ],
        'static-page:deleted' => [
            'static-page' => 'StaticPage\\Event\\PageEvent::deleted'
        ]
    ],
    
    'formatter' => [
        'static-page' => [
            'title'     => 'text',
            'content'   => 'text',
            'updated'   => 'date',
            'created'   => 'date',
            'user'      => [
                'type'      => 'object',
                'model'     => 'User\\Model\\User'
            ],
            'page'      => [
                'type'      => 'router',
                'params'    => [
                    'for'       => 'siteStaticPageSingle'
                ]
            ],
            'meta_title'        => 'text',
            'meta_description'  => 'text'
        ]
    ],
    
    'robot' => [
        'sitemap' => [
            'static-page' => 'StaticPage\\Library\\Robot::sitemap'
        ],
        'feed' => [
            'static-page' => 'StaticPage\\Library\\Robot::feed'
        ]
    ]
];