<?php 

namespace Ktpl\Common\Model\Editor;

class Config extends \Magento\Framework\DataObject
{
    /**
     * Get Image Field
     *
     * @return string
     */
    public function getImage()
    {
        return $this->getData('image');
    }

    /**
     * Set Image Field
     *
     * @param string $imageField
     * @return $this
     */
    public function setImage($image)
    {
        return $this->setData('image', $image);
    }

    /**
     * Get Editor Field
     *
     * @return string
     */
    public function getEditor()
    {
        return $this->getData('editor');
    }

    /**
     * Set Editor Field
     *
     * @param string $editorField
     * @return $this
     */
    public function setEditor($editor)
    {
        return $this->setData('editor', $editor);
    }

    /**
     * Get Object
     *
     * @return string
     */
    public function getObject()
    {
        return $this->getData('object');
    }

    /**
     * Set Object
     *
     * @param string $object
     * @return $this
     */
    public function setObject($object)
    {
        return $this->setData('object', $object);
    }

    /**
     * Get Path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->getData('path');
    }

    /**
     * Set Path
     *
     * @param string $path
     * @return $this
     */
    public function setPath($path)
    {
        return $this->setData('path', $path);
    }
}
