<?php
/**
 * Created by PhpStorm.
 * User: scott
 * Date: 10/03/19
 * Time: 6:21 PM
 */

namespace Trello\Model;


interface LabelInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $name
     * @return LabelInterface
     */
    public function setName(string $name);

    /**
     * @return string
     */
    public function getColor();

    /**
     * @param string $color
     * @return LabelInterface
     */
    public function setColor(string $color);

    /**
     * @return string
     */
    public function getBoardId();

    /**
     * @param string $boardId
     * @return LabelInterface
     */
    public function setBoardId(string $boardId);

    /**
     * Set board
     *
     * @param BoardInterface $board
     *
     * @return CardInterface
     */
    public function setBoard(BoardInterface $board);

    /**
     * Get board
     *
     * @return BoardInterface
     */
    public function getBoard();
}
