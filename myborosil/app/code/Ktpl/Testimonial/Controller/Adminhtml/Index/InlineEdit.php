<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ktpl\Testimonial\Controller\Adminhtml\Index;

use Magento\Backend\App\Action\Context;
use Ktpl\Testimonial\Api\TestimonialRepositoryInterface as TestimonialRepository;
use Magento\Framework\Controller\Result\JsonFactory;
use Ktpl\Testimonial\Api\Data\TestimonialInterface;

class InlineEdit extends \Magento\Backend\App\Action
{
    /** @var TestimonialRepository  */
    protected $testimonialRepository;

    /** @var JsonFactory  */
    protected $jsonFactory;

    /**
     * @param Context $context
     * @param TestimonialRepository $testimonialRepository
     * @param JsonFactory $jsonFactory
     */
    public function __construct(
        Context $context,
        TestimonialRepository $testimonialRepository,
        JsonFactory $jsonFactory
    ) {
        parent::__construct($context);
        $this->testimonialRepository = $testimonialRepository;
        $this->jsonFactory = $jsonFactory;
    }

    /**
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->jsonFactory->create();
        $error = false;
        $messages = [];

        if ($this->getRequest()->getParam('isAjax')) {
            $postItems = $this->getRequest()->getParam('items', []);
            if (!count($postItems)) {
                $messages[] = __('Please correct the data sent.');
                $error = true;
            } else {                

                foreach (array_keys($postItems) as $testimonialId) {


                    /** @var \Ktpl\Testimonial\Model\Testimonial $testimonial */
                    $testimonial = $this->testimonialRepository->getById($testimonialId);
                    try {
                        $testimonial->setData(array_merge($testimonial->getData(), $postItems[$testimonialId]));

                        $this->testimonialRepository->save($testimonial);
                    } catch (\Exception $e) {
                        $messages[] = $this->getErrorWithtestimonialId(
                            $testimonial,
                            __($e->getMessage())
                        );
                        $error = true;
                    }
                }
            }
        }

        return $resultJson->setData([
            'messages' => $messages,
            'error' => $error
        ]);
    }

    /**
     * Add Testimonial title to error message
     *
     * @param TestimonialInterface $testimonial
     * @param string $errorText
     * @return string
     */
    protected function getErrorWithTestimonialId(TestimonialInterface $testimonial, $errorText)
    {
        return '[Testimonial ID: ' . $testimonial->getId() . '] ' . $errorText;
    }
}
