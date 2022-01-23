<?php

namespace App\Http\Controllers;

use App\Roles;
use App\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ControladorRoles extends Controller
{
    protected $date_actual;
    public function __construct(){
        $this->date_actual = date('Y-m-d H:i:s');
    }

    // $roles = Roles::roles();
    public function viewRoles(){
        $role = DB::table('roles_has_permisos')->leftJoin('usuarios_has_roles', 'roles_has_permisos.rol_fk' , 'usuarios_has_roles.rol_fk')->where('usuarios_has_roles.usuario_fk',  Auth::user()->id)->where('roles_has_permisos.permiso', 'viewGeneralUsuariosRoles')->first();
        if($role || (Auth::user()->tipo_usuario == 5)){

            $registros = DB::table('roles')
                ->select(
                    'roles.id as rol_id',
                    'roles.nombre as rol_nombre',
                    'roles.slug as rol_slug',
                    'roles.estado as rol_estado',
                    'roles.created_at as rol_created_at',
                    'roles.creador_fk as rol_creador_fk'
                )
                ->where('eliminado', [0])
            ->get();
            $usuarios = User::whereNotIn('tipo_usuario', [5])->where('eliminado', [0])->get();
            return view('pages.usuarios.roles.roles', compact('registros','usuarios'));
        }else{
            return redirect()->route('home');
        }
    }
    public function loadRegistros(){
        $roles = DB::table('roles')
            ->select(
                'roles.id as rol_id',
                'roles.nombre as rol_nombre',
                'roles.slug as rol_slug',
                'roles.estado as rol_estado',
                'roles.created_at as rol_created_at',
                'roles.creador_fk as rol_creador_fk'
            )
            ->where('eliminado', [0])
        ->get();

        return response()->json($roles);
    }

    public function loadRoles(){
        $roles = Roles::roles();
        return response()->json($roles);
    }
    public function createRolPost(Request $request){   
        $consulta = DB::table('roles')->where('slug' , Str::slug($request->nombre))->where('estado', [1])->where('eliminado', [0])->first();        
        if($consulta){
            return response()->json(['error' => 'Ya existe un rol con ese nombre.']);
        }else{
            DB::beginTransaction();

            $rol_fk = DB::table('roles')->insertGetId([
                'nombre' => $request->nombre,
                'slug' => Str::slug($request->nombre),
                'created_at' => $this->date_actual,
                'creador_fk' => Auth::user()->id
            ]);

            $roles = json_decode($request->roles);
            $cont = 0;
            for ($i=0; $i < sizeof($roles); $i++) { 
                DB::table('roles_has_permisos')->insert([
                    'permiso' => $roles[$i]->rol,
                    'rol_fk' => $rol_fk,
                    'created_at' => $this->date_actual,
                    'creador_fk' => Auth::user()->id
                ]);
                $cont++;
            }
            
            if($rol_fk && ($cont == sizeof($roles))){
                DB::commit();
                return response()->json(['success' => 'Nuevo rol creado con éxito.']);
            }else{
                DB::rollback();
                return response()->json(['error' => 'Algo salió mal, vuelva a intentarlo.']);
            }
        }        
    }

    //==== ASIGN ROL
    public function asignRolEdit($id , $slug){
        $role = DB::table('roles_has_permisos')->leftJoin('usuarios_has_roles', 'roles_has_permisos.rol_fk' , 'usuarios_has_roles.rol_fk')->where('usuarios_has_roles.usuario_fk',  Auth::user()->id)->where('roles_has_permisos.permiso', 'viewGeneralUsuariosRolesAsign')->first();
        if($role || (Auth::user()->tipo_usuario == 5)){

            $existe = DB::table('roles')->where('id', $id)->where('slug', $slug)->first();
            if($existe){
                $rol = DB::table('roles')->where('id', $id)->where('slug', $slug)->first();
                $permisos = DB::table('roles_has_permisos')->where('rol_fk', $id)->get();

                $usuarios_has_roles = DB::table('usuarios_has_roles')
                    ->leftJoin('usuario', 'usuarios_has_roles.usuario_fk', 'usuario.id')
                    ->select('usuario.*')
                    ->where('usuarios_has_roles.rol_fk', $id)
                    ->orderBy('usuario.apellido_paterno')
                    ->orderBy('usuario.apellido_materno')
                    ->orderBy('usuario.primer_nombre')
                    ->orderBy('usuario.segundo_nombre')
                ->get();

                $usuarios = User::whereNotIn('usuario.tipo_usuario', [5])->where('eliminado', [0])->get();
                return view('pages.usuarios.roles.roles_asign', compact('rol' , 'permisos', 'usuarios_has_roles', 'usuarios'));
            }else{
                return redirect()->route('home');
            }    
        }else{
            return redirect()->route('home');
        }      
    }
    public function asignRolDeleteUsuarioPost(Request $request){
        DB::table('usuarios_has_roles')->where('rol_fk', $request->rol_fk)->where('usuario_fk', $request->usuario_fk)->delete();
        return redirect()->back();
    }
    public function asignUsuariosRolPost(Request $request){
        DB::beginTransaction();
        $conteo = 0;
        foreach ($request->usuarios as $item) {
            DB::table('usuarios_has_roles')->insert([
                'rol_fk' => $request->rol_fk,
                'usuario_fk' => $item,
                'created_at' => $this->date_actual,
                'creador_fk' => Auth::user()->id
            ]);
            $conteo++;
        };

        if($conteo == sizeof($request->usuarios)){
            DB::commit();
            return redirect()->back();
        }else{
            DB::rollBack();
            return redirect()->back();
        }
    }

    //==== EDIT
    public function loadRolesEdit($identificador){
        $roles = Roles::roles();
        $selecc = DB::table('roles')
            ->leftJoin('roles_has_permisos as permisos','roles.id','=','permisos.rol_fk')
            ->select(
                'permisos.id as permiso_id',
                'permisos.permiso as permiso_nombre',

                'roles.id as rol_id',
                'roles.nombre as rol_nombre',
                'roles.slug as rol_slug',
                'roles.estado as rol_estado'
            )
            ->where('roles.id', $identificador)
            ->where('roles.eliminado', [0])
        ->get();
        return response()->json([$roles , $selecc]);
    } 
    public function editRolsPost(Request $request){
        $existe = DB::table('roles')->where('id', $request->rol_fk)->first();
        if($existe){           
            $consulta = DB::table('roles')->where('slug' , Str::slug($request->nombre))->where('id', '!=' , $request->rol_fk)->where('estado', [1])->where('eliminado', [0])->first();         
            if($consulta){
                return response()->json(['warning' => 'Ya existe un rol con el nombre ingresado.']);
            }else{            
                DB::beginTransaction(); 
                
                $cambio =  DB::table('roles')->where('id', $request->rol_fk)->update([
                    'nombre' => $request->nombre,
                    'slug' => Str::slug($request->nombre)
                ]);                
                
                DB::table('roles_has_permisos')->where('rol_fk', $request->rol_fk)->delete();
                $roles = json_decode($request->roles);

                $cont = 0;
                for ($i=0; $i < sizeof($roles); $i++) { 
                    DB::table('roles_has_permisos')->insert([
                        'permiso' => $roles[$i]->rol,
                        'rol_fk' => $request->rol_fk,
                        'created_at' => $this->date_actual,
                        'creador_fk' => Auth::user()->id
                    ]);
                    $cont++;
                }

                if($cambio && $cont == sizeof($roles)){
                    DB::commit();
                    return response()->json(['success' => 'Rol modificado con éxito.']);
                }else{
                    DB::rollback();
                    return response()->json(['error' => 'Algo salió mal, vuelva a intentarlo.']);
                }
            }
        }else{
            return response()->json(['error' => 'Algo salió mal, vuelva a intentarlo.']);
        }        
    }

    //=== DELETE
    public function deleteRol(Request $request){
        $success = DB::table('roles')->where('id', $request->id)->update([
            'eliminado' => 1,
            'estado' => 0
        ]);

        if($success){
            return response()->json([200, 'success' => 'Rol eliminado con éxito.']);
        }else{
            return response()->json(['error' => 'Algo salió mal, vuelva a intentarlo.']);
        }
    }

}