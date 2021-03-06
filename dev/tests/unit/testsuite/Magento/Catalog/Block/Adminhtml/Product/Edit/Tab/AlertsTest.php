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
 * @copyright   Copyright (c) 2014 X.commerce, Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
namespace Magento\Catalog\Block\Adminhtml\Product\Edit\Tab;

class AlertsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Magento\Catalog\Block\Adminhtml\Product\Edit\Tab\Alerts
     */
    protected $alerts;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $storeConfigMock;

    protected function setUp()
    {
        $helper = new \Magento\TestFramework\Helper\ObjectManager($this);
        $this->storeConfigMock = $this->getMock('Magento\Core\Model\Store\Config', array(), array(), '', false);

        $this->alerts = $helper->getObject(
            'Magento\Catalog\Block\Adminhtml\Product\Edit\Tab\Alerts',
            array('storeConfig' => $this->storeConfigMock)
        );
    }

    /**
     * @param bool $priceAllow
     * @param bool $stockAllow
     * @param bool $canShowTab
     *
     * @dataProvider canShowTabDataProvider
     */
    public function testCanShowTab($priceAllow, $stockAllow, $canShowTab)
    {
        $valueMap = array(
            array('catalog/productalert/allow_price', null, $priceAllow),
            array('catalog/productalert/allow_stock', null, $stockAllow)
        );
        $this->storeConfigMock->expects($this->any())->method('getConfig')->will($this->returnValueMap($valueMap));
        $this->assertEquals($canShowTab, $this->alerts->canShowTab());
    }

    public function canShowTabDataProvider()
    {
        return array(
            'alert_price_and_stock_allow' => array(true, true, true),
            'alert_price_is_allowed_and_stock_is_unallowed' => array(true, false, true),
            'alert_price_is_unallowed_and_stock_is_allowed' => array(false, true, true),
            'alert_price_is_unallowed_and_stock_is_unallowed' => array(false, false, false)
        );
    }
}
