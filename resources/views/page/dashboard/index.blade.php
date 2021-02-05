@extends('layouts.app')

@section('content')

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
<!-- App scripts -->
<script src="{{ asset('assets/js/app.js') }}"></script>

<script>
    $(function () {
        setTimeout(function() {
            toastr.options = {
                timeOut: 2000,
                progressBar: !0,
                showMethod: "slideDown",
                hideMethod: "slideUp",
                showDuration: 200,
                hideDuration: 200,
                positionClass: "toast-top-center"
            }, 
            toastr.success(`Welcome {{ \Auth::user()->name }}, you're currently login as {{ \Auth::user()->roles[0]->name }}.`)
        }, 500)
    })
</script>
@endsection