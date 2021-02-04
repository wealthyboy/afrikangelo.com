@extends('layouts.app')
 
@section('content')

<div class="top-notice text-white bg--gray">
    <div class="container-fluid text-center">
        <div class="row">
            
            <div class="col-12">
                <h5 class="d-inline-block text-uppercase mb-0"><b><i class="fas fa-money-check"></i>
                    USE TLS10 and get 10% off when you shop over 600k</b>
                </h5>
            </div>

        </div>
        
    </div><!-- End .container -->
</div>



@if ($sliders->count())
<div class="owl-carousel main-banner-slider owl-theme">
    @foreach($sliders as $slider)
        <div class="item">
            <a href="{{ $slider->link }}"><img src="{{ $slider->image }}" /></a>
        </div>
    @endforeach
</div>
@endif

@if ($banners->count())
<div class="container-fliud mb-1 mt-1">
    <div class="row no-gutters">
        @foreach($banners as $banner)
        <div data-title="{{ $banner->title }}" class="{{ $banner->col }} {{ $banner->sm_col_width }} {{ $banner->col == 'col-lg-3' ?  'col-6    p-0' : '' }} p-1 {{ $banner->title }} text-center">
                <div class="ba">
                    <a class="portfolio-interior" href="{{ $banner->link }}">
                        <img src="{{ $banner->image }}" alt="{{ $banner->title }}" />
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endif
 
@endsection
@section('page-scripts')
@stop
@section('inline-scripts')
@stop

