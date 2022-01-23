<!-- CURSOS - ASIGNATURAS -->
<div class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-6 col-xl-6">
                <div class="col-md-12 col-xl-12">
                    <div class="card-box tilebox-two">
                        <i class="icon-puzzle float-right text-muted"></i>
                        <h6 class="text-success text-uppercase m-b-15 m-t-10">Materias</h6>
                        <h2 class="m-b-10"><span>{{ $asignaturas }}</span></h2>
                    </div>
                </div>



                <div class="col-md-12 col-xl-12">
                    <div class="card-box tilebox-two">
                        <i class="icon-close float-right text-muted"></i>
                        <h6 class="text-pink text-uppercase m-b-15 m-t-10">Inasistencias injustificadas</h6>
                        <h2 class="m-b-10"><span>{{ $faltas - $faltas_justificadas}}</span></h2>
                    </div>
                </div>
                <div class="col-md-12 col-xl-12">
                    <div class="card-box tilebox-two">
                        <i class="icon-close float-right text-muted"></i>
                        <h6 class="text-primary text-uppercase m-b-15 m-t-10">Inasistencias justificadas</h6>
                        <h2 class="m-b-10"><span>{{ $faltas_justificadas }}</span></h2>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-6">
                <div class="card-box" id="container_users" style="height: 440px;">
                    <canvas id="chart_usuarios" width="100%"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>




@section('script-herramienta')
<script src="{{asset('herramienta/lib/chart.js/chart.js')}}"></script>
<script>
    var contfaltas = @json($faltas - $faltas_justificadas);
    var contfaltasJust = @json($faltas_justificadas);
    var conttotalfaltas = @json($faltas);
    var peractual = @json(System::periodoActual());
    generateChartHome()

    function generateChartHome() {
        document.getElementById('container_users').innerHTML = "";
        document.getElementById('container_users').innerHTML = `<canvas id="chart_usuarios" width="100%"></canvas>`;
        var bar_horas = document.getElementById("chart_usuarios").getContext('2d');
        var myBarChart_horas = new Chart(bar_horas, {
            type: 'bar'
            , data: {
                labels: ["INASISTENCIAS", "JUSTIFICADAS.", "TOTAL"]
                , datasets: [{
                    label: "Cantidad"
                    , backgroundColor: ['#ff7aa3', '#039cfd', '#f1b53d']
                    , data: [contfaltas, contfaltasJust, conttotalfaltas]
                , }]
            , }
            , options: {
                title: {
                    display: true
                    , text: `INASISTENCIAS PERIODO LECTIVO ${peractual}`
                }
                , maintainAspectRatio: false
                , layout: {
                    padding: {
                        left: 0
                        , right: 0
                        , top: 0
                        , bottom: 0
                    }
                }
                , scales: {
                    xAxes: [{
                        gridLines: {
                            display: false
                            , drawBorder: false
                        }
                        , ticks: {
                            min: 0
                            , stepSize: 1
                        , }
                        , maxBarThickness: 25
                    , }]
                    , yAxes: [{
                        ticks: {
                            min: 0
                            , padding: 10
                            , stepSize: 1
                        , }
                        , gridLines: {
                            color: "rgb(234, 236, 244)"
                            , zeroLineColor: "rgb(234, 236, 244)"
                            , drawBorder: false
                            , borderDash: [2]
                            , zeroLineBorderDash: [2]
                        }
                    }]
                , }
                , legend: {
                    display: false
                    , position: "top"
                }
                , tooltips: {
                    titleMarginBottom: 10
                    , titleFontColor: '#6e707e'
                    , titleFontSize: 15
                    , backgroundColor: "rgb(255,255,255)"
                    , bodyFontColor: "#858796"
                    , borderColor: '#dddfeb'
                    , borderWidth: 1
                    , xPadding: 15
                    , yPadding: 15
                    , displayColors: true
                    , caretPadding: 10
                    , mode: 'index'
                , }
            , }
        });
    }

</script>
@endsection
