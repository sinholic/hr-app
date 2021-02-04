@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <div class="card bg-primary-bright text-primary">
                <div class="card-body text-center">
                    <h2 class="font-weight-bold">$1.422.315</h2>
                    <div>Sales</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-secondary-bright text-secondary">
                <div class="card-body text-center">
                    <h2 class="font-weight-bold">562</h2>
                    <div>Orders</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success-bright text-success">
                <div class="card-body text-center">
                    <h2 class="font-weight-bold">82</h2>
                    <div>Products</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info-bright text-info">
                <div class="card-body text-center">
                    <h2 class="font-weight-bold">1.482</h2>
                    <div>Customers</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-dark-bright text-dark">
                <div class="card-body text-center">
                    <h2 class="font-weight-bold">1807,8</h2>
                    <div>Average bill</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-danger-bright text-danger">
                <div class="card-body text-center">
                    <h2 class="font-weight-bold">1.482</h2>
                    <div>Return</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js-footer')
<!-- Slick.js -->
<script src="{{ asset('vendors/slick/slick.min.js') }}"></script>

<!-- Chartjs -->
<script src="{{ asset('vendors/charts/chartjs/chart.min.js') }}"></script>

<!-- Apex chart -->
<script src="https://apexcharts.com/samples/assets/irregular-data-series.js"></script>
<script src="{{ asset('vendors/charts/apex/apexcharts.min.js') }}"></script>

<!-- Circle progress -->
<script src="{{ asset('vendors/circle-progress/circle-progress.min.js') }}"></script>

<!-- Dashboard scripts -->
<script src="{{ asset('assets/js/examples/dashboard.js') }}"></script>
<div class="colors"> <!-- To use theme colors with Javascript -->
    <div class="bg-primary"></div>
    <div class="bg-primary-bright"></div>
    <div class="bg-secondary"></div>
    <div class="bg-secondary-bright"></div>
    <div class="bg-info"></div>
    <div class="bg-info-bright"></div>
    <div class="bg-success"></div>
    <div class="bg-success-bright"></div>
    <div class="bg-danger"></div>
    <div class="bg-danger-bright"></div>
    <div class="bg-warning"></div>
    <div class="bg-warning-bright"></div>
</div>

<!-- App scripts -->
<script src="{{ asset('assets/js/app.js') }}"></script>

<!-- <script>
    $(function () {
        $('.slick-js').slick({
            speed: 500,
            arrows: false,
            slidesToShow: 5,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 2000,
            responsive: [
                {
                    breakpoint: 1200,
                    settings: {
                        slidesToShow: 3
                    }
                },
                {
                    breakpoint: 992,
                    settings: {
                        slidesToShow: 2
                    }
                },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 1
                    }
                }
            ]
        });
    })
</script> -->
@endsection