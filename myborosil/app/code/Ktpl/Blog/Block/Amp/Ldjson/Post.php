<?php

namespace Ktpl\Blog\Block\Amp\Ldjson;

/**
 * Blog post list ldJson block
 */
class Post extends \Ktpl\Blog\Block\Post\View\Richsnippets
{
    /**
     * Retrieve page structure structure data in JSON
     *
     * @return string
     */
    public function getJson()
    {
        $json = parent::getOptions();
        return str_replace('\/', '/', json_encode($json));
    }
}
