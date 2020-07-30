<!doctype html>
<?php
/**
 * @var $trafficBoardViews \App\Http\Models\TrafficBoardViewData[]
 */
?>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('includes.head')
<body>

@include('includes.header')

<div class="col-md-3">
    @include('content.sidebar')
</div>

<div class="col-md-9 content">
    @include('content.' . $content)
</div>

@include('includes.footer')

</body>
</html>
