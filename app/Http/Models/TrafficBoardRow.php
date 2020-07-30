<?php


namespace App\Http\Models;


class TrafficBoardRow
{
    private $scheduledTime;
    private $minutesDelay;
    private $headsign;
    private $platform;

    /**
     * TrafficBoardRow constructor.
     *
     * @param $scheduledTime
     * @param $minutesDelay
     * @param $headsign
     * @param $platform
     */
    public function __construct(\DateTime $scheduledTime, int $minutesDelay, string $headsign, ?string $platform)
    {
        $this->scheduledTime = $scheduledTime;
        $this->minutesDelay = $minutesDelay;
        $this->headsign = $headsign;
        $this->platform = $platform;
    }

    /**
     * @return \DateTime
     */
    public function getScheduledTime(): \DateTime
    {
        return $this->scheduledTime;
    }

    /**
     * @return int
     */
    public function getMinutesDelay(): int
    {
        return $this->minutesDelay;
    }

    /**
     * @return string
     */
    public function getHeadsign(): string
    {
        return $this->headsign;
    }

    /**
     * @return null|string
     */
    public function getPlatform(): ?string
    {
        return $this->platform;
    }
}