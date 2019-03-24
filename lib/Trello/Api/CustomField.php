<?php
/**
 * Created by PhpStorm.
 * User: scott
 * Date: 23/03/19
 * Time: 7:57 AM
 */

namespace Trello\Api;


class CustomField extends AbstractApi
{
    protected $path = 'customFields';

    public static $fields = [
        'id',
        'idModel',
        'modelType',
        'fieldGroup',
        'name',
        'pos',
        'type',
        'options',
        'display'
    ];

    public function show($id, array $params = [])
    {
        return $this->get($this->getPath() . '/' . rawurlencode($id), $params);
    }

    public function create(array $params = [])
    {
        $this->validateRequiredParameters(array('name', 'idModel', 'modelType', 'name', 'type', 'pos'), $params);

        return $this->postRaw($this->getPath(), $params, ['Content-type' => 'application/json']);
    }

    public function update($id, array $params = [])
    {
        return $this->put($this->getPath() . '/' . rawurlencode($id), $params);
    }
}
