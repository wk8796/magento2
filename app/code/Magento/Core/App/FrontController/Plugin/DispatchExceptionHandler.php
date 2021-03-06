<?php
/**
 * Dispatch exception handler
 *
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
namespace Magento\Core\App\FrontController\Plugin;

use Magento\Core\Model\StoreManager;
use Magento\App\Filesystem;

class DispatchExceptionHandler
{
    /**
     * @var \Magento\Core\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * Filesystem instance
     *
     * @var \Magento\App\Filesystem
     */
    protected $filesystem;

    /**
     * @param StoreManager $storeManager
     * @param Filesystem $filesystem
     */
    public function __construct(StoreManager $storeManager, Filesystem $filesystem)
    {
        $this->_storeManager = $storeManager;
        $this->filesystem = $filesystem;
    }

    /**
     * Handle dispatch exceptions
     *
     * @param \Magento\App\FrontController $subject
     * @param callable $proceed
     * @param \Magento\App\RequestInterface $request
     *
     * @return mixed
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function aroundDispatch(
        \Magento\App\FrontController $subject,
        \Closure $proceed,
        \Magento\App\RequestInterface $request
    ) {
        try {
            return $proceed($request);
        } catch (\Magento\Session\Exception $e) {
            header('Location: ' . $this->_storeManager->getStore()->getBaseUrl());
            exit;
        } catch (\Magento\Core\Model\Store\Exception $e) {
            require $this->filesystem->getPath(Filesystem::PUB_DIR) . '/errors/404.php';
            exit;
        }
    }
}
