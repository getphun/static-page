<?php
/**
 * Page controller
 * @package static-page
 * @version 0.0.1
 * @upgrade false
 */

namespace StaticPage\Controller;
use StaticPage\Meta\Page;
use StaticPage\Model\StaticPage as SPage;

class PageController extends \SiteController
{
    public function indexAction(){
        // serve only if it's allowed to be served
        if(!$this->setting->static_page_index_enable)
            return $this->show404();
        
        $page = $this->req->getQuery('page', 1);
        $rpp  = 12;
        
        $cache= 60*60*24*7;
        if($page > 1 || is_dev())
            $cache = null;
        
        $pages = SPage::get([], $rpp, $page, 'created DESC');
        if(!$pages)
            return $this->show404();
        
        $pages = \Formatter::formatMany('static-page', $pages, false, ['user']);
        $params = [
            'pages' => $pages,
            'index' => new \stdClass(),
            'pagination' => [],
            'total' => SPage::count()
        ];
        
        $params['index']->meta = Page::index();
        
        // pagination
        if($params['total'] > $rpp)
            $params['pagination'] = calculate_pagination($page, $rpp, $params['total']);
        
        $this->respond('static-page/index', $params, $cache);
    }
    
    public function singleAction(){
        $slug = $this->param->slug;
        
        $page = SPage::get(['slug'=>$slug], false);
        if(!$page)
            return $this->show404();
        
        $cache = is_dev() ? null : 60*60*24*7;
        
        $page = \Formatter::format('static-page', $page, false, ['user']);
        $params = [
            'page' => $page
        ];
        
        $params['page']->meta = Page::single($page);
        
        $this->respond('static-page/single', $params, $cache);
    }
}