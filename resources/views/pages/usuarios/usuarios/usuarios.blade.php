@extends('layouts.herramienta.herramienta')
@section('title-herramienta', 'Usuarios')
@section('style-herramienta')

@endsection
@section('content-herramienta')
@csrf

<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box">
            <h4 class="page-title float-left">Usuarios</h4>
            <div class="float-right">
                @can('viewGeneralUsuariosCreate')
                <a href="javascript:" data-toggle="modal" data-target="#createRegister" class="btn btn-success waves-effect waves-light btn-sm"><i class="fa fa-user-plus"></i> Crear usuario</a>
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
                <table class="tablesaw table tablesaw-stack" id="mytable">
                    <thead class="thead_dar">
                        <tr>
                            <th width="5%" class="text-left">N°</th>
                            <th width="10%" class="text-left">Cédula</th>
                            <th width="23%" class="text-left">Nombres</th>
                            <th width="23%" class="text-left">Apellidos</th>
                            <th width="20%" class="text-left">Correo</th>
                            <th width="5%" class="text-center">Tipo</th>
                            <th width="14%" class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tbody_registros">
                        @foreach ($usuarios as $key => $item)
                        <tr>
                            {{-- <td>{{$key + 1}}</td> --}}
                            <td>{{ $rank++ }}</td>
                            <td>{{$item->cedula}}</td>
                            <td>{{$item->primer_nombre}} {{$item->segundo_nombre}}</td>
                            <td>{{$item->apellido_paterno}} {{$item->apellido_materno}}</td>
                            <td>{{$item->email}}</td>
                            <td class="text-center">
                                @if ($item->tipo_usuario == 1)<span class="badge badge-info">Usuario</span>@endif
                                @if ($item->tipo_usuario == 2)<span class="badge badge-primary">Estudiante</span>@endif
                                @if ($item->tipo_usuario == 3)<span class="badge badge-success">Docente</span>@endif
                                @if ($item->tipo_usuario == 4)<span class="badge badge-warning">Secretaria</span>@endif
                                @if ($item->tipo_usuario == 5)<span class="badge badge-warning">Administrador</span>@endif
                            </td>
                            <td class="text-center">
                                @can('viewGeneralUsuariosEdit')
                                <!-- Editar -->
                                <button type="button" class="btn btn-sm btn-primary" title="Editar usuario" data-toggle="modal" data-target="#editRegister" data-id="{{ $item->id }}" data-token="{{ $item->token }}" data-pnombre="{{ $item->primer_nombre }}" data-snombre="{{ $item->segundo_nombre }}" data-papellido="{{ $item->apellido_paterno }}" data-sapellido="{{ $item->apellido_materno }}" data-cedula="{{ $item->cedula }}" data-email="{{ $item->email }}" data-tipo="{{ $item->tipo_usuario }}"><i class="fa fa-pencil"></i></button>
                                @endcan
                                @can('viewGeneralUsuariosDelete')
                                <!-- Eliminar -->
                                <button type="button" class="btn btn-sm btn-danger" title="Eliminar usuario" data-toggle="modal" data-target="#deleteRegister" data-id="{{ $item->id }}"><i class="fa fa-trash"></i></button>
                                @endcan
                                @can('viewGeneralUsuariosBan')
                                @if ($item->estado == 1)
                                <!-- Banear -->
                                <button type="button" class="btn btn-sm btn-warning" title="Bloquear usuario" data-toggle="modal" data-target="#banearRegister" data-id="{{ $item->id }}"><i class="fa fa-ban"></i></button>
                                @endif
                                @if ($item->estado == 0)
                                <!-- Activar -->
                                <button type="button" class="btn btn-sm btn-success" title="Activar usuario" data-toggle="modal" data-target="#activeRegister" data-id="{{ $item->id }}"><i class="fa fa-undo"></i></button>
                                @endif
                                @endcan
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $usuarios->links() }}
            </div>
        </div>

    </div>
</div>

<!--################ MODAL CREATE #################### -->
<div class="modal fade" id="createRegister" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static" style="display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: #1f9148; color: #fff;">
                <h5 class="modal-title" id="exampleModalLabel">Crear usuario</h5>
                <button type="button" class="close" onclick="limpiarModalRegister();" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xl-6 form-group">
                        <label for="primernombre">Primer nombre <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="primernombre" autocomplete="off" onkeyup="validateRegisterSimple(this.id)">
                    </div>
                    <div class="col-xl-6 form-group">
                        <label for="segundonombre">Segundo nombre</label>
                        <input type="text" class="form-control" id="segundonombre" autocomplete="off">
                    </div>
                    <div class="col-xl-6 form-group">
                        <label for="primerapellido">Apellido paterno <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="primerapellido" autocomplete="off" onkeyup="validateRegisterSimple(this.id)">
                    </div>
                    <div class="col-xl-6 form-group">
                        <label for="segundoapellido">Apellido materno</label>
                        <input type="text" class="form-control" id="segundoapellido" autocomplete="off">
                    </div>
                    <div class="col-xl-6 form-group">
                        <label for="cedula">Cédula <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="cedula" autocomplete="off" onkeyup="validateRegisterSimple(this.id)">
                    </div>
                    <div class="col-xl-6 form-group">
                        <label for="correo">Correo <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="correo" autocomplete="off" onkeyup="validateRegisterSimple(this.id)">
                    </div>
                    <div class="col-xl-12 form-group">
                        <label for="correo">Tipo usuario <span class="text-danger">*</span></label>
                        <select id="tipo_usuario" class="form-control" onchange="validateRegisterSimple(this.id)">
                            <option value="">--</option>
                            <option value="1">Usuario</option>
                            <option value="2">Estudiante</option>
                            <option value="3">Docente</option>
                            <option value="4">Secretaria</option>
                            <option value="5">Administrador</option>
                        </select>
                    </div>
                    <div class="col-xl-12">
                        <div class="alert alert-info" style="margin-bottom:0px; padding: 5px 1.25rem; color:#37798b;">
                            <small class="text-mute"><i class="fa fa-exclamation-circle"></i> La contraseña será la cédula, podrá modificarla en el perfil de usuario.</small>
                        </div>
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
            <div class="modal-header" style="background: #1f9148; color: #fff;">
                <h5 class="modal-title" id="exampleModalLabel">Editar usuario</h5>
                <button type="button" class="close" onclick="limpiarModalEditRegister();" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="idEditRegistro_fk">
                <input type="hidden" id="edit_token">
                <div class="row">
                    <div class="col-xl-6 form-group">
                        <label for="edit_primernombre">Primer nombre <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_primernombre" autocomplete="off" onkeyup="validateRegisterSimple(this.id)">
                    </div>
                    <div class="col-xl-6 form-group">
                        <label for="edit_segundonombre">Segundo nombre</label>
                        <input type="text" class="form-control" id="edit_segundonombre" autocomplete="off">
                    </div>
                    <div class="col-xl-6 form-group">
                        <label for="edit_primerapellido">Apellido paterno <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_primerapellido" autocomplete="off" onkeyup="validateRegisterSimple(this.id)">
                    </div>
                    <div class="col-xl-6 form-group">
                        <label for="edit_segundoapellido">Apellido materno</label>
                        <input type="text" class="form-control" id="edit_segundoapellido" autocomplete="off">
                    </div>
                    <div class="col-xl-6 form-group">
                        <label for="edit_cedula">Cédula <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="edit_cedula" autocomplete="off" onkeyup="validateRegisterSimple(this.id)">
                    </div>
                    <div class="col-xl-6 form-group">
                        <label for="edit_correo">Correo <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="edit_correo" autocomplete="off" onkeyup="validateRegisterSimple(this.id)">
                    </div>
                    <div class="col-xl-12 form-group">
                        <label for="correo">Tipo usuario <span class="text-danger">*</span></label>
                        <select id="edit_tipo_usuario" class="form-control" onchange="validateRegisterSimple(this.id)">
                            <option value="">--</option>
                            <option value="1">Usuario</option>
                            <option value="2">Estudiante</option>
                            <option value="3">Docente</option>
                            <option value="4">Secretaria</option>
                            <option value="5">Administrador</option>
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
<!--################ BAN USUARIO #################### -->
<div class="modal fade" id="banearRegister" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static" style="display: none;">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: #f1b53d; color: #fff;">
                <h5 class="modal-title text-dark" id="exampleModalLabel">Deshabilitar usuario</h5>
                <button type="button" class="close" onclick="limpiarModalBanRegister();" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="id_ban_registro_fk">
                <div class="row">
                    <div class="col-xl-12 text-center">
                        <div><i class="fa fa-ban" style="color: #f1b53d; font-size: 125px;"></i></div>
                        <span style="font-weight: 500; font-size: 18px;">¿Está seguro de esta acción?</span>
                    </div>
                </div>
            </div>
            <div style="display: flex; justify-content: space-between; padding: 8px 15px; border-bottom-right-radius: 0.3rem; border-bottom-left-radius: 0.3rem;">
                <button type="button" class="btn btn-secondary" onclick="limpiarModalBanRegister();" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnBanRegister"><i class="fa fa-check"></i> Confirmar</button>
            </div>
        </div>
    </div>
</div>
<!--################ ACTIVAR USUARIO #################### -->
<div class="modal fade" id="activeRegister" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static" style="display: none;">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: #1f9148; color: #fff;">
                <h5 class="modal-title" id="exampleModalLabel">Activar usuario</h5>
                <button type="button" class="close" onclick="limpiarModalActiveRegister();" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="id_act_registro_fk">
                <div class="row">
                    <div class="col-xl-12 text-center">
                        <div><i class="fa fa-check-circle" style="color: #1f9148; font-size: 125px;"></i></div>
                        <span style="font-weight: 500; font-size: 18px;">¿Está seguro de esta acción?</span>
                    </div>
                </div>
            </div>
            <div style="display: flex; justify-content: space-between; padding: 8px 15px; border-bottom-right-radius: 0.3rem; border-bottom-left-radius: 0.3rem;">
                <button type="button" class="btn btn-secondary" onclick="limpiarModalActiveRegister();" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnActiveRegister"><i class="fa fa-check"></i> Confirmar</button>
            </div>
        </div>
    </div>
</div>
<!--################ DELETE REGISTER #################### -->
<div class="modal fade" id="deleteRegister" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static" style="display: none;">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: #eb877a; color: #fff;">
                <h5 class="modal-title text-dar" id="exampleModalLabel">Eliminar usuario</h5>
                <button type="button" class="close" onclick="limpiarModalDeleteRegister();" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="id_delete_registro_fk">
                <div class="row">
                    <div class="col-xl-12 text-center">
                        <div><i class="fa fa-trash" style="color: #eb877a; font-size: 125px;"></i></div>
                        <span style="font-weight: 500; font-size: 15px;">¿Está seguro de eliminar este usuario?</span>
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
<script src="{{asset("herramienta/usuarios/usuarios.js?v=$ran")}}"></script>
@endsection
