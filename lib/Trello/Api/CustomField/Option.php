<?php

namespace Trello\Api\CustomField;

use Trello\Api\AbstractApi;

/**
 */
class Option extends AbstractApi
{
    /**
     * @var string
     */
    protected $path = 'customFields/#id#/options';

    /**
     *
     */
    public function all($id, array $params = array())
    {
        return $this->get($this->getPath($id), $params);
    }
}
