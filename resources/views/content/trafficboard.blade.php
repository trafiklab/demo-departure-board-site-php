<?php
/**
 * @var $trafficBoardViews \App\Http\Models\TrafficBoardViewData[]
 */
?>
@foreach($trafficBoardViews as $trafficBoardViewData)
<h2 class="title-data">
    {{ $trafficBoardViewData->getTitle() }}
</h2>

<table class="table trafficdataboard">
    <thead>
    <tr>
        <th class="text-centered">
            @if($trafficBoardViewData->isDeparturesBoard())
                {{ __("Departure time") }}
            @else
                {{ __("Arrival time") }}
            @endif
        </th>
        <th>
            {{ __("Delay") }}
        </th>
        <th>
            @if($trafficBoardViewData->isDeparturesBoard())
                {{ __("Destination") }}
            @else
                {{ __("Origin") }}
            @endif
        </th>
        <td class="text-centered">
            {{ __("Platform") }}
        </td>
    </tr>
    </thead>
    <tbody>
    @foreach($trafficBoardViewData->getTrafficBoardRows() as $trafficBoardRow)
        <tr>
            <td class="scheduledtime text-centered">
                <span class="scheduledtime">{{ $trafficBoardRow->getScheduledTime()->format('H:i') }}</span>
            </td>
            <td class="delay">
                @if($trafficBoardRow->getMinutesDelay() > 0)
                    <span class="delay">{{ '+' . $trafficBoardRow->getMinutesDelay() . '\'' }}</span>
                @endif
            </td>
            <td class="headsign">
                <span class="headsign">{{ $trafficBoardRow->getHeadsign() }}</span>
            </td>
            <td class="platform text-centered">
                <span class="platform"> {{ $trafficBoardRow->getPlatform() }}</span>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
@endforeach
