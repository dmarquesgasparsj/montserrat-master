<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Laravel Logger Language Lines - Global
    |--------------------------------------------------------------------------
    */
    'userTypes' => [
        'guest'      => 'Invitado',
        'registered' => 'Registrado',
        'crawler'    => 'Rastreador',
    ],

    'verbTypes' => [
        'created'    => 'Creado',
        'edited'     => 'Editado',
        'deleted'    => 'Eliminado',
        'viewed'     => 'Visto',
        'crawled'    => 'Rastreado',
    ],

    'tooltips' => [
        'viewRecord' => 'Ver detalles del registro',
    ],

    /*
    |--------------------------------------------------------------------------
    | Laravel Logger Admin Dashboard Language Lines
    |--------------------------------------------------------------------------
    */
    'dashboard' => [
        'title'     => 'Registro de Actividad',
        'subtitle'  => 'Eventos',

        'labels'    => [
            'id'            => 'Id',
            'time'          => 'Hora',
            'description'   => 'Descripción',
            'user'          => 'Usuario',
            'method'        => 'Método',
            'route'         => 'Ruta',
            'ipAddress'     => '<span class="hidden-sm hidden-xs">Dirección </span>Ip',
            'agent'         => 'Agente<span class="hidden-sm hidden-xs"> de Usuario</span>',
            'deleteDate'    => 'Eliminado<span class="hidden-sm hidden-xs"> el</span> ',
        ],

        'menu'      => [
            'alt'           => 'Menú del Registro de Actividades',
            'clear'         => 'Limpiar registro de actividad',
            'show'          => 'Mostrar registros eliminados',
            'back'          => 'Volver al Registro de Actividad',
        ],

        'search'    => [
            'all'           => 'Todos',
            'search'        => 'Buscar',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Laravel Logger Admin Drilldown Language Lines
    |--------------------------------------------------------------------------
    */

    'drilldown' => [
        'title'                 => 'Registro de Actividad :id',
        'title-details'         => 'Detalles de la actividad',
        'title-ip-details'      => 'Detalles de la dirección IP',
        'title-user-details'    => 'Detalles del usuario',
        'title-user-activity'   => 'Actividad adicional del usuario',

        'buttons'   => [
            'back'      => '<span class="hidden-xs hidden-sm">Volver al </span><span class="hidden-xs">Registro de Actividad</span>',
        ],

        'labels' => [
            'userRoles'     => 'Roles de usuario',
            'userLevel'     => 'Nivel',
        ],

        'list-group' => [
            'labels'    => [
                'id'            => 'ID del registro de actividad:',
                'ip'            => 'Dirección IP',
                'description'   => 'Descripción',
                'userType'      => 'Tipo de usuario',
                'userId'        => 'ID de usuario',
                'route'         => 'Ruta',
                'agent'         => 'Agente de usuario',
                'locale'        => 'Idioma',
                'referer'       => 'Referente',

                'methodType'    => 'Tipo de método',
                'createdAt'     => 'Hora del evento',
                'updatedAt'     => 'Actualizado el',
                'deletedAt'     => 'Eliminado el',
                'timePassed'    => 'Tiempo transcurrido',
                'userName'      => 'Nombre de usuario',
                'userFirstName' => 'Nombre',
                'userLastName'  => 'Apellido',
                'userFulltName' => 'Nombre completo',
                'userEmail'     => 'Correo electrónico',
                'userSignupIp'  => 'IP de registro',
                'userCreatedAt' => 'Creado el',
                'userUpdatedAt' => 'Actualizado el',
            ],
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Laravel Logger Modals
    |--------------------------------------------------------------------------
    */

    'modals' => [
        'shared' => [
            'btnCancel'     => 'Cancelar',
            'btnConfirm'    => 'Confirmar',
        ],
        'clearLog' => [
            'title'     => 'Limpiar Registro de Actividad',
            'message'   => '¿Estás seguro de que deseas limpiar el registro de actividad?',
        ],
        'deleteLog' => [
            'title'     => 'Eliminar permanentemente el registro de actividad',
            'message'   => '¿Estás seguro de que deseas ELIMINAR permanentemente el registro de actividad?',
        ],
        'restoreLog' => [
            'title'     => 'Restaurar registro de actividad eliminado',
            'message'   => '¿Estás seguro de que deseas restaurar los registros de actividad eliminados?',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Laravel Logger Flash Messages
    |--------------------------------------------------------------------------
    */

    'messages' => [
        'logClearedSuccessfuly'   => 'Registro de actividad limpiado correctamente',
        'logDestroyedSuccessfuly' => 'Registro de actividad eliminado correctamente',
        'logRestoredSuccessfuly'  => 'Registro de actividad restaurado correctamente',
    ],

    /*
    |--------------------------------------------------------------------------
    | Laravel Logger Cleared Dashboard Language Lines
    |--------------------------------------------------------------------------
    */

    'dashboardCleared' => [
        'title'     => 'Registros de actividad eliminados',
        'subtitle'  => 'Eventos eliminados',

        'menu'      => [
            'deleteAll'  => 'Eliminar todos los registros de actividad',
            'restoreAll' => 'Restaurar todos los registros de actividad',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Laravel Logger Pagination Language Lines
    |--------------------------------------------------------------------------
    */
    'pagination' => [
        'countText' => 'Mostrando :firstItem - :lastItem de :total resultados <small>(:perPage por página)</small>',
    ],

];
