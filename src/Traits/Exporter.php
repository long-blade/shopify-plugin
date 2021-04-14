<?php

namespace Shopify\Traits;

use Cake\Core\Configure;

/**
 * Trait Exporter
 * @package Shopify\Traits
 */
trait Exporter
{
    private $fileType;

    /**
     * @param array $data
     *
     * @return $this
     */
    public function export(array $data)
    {
        //get the file if exists and if yes and needs update update it else serve it
        if ($this->fileNeedsUpdate())
        {
            // if file exists update it, else create it.
            $file = file_put_contents($this->getResourceAbsolutePath(), json_encode($data));

            // TODO: put a print to a log file or smt
            if ($file)
            {
                echo sprintf('Created file %s.', $this->getResourceAbsolutePath()) . "\n";
            }
            else
            {
                echo sprintf('Unable to Created file in %s.', $this->getResourceAbsolutePath()) . "\n";
            }

        }
        else
        {
            echo sprintf('Not update needed yet for %s.', $this->getResourceAbsolutePath()) . "\n";
        }

        return $this;
    }

    /**
     * Get the files absolute path.
     *
     * @return string
     */
    public function getResourceAbsolutePath(): string
    {
        // Construct a part of the file name from path.
        $filename = str_replace('/', '_', $this->getResourcePath());
        $this->fileType = '.' . (string) Configure::read('export.file_type', 'json');

        return "{$this->getExportDir()}{$filename}_{$this->site->getEntryId()}$this->fileType";
    }

    /**
     * @return string
     */
    protected function getExportDir(): string
    {
        return (string) Configure::read('export.path', RESOURCES);
    }

    /**
     * Check if the file exist and should be updated.
     *
     * @return bool
     */
    protected function fileNeedsUpdate(): bool
    {
        $path = $this->getResourceAbsolutePath();

        if (is_file($path))
        {
            $lifetime = (string) Configure::read('export.lifetime', 1800);
            return time() - filemtime($path) >= $lifetime;
        }

        return true;
    }

    /**
     * @return string
     */
    abstract function getResourcePath(): string;

    /**
     * @return array|null
     */
    abstract function getResource(): ?array;
}
