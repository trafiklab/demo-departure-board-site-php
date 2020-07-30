<?php


namespace App\Http\Controllers;


use Illuminate\Support\Facades\Input;
use Trafiklab\Common\Model\Contract\PublicTransportApiWrapper;

class StopLocationSearchController extends Controller
{
    public function search()
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

        $timeTableRequest = $transportDataApi->createStopLocationLookupRequestObject();
        $timeTableRequest->setSearchQuery(Input::post('name'));

        $response = $transportDataApi->lookupStopLocation($timeTableRequest);
        $id = $response->getFoundStopLocations()[0]->getId();

        if (Input::post('type') == 'arrivals') {
            return redirect()->route('showArrivalBoard', ['id' => $id]);
        } else {
            return redirect()->route('showDepartureBoard', ['id' => $id]);
        }
    }

    public function autocomplete(string $query)
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

        $timeTableRequest = $transportDataApi->createStopLocationLookupRequestObject();
        $timeTableRequest->setSearchQuery($query);

        $response = $transportDataApi->lookupStopLocation($timeTableRequest);

        $names = [];
        foreach ($response->getFoundStopLocations() as $stopLocation) {
            $names[$stopLocation->getName()] = $stopLocation->getWeight();
        }

        asort($names);
        $names = array_keys($names);
        $names = array_reverse($names);
        return response()->json($names)->header('Cache-Control', 'private, max-age: ' . 60 * 60 * 24 );
    }
}