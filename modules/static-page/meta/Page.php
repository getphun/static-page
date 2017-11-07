<?php
/**
 * Meta provider
 * @package static-page
 * @version 0.0.1
 * @upgrade true
 */

namespace StaticPage\Meta;

class Page
{
    static function index(){
        $dis = \Phun::$dispatcher;
        
        $page = $dis->req->getQuery('page', 1);
        
        $base_url   = $dis->router->to('siteHome');
        
        $meta_title = $dis->setting->static_page_index_meta_title;
        $meta_desc  = $dis->setting->static_page_index_meta_description;
        $meta_keys  = $dis->setting->static_page_index_meta_keywords;
        $meta_url   = $dis->router->to('siteStaticPage');
        $meta_image = $base_url . 'theme/site/static/logo/500x500.png';
        
        if($page && $page > 1){
            $meta_title = sprintf('Page %s %s', $page, $meta_title);
            $meta_desc  = sprintf('Page %s %s', $page, $meta_desc);
            $meta_url   = $meta_url . '?page=' . $page;
        }
        
        $index = (object)[
            '_schemas' => [],
            '_metas'   => [
                'title'         => $meta_title,
                'canonical'     => $meta_url,
                'description'   => $meta_desc,
                'keywords'      => $meta_keys,
                'image'         => $meta_image,
                'type'          => 'website'
            ]
        ];
        
        // my rss feed?
        if(module_exists('robot'))
            $index->_metas['feed'] = $dis->router->to('siteStaticPageFeed');
        
        // Schema
        $schema = [
            '@context'      => 'http://schema.org',
            '@type'         => 'CollectionPage',
            'name'          => $meta_title,
            'description'   => $meta_desc,
            'publisher'     => $dis->meta->schemaOrganization(),
            'url'           => $meta_url,
            'image'         => $meta_image
        ];
        
        $index->_schemas[] = $schema;
        
        return $index;
    }
    
    static function single($page){
        $dis = \Phun::$dispatcher;
        
        $base_url = $dis->router->to('siteHome');
        
        $meta_desc  = $page->meta_description->safe;
        if(!$meta_desc)
            $meta_desc = $page->content->chars(160);
        $meta_image = $base_url . 'theme/site/static/logo/500x500.png';
        $meta_url   = $page->page;
        $meta_title = $page->meta_title->safe;
        $meta_keys  = $page->meta_keywords;
        if(!$meta_title)
            $meta_title = $page->title->safe;
        
        // metas
        $single = (object)[
            '_schemas' => [],
            '_metas'   => [
                'title'         => $meta_title,
                'canonical'     => $meta_url,
                'description'   => $meta_desc,
                'keywords'      => $meta_keys,
                'image'         => $meta_image,
                'type'          => 'website'
            ]
        ];
        
        // schemas 
        if($page->meta_schema){
            $schema = [
                '@context'      => 'http://schema.org',
                '@type'         => $page->meta_schema,
                'name'          => $page->title,
                'description'   => $meta_desc,
                'dateCreated'   => $page->created,
                'dateModified'  => $page->updated,
                'datePublished' => $page->created,
                'publisher'     => $dis->meta->schemaOrganization(),
                'thumbnailUrl'  => $meta_image,
                'url'           => $meta_url,
                'image'         => $meta_image
            ];
            $single->_schemas[] = $schema;
        }
        
        // schema breadcrumbs
        $second_item = [
            '@type' => 'ListItem',
            'position' => 2,
            'item' => [
                '@id' => $base_url . '#page',
                'name' => 'Page'
            ]
        ];
        if($dis->setting->static_page_index_enable){
            $second_item = [
                '@type' => 'ListItem',
                'position' => 2,
                'item' => [
                    '@id' => $dis->router->to('siteStaticPage'),
                    'name' => $dis->setting->static_page_index_meta_title
                ]
            ];
        }
        
        $schema = [
            '@context'  => 'http://schema.org',
            '@type'     => 'BreadcrumbList',
            'itemListElement' => [
                [
                    '@type' => 'ListItem',
                    'position' => 1,
                    'item' => [
                        '@id' => $base_url,
                        'name' => $dis->config->name
                    ]
                ],
                $second_item
            ]
        ];
        
        $single->_schemas[] = $schema;
        
        return $single;
    }
}