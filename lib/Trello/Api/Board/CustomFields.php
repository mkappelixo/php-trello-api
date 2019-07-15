<?php

namespace Trello\Api\Board;

use Trello\Api\AbstractApi;

/**
 * Trello CustomFields API
 * @link https://trello.com/docs/api/board
 */
class CustomFields extends AbstractApi
{
    /**
     * Base path of CustomFields actions api
     * @var string
     */
    protected $path = 'boards/#id#/customFields';

    /**
     * Get customFields Definition
     * @link https://developers.trello.com/reference/#boardsidcustomfields
     *
     * @param string $id     the board's id
     * @param array  $params optional parameters
     *
     * @return array
     */
    public function all($id)
    {
        return $this->get($this->getPath($id));
    }
}
