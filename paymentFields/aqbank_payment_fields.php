<?php

// Metodo para inicializar os campos de configuração dinamicos do admin
// é exportado e executado no arquivo aqbank_payment_gateway_class.php

function aqbank_init_admin_fields(){                
    return array(
        'enabled' => array(
            'title'       => 'Habilitar / Desabilitar',
            'label'       => 'Habilitar AqBank Gateway',
            'type'        => 'checkbox',
            'description' => '',
            'default'     => 'no'
        ),
        'title' => array(
            'title'       => 'Cartão de Credito',
            'type'        => 'text',
            'description' => 'This controls the title which the user sees during checkout.',
            'default'     => 'AqBank Gateway',
            'desc_tip'    => true,
        ),
        'description' => array(
            'title'       => 'Description',
            'type'        => 'textarea',
            'description' => 'This controls the description which the user sees during checkout.',
            'default'     => 'Pay with AqBank Gateway',
        ),
        'taxDocument' => array(
            'title'       => 'Tax Document',
            'type'        => 'text',
            'description' => 'Cnpj ou CPF do cadastrado na AqBank',
            'default'     => '',
            'desc_tip'    => true,
        ),
        'secretKey' => array(
            'title'       => 'Secret Key',
            'type'        => 'text',
            'description' => 'Token de acesso',
            'default'     => '',
            'desc_tip'    => true,
        ),
        'publicToken' => array(
            'title'       => 'Public Token',
            'type'        => 'text',
            'description' => 'Token Publico',
            'default'     => '',
            'desc_tip'    => true,
        )
    );
}