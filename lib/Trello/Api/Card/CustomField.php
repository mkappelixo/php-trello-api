<?php

namespace Trello\Api\Card;

use Trello\Api\AbstractApi;

class CustomField extends AbstractApi
{
    protected $path = 'card/#id#/customField';

    /**
     * @param $cardId
     * @param $customFieldId
     * @param array $params
     * @return mixed
     * @throws \Exception
     */
    public function set($cardId, $customFieldId, $params = array())
    {
        return $this->put($this->getPath($cardId) . '/' . rawurlencode($customFieldId) . '/item', $params, ['Content-type' => 'application/json'], true);
    }

    /**
     * @param $cardId
     * @param $customFieldId
     * @return mixed
     * @throws \Exception
     */
    public function setChecked($cardId, $customFieldId){
        return $this->put($this->getPath($cardId) . '/' . rawurlencode($customFieldId) . '/item', ['value' => ["checked" => "true"]], ['Content-type' => 'application/json'], true);
    }
}
