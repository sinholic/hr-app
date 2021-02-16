'use strict';
$(document).ready(function () {

    $('#myTable').DataTable({
        // responsive: true
    });

    $('#example1').DataTable({
        responsive: true
    });

    $('#example2').DataTable({
        "scrollY": "400px",
        "scrollCollapse": true
    });

});