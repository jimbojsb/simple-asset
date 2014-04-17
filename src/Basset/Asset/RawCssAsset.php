<?php
namespace Basset\Asset;

class RawCssAsset
{
    private $body;

    public function __construct($body)
    {
        $this->body = $body;
    }

    public function __toString()
    {
        return sprintf('<style type="text/css">%s</style>', "\n$this->body\n");
    }
}