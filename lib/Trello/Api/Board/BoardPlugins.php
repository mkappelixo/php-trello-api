<?php

namespace Trello\Api\Board;

use Trello\Api\AbstractApi;

/**
 * Trello Board Actions API
 * @link https://trello.com/docs/api/board
 *
 * Fully implemented.
 */
class BoardPlugins extends AbstractApi
{
    /**
     * Base path of board actions api
     * @var string
     */
    protected $path = 'boards/#id#/boardPlugins';

    /**
     * Get actions related to a given board
     * @link https://trello.com/docs/api/board/#get-1-boards-board-id-actions
     *
     * @param string $id     the board's id
     * @param array  $params optional parameters
     *
     * @return array
     */
    public function all($id, array $params = array())
    {
        return $this->get($this->getPath($id), $params);
    }

    public function create($id, $params)
    {
        $this->validateRequiredParameters(['idPlugin'], $params);
        $this->post($this->getPath($id), $params);
    }
}
