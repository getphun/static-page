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

    public function feedAction(){
        if(!module_exists('robot'))
            return $this->show404();
        
        $feed_host   = $this->setting->static_page_index_enable ? 'siteStaticPage' : 'siteHome';
        
        $feed = (object)[
            'url'         => $this->router->to('siteStaticPageFeed'),
            'description' => hs($this->setting->static_page_index_meta_description),
            'updated'     => null,
            'host'        => $this->router->to($feed_host),
            'title'       => hs($this->setting->static_page_index_meta_title)
        ];
        
        $pages = Robot::feed();
        $this->robot->feed($feed, $pages);
    }
}