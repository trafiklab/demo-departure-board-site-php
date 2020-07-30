<?php
/**
 * @var $sidebarContext SidebarContext
 */

use App\Http\Models\SidebarContext;

?>
<div class="sidebar">
    <div class="search">
        <h2>{{ __('Search') }}</h2>
        <form action="{{ route('searchStopLocation') }}" method="post">
            <input type="text" name="name" placeholder="{{ __('Station name') }}">
            <select name="type">
                <option value="departures">{{ __('Departures') }}</option>
                <option value="arrivals">{{ __('Arrivals') }}</option>
            </select>
            <input type="submit" value="Search">
        </form>
    </div>
    @if(isset($sidebarContext) && $sidebarContext->doesSupportFullscreen())
        <div class="permalink">
            <label for="permalink-copy">Permalink:</label>
            <input id="permalink-copy" type="text" value="{{ $sidebarContext->getPermaLink() }}">
            <label for="fullscreen-link-copy">Fullscreen:</label>
            <input id="fullscreen-link-copy" type="text" value="{{ $sidebarContext->getFullscreenLink() }}">
            <a href="{{ $sidebarContext->getFullscreenLink() }}"><button>View fullscreen</button></a>
        </div>
    @endif
</div>
