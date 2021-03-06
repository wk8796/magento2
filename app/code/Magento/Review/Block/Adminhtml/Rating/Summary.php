<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Magento
 * @package     Magento_Review
 * @copyright   Copyright (c) 2014 X.commerce, Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
namespace Magento\Review\Block\Adminhtml\Rating;

use Magento\Rating\Model\Resource\Rating\Collection as RatingCollection;

/**
 * Adminhtml summary rating stars
 */
class Summary extends \Magento\Backend\Block\Template
{
    /**
     * Rating summary template name
     *
     * @var string
     */
    protected $_template = 'Magento_Rating::rating/stars/summary.phtml';

    /**
     * Core registry
     *
     * @var \Magento\Registry
     */
    protected $_coreRegistry = null;

    /**
     * Rating resource option model
     *
     * @var \Magento\Rating\Model\Resource\Rating\Option\Vote\CollectionFactory
     */
    protected $_votesFactory;

    /**
     * Rating model
     *
     * @var \Magento\Rating\Model\RatingFactory
     */
    protected $_ratingFactory;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Rating\Model\Resource\Rating\Option\Vote\CollectionFactory $votesFactory
     * @param \Magento\Rating\Model\RatingFactory $ratingFactory
     * @param \Magento\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Rating\Model\Resource\Rating\Option\Vote\CollectionFactory $votesFactory,
        \Magento\Rating\Model\RatingFactory $ratingFactory,
        \Magento\Registry $registry,
        array $data = array()
    ) {
        $this->_votesFactory = $votesFactory;
        $this->_ratingFactory = $ratingFactory;
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * Initialize review data
     *
     * @return void
     */
    protected function _construct()
    {
        if ($this->_coreRegistry->registry('review_data')) {
            $this->setReviewId($this->_coreRegistry->registry('review_data')->getId());
        }
    }

    /**
     * Get collection of ratings
     *
     * @return RatingCollection
     */
    public function getRating()
    {
        if (!$this->getRatingCollection()) {
            $ratingCollection = $this->_votesFactory->create()->setReviewFilter(
                $this->getReviewId()
            )->addRatingInfo()->load();
            $this->setRatingCollection($ratingCollection->getSize() ? $ratingCollection : false);
        }
        return $this->getRatingCollection();
    }

    /**
     * Get rating summary
     *
     * @return string
     */
    public function getRatingSummary()
    {
        if (!$this->getRatingSummaryCache()) {
            $this->setRatingSummaryCache($this->_ratingFactory->create()->getReviewSummary($this->getReviewId()));
        }

        return $this->getRatingSummaryCache();
    }
}
