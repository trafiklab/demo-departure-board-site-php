<?php


namespace App\Http\Models;


class TrafficBoardViewData
{
    private $isDeparturesBoard;
    private $trafficBoardRows;
    private $title;

    /**
     * TrafficBoardViewData constructor.
     *
     * @param $title
     * @param $isDeparturesBoard
     * @param $trafficBoardRows TrafficBoardRow[]
     */
    public function __construct(string $title, bool $isDeparturesBoard, array $trafficBoardRows)
    {
        $this->isDeparturesBoard = $isDeparturesBoard;
        $this->trafficBoardRows = $trafficBoardRows;
        $this->title = $title;
    }

    /**
     * @return bool
     */
    public function isDeparturesBoard(): bool
    {
        return $this->isDeparturesBoard;
    }

    /**
     * @return bool
     */
    public function isArrivalsBoard(): bool
    {
        return !$this->isDeparturesBoard;
    }

    /**
     * @return TrafficBoardRow[]|array
     */
    public function getTrafficBoardRows()
    {
        return $this->trafficBoardRows;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }
}