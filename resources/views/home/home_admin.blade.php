@section('style-herramienta')

<style>
   
</style>
@endsection


<!-- COUNTS USUARIOS -->
<div class="row">
    <div class="col-md-12 col-xl-6">
        <div class="row">
            <div class="col-md-6 col-xl-6">
                <div class="card-box tilebox-two">
                    <i class="icon-people float-right text-muted"></i>
                    <h6 class="text-warning text-uppercase m-b-15 m-t-10">Usuarios</h6>
                    <h2 class="m-b-10"><span>{{ $total_usuarios }}</span></h2>
                </div>
            </div>
            <div class="col-md-6 col-xl-6">
                <div class="card-box tilebox-two">
                    <i class="icon-user float-right text-muted"></i>
                    <h6 class="text-success text-uppercase m-b-15 m-t-10">Docentes</h6>
                    <h2 class="m-b-10"><span>{{ $total_docentes }}</span></h2>
                </div>
            </div>
            <div class="col-md-6 col-xl-6">
                <div class="card-box tilebox-two">
                    <i class="icon-user-female float-right text-muted"></i>
                    <h6 class="text-pink text-uppercase m-b-15 m-t-10">Secretarias</h6>
                    <h2 class="m-b-10"><span>{{ $total_secretaria }}</span></h2>
                </div>
            </div>
            <div class="col-md-6 col-xl-6">
                <div class="card-box tilebox-two">
                    <i class="icon-user float-right text-muted"></i>
                    <h6 class="text-primary text-uppercase m-b-15 m-t-10">Estudiantes</h6>
                    <h2 class="m-b-10"><span>{{ $total_estudiantes }}</span></h2>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-xl-6">
        <div class="card-box" id="container_users" style="height: 285px;">
            <canvas id="chart_usuarios" width="100%"></canvas>
        </div>
    </div>
</div>

<!-- CURSOS - ASIGNATURAS -->
<div class="row">    
    <div class="col-md-6 col-xl-6">
        <div class="card-box tilebox-two">
            <i class="icon-badge float-right text-muted"></i>
            <h6 class="text-warning text-uppercase m-b-15 m-t-10">Total cursos</h6>
            <h2 class="m-b-10"><span>{{ $total_cursos }}</span></h2>
        </div>
    </div>
    <div class="col-md-6 col-xl-6">
        <div class="card-box tilebox-two">
            <i class="icon-puzzle float-right text-muted"></i>
            <h6 class="text-success text-uppercase m-b-15 m-t-10">Total asignaturas</h6>
            <h2 class="m-b-10"><span>{{ $total_materias }}</span></h2>
        </div>
    </div>
    
    {{-- <div class="col-md-6 col-xl-3">
        <div class="card-box tilebox-two">
            <i class="icon-user-female float-right text-muted"></i>
            <h6 class="text-pink text-uppercase m-b-15 m-t-10">Total matriculas</h6>
            <h2 class="m-b-10"><span>{{ $total_secretaria }}</span></h2>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card-box tilebox-two">
            <i class="icon-user float-right text-muted"></i>
            <h6 class="text-primary text-uppercase m-b-15 m-t-10">Total Estudiantes</h6>
            <h2 class="m-b-10"><span>{{ $total_estudiantes }}</span></h2>
        </div>
    </div> --}}
</div>



@section('script-herramienta')
<script src="{{asset('herramienta/lib/chart.js/chart.js')}}"></script>
<script>
    var contusuarios = @json($total_usuarios);
    var contedocentes = @json($total_docentes);
    var contsecretaria = @json($total_secretaria);
    var contestudiante = @json($total_estudiantes);
    generateChartHome()
    function generateChartHome(){
        document.getElementById('container_users').innerHTML = "";
        document.getElementById('container_users').innerHTML = `<canvas id="chart_usuarios" width="100%"></canvas>`;
        var bar_horas= document.getElementById("chart_usuarios").getContext('2d');
        var myBarChart_horas = new Chart(bar_horas, {
            type: 'horizontalBar',
            data: {
                labels: ["Usuarios", "Docentes", "Secretaria", "Estudiantes"],
                datasets: [
                    {
                    label: "Cantidad",
                    backgroundColor: ['#f1b53d','#1bb99a', '#ff7aa3','#039cfd'],
                    data: [contusuarios, contedocentes , contsecretaria , contestudiante],
                }],
            },
            options: {
                title: {
                    display: false,
                    text: 'Componentes: Total de horas'
                },
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        left: 0,
                        right: 0,
                        top: 0,
                        bottom: 0
                    }
                },
                scales: {
                    xAxes: [{
                        gridLines: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            min: 0,
                            stepSize: 1,
                        },
                        maxBarThickness: 25,
                    }],
                    yAxes: [{
                        ticks: {
                            min: 0,
                            padding: 10,
                            stepSize: 1,
                        },
                        gridLines: {
                            color: "rgb(234, 236, 244)",
                            zeroLineColor: "rgb(234, 236, 244)",
                            drawBorder: false,
                            borderDash: [2],
                            zeroLineBorderDash: [2]
                        }
                    }],
                },
                legend: {
                    display: false,
                    position: "top"
                },
                tooltips: {
                    titleMarginBottom: 10,
                    titleFontColor: '#6e707e',
                    titleFontSize: 15,
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: true,
                    caretPadding: 10,
                    mode: 'index',
                },
            }
        });
    }
</script>
@endsection



