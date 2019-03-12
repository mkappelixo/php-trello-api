<?php
/**
 * Created by PhpStorm.
 * User: scott
 * Date: 10/03/19
 * Time: 6:19 PM
 */

namespace Trello\Model;

class Label extends AbstractObject implements LabelInterface
{
    protected $apiName = 'label';

    protected $loadParams = [
        'fields' => 'all'
    ];

    /**
     * @return string
     */
    public function getName()
    {
        return $this->data['name'];
    }

    /**
     * @param string $name
     * @return LabelInterface
     */
    public function setName(string $name)
    {
        $this->data['name'] = $name;
    }

    /**
     * @return string
     */
    public function getColor()
    {
        return $this->data['color'];
    }

    /**
     * @param string $color Must be one of the Trello\Api\Label::COLOR_* constants
     * @return LabelInterface
     */
    public function setColor(string $color)
    {
        $this->data['color'] = $color;
    }

    /**
     * @return string
     */
    public function getBoardId()
    {
        return $this->data['idBoard'];
    }

    /**
     * @param string $boardId
     * @return LabelInterface
     */
    public function setBoardId(string $boardId)
    {
        $this->data['idBoard'] = $boardId;
    }

    /**
     * {@inheritdoc}
     */
    public function setBoard(BoardInterface $board)
    {
        return $this->setBoardId($board->getId());
    }

    /**
     * {@inheritdoc}
     */
    public function getBoard()
    {
        return new Board($this->client, $this->getBoardId());
    }
}
