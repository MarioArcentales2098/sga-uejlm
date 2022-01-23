@extends('layouts.herramienta.herramienta')
@section('title-herramienta', "Asignar roles y permisos: $rol->nombre")
@section('style-herramienta')
@endsection
@section('content-herramienta')
@csrf

<div class="row">
    <div class="col-xl-12">
        <div class="page-title-box">
            <h4 class="page-title float-left"><a href="{{ route('viewRoles') }}" class="btn btn-sm btn-primary"><i class="fa fa-arrow-left"></i> Regresar</a> Usuarios con el rol: {{ $rol->nombre }}</h4>
            <div class="float-right">
                <a href="javascript:" data-toggle="modal" data-target="#asignRegister" class="btn btn-success waves-effect waves-light btn-sm"><i class="fa fa-plus"></i> Agregar usuarios</a>
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
                            <th width="40%" class="text-left">Usuarios</th>
                            <th width="5%" class="text-center">Acción</th>
                        </tr>
                    </thead>
                    <tbody id="tbody_registros">
                        @foreach ($usuarios_has_roles as $key => $item)
                        <tr>
                            <td>{{$key + 1}}</td>
                            <td class="text-uppercase">{{$item->apellido_paterno}} {{$item->apellido_materno}} {{$item->primer_nombre}} {{$item->segundo_nombre}}</td>
                            <td class="text-center">
                                <form method="POST" action="{{ route('asignRolDeleteUsuarioPost') }}"> @csrf
                                    <input type="hidden" name="usuario_fk" value="{{ $item->id }}">
                                    <input type="hidden" name="rol_fk" value="{{ $rol->id }}">
                                    <button type="submit" class="btn btn-sm btn-danger" title="Eliminar" onclick="return confirm('Está seguro de eliminar este usuario?')"><i class="fa fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<!--################ MODAL ASIGNAR #################### -->
<form action="{{ route('asignUsuariosRolPost') }}" method="POST">@csrf
    <input type="hidden" name="rol_fk" value="{{ $rol->id }}">
    <div class="modal fade" id="asignRegister" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static" style="display: none;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background: #1f9148; color: #fff;">
                    <h5 class="modal-title" id="exampleModalLabel">Asignar usuarios a rol</h5>
                    <button type="button" class="close" onclick="limpiarModalAsignRegister();" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="idasign_fk">
                    <div class="row">
                        <div class="col-xl-12 mb-4">
                            <label>Nombre rol:</label>
                            <div class="text-uppercase" style="font-size: 17px; color: #004eff; font-weight: 500;"> {{ $rol->nombre }}</div>
                        </div>
                        <div class="col-xl-12">
                            <div class="form-group">
                                <label>Usuarios <span class="text-danger">*</span></label>
                                <select id="selec_usuario" multiple class="select-destin usuarios" name="usuarios[]" style="width: 100%;" required>
                                    @foreach ($usuarios as $item)
                                    <option value="{{ $item->id}}" @foreach ($usuarios_has_roles as $yala) {{$yala->id == $item->id ? 'disabled': ''}} @endforeach>{{ $item->apellido_paterno }} {{ $item->apellido_materno }} {{ $item->primer_nombre }} {{ $item->segundo_nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="display: flex; justify-content: space-between; padding: 8px 15px;">
                    <button type="button" class="btn btn-secondary" onclick="limpiarModalAsignRegister();" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="btnAsignRegister"><i class="fa fa-check"></i> Confirmar</button>
                </div>
            </div>
        </div>
    </div>
</form>

@endsection

@section('script-herramienta')
<script>
    $(document).ready(function() {
        $('.select-destin').select2();
    });

</script>
@endsection
