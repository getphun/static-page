<?php
/**
 * Static page events
 * @package static-page
 * @version 0.0.1
 * @upgrade false
 */

namespace StaticPage\Event;

class PageEvent{
    
    static function general($object, $old=null){
        $dis = \Phun::$dispatcher;
        $page = $dis->router->to('siteStaticPageSingle', ['slug'=>$object->slug]);
        $dis->cache->removeOutput($page);
    }
    
    static function created($object){
        self::general($object);
    }
    
    static function updated($object, $old=null){
        self::general($object, $old);
    }
    
    static function deleted($object){
        self::general($object);
    }
}