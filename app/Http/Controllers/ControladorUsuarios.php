<?php

namespace App\Http\Controllers;

use App\User;
use App\Roles;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ControladorUsuarios extends Controller
{
    protected $date_actual;
    public function __construct()
    {
        $this->date_actual = date('Y-m-d H:i:s');
    }

    //VALIDACIONES
    protected function validateEmail($parametro)
    {
        if (filter_var($parametro, FILTER_VALIDATE_EMAIL) == false) {
            return 'error';
        } else {
            return 'success';
        }
    }
    protected function validateExistEmail($parametro)
    {
        return 'success';
        // $existencia_email = DB::table('usuario')->where('email', $parametro)->first();
        // if($existencia_email){ return 'error'; }else{ return 'success'; }
    }
    protected function validateCedula($parametro)
    {
        if (strlen($parametro) == 10) {
            return 'success';
        } else {
            return 'error';
        }
    }
    protected function validateExistCedula($parametro)
    {
        $existencia_cedula = DB::table('usuario')->where('cedula', $parametro)->where('eliminado', [0])->first();
        if ($existencia_cedula) {
            return 'error';
        } else {
            return 'success';
        }
    }
    protected function validatePasswordOld($parametro)
    {
        if (Hash::check($parametro, Auth::user()->password)) {
            return 'success';
        } else {
            return 'error';
        }
    }
    protected function validatePasswordNew($parametro)
    {
        if (strlen($parametro) >= 6) {
            if (Hash::check($parametro, Auth::user()->password)) {
                return 'error_existe';
            } else {
                return 'success';
            }
        } else {
            return 'error';
        }
    }

    //######################################################### NORMALES #############################################
    public function perfilUsuario()
    {
        return view('pages.usuarios.perfil.perfil');
    }
    public function perfilEditUsuario(Request $request)
    {
        $usuario = DB::table('usuario')->where('id', $request->id)->where('token', $request->token)->first();
        if ($usuario) {
            $validate_email = $this->validateEmail($request->correo);
            $validate_cedula = $this->validateCedula($request->cedula);
            if ($validate_email == "success" && $validate_cedula == "success") {

                $existencia_ced = DB::table('usuario')->where('cedula', $request->cedula)->where('id', '!=', $request->id)->first();
                $existencia_ema = DB::table('usuario')->where('email', $request->correo)->where('id', '!=', $request->id)->first();

                if ($existencia_ced == null && $existencia_ema == null) {
                    DB::beginTransaction();
                    $success = DB::table('usuario')->where('id', $usuario->id)->update([
                        'cedula' => $request->cedula,
                        'primer_nombre' => $request->primernombre,
                        'segundo_nombre' => $request->segundonombre,
                        'apellido_paterno' => $request->primerapellido,
                        'apellido_materno' => $request->segundoapellido,
                        'email' => $request->correo,
                        'creador_fk' => Auth::user()->id,
                        'updated_at' => $this->date_actual
                    ]);

                    if ($success) {
                        DB::commit();
                        return response()->json(['success' => 'Datos actualizados con éxito.']);
                    } else {
                        DB::rollBack();
                        return response()->json(['error' => 'Algo salió mal, vuelva a intentarlo.']);
                    }
                } else {
                    if ($existencia_ced && $existencia_ema) {
                        return response()->json(['error' => 'La cédula y el correo ingresados ya existen.']);
                    }
                    if ($existencia_ced) {
                        return response()->json(['error' => 'La cédula ingresada ya existen.']);
                    }
                    if ($existencia_ema) {
                        return response()->json(['error' => 'El correo ingresado ya existen.']);
                    }
                }
            } else {
                if ($validate_email == "error" && $validate_cedula == "error") {
                    return response()->json(['error' => 'Cédula y Correo no son validos.']);
                }
                if ($validate_email == "error") {
                    return response()->json(['error' => 'Correo no valido.']);
                }
                if ($validate_cedula == "error") {
                    return response()->json(['error' => 'Cédula no valida.']);
                }
            }
        } else {
            return response()->json(['error' => 'Algo salió mal, vuelva a intentarlo1.']);
        }
    }
    public function perfilChangePassword(Request $request)
    {
        $validateOld = $this->validatePasswordOld($request->old_pass);
        $validateNew = $this->validatePasswordNew($request->new_pass);

        if ($validateOld == "success" && $validateNew == "success") {
            DB::beginTransaction();
            $cambio = DB::table('usuario')->where('id', Auth::user()->id)->update([
                'password' => Hash::make($request->new_pass),
                'updated_at' => $this->date_actual
            ]);

            if ($cambio) {
                DB::commit();
                Auth::logout();
                return response()->json(['success' => 'Contraseña cambiada con éxito.']);
            } else {
                DB::rollback();
                return response()->json(['error' => 'Algo salió mal, vuelva a intentarlo.']);
            }
        } else {
            if ($validateOld == 'error') {
                return response()->json(['error' => 'La contraseña actual ingresada no es correcta.']);
            }
            if ($validateNew == 'error') {
                return response()->json(['error' => 'La nueva contraseña debe tener mínimo 6 carácteres.']);
            }
            if ($validateNew == 'error_existe') {
                return response()->json(['error' => 'La nueva contraseña debe ser diferente a la actual.']);
            }
        }
    }

    //================== USUARIOS
    public function viewUsuarios()
    {
        $role = DB::table('roles_has_permisos')->leftJoin('usuarios_has_roles', 'roles_has_permisos.rol_fk', 'usuarios_has_roles.rol_fk')->where('usuarios_has_roles.usuario_fk',  Auth::user()->id)->where('roles_has_permisos.permiso', 'viewGeneralUsuarios')->first();
        if ($role || (Auth::user()->tipo_usuario == 5)) {

            $usuarios = User::whereIn('estado', [1, 0])->where('eliminado', [0])->whereNotIn('tipo_usuario', [5])->orderBy('tipo_usuario')->orderBy('primer_nombre')->orderBy('segundo_nombre')->orderBy('apellido_paterno')->orderBy('apellido_materno')->paginate(5);
            $rank = $usuarios->firstItem();

            return view('pages.usuarios.usuarios.usuarios', compact('usuarios', 'rank'));
        } else {
            return redirect()->route('home');
        }
    }
    public function getUsuariosFetch()
    {
        $usuarios = User::whereIn('estado', [1, 0])
            ->where('tipo_usuario', [2])
            ->where('eliminado', [0])
            ->orderBy('primer_nombre')
            ->orderBy('segundo_nombre')
            ->orderBy('apellido_paterno')
            ->orderBy('apellido_materno')
            ->get();
        return response()->json($usuarios);
    }
    public function postCreateUsuarioFetch(Request $request)
    {
        $validate_email = $this->validateEmail($request->correo);
        $validate_cedula = $this->validateCedula($request->cedula);
        $validateExistEmail = $this->validateExistEmail($request->correo);
        $validateExistCedula = $this->validateExistCedula($request->cedula);

        if ($validate_email == "success" && $validate_cedula == "success") {
            if ($validateExistEmail == "success" && $validateExistCedula == "success") {
                $token = Str::random(20);
                DB::beginTransaction();

                $usuario = DB::table('usuario')->insert([
                    'cedula' => $request->cedula,
                    'primer_nombre' => $request->primernombre,
                    'segundo_nombre' => $request->segundonombre,
                    'apellido_paterno' => $request->primerapellido,
                    'apellido_materno' => $request->segundoapellido,
                    'token' => $token,
                    'tipo_usuario' => $request->tipo_usuario,
                    'email' => $request->correo,
                    'password' => Hash::make($request->cedula),
                    'created_at' => $this->date_actual,
                    'creador_fk' => Auth::user()->id
                ]);

                if ($usuario) {
                    DB::commit();
                    return response()->json(['success' => 'Usuario creado con éxito.']);
                } else {
                    DB::rollBack();
                    return response()->json(['error' => 'Algo salió mal, vuelva a intentarlo.']);
                }
            } else {
                if ($validateExistEmail == "error" && $validateExistCedula == "error") {
                    return response()->json(['error' => 'Ya existe un usuario con la cédula y correo ingresados.']);
                }
                if ($validateExistEmail == "error") {
                    return response()->json(['error' => 'Ya existe un usuario con el correo ingresados']);
                }
                if ($validateExistCedula == "error") {
                    return response()->json(['error' => 'Ya existe un usuario con  la cédula ingresada.']);
                }
            }
        } else {
            if ($validate_email == "error" && $validate_cedula == "error") {
                return response()->json(['error' => 'Cédula y Correo no son validos.']);
            }
            if ($validate_email == "error") {
                return response()->json(['error' => 'Correo no valido.']);
            }
            if ($validate_cedula == "error") {
                return response()->json(['error' => 'Cédula no valida.']);
            }
        }
    }
    public function postEditUsuarioFetch(Request $request)
    {
        $usuario = DB::table('usuario')->where('id', $request->id)->where('token', $request->token)->first();
        if ($usuario) {
            $validate_email = $this->validateEmail($request->correo);
            $validate_cedula = $this->validateCedula($request->cedula);
            if ($validate_email == "success" && $validate_cedula == "success") {

                $existencia_ced = DB::table('usuario')->where('cedula', $request->cedula)->where('id', '!=', $request->id)->first();
                // $existencia_ema = DB::table('usuario')->where('email', $request->correo)->where('id', '!=' ,$request->id)->first();

                if ($existencia_ced == null) {
                    DB::beginTransaction();
                    $success = DB::table('usuario')->where('id', $usuario->id)->update([
                        'cedula' => $request->cedula,
                        'primer_nombre' => $request->primernombre,
                        'segundo_nombre' => $request->segundonombre,
                        'apellido_paterno' => $request->primerapellido,
                        'apellido_materno' => $request->segundoapellido,
                        'tipo_usuario' => $request->tipo_usuario,
                        'email' => $request->correo,
                        'creador_fk' => Auth::user()->id,
                        'updated_at' => $this->date_actual
                    ]);

                    if ($success) {
                        DB::commit();
                        return response()->json(['success' => 'Usuario actualizado con éxito.']);
                    } else {
                        DB::rollBack();
                        return response()->json(['error' => 'Algo salió mal, vuelva a intentarlo.']);
                    }
                } else {
                    // if($existencia_ced && $existencia_ema){return response()->json(['error'=> 'La cédula y el correo ingresados ya existen.']); }
                    if ($existencia_ced) {
                        return response()->json(['error' => 'La cédula ingresada ya existen.']);
                    }
                    // if($existencia_ema){  return response()->json(['error'=> 'El correo ingresado ya existen.']); }
                }
            } else {
                if ($validate_email == "error" && $validate_cedula == "error") {
                    return response()->json(['error' => 'Cédula y Correo no son validos.']);
                }
                if ($validate_email == "error") {
                    return response()->json(['error' => 'Correo no valido.']);
                }
                if ($validate_cedula == "error") {
                    return response()->json(['error' => 'Cédula no valida.']);
                }
            }
        } else {
            return response()->json(['error' => 'Algo salió mal, vuelva a intentarlo1.']);
        }
    }
    public function postBanearUsuarioFetch(Request $request)
    {
        DB::beginTransaction();
        $success = DB::table('usuario')->where('id', $request->id)->update([
            'estado' => 0,
            'updated_at' => $this->date_actual
        ]);

        if ($success) {
            DB::commit();
            return response()->json(['success' => 'Usuario deshabilitado con éxito.']);
        } else {
            DB::rollback();
            return response()->json(['error' => 'Algo salió mal, vuelva a intentarlo.']);
        }
    }
    public function postHabilitarUsuarioFetch(Request $request)
    {
        DB::beginTransaction();
        $success = DB::table('usuario')->where('id', $request->id)->update([
            'estado' => 1,
            'updated_at' => $this->date_actual
        ]);

        if ($success) {
            DB::commit();
            return response()->json(['success' => 'Usuario habilitado con éxito.']);
        } else {
            DB::rollback();
            return response()->json(['error' => 'Algo salió mal, vuelva a intentarlo.']);
        }
    }
    public function postDeleteUsuarioFetch(Request $request)
    {
        DB::beginTransaction();
        $success = DB::table('usuario')->where('id', $request->id)->update([
            'eliminado' => 1,
            'updated_at' => $this->date_actual
        ]);

        if ($success) {
            DB::commit();
            return response()->json(['success' => 'Usuario eliminado con éxito.']);
        } else {
            DB::rollback();
            return response()->json(['error' => 'Algo salió mal, vuelva a intentarlo.']);
        }
    }
}
