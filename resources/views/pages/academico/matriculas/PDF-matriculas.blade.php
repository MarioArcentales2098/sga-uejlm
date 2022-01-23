<html>
<head>
    <title>PROFORMA INVOICE </title>
    <style>
        @page {
            margin: 0cm 0cm;
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            /* margin-top: 3.7cm; */
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
            position: fixed;
            top: 0.5cm;
            left: 0.25cm;
            right: 0.25cm;
            height: 5cm;
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


        .oficio {
            /* color: black; */
            /* width: 628px; */
            /* margin-left: auto; */
            /* margin-right: auto; */
            /* padding: 20px; */
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
            margin-top: 30px;
            /* margin-right: 15px; */
        }

        .f25 {
            font-size: 25px
        }

        .f20 {
            font-size: 20px
        }

        .f18 {
            font-size: 18px
        }

        .f15 {
            font-size: 15px;
        }

        .f14 {
            font-size: 14px;
        }

        .f13 {
            font-size: 13px;
        }

        .f12 {
            font-size: 12px;
        }

        .f11 {
            font-size: 11px;
        }

        .f10 {
            font-size: 10px;
        }

        .f8 {
            font-size: 8px;
        }

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
            font-family: Arial, Helvetica, sans-serif;
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
            /* background: #1D2B36; */
            background: #2C3E50;
            color: #fff;
            /* border: solid 1px #585858; */
            /* text-align: center; */
        }

        .tfoot-dark-1 tr th {
            /* background: #1D2B36; */
            background: #2C3E50;
            color: #fff;
            /* border: solid 1px #585858; */
            /* text-align: right; */
            font-size: 10px;
            padding: 0;
        }

        .table tr th {
            padding: 5px;
            font-size: 10px;
        }

        .table tr td,{
            padding: .05rem;
            font-size: 10px;
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

    </style>
</head>

<body>
    <header>
        <div class="d-flex border-b">
            <div class="col-2 text-center"><img src="{{ public_path('recursos/pdf/1986.png') }}" style="margin-top: 10px" width="80px" height="160px" /></div>
            <div class="col-8 text-center">
                <div class="head-datos" style="margin-top:50px;">
                    <div class="f18 text-gros">PROPEMAR INC</div>
                    <div class="f12"></span>6202 NW 38TH DRIVE CORAL SPRINGS, FL 33067</div>
                    <div class="f12"><span class="text-gros">Phone: </span> +593 5-231-8588</div>
                    <div class="f12"><span class="text-gros">Email: </span><a href="mailto:exportaciones@propemar.com.ec" target="_blank">exportaciones@propemar.com.ec</a></div>
                    <div class="f12"><span class="text-gros">Registration FDA: </span>19657390388</div>
                    <div class="f13 text-gros" style="margin-top: 20px">PROFORMA INVOICE Nº </div>
                </div>
            </div>
            <div class="col-2" >
                <div style=" text-align: right; margin-right:20px; margin-top:20px;">
                    {{-- @if ($cabecera->productof_fk == "EC")<img src="{{ public_path('recursos/pdf/ecuadorproduct.png') }}" style="margin-top: 10px" width="80px" height="120Px" /> @endif
                    @if ($cabecera->productof_fk == "PE")<img src="{{ public_path('recursos/pdf/perupdf.png') }}" style="margin-top: 30px" width="110px" height="80Px" />@endif --}}
                </div>
            </div>
        </div>
    </header>
    <footer></footer>
    <main>
        <div class="oficio">
            <div class="cuerpo-oficio">
                <!-- CLIENTE -- VENDEDOR -->
                <div class="mb-1-5" >
                    <div style="height: 110px;">
                        <div class="d-flex" >
                            <div  style="height: 116px; background:#F5F6FA; width:50%; ">
                                <div style="padding:5px;">
                                    <div class="text-gros f14">DATA CONSIGNEE</div>
                                    <table class="table">
                                        <tbody>
                                            {{-- <tr><td><span class="f11 text-gros">{{ mb_strtoupper($cabecera->cliente_nombre) == null ? '--' : $cabecera->cliente_nombre}}</span></td></tr>
                                            <tr><td><span class="f11 text-gros">Address: </span><span class="f11">{{ $cabecera->cabecera_direccion == null ? '--' : $cabecera->cabecera_direccion}}</span></td></tr>
                                            <tr><td><span class="f11 text-gros">Phone: </span><span class="f11">{{ mb_strtoupper($cabecera->cabecera_telefono) == null ? '--' : $cabecera->cabecera_telefono}}</span></td></tr>
                                            <tr><td><span class="f11 text-gros">Email: </span><span class="f11">{{ $cabecera->cabecera_correo == null ? '--' : $cabecera->cabecera_correo}}</span></td></tr> --}}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div style="height: 116px; background:#F5F6FA; width:49%; margin-left: 51%">
                                <div style="padding:5px;">
                                    <div class="text-gros f14">VENDOR</div>
                                    <table class="table">
                                        <tbody>
                                            {{-- <tr>
                                                @php $apellido = explode(" ", $cabecera->usuario_apellido) @endphp
                                                <td><span class="f11 text-gros">{{ (mb_strtoupper($cabecera->usuario_nombre)) }} {{  ( mb_strtoupper($apellido[0])) }}</span></td>
                                            </tr>
                                            <tr><td><span class="f11 text-gros">Date: </span><span class="f11">{{ date('d/m/Y' , strtotime($cabecera->cabecera_fecha))}}</span></td></tr>
                                            <tr><td><span class="f11 text-gros">Payment Terms: <br> </span><span class="f11">{{ $cabecera->termino_nombre == null ? '--' : $cabecera->termino_nombre}}</span></td></tr> --}}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- DETALLE -- NOTAS -->
                <div class="mb-1-5" >
                    <div style="height: 110px;">
                        <div class="d-flex" >
                            <div style="height: 116px; background:#F5F6FA; width:50%; ">
                                <div style="padding:5px;">
                                    <div class="text-gros f14">DETAILS</div>
                                    <table class="table">
                                        <tbody>
                                            {{-- <tr>
                                                <td><span class="f11 text-gros">PO: </span> <span class="f11">{{ $cabecera->cabecera_po == null ? '--' : $cabecera->cabecera_po }}</span></td>
                                            </tr>
                                            <tr>
                                                <td><span class="f11 text-gros">Container: </span><span class="f11">{{ mb_strtoupper($cabecera->cabecera_contenedor == null ? '--' : $cabecera->cabecera_contenedor) }}</span></td>
                                            </tr>
                                            <tr>
                                                <td><span class="f11 text-gros">Incotems: </span><span class="f11">{{ $cabecera->incoterms_nombre == null ? '--' : $cabecera->incoterms_nombre}}</span> </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <span class="f11 text-gros">Origin: </span><span class="f11">@if ($cabecera->puerto_origen == null) -- @else {{$cabecera->puerto_origen}} @endif
                                                        @if ($cabecera->puerto_origen_pais != null)/ {{$cabecera->puerto_origen_pais}} @endif
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><span class="f11 text-gros">Detination: </span><span class="f11">@if ($cabecera->puerto_destino == null) -- @else {{$cabecera->puerto_destino}} @endif
                                                        @if ($cabecera->puerto_destino_pais != null)/ {{$cabecera->puerto_destino_pais}} @endif
                                                    </span>
                                                </td>
                                            </tr> --}}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div  style="height: 116px; background:#F5F6FA; width:49%; margin-left: 51%">
                                <div style="padding:5px;">
                                    <div class="text-gros f14">NOTES</div>
                                    {{-- <table class="table"><tbody><tr><td><span class="f11">{{ $cabecera->cabecera_observacion == null ? '--' : $cabecera->cabecera_observacion}}</span></td> </tr></tbody></table> --}}
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- TABLA -->
                {{-- <div class="mb-1">
                    <table class="table table-striped table-bordere">
                        <thead class="thead-dark-1">
                            <tr>
                                <th class="text-center" width="5%">BOXES</th>
                                <th class="text-center" width="25%">DESCRIPTION</th>
                                <th class="text-center" width="10%">BRAND</th>
                                <th class="text-center" width="10%">PT</th>
                                <th class="text-center" width="5%">PACK</th>
                                <th class="text-center" width="10%">TOTAL WT</th>
                                <th class="text-center" width="10%">PRICE</th>
                                <th class="text-center" width="10%">AMOUNT</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $cajas = 0; $totalwt = 0;@endphp
                            @foreach ($contenido as $item)
                            <tr>
                                <td class="text-center">{{$item->contenido_cajas}} @php $cajas += $item->contenido_cajas @endphp</td>
                                <td class="text-left">{{$item->producto_ch_especie}} {{$item->producto_especie}} {{$item->producto_proceso}} {{$item->producto_talla}}</td>
                                <td class="text-left">{{$item->producto_marca}}</td>
                                <td class="text-center">{{number_format($item->producto_peso, 2)}} {{abreviarMedida($item->producto_peso_unidad)}}</td>
                                <td class="text-center">{{$item->producto_pack}}</td>
                                <td class="text-right">${{number_format($item->contenido_total_wt, 2)}} @php $totalwt += $item->contenido_total_wt @endphp</td>
                                <td class="text-right">${{number_format($item->contenido_precio, 2)}}</td>
                                <td class="text-right">${{number_format($item->contenido_total_row, 2)}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>

                                <td colspan="7" class="text-right"><span>SUBTOTAL</span></td>
                                <td class="text-right">${{number_format($cabecera->cabecera_subtotal, 2)}}</td>
                            </tr>
                        </tfoot>
                        <tfoot>
                            <tr>

                                <td colspan="7" class="text-right"><span>ADVANCE</span></td>
                                <td class="text-right">${{number_format($cabecera->cabecera_anticipo, 2)}}</td>
                            </tr>
                        </tfoot>
                        <tfoot class="tfoot-dark-1">
                            <tr>
                                <th class="text-center">{{ $cajas }}</th><th></th>
                                <th colspan="3" class="text-right"><span>TOTAL</span></th>
                                <th class="text-right"><span></span>${{number_format($totalwt, 2)}}</th><th></th>
                                <th class="text-right">${{number_format($cabecera->cabecera_total, 2)}}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="mb-1 f8">
                    <div>All claims or discrepancies must be notify by writting to our attention within 24 hours after received the product.</div>
                    <div>Do not dispose or return product with out written approval.</div>
                    <div>Per month 1 ½ % will be charged to all past due Invoices.</div>
                    <div>Buyer agrees to pay all costs of collection, including reasonable attorney’s fees.</div>
                </div>

                <div class="mb-1-5" style="height: 160px; margin-top: 30px;">
                    <div class="d-flex" >
                        <div class="col-6 text-center" ><img src="{{ public_path('recursos/pdf/rts.png') }}"   height="100%"></div>
                        <div class="col-6 text-center" ><img src="{{ public_path('recursos/pdf/rts.png') }}"   height="100%"> </div>
                    </div>
                </div> --}}
            </div>
        </div>

        <!--paginación -->
        @php $anio = date('Y'); @endphp
        <script type="text/php">
            if ( isset($pdf) ) {
                $pdf->page_script('
                    $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");
                    $color = array(0.35, 0.35, 0.35);
                    $pdf->text(20, 823, "© PROPEMAR {{$anio}} ", $font, 8, $color);
                    $pdf->text(520, 820, "PÁGINA $PAGE_NUM DE $PAGE_COUNT", $font, 8, $color);
                ');
            }
        </script>
    </main>
</body>

</html>