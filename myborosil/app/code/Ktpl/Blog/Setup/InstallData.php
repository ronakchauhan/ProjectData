<?php

namespace Ktpl\Blog\Setup;

use Ktpl\Blog\Model\Post;
use Ktpl\Blog\Model\PostFactory;
use Magento\Framework\Module\Setup\Migration;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 * @codeCoverageIgnore
 */
class InstallData implements InstallDataInterface
{
    /**
     * Post factory
     *
     * @var \Ktpl\Blog\Model\PostFactory
     */
    private $_postFactory;

    /**
     * Init
     *
     * @param \Ktpl\Blog\Model\PostFactory $postFactory
     */
    public function __construct(\Ktpl\Blog\Model\PostFactory $postFactory)
    {
        $this->_postFactory = $postFactory;
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $data = [
            'title' => 'Hello world!',
            'meta_keywords' => 'magento 2 blog',
            'meta_description' => 'Magento 2 blog default post.',
            'identifier' => 'hello-world',
            'content_heading' => 'Hello world!',
            'content' => '<p>Welcome to <a title="Ktpl - solutions for Magento 2" href="http://ktpl.com/" target="_blank">Ktpl</a> <a title="Magento 2 Blog extension" href="https://ktpl.com/magento2-blog-extension/" target="_blank">blog extension for Magento&reg; 2</a>. This is your first post. Edit or delete it, then start blogging!</p>
<p><!-- pagebreak --></p>
<p>Please also read&nbsp;<a title="Magento 2 Blog online documentation" href="http://ktpl.com/docs/magento-2-blog/" target="_blank">Online documentation</a>&nbsp;and&nbsp;<a href="http://ktpl.com/blog/add-read-more-tag-to-blog-post-content/" target="_blank">How to add "read more" tag to post content</a></p>
<p>Follow Ktpl on:</p>
<p><a title="Blog Extension for Magento 2 code" href="https://github.com/ktpl/module-blog" target="_blank">GitHub</a>&nbsp;|&nbsp;<a href="https://twitter.com/magento2fan" target="_blank">Twitter</a>&nbsp;|&nbsp;<a href="https://www.facebook.com/ktpl/" target="_blank">Facebook</a>&nbsp;|&nbsp;<a href="https://plus.google.com/+Ktpl_Magento_2/posts/" target="_blank">Google +</a></p>',
            'store_ids' => [0]
        ];

        $this->_postFactory->create()->setData($data)->save();
    }

}
