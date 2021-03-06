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
 * @package     Magento_Backup
 * @copyright   Copyright (c) 2014 X.commerce, Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
namespace Magento\Backup;

/**
 * Class to work media folder and database backups
 *
 * @category    Magento
 * @package     Magento_Backup
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Media extends Snapshot
{
    /**
     * Implementation Rollback functionality for Media
     *
     * @throws \Magento\Exception
     * @return bool
     */
    public function rollback()
    {
        $this->_prepareIgnoreList();
        return parent::rollback();
    }

    /**
     * Implementation Create Backup functionality for Media
     *
     * @throws \Magento\Exception
     * @return bool
     */
    public function create()
    {
        $this->_prepareIgnoreList();
        return parent::create();
    }

    /**
     * Overlap getType
     *
     * @return string
     * @see BackupInterface::getType()
     */
    public function getType()
    {
        return 'media';
    }

    /**
     * Add all folders and files except media and db backup to ignore list
     *
     * @return $this
     */
    protected function _prepareIgnoreList()
    {
        $rootDir = $this->getRootDir();
        $map = array(
            $rootDir => array('media', 'var', 'pub'),
            $rootDir . '/pub' => array('media'),
            $rootDir . '/var' => array($this->getDbBackupFilename())
        );

        foreach ($map as $path => $whiteList) {
            foreach (new \DirectoryIterator($path) as $item) {
                $filename = $item->getFilename();
                if (!$item->isDot() && !in_array($filename, $whiteList)) {
                    $this->addIgnorePaths($item->getPathname());
                }
            }
        }

        return $this;
    }
}
