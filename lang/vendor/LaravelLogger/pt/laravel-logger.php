<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Laravel Logger Language Lines - Global
    |--------------------------------------------------------------------------
    */
    'userTypes' => [
        'guest'      => 'Anônimo',
        'registered' => 'Membro',
        'crawler'    => 'Robô',
    ],

    'verbTypes' => [
        'created'    => 'Criado',
        'edited'     => 'Editado',
        'deleted'    => 'Excluído',
        'viewed'     => 'Visualizado',
        'crawled'    => 'Visitado',
    ],

    'tooltips' => [
        'viewRecord' => 'Ver detalhes deste Registro',
    ],

    /*
    |--------------------------------------------------------------------------
    | Laravel Logger Admin Dashboard Language Lines
    |--------------------------------------------------------------------------
    */
    'dashboard' => [
        'title'     => 'Registro de Atividades',
        'subtitle'  => 'Eventos',
        'labels'    => [
            'id'            => 'Evento Id',
            'time'          => 'Tempo',
            'description'   => 'Descrição',
            'user'          => 'Usuário',
            'method'        => 'Método',
            'route'         => 'Rota',
            'ipAddress'     => '<span class="hidden-sm hidden-xs">Endereço </span>Ip',
            'agent'         => 'Agente<span class="hidden-sm hidden-xs"> Usuário</span>',
            'deleteDate'    => 'Excluído<span class="hidden-sm hidden-xs"> em</span> ',
        ],

        'menu'      => [
            'alt'           => 'Menu do Registro de Atividades',
            'clear'         => 'Limpar registro',
            'show'          => 'Mostrar registros excluídos',
            'back'          => 'Voltar ao Registro de Atividades',
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
        'title'                 => 'Atividade',
        'title-details'         => 'Detalhes',
        'title-ip-details'      => 'Endereço Ip',
        'title-user-details'    => 'Usuário',
        'title-user-activity'   => 'Atividade Adicional do Usuário',
        'buttons'               => [
            'back'      => '<span class="hidden-xs hidden-sm">Voltar ao </span><span class="hidden-xs"> Registro de Atividades</span>',
        ],

        'labels' => [
            'userRoles'      => 'Funções',
            'userNiveau'     => 'Nível',
        ],

        'list-group' => [
            'labels'    => [
                'id'            => 'Atividade Id :',
                'ip'            => 'Endereço Ip',
                'description'   => 'Descrição',
                'userType'      => 'Tipo de Usuário',
                'userId'        => 'Id do Usuário',
                'route'         => 'Rota',
                'agent'         => 'Agente do usuário',
                'locale'        => 'Local',
                'referer'       => 'Referente',

                'methodType'    => 'Tipo de método',
                'createdAt'     => 'Evento',
                'updatedAt'     => 'Atualizado em',
                'deletedAt'     => 'Excluído em',
                'timePassed'    => 'Tempo decorrido',
                'userName'      => 'Nome de usuário',
                'userFirstName' => 'Primeiro nome',
                'userLastName'  => 'Sobrenome',
                'userFulltName' => 'Nome completo',
                'userEmail'     => 'Email',
                'userSignupIp'  => 'Inscrição Ip',
                'userCreatedAt' => 'Criado em',
                'userUpdatedAt' => 'Atualizado em',
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
            'title'     => 'Limpar Registro de Atividades',
            'message'   => 'Tem certeza de que deseja limpar o registro de atividades?',
        ],
        'deleteLog' => [
            'title'     => 'Excluir permanentemente o registro de atividades',
            'message'   => 'Tem certeza de que deseja excluir permanentemente o registro de atividades?',
        ],
        'restoreLog' => [
            'title'     => 'Restaurar o registro de atividades excluído',
            'message'   => 'Tem certeza de que deseja restaurar o registro de atividades excluído?',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Laravel Logger Flash Messages
    |--------------------------------------------------------------------------
    */

    'messages' => [
        'logClearedSuccessfuly'   => 'Atividade limpa com sucesso',
        'logDestroyedSuccessfuly' => 'Atividade excluída com sucesso',
        'logRestoredSuccessfuly'  => 'Atividade restaurada com sucesso',
    ],

    /*
    |--------------------------------------------------------------------------
    | Laravel Logger Cleared Dashboard Language Lines
    |--------------------------------------------------------------------------
    */

    'dashboardCleared' => [
        'title'     => 'Registro de atividades excluídas',
        'subtitle'  => 'Eventos excluídos',

        'menu'      => [
            'deleteAll'  => 'Excluir todos os registros de atividades',
            'restoreAll' => 'Restaurar todos os registros de atividades',
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