<?php

namespace Ktpl\Blog\Plugin\Plumrocket\Amp;

/**
 * Plugin for source page (amp extension by Plumrocket)
 */
class SourcePagePlugin
{
    /**
     * Add ktpl blog pages to soruce
     * @param  \Plumrocket\Amp\Model\System\Config\Source\Page $page
     * @param  array $pages
     * @return array
     */
    public function afterToArray(\Plumrocket\Amp\Model\System\Config\Source\Page $page, $pages)
    {
        $pages['ktpl_blog_index_index'] = __('Blog Main Page');
        $pages['ktpl_blog_post_view'] = __('Blog Post Pages');
        $pages['ktpl_blog_category_view'] = __('Blog Category Pages');
        $pages['ktpl_blog_category_view'] = __('Blog Category Pages');
        $pages['ktpl_blog_archive_view'] = __('Blog Archive Pages');
        $pages['ktpl_blog_author_view'] = __('Blog Author Pages');
        $pages['ktpl_blog_tag_view'] = __('Blog Tag Pages');

        return $pages;
    }

}