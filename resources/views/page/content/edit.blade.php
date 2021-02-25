@extends('layouts.app')

@section('css-header')
<link rel="stylesheet" href="{{ asset('vendors/select2/css/select2.min.css') }}" type="text/css">
<!-- Css -->
<link rel="stylesheet" href="{{ asset('vendors/datepicker/daterangepicker.css') }}" type="text/css">
@endsection
@section('content')
    <?php $logs = $logs ?? null ?>
    <x-content.FormUpdate 
        :contents=$contents 
        :model=$model 
        :logs=$logs
    />
@endsection

@section('js-footer')
<!-- Javascript -->
<script src="{{ asset('vendors/select2/js/select2.min.js') }}"></script>
<script src="{{ asset('vendors/datepicker/daterangepicker.js') }}"></script>
<!-- <script src="{{ asset('vendors/input-mask/jquery.mask.js') }}"></script> -->
<!-- Javascript -->
<script src="{{ asset('assets/js/examples/select2.js') }}"></script>
<script src="{{ asset('assets/js/examples/datepicker.js') }}"></script>
<!-- <script src="{{ asset('assets/js/examples/input-mask.js') }}"></script> -->
@endsection