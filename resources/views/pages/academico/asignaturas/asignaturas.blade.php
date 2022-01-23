@extends('layouts.herramienta.herramienta')
@section('title-herramienta', 'Asignaturas')
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


    .ckedelte {
        filter: hue-rotate(135deg);
        margin-left: 3px;
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
        max-width: 347px;
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
            <h4 class="page-title float-left">Asignaturas</h4>
            <div class="float-right">
                @can('viewGeneralAsignaturaCreate')
                <a href="javascript:" data-toggle="modal" data-target="#createRegister" class="btn btn-success waves-effect waves-light btn-sm"><i class="fa fa-plus"></i> Crear asignatura</a>
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
                    <form action="{{ route('viewAsignaturas') }}" method="GET" id="formSearch"><input type="text" class="form-control" name="texto" value="{{$texto}}" placeholder="Buscador...." autocomplete="off"></form>
                </div>
                <div style="margin-left:10px; width: 100px">
                    <button type="submit" class="btn btn-primary w-100" form="formSearch"><i class="fa fa-search"></i></button>
                </div>
            </div>
            <div class="table-responsive" id="contenido_datos">
                <input type="hidden" value="{{System::countAsingatura()}}" id="countasig">
                <table class="tablesaw table tablesaw-stack">
                    <thead>
                        <tr>
                            <th width="10%" class="text-left">N°</th>
                            <th width="18%" class="text-left">Código asignatura</th>
                            <th width="60%" class="text-left">Nombre</th>
                            <th width="12%" class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tbody_registros">
                        @foreach ($asignaturas as $item)
                        <tr>
                            <td>{{ $rank++ }}</td>
                            <td>{{$item->codigo_asignatura}}{{$item->codigo_asignatura_num}}</td>
                            <td>{{$item->nombre}}</td>
                            <td class="text-center">
                                @can('viewGeneralAsignaturaEdit')
                                <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#editRegister" data-id="{{$item->id}}" data-nombre="{{$item->nombre}}" data-countasig="{{$item->codigo_asignatura_num}}" title="Editar registro"><i class="fa fa-pencil"></i></button>
                                @endcan
                                @can('viewGeneralAsignaturaDelete')
                                <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteRegister" data-id="{{$item->id}}" data-edit_nombre="{{$item->nombre}}" title="Eliminar registro"><i class="fa fa-trash"></i></button>
                                @endcan
                                @can('viewGeneralAsignaturaAsignDoce')
                                <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#asignRegister" data-id="{{$item->id}}" data-nombre="{{$item->nombre}}" title="Agregar docentes a la asignatura"><i class="fa fa-user-circle"></i></button>
                                @endcan
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                {{ $asignaturas->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>

<!--################ MODAL CREATE #################### -->
<div class="modal fade" id="createRegister" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static" style="display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: #1f9148; color: #fff;">
                <h5 class="modal-title" id="exampleModalLabel">Crear asignatura</h5>
                <button type="button" class="close" onclick="limpiarModalRegister();" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xl-12 form-group">
                        <label>Nombre asignatura <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nombre" autocomplete="off" onkeyup="validateRegisterSimple(this.id); generateFirstLetter(this.id , 'codigo_asignatura');">
                    </div>
                    <div class="col-xl-12 form-group">
                        <label>Código asignatura <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="codigo_asignatura" autocomplete="off" readonly>
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
                <h5 class="modal-title" id="exampleModalLabel">Editar asignatura</h5>
                <button type="button" class="close" onclick="limpiarModalEditRegister();" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="idEditRegistro_fk">
                <input type="hidden" id="edit_countasig">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="form-group">
                            <label>Nombre asignatura <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_nombre" autocomplete="off" onkeyup="validateRegisterSimple(this.id); generateFirstLetter(this.id , 'edit_codigo_asignatura');">
                        </div>
                        <div class="form-group">
                            <label>Código asignatura <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_codigo_asignatura" autocomplete="off" readonly>
                        </div>
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
                        <span style="font-weight: 500; font-size: 15px;">¿Está seguro de eliminar la asignatura?</span>
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
<!--################ MODAL ASIGN #################### -->
<div class="modal fade" id="asignRegister" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static" style="display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: #1f9148; color: #fff;">
                <h5 class="modal-title" id="exampleModalLabel">Docentes y asignaturas</h5>
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
                        <input type="text" id="searchDocentes" class="form-control" placeholder="Buscar docente por nombres o cédula.....">
                    </div>
                    <!-- spinner buscador -->
                    <div id="spinsearch" style="display: none;">
                        <div class="d-flex justify-content-center"><i class="fa fa-circle-o-notch fa-spin" style="font-size: 22px; margin-bottom: 15px;"></i></div>
                    </div>
                    <!-- container asign -->
                    <div id="containerasign" style="display: none;">
                        <div class="row" style="padding: 0px 12px; overflow-y: auto; max-height: 350px;" id="contenido_docentes2">
                            <table class="w-100" id="contenido_docentes"></table>
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
@endsection

@section('script-herramienta')
@php $ran = rand(); @endphp
<script src="{{asset("herramienta/academico/asignaturas.js?v=$ran")}}"></script>
@endsection
