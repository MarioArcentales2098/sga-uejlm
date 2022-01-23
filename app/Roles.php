<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Roles
{


    public static function roles()
    {
        $data = [
            //academico
            [
                'modulo' => 'Académico (Vista General)',
                'modulo_principal' => 1,
                'roles' => [
                    ['id' => 'viewGeneralAcademico', 'text' => 'Ver modulo']
                ]
            ],
            [
                'modulo' => 'Académico (Periodo académico)',
                'modulo_principal' => 0,
                'roles' => [
                    ['id' => 'viewGeneralPeriodoAcademico', 'text' => 'Ver submodulo'],
                    ['id' => 'viewGeneralPeriodoAcademicoCreate', 'text' => 'Crear registros'],
                    ['id' => 'viewGeneralPeriodoAcademicoEdit', 'text' => 'Editar registros'],
                    ['id' => 'viewGeneralPeriodoAcademicoDelete', 'text' => 'Eliminar registros'],
                    ['id' => 'viewGeneralPeriodoAcademicoParciales', 'text' => 'Abrir | Cerrar  Parcial']

                    
                ]
            ],
            [
                'modulo' => 'Académico (Asignaturas)',
                'modulo_principal' => 0,
                'roles' => [
                    ['id' => 'viewGeneralAsignatura', 'text' => 'Ver submodulo'],
                    ['id' => 'viewGeneralAsignaturaCreate', 'text' => 'Crear registros'],
                    ['id' => 'viewGeneralAsignaturaEdit', 'text' => 'Editar registros'],
                    ['id' => 'viewGeneralAsignaturaDelete', 'text' => 'Eliminar registros'],
                    ['id' => 'viewGeneralAsignaturaAsignDoce', 'text' => 'Agregar docentes a registro']
                ]
            ],
            [
                'modulo' => 'Académico (Cursos)',
                'modulo_principal' => 0,
                'roles' => [
                    ['id' => 'viewGeneralCursos', 'text' => 'Ver submodulo'],
                    ['id' => 'viewGeneralCursosCreate', 'text' => 'Crear registros'],
                    ['id' => 'viewGeneralCursosPDF', 'text' => 'Generar PDF'],
                    ['id' => 'viewGeneralCursosAsignar', 'text' => 'Asignar registros'],
                    ['id' => 'viewGeneralCursosDelete', 'text' => 'Eliminar registros']
                ]
            ],
            [
                'modulo' => 'Académico (Matrículas)',
                'modulo_principal' => 0,
                'roles' => [
                    ['id' => 'viewGeneralMatricula', 'text' => 'Ver submodulo'],
                    ['id' => 'viewGeneralMatriculaCreate', 'text' => 'Crear registros'],
                    // ['id' => 'viewGeneralMatriculaPDF', 'text' => 'Generar PDF'],
                    ['id' => 'viewGeneralMatriculaEstudiantePDF', 'text' => 'Generar PDF matriculado'],
                    ['id' => 'viewGeneralMatriculaDelete', 'text' => 'Eliminar registros']
                ]
            ],
            [
                'modulo' => 'Académico (Estudiantes)',
                'modulo_principal' => 0,
                'roles' => [
                    ['id' => 'viewGeneralEstudiantes', 'text' => 'Ver submodulo'],
                    ['id' => 'viewGeneralEstudiantesCreate', 'text' => 'Crear estudiante'],
                    ['id' => 'viewGeneralEstudiantesEdit', 'text' => 'Editar estudiante'],
                    ['id' => 'viewGeneralEstudiantesDelete', 'text' => 'Eliminar estudiante'],
                    ['id' => 'viewGeneralEstudiantesBan', 'text' => 'Banear estudiante']
                ]                
            ],
            [
                'modulo' => 'Académico (Docentes)',
                'modulo_principal' => 0,
                'roles' => [
                    ['id' => 'viewGeneralDocentes', 'text' => 'Ver submodulo'],
                    ['id' => 'viewGeneralDocentesCreate', 'text' => 'Crear docente'],
                    ['id' => 'viewGeneralDocentesEdit', 'text' => 'Editar docente'],
                    ['id' => 'viewGeneralDocentesDelete', 'text' => 'Eliminar docente'],
                    ['id' => 'viewGeneralDocentesBan', 'text' => 'Banear docente']
                ]
            ],

            //clases
            [
                'modulo' => 'Clases (Vista General)',
                'modulo_principal' => 1,
                'roles' => [
                    ['id' => 'viewGeneralClases', 'text' => 'Ver modulo']
                ]
            ],
            
            //usuarios
            [
                'modulo' => 'Configuración (Vista General)',
                'modulo_principal' => 1,
                'roles' => [
                    ['id' => 'viewGeneralConfUsuarios', 'text' => 'Ver modulo']
                ]
            ],
            [
                'modulo' => 'Configuración (Usuarios)',
                'modulo_principal' => 0,
                'roles' => [
                    ['id' => 'viewGeneralUsuarios', 'text' => 'Ver submodulo'],
                    ['id' => 'viewGeneralUsuariosCreate', 'text' => 'Crear usuarios'],
                    ['id' => 'viewGeneralUsuariosEdit', 'text' => 'Editar usuarios'],
                    ['id' => 'viewGeneralUsuariosDelete', 'text' => 'Eliminar usuarios'],
                    ['id' => 'viewGeneralUsuariosBan', 'text' => 'Banear usuarios']
                    
                ]
            ],
            [
                'modulo' => 'Configuración (Roles)',
                'modulo_principal' => 0,
                'roles' => [
                    ['id' => 'viewGeneralUsuariosRoles', 'text' => 'Ver submodulo'],
                    ['id' => 'viewGeneralUsuariosRolesCreate', 'text' => 'Crear rol'],
                    ['id' => 'viewGeneralUsuariosRolesEdit', 'text' => 'Editar rol'],
                    ['id' => 'viewGeneralUsuariosRolesDelete', 'text' => 'Eliminar rol'],
                    ['id' => 'viewGeneralUsuariosRolesAsign', 'text' => 'Asignar rol a usuarios']
                ]
            ]
        ];

        return $data;

        //esta sirve para un select2 con grouplist
        // $data = [
        //     ['id' => 'panelView' , 'text' => 'Panel' , 
        //         'children' => [
        //             ['id' => 'panelView' , 'text' => 'Panel1'],
        //             ['id' => 'panelView' , 'text' => 'Panel2'],
        //             ['id' => 'panelView' , 'text' => 'Panel3'],
        //             ['id' => 'panelView' , 'text' => 'Panel4'],
        //             ['id' => 'panelView' , 'text' => 'Panel5']
        //         ]
        //     ],
        //     ['id' => 'usuariosView' , 'text' => 'Modulo Usuarios' , 
        //         'children' => [
        //             ['id' => 'panelView' , 'text' => 'Panel1'],
        //             ['id' => 'panelView' , 'text' => 'Panel2'],
        //             ['id' => 'panelView' , 'text' => 'Panel3'],
        //             ['id' => 'panelView' , 'text' => 'Panel4'],
        //             ['id' => 'panelView' , 'text' => 'Panel5']
        //         ]
        //     ]
        // ];


    }


    //############# ROLES Y PERMISOS #################################
    //==== USUARIOS
    public static $usuariosView = 'usuariosView';
    public static $camaraIngreso = 'camaraIngreso';
    public static $camaraEgreso = 'camaraEgreso';
    public static $camaraMovimiento = 'camaraMovimiento';
    public static $camaraSobrantes = 'camaraSobrantes';
    public static $camaraClaseB = 'camaraClaseB';
    public static $camaraDeleteRegistros = 'camaraDeleteRegistros';
    //=======================================================
}
