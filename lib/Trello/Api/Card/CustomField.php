<?php

namespace Trello\Api\Card;

use Trello\Api\AbstractApi;

class CustomField extends AbstractApi
{
    protected $path = 'cards/#id#/customField';

    public function set($id, $customFieldId, $params = array())
    {
        return $this->put($this->getPath($id) . '/' . rawurlencode($customFieldId) . '/item', $params, ['Content-type' => 'application/json']);
    }
}
