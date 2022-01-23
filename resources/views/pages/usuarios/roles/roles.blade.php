@extends('layouts.herramienta.herramienta')
@section('title-herramienta', 'Roles y Permisos')
@section('style-herramienta')
<style>
    .thead_dark {
        background: #2b3d51;
        color: #fff;
    }

    .thead_dark tr th {
        border: solid 1px #435467;
    }

</style>
@endsection

@section('content-herramienta')
@csrf

<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box">
            <h4 class="page-title float-left">Roles y permisos</h4>
            <div class="float-right">
                @can('viewGeneralUsuariosRolesCreate')
                <a href="javascript:" data-toggle="modal" data-target="#createRegister" class="btn btn-success waves-effect waves-light btn-sm"><i class="fa fa-plus"></i> Crear nuevo rol</a>
                @endcan
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card-box">
            <div class="table-responsive">
                <table class="tablesaw table tablesaw-stack" id="mytable">
                    <thead class="thead_dar">
                        <tr>
                            <th width="5%" class="text-left">N°</th>
                            <th width="40%" class="text-left">Rol</th>
                            <th width="5%" class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tbody_registros">
                        @foreach ($registros as $key => $item)
                        <tr>
                            <td>{{$key + 1}}</td>
                            <td>{{$item->rol_nombre}}</td>
                            <td class="text-center">
                                @can('viewGeneralUsuariosRolesEdit')
                                <!-- EDITAR -->
                                <button type="button" class="btn btn-sm btn-primary" title="Editar" data-toggle="modal" data-target="#editRegister" onclick="consultaRolesEdit({{$item->rol_id}}, '{{ $item->rol_nombre }}');"><i class="fa fa-pencil"></i></button>
                                @endcan
                                @can('viewGeneralUsuariosRolesDelete')
                                <!-- ELIMINAR -->
                                <button type="button" class="btn btn-sm btn-danger" title="Eliminar" data-toggle="modal" data-target="#deleteRegister" data-id="{{ $item->rol_id }}"><i class="fa fa-trash"></i></button>
                                @endcan
                                @can('viewGeneralUsuariosRolesAsign')
                                <!-- ASIGNAR ROL -->
                                <a href="{{route('asignRolEdit', ['id'=>$item->rol_id , 'slug' => $item->rol_slug]) }}" class="btn btn-sm btn-warning" title="asignar roles a usuarios"><i class="fa fa-key"></i></a>
                                @endcan
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!--################ MODAL CREATE #################### -->
<div class="modal fade" id="createRegister" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static" style="display: none;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: #1f9148; color: #fff;">
                <h5 class="modal-title" id="exampleModalLabel">Crear nuevo rol</h5>
                <button type="button" class="close" onclick="limpiarModalRegister();" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="form-group">
                            <label>Nombre rol<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nombre" autocomplete="off" onkeyup="validateRegisterSimple(this.id)">
                        </div>
                    </div>
                    <div class="col-xl-12">
                        <label>Permisos</label>
                        <div class="row" id="container_roles" style="padding: 4px 12px;"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="display: flex; justify-content: space-between; padding: 8px 15px;">
                <button type="button" class="btn btn-secondary" onclick="limpiarModalRegister();" data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</button>
                <button type="button" class="btn btn-primary ml-auto" id="btnCreateRegister"><i class="fa fa-check"></i> Confirmar</button>
            </div>
        </div>
    </div>
</div>
<!--################ MODAL EDIT #################### -->
<div class="modal fade" id="editRegister" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static" style="display: none;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: #1f9148; color: #fff;">
                <h5 class="modal-title" id="exampleModalLabel">Editar rol</h5>
                <button type="button" class="close" onclick="limpiarModalEditRegister();" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="idEditRegistro_fk">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="form-group">
                            <label>Nombre <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_nombre" autocomplete="off" onkeyup="validateRegisterSimple(this.id)">
                        </div>
                    </div>
                    <div class="col-xl-12">
                        <label>Permisos</label>
                        <div class="row" id="edit_container_roles" style="padding: 4px 12px;"></div>
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
<!--################ MODAL DELETE REGISTER #################### -->
<div class="modal fade" id="deleteRegister" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static" style="display: none;">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: #eb877a; color: #fff;">
                <h5 class="modal-title text-dar" id="exampleModalLabel">Eliminar rol</h5>
                <button type="button" class="close" onclick="limpiarModalDeleteRegister();" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="id_delete_registro_fk">
                <div class="row">
                    <div class="col-xl-12 text-center">
                        <div><i class="fa fa-trash" style="color: #eb877a; font-size: 125px;"></i></div>
                        <span style="font-weight: 500; font-size: 15px;">¿Está seguro de eliminar este rol?</span>
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
<script src="{{asset("herramienta/usuarios/roles.js?v=$ran")}}"></script>
<script>
    $(document).ready(function() {
        $('.select-destin').select2();
    });

</script>
@endsection
