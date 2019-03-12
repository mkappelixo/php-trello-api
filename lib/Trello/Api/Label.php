<?php
/**
 * Created by PhpStorm.
 * User: scott
 * Date: 10/03/19
 * Time: 6:00 PM
 */

namespace Trello\Api;


use Trello\Exception\ValidationFailedException;

class Label extends AbstractApi
{
    protected $path = 'labels';

    public static $fields = [
        'id',
        'idBoard',
        'name',
        'color'
    ];

    const COLOR_YELLOW = 'yellow';
    const COLOR_PURPLE = 'purple';
    const COLOR_BLUE = 'blue';
    const COLOR_RED = 'red';
    const COLOR_GREEN = 'green';
    const COLOR_ORANGE = 'orange';
    const COLOR_BLACK = 'black';
    const COLOR_SKY = 'sky';
    const COLOR_PINK = 'pink';
    const COLOR_LIME = 'lime';
    const COLOR_NULL = 'null';

    public function all()
    {
        return $this->get($this->getPath());
    }


    public function show($id, array $params = [])
    {
        return $this->get($this->getPath() . '/' . rawurlencode($id), $params);
    }

    public function create(array $params = [])
    {
        $this->validateRequiredParameters(array('id', 'idBoard', 'color'), $params);
        $this->validateAllowedColors($params['color']);

        return $this->post($this->getPath(), $params);
    }

    public function update($id, array $params = [])
    {
        if ($params['color'])
        {
            $this->validateAllowedColors($params['color']);
        }

        return $this->put($this->getPath() . '/' . rawurlencode($id), $params);
    }

    protected function validateAllowedColors($color)
    {
        switch ($color) {
            case COLOR_YELLOW:
            case COLOR_PURPLE:
            case COLOR_BLUE:
            case COLOR_RED:
            case COLOR_GREEN:
            case COLOR_ORANGE:
            case COLOR_BLACK:
            case COLOR_SKY:
            case COLOR_PINK:
            case COLOR_LIME:
            case COLOR_NULL:
                break;
            default:
                throw new ValidationFailedException('Color provided was not a valid color');

        }
    }
}
