<?php

namespace OuterEdge\Layout\Model\Elements;

use Magento\Framework\UrlInterface;
use Magento\Framework\Filesystem;
use Magento\Framework\App\Filesystem\DirectoryList;

class Image
{
    /**
     * Media sub folder
     * @var string
     */
    protected $subDir = 'outeredge/layout';

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * @var Filesystem
     */
    protected $fileSystem;

    /**
     * @param UrlInterface $urlBuilder
     * @param Filesystem $fileSystem
     */
    public function __construct(
        UrlInterface $urlBuilder,
        Filesystem $fileSystem
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->fileSystem = $fileSystem;
    }

    /**
     * Get images base url
     *
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->urlBuilder->getBaseUrl() . '../pub/' . UrlInterface::URL_TYPE_MEDIA . '/' . $this->subDir . '/image';
    }

    /**
     * Get base image dir
     *
     * @return string
     */
    public function getBaseDir()
    {
        return $this->fileSystem->getDirectoryWrite(DirectoryList::MEDIA)->getAbsolutePath($this->subDir . '/image');
    }
}
