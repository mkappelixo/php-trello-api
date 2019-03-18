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
        $this->validateRequiredParameters(array('name', 'idBoard', 'color'), $params);
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
            case self::COLOR_YELLOW:
            case self::COLOR_PURPLE:
            case self::COLOR_BLUE:
            case self::COLOR_RED:
            case self::COLOR_GREEN:
            case self::COLOR_ORANGE:
            case self::COLOR_BLACK:
            case self::COLOR_SKY:
            case self::COLOR_PINK:
            case self::COLOR_LIME:
            case self::COLOR_NULL:
                break;
            default:
                throw new ValidationFailedException('Color provided was not a valid color');

        }
    }
}
