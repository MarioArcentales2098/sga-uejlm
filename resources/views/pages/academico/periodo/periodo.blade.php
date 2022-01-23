@extends('layouts.herramienta.herramienta')
@section('title-herramienta', 'Periodos académico')
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
            <h4 class="page-title float-left">Periodos académico</h4>
            <div class="float-right">
                @can('viewGeneralPeriodoAcademicoCreate')
                <a href="javascript:" data-toggle="modal" data-target="#createRegister" class="btn btn-success waves-effect waves-light btn-sm" onclick="condition();"><i class="fa fa-plus"></i> Crear periodo</a>
                @endcan
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card-box">
            <div class="table-responsive" id="contenido_datos">
                <input type="hidden" id="condicionestado" value="{{ $activo }}">
                <table class="tablesaw table tablesaw-stack" id="mytable">
                    <thead class="thead_dar">
                        <tr>
                            <th width="5%" class="text-left">N°</th>
                            <th width="15%" class="text-left">Descripción</th>
                            <th width="15%" class="text-left">Estado periodo</th>
                            @can('viewGeneralPeriodoAcademicoParciales')
                            <th width="23%" class="text-left">Quimestre 1</th>
                            <th width="23%" class="text-left">Quimestre 2</th>
                            @endcan
                            <th width="15%" class="text-left">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tbody_registros">
                        @foreach ($periodos as $key => $item)
                        <tr>
                            <td>{{ $rank ++}}</td>
                            <td>{{ $item->nombre }}</td>
                            <td class="text-left">
                                @if ($item->estado == 1)<span class="badge badge-success">Activo</span> @endif
                                @if ($item->estado == 0) <span class="badge badge-warning">Inactivo</span> @endif
                            </td>
                            @can('viewGeneralPeriodoAcademicoParciales')
                            <td>
                                Parcial 1 @foreach ($qum1_parcial1 as $par)@if ($par->periodo_fk == $item->id) <span class="badge {{$par->activo == 1 ? "badge-success" : "badge-secondary"}}" @if ($item->estado == 1 ) data-toggle="modal" data-target="{{$par->activo == 1 ? '#modalCerrarParcial' : '#modalAbrirParcial' }}" data-ident="{{$item->id}}-{{$par->id}}-{{$par->quimestre_fk}}" style="cursor:pointer" @endif> <i class="fa {{$par->activo == 1 ? 'fa-unlock' : 'fa-lock'}}" title="{{$par->activo == 1 ?  "Abierto" : "Cerrado"}}"></i></span> @endif @endforeach<br>
                                Parcial 2 @foreach ($qum1_parcial2 as $par)@if ($par->periodo_fk == $item->id) <span class="badge {{$par->activo == 1 ? "badge-success" : "badge-secondary"}}" @if ($item->estado == 1 ) data-toggle="modal" data-target="{{$par->activo == 1 ? '#modalCerrarParcial' : '#modalAbrirParcial' }}" data-ident="{{$item->id}}-{{$par->id}}-{{$par->quimestre_fk}}" style="cursor:pointer" @endif> <i class="fa {{$par->activo == 1 ? 'fa-unlock' : 'fa-lock'}}" title="{{$par->activo == 1 ?  "Abierto" : "Cerrado"}}"></i></span> @endif @endforeach
                            </td>
                            <td>
                                Parcial 1 @foreach ($qum2_parcial1 as $par)@if ($par->periodo_fk == $item->id) <span class="badge {{$par->activo == 1 ? "badge-success" : "badge-secondary"}}" @if ($item->estado == 1 ) data-toggle="modal" data-target="{{$par->activo == 1 ? '#modalCerrarParcial' : '#modalAbrirParcial' }}" data-ident="{{$item->id}}-{{$par->id}}-{{$par->quimestre_fk}}" style="cursor:pointer" @endif> <i class="fa {{$par->activo == 1 ? 'fa-unlock' : 'fa-lock'}}" title="{{$par->activo == 1 ?  "Abierto" : "Cerrado"}}"></i></span> @endif @endforeach<br>
                                Parcial 2 @foreach ($qum2_parcial2 as $par)@if ($par->periodo_fk == $item->id) <span class="badge {{$par->activo == 1 ? "badge-success" : "badge-secondary"}}" @if ($item->estado == 1 ) data-toggle="modal" data-target="{{$par->activo == 1 ? '#modalCerrarParcial' : '#modalAbrirParcial' }}" data-ident="{{$item->id}}-{{$par->id}}-{{$par->quimestre_fk}}" style="cursor:pointer" @endif> <i class="fa {{$par->activo == 1 ? 'fa-unlock' : 'fa-lock'}}" title="{{$par->activo == 1 ?  "Abierto" : "Cerrado"}}"></i></span> @endif @endforeach
                            </td>
                            @endcan
                            <td class="text-left">
                                @can('viewGeneralPeriodoAcademicoEdit')
                                <a href="javascript:" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#editRegister" data-id="{{$item->id}}" data-inicio="{{$item->anio_inicio}}" data-fin="{{$item->anio_fin}}" data-estado="{{$item->estado}}" title="Editar registro"><i class="fa fa-pencil"></i></a>
                                @endcan
                                @can('viewGeneralPeriodoAcademicoDelete')
                                <a href="javascript:" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteRegister" data-id="{{$item->id}}" data-dele_nombre="{{$item->nombre}}" title="Eliminar registro"><i class="fa fa-trash"></i></a>
                                @endcan
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $periodos->links() }}
            </div>
        </div>

    </div>
</div>

<!--################ MODAL CREATE #################### -->
<div class="modal fade" id="createRegister" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static" style="display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: #1f9148; color: #fff;">
                <h5 class="modal-title" id="exampleModalLabel">Crear periodo</h5>
                <button type="button" class="close" onclick="limpiarModalRegister();" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xl-12 form-group">
                        <label>Año lectivo</label>
                        @php $anioactual = date('Y'); @endphp
                        <div class="input-group">
                            <select id="anio_inicio" class="form-control" onchange="validateRegisterSimple(this.id)">
                                <option value="">--</option>
                                @for ($i = 2020; $i < 2050; $i++) <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                            </select>
                            <div class="input-group-prepend"><span class="input-group-text">-</span></div>
                            <select id="anio_fin" class="form-control" onchange="validateRegisterSimple(this.id)">
                                <option value="">--</option>
                                @for ($i = 2020; $i < 2050; $i++) <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                            </select>
                        </div>
                    </div>
                    <div class="col-xl-12 form-group">
                        <label>Estado</label>
                        <select id="estado" class="form-control" onchange="validateRegisterSimple(this.id)">
                            <option value="">Seleccionar</option>
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
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
                <h5 class="modal-title" id="exampleModalLabel">Editar periodo</h5>
                <button type="button" class="close" onclick="limpiarModalEditRegister();" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="idEditRegistro_fk">
                <div class="row">
                    <div class="col-xl-12 form-group">
                        {{-- @php 
                        $anioactualresp = date('Y'); 
                        $anioactualedit = date('Y', strtotime($anioactualresp."-1 year"));
                        
                        @endphp
                        <label>Año lectivo</label>
                        <div class="input-group">
                            <select id="edit_anio_inicio" class="form-control" onchange="validateRegisterSimple(this.id)">
                                <option value="">--</option>
                                @for ($i = 0; $i < 10; $i++) 
                                    <option value="{{ date('Y', strtotime($anioactualedit."+ ".$i." year")) }}">{{ date('Y', strtotime($anioactualedit."+ ".$i." year")) }}</option>
                        @endfor
                        </select>
                        <div class="input-group-prepend"><span class="input-group-text">-</span></div>
                        <select id="edit_anio_fin" class="form-control" onchange="validateRegisterSimple(this.id)">
                            <option value="">--</option>
                            @for ($i = 0; $i <= 10; $i++) <option value="{{ date('Y', strtotime($anioactualedit."+ ".$i." year")) }}">{{ date('Y', strtotime($anioactualedit."+ ".$i." year")) }}</option>
                                @endfor
                        </select>
                    </div> --}}
                    <label>Año lectivo</label>
                    <div class="input-group">
                        <select id="edit_anio_inicio" class="form-control" onchange="validateRegisterSimple(this.id)">
                            <option value="">--</option>
                            @for ($i = 2020; $i < 2050; $i++) <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                        </select>
                        <div class="input-group-prepend"><span class="input-group-text">-</span></div>
                        <select id="edit_anio_fin" class="form-control" onchange="validateRegisterSimple(this.id)">
                            <option value="">--</option>
                            @for ($i = 2020; $i < 2050; $i++) <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                        </select>
                    </div>
                </div>
                <div class="col-xl-12 form-group">
                    <label>Estado</label>
                    <select id="edit_estado" class="form-control" onchange="validateRegisterSimple(this.id)">
                        <option value="">Seleccionar</option>
                        <option value="1">Activo</option>
                        <option value="0">Inactivo</option>
                    </select>
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
                <h5 class="modal-title text-dar" id="exampleModalLabel">Eliminar periodo</h5>
                <button type="button" class="close" onclick="limpiarModalDeleteRegister();" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body" style="padding: 1rem 1rem 6px;">
                <input type="hidden" id="id_delete_registro_fk">
                <div class="row">
                    <div class="col-xl-12 text-center">
                        <div><i class="fa fa-trash" style="color: #eb877a; font-size: 125px;"></i></div>
                        <div style="font-weight: 500; font-size: 15px; color: #eb877a;"><span id="delete-text-register"></span></div>
                        <span style="font-weight: 500; font-size: 15px;">¿Está seguro de eliminar el periodo?</span>

                    </div>
                    <div class="col-xl-12 text-center mt-4">
                        <div style="background-color: #fbebcc;border-color: #f9e1af;border-radius: 5px;color: #c38e24;
                        padding:5px 6px;line-height:10px;font-size:13px;text-align:justify;">
                            <small><i class="fa fa-exclamation-triangle"></i> Si elimina podrá seguir visualizando la información, pero no se podrá agregar mas contenido.</small>
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
<!--################ CERRAR PARCIAL #################### -->
<div class="modal fade" id="modalCerrarParcial" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static" style="display: none;">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: #ffc107; color: #fff; border-bottom: none;">
                <h5 class="modal-title text-dar" id="exampleModalLabel">Cerrar parcial</h5>
                <button type="button" class="close" onclick="limpiarModalDeleteRegister();" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body" style="padding: 1rem 1rem 6px;">
                <input type="hidden" id="idparcial">
                <input type="hidden" id="idquimestre">                
                <input type="hidden" id="idperiodo">
                <div class="row">
                    <div class="col-xl-12 text-center">
                        <div><i class="fa fa-lock" style="color: #ffc107; font-size: 125px;"></i></div>
                        <div style="font-weight: 500; font-size: 15px; color: #ffc107;"><span id="delete-text-register"></span></div>
                        <span style="font-weight: 500; font-size: 15px;">¿Está seguro de cerrar el parcial?</span>
                    </div>
                </div>
            </div>
            <div style="display: flex; justify-content: space-between; padding: 8px 15px; border-bottom-right-radius: 0.3rem; border-bottom-left-radius: 0.3rem;">
                <button type="button" class="btn btn-sm btn-success w-100" id="btnCerrarParcial"><i class="fa fa-check"></i> Confirmar</button>
            </div>
        </div>
    </div>
</div>
<!--################ ABRIR PARCIAL #################### -->
<div class="modal fade" id="modalAbrirParcial" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static" style="display: none;">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: #6f42c1; color: #fff; border-bottom: none;">
                <h5 class="modal-title text-dar" id="exampleModalLabel">Abrir parcial</h5>
                <button type="button" class="close" onclick="limpiarModalDeleteRegister();" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body" style="padding: 1rem 1rem 6px;">
                <input type="hidden" id="idparcial_a">
                <input type="hidden" id="idquimestre_a"> 
                <input type="hidden" id="idperiodo_a">
                <div class="row">
                    <div class="col-xl-12 text-center">
                        <div><i class="fa fa-unlock-alt" style="color: #6f42c1; font-size: 125px;"></i></div>
                        <div style="font-weight: 500; font-size: 15px; color: #6f42c1;"><span id="delete-text-register"></span></div>
                        <span style="font-weight: 500; font-size: 15px;">¿Está seguro de abrir el parcial?</span>
                    </div>
                </div>
            </div>
            <div style="display: flex; justify-content: space-between; padding: 8px 15px; border-bottom-right-radius: 0.3rem; border-bottom-left-radius: 0.3rem;">
                <button type="button" class="btn btn-sm btn-success w-100" id="btnAbrirParcial"><i class="fa fa-check"></i> Confirmar</button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script-herramienta')
@php $ran = rand(); @endphp
{{-- <script>new simpleDatatables.DataTable("#myTable");</script> --}}
<script src="{{asset("herramienta/academico/periodo.js?v=$ran")}}"></script>
@endsection
