<!doctype html>
<?php
/**
 * @var $trafficBoardViews \App\Http\Models\TrafficBoardViewData[]
 */
?>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('includes.head')
<body class="fullscreen">
    @include('content.' . $content)
</body>
</html>
