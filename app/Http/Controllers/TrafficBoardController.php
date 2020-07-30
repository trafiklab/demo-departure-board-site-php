<?php


namespace App\Http\Controllers;


use App\Http\Models\SidebarContext;
use App\Http\Models\TrafficBoardRow;
use App\Http\Models\TrafficBoardViewData;
use Illuminate\Support\Facades\Input;
use Illuminate\View\View;
use Trafiklab\Common\Model\Contract\PublicTransportApiWrapper;
use Trafiklab\Common\Model\Contract\TimeTableEntry;
use Trafiklab\Common\Model\Contract\TimeTableResponse;
use Trafiklab\Common\Model\Enum\TimeTableType;
use Trafiklab\Common\Model\Exceptions\InvalidKeyException;
use Trafiklab\Common\Model\Exceptions\InvalidRequestException;
use Trafiklab\Common\Model\Exceptions\InvalidStopLocationException;
use Trafiklab\Common\Model\Exceptions\KeyRequiredException;
use Trafiklab\Common\Model\Exceptions\QuotaExceededException;
use Trafiklab\Common\Model\Exceptions\RequestTimedOutException;
use Trafiklab\Common\Model\Exceptions\ServiceUnavailableException;

class TrafficBoardController extends Controller
{

    public function showDepartureBoard($id): ?View
    {
        try {
            return $this->buildTrafficBoard($id, true);
        } catch (InvalidKeyException $e) {
            abort(500);
        } catch (InvalidStopLocationException $e) {
            abort(404);
        } catch (KeyRequiredException $e) {
            abort(401);
        } catch (InvalidRequestException $e) {
            abort(400);
        } catch (QuotaExceededException $e) {
            abort(429);
        } catch (RequestTimedOutException $e) {
            abort(503);
        } catch (ServiceUnavailableException $e) {
            abort(503);
        }
        return null;
    }


    public function showArrivalBoard($id): ?View
    {
        try {
            return $this->buildTrafficBoard($id, false);
        } catch (InvalidKeyException $e) {
            abort(500);
        } catch (InvalidStopLocationException $e) {
            abort(404);
        } catch (KeyRequiredException $e) {
            abort(401);
        } catch (InvalidRequestException $e) {
            abort(400);
        } catch (QuotaExceededException $e) {
            abort(429);
        } catch (RequestTimedOutException $e) {
            abort(503);
        } catch (ServiceUnavailableException $e) {
            abort(503);
        }
        return null;
    }


    /**
     * @param array $trafficBoardViews TrafficBoardViewData[]
     *
     * @return \Illuminate\Contracts\View\Factory|View
     */
    private function show($trafficBoardViews): View
    {
        $view = 'content';
        if (Input::get('fullscreen') == true) {
            $view = 'fullscreen';
        }

        $sidebarContext = new SidebarContext(true, url()->current());

        return view($view,
            [
                'content' => 'trafficboard',
                'trafficBoardViews' => $trafficBoardViews,
                'sidebarContext' => $sidebarContext
            ]
        );
    }

    /**
     * @param $ids string One or more ids separated by commas
     * @param $forDepartureBoard
     *
     * @return View
     * @throws InvalidKeyException
     * @throws InvalidRequestException
     * @throws InvalidStopLocationException
     * @throws KeyRequiredException
     * @throws QuotaExceededException
     * @throws RequestTimedOutException
     * @throws ServiceUnavailableException
     */
    private function buildTrafficBoard($ids, $forDepartureBoard): View
    {
        /**
         * @var PublicTransportApiWrapper $transportDataApi Get an instance of PublicTransportApiWrapper. Which instance
         *                                                  is returned is configured in AppServiceProvider.php
         *
         *                                                  In this case it is configured to be an instance of an
         *                                                  SlApiWrapper if the ENV_DATASOURCE variable is set to SL,
         *                                                  otherwise an instance of ResRobotApiWrapper is returned.
         */

        $transportDataApi = app(PublicTransportApiWrapper::class);

        $ids = trim($ids);
        if (strpos($ids, ',') !== false) {
            $ids = explode(',', $ids); // Create array from comma separated values
        } else {
            $ids = [$ids]; // Single value to array
        }

        $trafficBoards = [];
        foreach ($ids as $id) {
            $timeTableRequest = $this->buildTimeTableRequest($id, $forDepartureBoard, $transportDataApi);
            $timeTable = $transportDataApi->getTimeTable($timeTableRequest);

            $rows = $this->convertTimetableResponseToTrafficBoardRows($timeTable);
            $title = $this->buildTitle($forDepartureBoard, $timeTable);
            $trafficBoards[] = new TrafficBoardViewData($title, $forDepartureBoard, $rows);
        }
        return $this->show($trafficBoards);
    }

    /**
     * @param $forDepartureBoard
     * @param $timeTable TimeTableResponse
     *
     * @return string
     */
    private function buildTitle($forDepartureBoard, $timeTable): string
    {
        if ($forDepartureBoard) {
            $title = __("Departures from") . ' ' . $timeTable->getTimetable()[0]->getStopName();
        } else {
            $title = __("Arrivals from") . ' ' . $timeTable->getTimetable()[0]->getStopName();
        }
        return $title;
    }

    /**
     * @param $timeTable TimeTableResponse
     *
     * @return array
     */
    private function convertTimetableResponseToTrafficBoardRows($timeTable): array
    {
        $limit = Input::get('rows') ?: count($timeTable->getTimetable());

        $rows = [];
        for ($i = 0; $i < $limit; $i++) {
            $timeTableEntry = $timeTable->getTimetable() [$i];
            $rows[] = new TrafficBoardRow($timeTableEntry->getScheduledStopTime(),
                $this->getDelay($timeTableEntry),
                $timeTableEntry->getDirection(),
                $timeTableEntry->getPlatform() ?: ''
            );
        }
        return $rows;
    }

    /**
     * @param $id
     * @param $forDepartureBoard
     * @param $transportDataApi PublicTransportApiWrapper
     *
     * @return mixed
     */
    private function buildTimeTableRequest($id, $forDepartureBoard, $transportDataApi)
    {
        $timeTableRequest = $transportDataApi->createTimeTableRequestObject();
        $timeTableRequest->setStopId($id);
        if ($forDepartureBoard) {
            $timeTableRequest->setTimeTableType(TimeTableType::DEPARTURES);
        } else {
            $timeTableRequest->setTimeTableType(TimeTableType::ARRIVALS);
        }
        return $timeTableRequest;
    }

    /**
     * @param $timeTableEntry TimeTableEntry
     *
     * @return int The delay in minutes.
     */
    private function getDelay($timeTableEntry): int
    {
        if ($timeTableEntry->getEstimatedStopTime() === null) {
            return 0;
        }
        return ($timeTableEntry->getEstimatedStopTime()->getTimestamp() -
                $timeTableEntry->getScheduledStopTime()->getTimestamp()) / 60;
    }

}
