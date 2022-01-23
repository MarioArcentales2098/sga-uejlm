@extends('layouts.herramienta.herramienta')
@section('title-herramienta', 'Cursos')
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

    .card-asign-true .sect-check {
        /* font-size: 10px; */
        font-size: 5px;
        color: #393939;
        width: 45px;
        filter: hue-rotate(299deg)
    }

    .card-asign .card-asign-content,
    .card-asign-true .card-asign-content {
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

    .card-asign-true {
        display: flex;
        border: solid 1px #e9ebff;
        align-items: center;
        background: #6e1cc9;
        padding: 1px 4px 3px 0px;
        border: solid 1px rgb(82, 82, 82);
    }

    .card-asign-true .card-asign-content .card-asign-title {
        color: #fff;
    }

    .card-asign-true .card-asign-content .card-asign-desc {
        color: #fff;
    }

</style>
@endsection

@section('content-herramienta')
@csrf

<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box">
            <h4 class="page-title float-left">Cursos</h4>
            @can('viewGeneralCursosCreate')
            <div class="float-right">
                <a href="javascript:" data-toggle="modal" data-target="#createRegister" class="btn btn-success waves-effect waves-light btn-sm"><i class="fa fa-plus"></i> Crear curso</a>
            </div>
            @endcan
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
                    <form action="{{ route('viewCursos') }}" method="GET" id="formSearch"><input type="text" class="form-control" name="texto" value="{{$texto}}" placeholder="Buscador...." autocomplete="off"></form>
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
                            <th width="55%" class="text-left">Curso</th>
                            <th width="10%" class="text-center">Nivel</th>
                            <th width="10%" class="text-center">Paralelo</th>
                            <th width="15%" class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tbody_registros">
                        @foreach ($cursos as $item)
                        <tr>
                            <td>{{ $rank++ }}</td>
                            <td>{{ $item->nombre }}</td>
                            <td class="text-center">{{ $item->nivel }}</td>
                            <td class="text-center">{{ $item->paralelo }}</td>
                            <td class="text-center">
                                @can('viewGeneralCursosPDF')
                                <a href="{{ route('generatePDFCursosAsing', ['id'=>$item->id]) }}" class="btn btn-sm btn-primary" target="_blank"><i class="ti-printer"></i></a>
                                @endcan
                                @can('viewGeneralCursosAsignar')
                                <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#asignRegister" data-id="{{$item->id}}" data-asign_nombre="{{$item->nombre}}" title="Vincular asignaturas"><i class="fa fa-link"></i></button>
                                @endcan
                                @can('viewGeneralCursosDelete')
                                <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteRegister" data-id="{{$item->id}}" data-edit_nombre="{{$item->nombre}}" title="Eliminar registro"><i class="fa fa-trash"></i></button>
                                @endcan
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                {{ $cursos->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>

<!--################ MODAL CREATE #################### -->
<div class="modal fade" id="createRegister" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static" style="display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: #1f9148; color: #fff;">
                <h5 class="modal-title" id="exampleModalLabel">Crear nuevo curso</h5>
                <button type="button" class="close" onclick="limpiarModalRegister();" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xl-12 form-group">
                        <label>Curso <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nombre" autocomplete="off" onkeyup="validateRegisterSimple(this.id)">
                    </div>
                    <div class="col-xl-6 form-group">
                        <label>Nivel <span class="text-danger">*</span></label>
                        <select class="form-control" id="nivel" onchange="validateRegisterSimple(this.id)">
                            <option value="">-- Seleccionar --</option>
                            @for($i=1; $i<=13; $i++) <option value="{{$i}}">{{$i}}</option>
                                @endfor
                        </select>
                    </div>
                    <div class="col-xl-6 form-group">
                        <label>Paralelo <span class="text-danger">*</span></label>
                        <select class="form-control" id="paralelo" onchange="validateRegisterSimple(this.id)">
                            <option value="">-- Seleccionar --</option>
                            @for($i=65; $i<=72; $i++) <option value="{{ chr($i) }}">{{ chr($i) }}</option>
                                @endfor
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
<!--################ MODAL EDIT #################### -->
<div class="modal fade" id="editRegister" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static" style="display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: #64B0F2; color: #fff;">
                <h5 class="modal-title" id="exampleModalLabel">Editar curso</h5>
                <button type="button" class="close" onclick="limpiarModalEditRegister();" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="idEditRegistro_fk">
                <div class="row">
                    <div class="col-xl-12 form-group">
                        <label for="nombre">Nombre asignatura <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_nombre" autocomplete="off" onkeyup="validateRegisterSimple(this.id)">
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="display: flex; justify-content: space-between; padding: 8px 15px;">
                <button type="button" class="btn btn-secondary" onclick="limpiarModalEditRegister();" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnEditRegister"><i class="fa fa-check"></i> Confirmar</button>
            </div>
        </div>
    </div>
</div>
<!--################ ASIGNAR MATERIAS #################### -->
<div class="modal fade" id="asignRegister" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static" style="display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: #1f9148; color: #fff;">
                <h5 class="modal-title" id="exampleModalLabel">Agregar Asignaturas a Cursos</h5>
                <button type="button" class="close" onclick="limpiarModalAsignRegister();" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="id_asign_registro_fk">

                <div class="row">
                    <div class="col-xl-12 text-uppercase" style="font-weight: 700; font-size: 23px; color:#4b4b4b; border-bottom: solid 1px #efefef; text-align: center;" id="asign-text-register"></div>
                </div>
                <div class="mt-3" id="spinner_asignat" style="display: none;">
                    <div class="d-flex justify-content-center"><i class="fa fa-spinner fa-spin" style="font-size: 30px;"></i> </div>
                </div>

                <div class="mt-3" id="content_asig" style="display: none;">
                    <!-- inpt buscador -->
                    <div class="mb-3" style="padding: 0px 2px;">
                        <input type="text" id="searchAsignatura" class="form-control" placeholder="Buscar asignatura por nombre o código.....">
                    </div>
                    <!-- spinner buscador -->
                    <div id="spinsearch" style="display: none;">
                        <div class="d-flex justify-content-center"><i class="fa fa-circle-o-notch fa-spin" style="font-size: 22px; margin-bottom: 15px;"></i></div>
                    </div>
                    <!-- container asign -->
                    <div id="containerasign" style="display: none;">
                        <div class="row" style="padding: 0px 12px; overflow-y: auto; max-height: 350px;">
                            <table class="w-100" id="contenido_asignaturas"></table>
                        </div>
                    </div>
                </div>
                <div class="mt-3 text-center" id="content_asig_text" style="font-size: 20px;font-weight: 400;display: none;"></div>
            </div>
            <div class="modal-footer" style="display: flex; justify-content: space-between; padding: 8px 15px;">
                <button type="button" class="btn btn-secondary" onclick="limpiarModalAsignRegister();" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnAsignRegister"><i class="fa fa-check"></i> Confirmar</button>
            </div>
        </div>
    </div>
</div>

<!--################ DELETE REGISTER #################### -->
<div class="modal fade" id="deleteRegister" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static" style="display: none;">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: #eb877a; color: #fff;">
                <h5 class="modal-title text-dar" id="exampleModalLabel">Eliminar asignatura</h5>
                <button type="button" class="close" onclick="limpiarModalDeleteRegister();" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="id_delete_registro_fk">
                <div class="row">
                    <div class="col-xl-12 text-center">
                        <div><i class="fa fa-trash" style="color: #eb877a; font-size: 125px;"></i></div>
                        <div style="font-weight: 500; font-size: 15px; color: #eb877a;"><span id="delete-text-register"></span></div>
                        <span style="font-weight: 500; font-size: 15px;">¿Está seguro de eliminar este curso?</span>
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
<script src="{{asset("herramienta/academico/cursos.js?v=$ran")}}"></script>
@endsection
