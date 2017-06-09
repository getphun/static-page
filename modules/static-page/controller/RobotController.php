<?php
/**
 * Static page robot provider
 * @package static-page
 * @version 1.0.0
 */

namespace StaticPage\Controller;
use StaticPage\Library\Robot;

class RobotController extends \SiteController
{
    private function feed($type='xml'){
        if(!module_exists('robot'))
            return $this->show404();
        
        if($type === 'json' && !$this->config->robot['json'])
            return $this->show404();
        
        $feed_router = $type === 'xml' ? 'siteStaticPageFeedXML' : 'siteStaticPageFeedJSON';
        $feed_host   = $this->setting->static_page_index_enable ? 'siteStaticPage' : 'siteHome';
        
        $feed = (object)[
            'url'         => $this->router->to($feed_router),
            'description' => hs($this->setting->static_page_index_meta_description),
            'updated'     => null,
            'host'        => $this->router->to($feed_host),
            'title'       => hs($this->setting->static_page_index_meta_title)
        ];
        
        $pages = Robot::feed();
        $this->robot->feed($feed, $pages, $type);
    }
    
    public function feedXmlAction(){
        $this->feed('xml');
    }
    
    public function feedJsonAction(){
        $this->feed('json');
    }
}