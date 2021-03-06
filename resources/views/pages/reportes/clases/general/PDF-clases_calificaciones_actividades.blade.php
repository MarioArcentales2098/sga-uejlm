

<html>
    <head>
        <title>{{ $nombre_pdf }}</title>
        <style>
            @page {
                margin: 0cm 0cm;
                /* font-family: Arial, Helvetica, sans-serif; */
                font-family: "Times New Roman", Times, serif;
            }

            body {
                /* font-family: Arial, Helvetica, sans-serif; */
                font-family: "Times New Roman", Times, serif;
                margin-top: 5.5cm;
                margin-left: 1cm;
                margin-right: 1cm;
                margin-bottom: 1cm;
                color: #1D2B36;
            }

            a,
            a:hover,
            a:focus {
                color: #1D2B36;
                text-decoration: none;
            }

            header {
                /* background: red; */
                position: fixed;
                top: 0.5cm;
                left: 0.25cm;
                right: 0.25cm;
                height: 5cm;
                /* height: 8cm; */
            }

            footer {
                position: fixed;
                bottom: 0cm;
                left: 0cm;
                right: 0cm;
                height: 1cm;
            }

            .text-left {
                text-align: left;
            }

            .text-center {
                text-align: center;
            }

            .text-right {
                text-align: right;
            }

            .title {
                font-size: 20px;
                font-weight: bolder;
                margin-bottom: 15px;
            }

            .text-strong {
                font-weight: bold;
            }

            .conten {
                line-height: 25px;
            }

            .d-flex {
                display: flex !important;
            }

            .col-0-1 {
                float: left;
                width: 4.33333%;
            }

            .col-1 {
                float: left;
                width: 8.33333%;
            }

            .col-2 {
                float: left;
                width: 16.66667%;
            }

            .col-3 {
                float: left;
                width: 25%;
            }

            .col-4 {
                float: left;
                width: 33.33%;
            }

            .col-5 {
                float: left;
                width: 41.66667%;
            }
            .col-5-5 {
                float: left;
                width: 44.66667%;
            }

            .col-6 {
                float: left;
                width: 50% !important;
            }

            .col-7 {
                float: left;
                width: 58.33333%;
            }

            .col-8 {
                float: left;
                width: 66.66667%;
            }

            .col-9 {
                float: left;
                width: 75%;
            }

            .col-10 {
                float: left;
                width: 83.33333%;
            }

            .col-11 {
                float: left;
                width: 91.66667%
            }

            .col-12 {
                float: left;
                width: 100%;
            }

            .head-datos {
                text-emphasis: center;
                /* margin-top: 18px; */
                /* margin-top: 30px; */
                /* margin-right: 15px; */
            }

            .f25{font-size: 25px;}
            .f20{font-size: 20px;}
            .f19{font-size: 19px;}
            .f18{font-size: 18px;}
            .f17{font-size: 17px;}
            .f16{font-size: 16px;}
            .f15{font-size: 15px;}
            .f14{font-size: 14px;}
            .f13{font-size: 13px;}
            .f12{font-size: 12px;}
            .f11{font-size: 11px;}
            .f10{font-size: 10px;}
            .f8{font-size: 8px;}

            /*footer*/
            .fot {
                margin-left: 20px;
            }

            .text-gros {
                font-weight: 700;
            }

            .text-uppercase {
                text-transform: uppercase;
            }

            .titulo-archivo {
                padding: 2px 5px;
                background: #2C3E50;
                color: #fff;
            }

            table {
                margin: 0px;
                /* font-family: Arial, Helvetica, sans-serif; */
                width: 100%;
                max-width: 100%;
                background-color: transparent;
                border-collapse: collapse;
            }
            .thead {
                /* display: table-header-group; */
                /* vertical-align: middle; */
                /* border-color: inherit; */
            }
            tr {
                display: table-row;
                vertical-align: inherit;
                border-color: inherit;
            }

            .thead-dark-1 tr th,
            .thead-dark-1 tr td {
                color: #000;
                background-color: rgba(0, 0, 0, 0.05);
                border: solid 1px rgb(201, 201, 201) !important;
            }

            .tfoot-dark-1 tr th {
                /* background: #1D2B36; */
                background: #EFEFEF;
                color: #fff;
                /* border: solid 1px #585858; */
                /* text-align: right; */
                font-size: 10px;
                padding: 0;
            }

            .table tr th {
                padding: 5px;
                font-size: 12px;
            }

            .table tr td,{
                padding: 5px;
                font-size: 11px;
            }

            .table-bordered tr th,
            .table-bordered tr td {
                border: 1px solid #e3ebf3;
            }
            .table-striped tbody tr:nth-of-type(odd) {
                background-color: rgba(0, 0, 0, 0.05);
            }
            .mb-1 {
                margin-bottom: 10px !important;
            }
            .mb-1-5 {
                margin-bottom: 15px !important;
            }
            .mb-2 {
                margin-bottom: 20px !important;
            }
            .mr-2 {
                margin-right: 20px !important;
            }
            .mb-2-5 {
                margin-bottom: 25px !important;
            }
            .mb-3 {
                margin-bottom: 30px !important;
            }
            .ml-auto{
                margin-left: auto !important;
            }
            .mr-auto{
                margin-right: auto !important;
            }
            .firmas {
                margin-top: 140px;
            }
            .hr {
                border: 0;
                margin-top: 0px;
                border: 1px solid #333;
                height: 0;
                width: 100%;
                margin-bottom: 5px
            }
            .section-izq{
                float: left; width: 45%;
            }
            .section-der{
                margin-top: 0px;
                margin-left: 50%; width:45%;
            }
            .border_db{
                margin-left: 25px;
                margin-right: 25px;
                width:93%;
                margin-top: 70px;
                border-top: none;
                border-left: none;
                border-right: none;
                /* border: solid 1px red;  */
                border-bottom-style: double;
                border-bottom-width: 3px;
                /* border-spacing: 10px; */
            }
            .mt-1{ margin-top: 10px !important;}
            .mt-2{ margin-top: 20px !important;}
            .mt-3{ margin-top: 30px !important;}
            .mt-4{ margin-top: 40px !important;}
        </style>
    </head>
    <body>
        @php
            function funcionTransformarDatos($array_notas) {
                $sumatoria = 0.00;
                foreach ($array_notas as $key) {
                    $sumatoria += $key;
                }
                return number_format($sumatoria, 2);
            }
        @endphp
        <header>
            <div class="d-flex">
                <div class="col-2 text-center" onkeypress="" style="margin-top: 30px;"><img src="{{ public_path('recursos/logo_ue.jpg') }}" width="90px" height="70px" /></div>
                <div class="col-9 text-center" style="margin-top: 30px;">
                    <div class="head-datos">
                        <div class="f20 text-gros">UNIDAD EDUCATIVA PARTICULAR "JUAN LE??N MERA"</div>
                        <div class="f13 text-gros" style="margin-top: 5px;"></span>RESOLUCI??N N.?? 038</div>
                    </div>
                </div>
                <div class="col-1"></div>
            </div>
            <div class="d-flex" style="margin-top: 115px;">
                <div class="col-8 text-left" style="margin-left: 25px">
                    <div class="f13"><span class="text-gros">DIRECCI??N:</span>   <span style="margin-left: 23px">Calle Haraway entre las Avenidas Anibal San Andres e Isacc Mendoza</span></div>
                    <div class="f13"><span class="text-gros">E-MAIL:</span>      <span style="margin-left: 50px">uejuanleonmerajaramijo@hotmail.com</span></div>
                    <div class="f13"><span class="text-gros">C??DIGO AMIE:</span> <span style="margin-left: 5px">13H04278</span></div>
                    <div class="f13"><span class="text-gros">CANT??N:</span>      <span style="margin-left: 42px">Jaramij??</span></div>
                </div>
                <div class="col-3 text-left" style="margin-left: 40px;">
                    <div class="f13" style="margin-top: 30px"><span class="text-gros">TELEFONO:</span>    <span style="margin-left: 14px">0986424074</span></div>
                    <div class="f13"><span class="text-gros">PARROQUIA:</span>                            <span style="margin-left: 5px">Jaramij??</span></div>
                </div>
            </div>
            <div class="border_db"></div>
        </header>
        <footer></footer>
        <main>
            <div class="cuerpo-oficio mt-4">
                <div class="text-center">
                    <div class="f20 text-gros">REPORTE DE CALIFICACIONES</div>
                </div>

                <div class="mt-2 text-center f15"><span class="text-gros">ASIGNATURA:</span> {{mb_strtoupper($asignatura->asignatura_nombre)}}  </div>
                <div class="mt-2 f14" style="height: 70px;">
                    <div class="d-flex">
                        <div class="text-center" style="width:32%;"><span class="text-gros">GRADO/CURSO:</span> {{mb_strtoupper($asignatura->curso_nombre)}} {{mb_strtoupper($asignatura->curso_nivel)}}"{{mb_strtoupper($asignatura->curso_paralelo)}}"</div>
                        <div class="text-center" style="width:32%; margin-left: 32.3%"><span class="text-gros">JORNADA:</span> MATUTINA</div>
                        <div class="text-center" style="width:32%; margin-left: 65%"><span class="text-gros">PERIODO:</span> {{mb_strtoupper(System::periodoActual())}} - {{mb_strtoupper($parciales->quimestre_nombre)}}</div>
                    </div>
                </div>


                <!-- TABLA -->
                <div class="mb-1">
                    <table class="table table-bordered">
                        <thead class="thead-dark-1">
                            <tr>
                                <th class="text-center" width="40%">Estudiante</th>
                                @foreach ($actividades as $item)
                                    <th class="text-center" style=" height: 110px; padding: 0px; width:10%"><div>
                                     {{$item->actividad_abr}}: {{$item->nombre}}</div>
                                    </th>
                                @endforeach
                                <th class="text-center" width="10%">
                                    <div>Promedio</div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (sizeof($estudiantes) > 0)
                                @foreach ($estudiantes as $alumn)
                                    <tr>
                                        <td class="text-left">
                                            {{mb_strtoupper($alumn->apellido_paterno)}}
                                            {{mb_strtoupper($alumn->apellido_materno)}}
                                            {{mb_strtoupper($alumn->primer_nombre)}}
                                            {{mb_strtoupper($alumn->segundo_nombre)}}
                                        </td>
                                        @if (sizeof($actividades) > 0)
                                            @foreach ($actividades as $actividad)
                                                @foreach ($calificaciones as $cal)
                                                    @if ($cal->actividad_fk == $actividad->id && $cal->matriculado_fk == $alumn->ident_matricula)
                                                        <td class="text-center">{{$cal->calificacion}}</td>
                                                    @endif
                                                @endforeach
                                            @endforeach
                                            @foreach ($calificaciones_parcial as $ep)
                                                @if ($ep->matriculado_fk == $alumn->ident_matricula)
                                                    <td class="text-center">{{$ep->calificacion}}</td>
                                                @endif
                                            @endforeach
                                        @else
                                            <td class="text-center">0.00</td>
                                        @endif
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="{{sizeof($actividades) + 2}}">
                                        No se encontraron datos.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>

                </div>
            </div>


            <!--paginaci??n -->
            @php $anio = date('Y'); @endphp
            <script type="text/php">
                if ( isset($pdf) ) {
                    $pdf->page_script('
                        $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");
                        $color = array(0.35, 0.35, 0.35);
                        $pdf->text(20, 823, "?? SGA-UEJLM {{$anio}} ", $font, 8, $color);
                        $pdf->text(520, 820, "P??GINA $PAGE_NUM DE $PAGE_COUNT", $font, 8, $color);
                    ');
                }

            </script>
        </main>
    </body>
    </html>

