<?php

namespace Ktpl\Blog\Plugin\Plumrocket\Amp;

/**
 * Plugin for helper data (amp extension by Plumrocket)
 */
class HelperDataPlugin
{
    /**
     * Add ktpl blog actions to allowed list
     * @param  \Plumrocket\Amp\Helper\Data $helper
     * @param  array $allowedPages
     * @return array
     */
    public function afterGetAllowedPages(\Plumrocket\Amp\Helper\Data $helper, $allowedPages)
    {
        foreach ($allowedPages as &$value) {
            if (strpos($value, 'ktpl_blog_') === 0) {
                $value = str_replace('ktpl_blog_', 'blog_', $value);
            }
        }

        return $allowedPages;
    }

}