<?php


// Include our form fields file
require_once 'adminFields/aqbank_admin_fields.php';

// Include Pix class
require_once 'src/pix.php';


/* A classe desse arquivo extende a classe WC_Payment_Gateway do woocommerce, 
seus metodos e propriedades são sobrescritos para atender as necessidades do gateway */

class Aqbank_Payment_Gateway extends WC_Payment_Gateway {

    //funcão construtora da classe, é chamada quando a classe é instanciada
    public function __construct() {


        //main properties
        //propriedades principais da classe
        $this->id = 'aqbankid'; 
        $this->icon = ''; //icone do gateway no checkout
        $this->has_fields = true; // viabiliza o formulário personalizado de cartão de crédito
        $this->method_title = 'AqBank Gateway';
        $this->method_description = 'Description aqbank payment gateway'; // will be displayed on the options page
    
        // gateways can support subscriptions, refunds, saved payment methods,
        $this->supports = array(
            'products'
        );
    
        // Metodo para inicializar os campos de configuração dinamicos do admin
        // importado do arquivo aqbank_admin_fields.php
        $this->form_fields = aqbank_init_admin_fields();
    
        // Carregando as configurações salvas no banco de dados para cada form field
        //a função get_option é herdada da classe WC_Settings_API e é usada para obter as configurações salvas no banco de dados
        $this->init_settings();
        $this->title = $this->get_option( 'title' );
        $this->description = $this->get_option( 'description' );
        $this->enabled = $this->get_option( 'enabled' );
        $this->taxDocument = $this->get_option('taxDocument');
        $this->secretKey = $this->get_option('secretKey');
        $this->publicToken = $this->get_option('publicToken');
    

        // This action hook saves the settings
        // desse modo, quando o admin salvar as configurações, a função process_admin_options é chamada
        // e atualiza as configurações salvas no banco de dados
        add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
    
        // We need custom JavaScript to obtain a token
        //add_action( 'wp_enqueue_scripts', array( $this, 'payment_scripts' ) );
        
        // You can also register a webhook here
        // add_action( 'woocommerce_api_{webhook name}', array( $this, 'webhook' ) ); */
     }


    public function generate_pix_code() {
            // Get the secret key and tax document from the saved settings
            $secretKey = $this->get_option('secretKey');
            $taxDocument = $this->get_option('taxDocument');

            // Create a new Pix object
            $pix = new Pix($secretKey, $taxDocument, 'your-api-url');

            // Create a new ChargePixTaxId object
            $chargePixTaxId = new ChargePixTaxId();
            $chargePixTaxId->setInvoiceName('Teste Compra')
                            ->setAmount(1.00)
                            ->setValidate('2024-05-05')
                            ->setDescription('Invoice Description')
                            ->setTaxpayerId('77124372895');

            // Call the method to generate the Pix code
            $pixCode = $pix->createOrderPixTaxDocument($chargePixTaxId);

            // Return the Pix code
            return $pixCode;
    }     

    public function payment_fields() {
        // Get current user
        $user = wp_get_current_user();
    
        // Start the form
        echo '<fieldset id="wc-' . esc_attr($this->id) . '-pix-form" class="wc-pix-form wc-payment-form" style="background:transparent;">';
    
        // Form fields
        echo '<div class="form-row form-row-wide">
                  <button id="generate_pix_code" type="button">Gerar código Pix</button>
              </div>';
    
        // JavaScript to handle the button click
        echo '<script type="text/javascript">
                  document.getElementById("generate_pix_code").addEventListener("click", function() {
                      var pixCode = ' . $this->generate_pix_code() . ';
                      console.log(pixCode);
                  });
              </script>';
    
        // End the form
        echo '<div class="clear"></div></fieldset>';
    }


    /*
     * Custom CSS and JS
     */
    public function payment_scripts() {
        // payment scripts content
    }

    /*
     * Fields validation
     */
    public function validate_fields() {
        // validate fields content
    }

    /*
     * Processing the payments
     */
    public function process_payment( $order_id ) {
        // process payment content
    }

    /*
     * Webhook, like PayPal IPN etc
     */
    public function webhook() {
        // webhook content
    }
}