<?php
/**
 * Robot provider
 * @package static-page
 * @version 0.0.1
 * @upgrade true
 */

namespace StaticPage\Library;
use StaticPage\Model\StaticPage as SPage;

class Robot
{
    static function _getPages(){
        // get all pages that is updated last 2 days
        $last2days = date('Y-m-d H:i:s', strtotime('-2 days'));
        
        $pages = SPage::get([
            'updated >= :updated',
            'bind' => [
                'updated' => $last2days
            ]
        ], true);
        
        if(!$pages)
            return false;
        
        return \Formatter::formatMany('static-page', $pages, false, ['user']);
    }
    
    static function feed(){
        $result = [];
        
        $pages = self::_getPages();
        
        if(!$pages)
            return $result;
        
        foreach($pages as $page){
            $desc = $page->meta_description->safe;
            if(!$desc)
                $desc = $page->content->chars(160);
            
            $result[] = (object)[
                'author'      => hs($page->user->fullname),
                'description' => $desc,
                'page'        => $page->page,
                'published'   => $page->created->format('c'),
                'updated'     => $page->updated->format('c'),
                'title'       => $page->title->safe
            ];
        }
        
        return $result;
    }
    
    static function sitemap(){
        $result = [];
        
        $pages = self::_getPages();
        
        if(!$pages)
            return $result;
        
        foreach($pages as $page){
            $result[] = (object)[
                'url'       => $page->page,
                'lastmod'   => $page->updated->format('Y-m-d'),
                'changefreq'=> 'monthly',
                'priority'  => 0.4
            ];
        }
        
        return $result;
    }
}