@extends('layouts.herramienta.herramienta')
@section('title-herramienta', 'Matrículas')
@section('style-herramienta')
<style>
    .card-asign {
        display: flex;
        border: solid 1px #e9ebff;
        align-items: center;
        background: #F5F6FE;
        padding: 1px 9px 3px 0px;
    }

    .card-asign .sect-check {
        /* font-size: 10px; */
        font-size: 5px;
        color: #393939;
        width: 45px;
        filter: hue-rotate(299deg)
    }

    .card-asign .card-asign-content {
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .card-asign-content .card-asign-title {
        font-size: 14px;
        font-weight: 600;
        color: #575757;
        text-transform: uppercase;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        max-width: 300px;
    }

    .card-asign-content .card-asign-desc {
        color: #575757;
        font-size: 11px;
        font-weight: 400;
    }

    .card-asign:hover {
        border: solid 1px #3c3c3c;
        align-items: center;
        color: #fff;
        background: #494949;
    }

    .card-asign:hover .card-asign-content .card-asign-title {
        color: #fff;
    }

    .card-asign:hover .card-asign-content .card-asign-desc {
        color: #fff;
    }

</style>
@endsection

@section('content-herramienta')
@csrf

<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box">
            <h4 class="page-title float-left">Matrículas</h4>
            <div class="float-right">
                @can('viewGeneralMatriculaCreate') 
                <a href="javascript:" data-toggle="modal" data-target="#createRegister" class="btn btn-success waves-effect waves-light btn-sm"><i class="fa fa-plus"></i> Nueva matrícula</a>
                @endcan
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card-box">
            <!-- BUSCADOR -->
            <div class="mb-4 d-flex" style="justify-content:right; align-items: center; ">
                <div class="w-50">
                    <form action="{{ route('viewMatriculas') }}" method="GET" id="formSearch"><input type="text" class="form-control" name="texto" value="{{$texto}}" placeholder="Buscador...." autocomplete="off"></form>
                </div>
                <div style="margin-left:10px; width: 100px">
                    <button type="submit" class="btn btn-primary w-100" form="formSearch"><i class="fa fa-search"></i></button>
                </div>
            </div>

            <div class="table-responsive" id="contenido_datos">
                <table class="tablesaw table tablesaw-stack">
                    <thead class="thead_dar">
                        <tr>
                            <th width="5%" class="text-left">N°</th>
                            <th width="10%" class="text-center">Fecha</th>
                            <th width="25%" class="text-left">Apellidos</th>
                            <th width="25%" class="text-left">Nombres</th>
                            <th width="10%" class="text-center">Curso</th>
                            <th width="15%" class="text-center">Año lectivo</th>
                            <th width="5%" class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tbody_registros">
                        @foreach ($matriculas as $item)
                        <tr>
                            <td class="text-center">{{ $rank ++}}</td>
                            <td class="text-center">{{ date('d/m/Y' , strtotime($item->fecha_matricula))}}</td>
                            <td class="text-left">{{$item->usuario_papellido}} {{$item->usuario_sapellido}}</td>
                            <td class="text-left">{{$item->usuario_pnombre}} {{$item->usuario_snombre}}</td>
                            <td class="text-center">{{mb_strtoupper($item->curso_nombre)}} {{mb_strtoupper($item->curso_nivel)}} "{{mb_strtoupper($item->curso_paralelo)}}"</td>
                            <td class="text-center">{{$item->periodo_nombre}}</td>
                            <td class="text-center">
                                @can('viewGeneralMatriculaDelete')
                                    <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteRegister" data-id="{{ $item->matricula_id }}"><i class="fa fa-trash"></i></button>
                                @endcan
                                @can('viewGeneralMatriculaEstudiantePDF')
                                    <a href="{{route('generatePDFMatriculasEstudiante', ['asign'=>$item->matricula_id,'id'=>$item->usuario_fk,'token'=>$item->usuario_token])}}" class="btn btn-sm btn-info" target="_blank"><i class="ti-printer"></i></a>
                                @endcan
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="col-md-12 text-center">
                    {{ $matriculas->withQueryString()->links() }}
                </div>
            </div>
        </div>

    </div>
</div>

<!--################ MODAL CREATE #################### -->
<div class="modal fade" id="createRegister" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: #1f9148; color: #fff;">
                <h5 class="modal-title" id="exampleModalLabel">Registrar matrícula</h5>
                <button type="button" class="close" onclick="limpiarModalRegister();" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xl-12 form-group">
                        <label>Cédula <span class="text-danger">*</span></label>
                        {{-- <input type="number" class="form-control" id="cedula" autocomplete="off" onkeyup="validateRegisterSimple(this.id);" onchange="consultUsuario();">          --}}

                        <div class="input-group mb-2 mr-sm-2 mb-sm-0">
                            <input type="number" class="form-control" id="cedula" autocomplete="off" onkeyup="validateRegisterSimple(this.id); limpiar();">
                            <div class="input-group-prepend">
                                <button type="button" class="btn btn-sm btn-success" onclick="consultUsuario();" id="btnsearchcedula"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-12 form-group">
                        <label>Nombres</label>
                        <input type="text" class="form-control" id="nombre" autocomplete="off" readonly onkeyup="validateRegisterSimple(this.id);">
                        <input type="hidden" class="form-control" id="usuario_fk" autocomplete="off">
                    </div>
                    <div class="col-xl-6 form-group">
                        <label>Año lectivo <span class="text-danger">*</span></label>
                        <select class="form-control" id="periodo" onchange="validateRegisterSimple(this.id)">
                            <option value="">-- Seleccionar --</option>
                            @foreach ($periodos as $item)
                            <option value="{{$item->id}}">{{$item->nombre}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-xl-6 form-group">
                        <label>Curso <span class="text-danger">*</span></label>
                        <select class="form-control" id="curso" onchange="validateRegisterSimple(this.id)">
                            <option value="">-- Seleccionar --</option>
                            @foreach ($cursos as $item)
                            <option value="{{$item->id}}">{{mb_strtoupper($item->nombre)}} {{mb_strtoupper($item->nivel)}} {{mb_strtoupper($item->paralelo)}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="display: flex; justify-content: space-between; padding: 8px 15px;">
                <button type="button" class="btn btn-secondary" onclick="limpiarModalRegister();" data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</button>
                <button type="button" class="btn btn-primary ml-auto" id="btnCreateRegister"><i class="fa fa-check"></i> Guardar</button>
            </div>
        </div>
    </div>
</div>
<!--################ DELETE REGISTER #################### -->
<div class="modal fade" id="deleteRegister" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static" style="display: none;">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: #eb877a; color: #fff;">
                <h5 class="modal-title text-dar" id="exampleModalLabel">Eliminar matrícula</h5>
                <button type="button" class="close" onclick="limpiarModalDeleteRegister();" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body" style="padding: 1rem 1rem 6px;">
                <input type="hidden" id="id_delete_registro_fk">
                <div class="row">
                    <div class="col-xl-12 text-center">
                        <div><i class="fa fa-trash" style="color: #eb877a; font-size: 125px;"></i></div>
                        <div style="font-weight: 500; font-size: 15px; color: #eb877a;"><span id="delete-text-register"></span></div>
                        <span style="font-weight: 500; font-size: 15px;">¿Está seguro de eliminar el registro?</span>

                    </div>
                    <div class="col-xl-12 text-center mt-4">
                        <div style="background-color: #fbebcc;border-color: #f9e1af;border-radius: 5px;color: #c38e24;
                        padding:5px 6px;line-height:10px;font-size:13px;text-align:justify;">
                            <small><i class="fa fa-exclamation-triangle"></i> Si elimina el registro se perderán datos en caso de existir.</small>
                        </div>
                    </div>
                </div>
            </div>
            <div style="display: flex; justify-content: space-between; padding: 8px 15px; border-bottom-right-radius: 0.3rem; border-bottom-left-radius: 0.3rem;">
                <button type="button" class="btn btn-secondary" onclick="limpiarModalDeleteRegister();" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnDeleteRegister"><i class="fa fa-check"></i> Confirmar</button>
            </div>
        </div>
    </div>
</div>

@endsection
@section('script-herramienta')
@php $ran = rand(); @endphp
<script src="{{asset("herramienta/academico/matricula.js?v=$ran")}}"></script>
@endsection
