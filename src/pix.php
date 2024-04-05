<?php

class Pix {
    private $document;
    private $token;
    private $apiUrl;

    public function __construct($document, $token, $apiUrl) {
        $this->document = $document;
        $this->token = $token;
        $this->apiUrl = $apiUrl;
    }

    public function getDocument() {
        return $this->document;
    }

    public function setDocument($document) {
        $this->document = $document;
        return $this;
    }

    public function getToken() {
        return $this->token;
    }

    public function setToken($token) {
        $this->token = $token;
        return $this;
    }

    public function getApiUrl() {
        return $this->apiUrl;
    }

    public function setApiUrl($apiUrl) {
        $this->apiUrl = $apiUrl;
        return $this;
    }

    public function createOrderPixTaxDocument($chargePixTaxId) {
        $url = $this->apiUrl . '/invoice-pix';

        /// Dados da cobrança Pix
        $data = array(
            'invoiceName' => $chargePixTaxId->getInvoiceName(),
            'amount' => $chargePixTaxId->getAmount(),
            'validate' => $chargePixTaxId->getValidate(),
            'description' => $chargePixTaxId->getDescription(),
            'taxpayerId' => $chargePixTaxId->getTaxpayerId()
        );

        // Configuração da requisição
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n" .
                             "Authorization: Bearer " . $this->token . "\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data)
            )
        );

        // Cria um contexto para a requisição
        $context  = stream_context_create($options);

        // Envia a requisição
        // O resultado é um JSON com os dados da cobrança Pix
        $result = file_get_contents($url, false, $context);

        if ($result === FALSE) {
            return 'Erro ao criar cobrança Pix';
        }

        return $result;
    }
}

class ChargePixTaxId {
    private $invoiceName;
    private $amount;
    private $validate;
    private $description;
    private $taxpayerId;

    public function setInvoiceName($invoiceName) {
        $this->invoiceName = $invoiceName;
        return $this;
    }

    public function getInvoiceName() {
        return $this->invoiceName;
    }

    public function setAmount($amount) {
        $this->amount = $amount;
        return $this;
    }

    public function getAmount() {
        return $this->amount;
    }

    public function setValidate($validate) {
        $this->validate = $validate;
        return $this;
    }

    public function getValidate() {
        return $this->validate;
    }

    public function setDescription($description) {
        $this->description = $description;
        return $this;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setTaxpayerId($taxpayerId) {
        $this->taxpayerId = $taxpayerId;
        return $this;
    }

    public function getTaxpayerId() {
        return $this->taxpayerId;
    }
}

// Exemplo de uso

$seller_doc = '0000000000'; // substitua pelo seu documento de vendedor
$seller_token = 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX'; // substitua pelo seu token de vendedor
$apiUrl = 'https://api.aqbank.com/'; // URL da API

$pix = new Pix($seller_doc, $seller_token, $apiUrl);

$chargePixTaxId = new ChargePixTaxId();
$chargePixTaxId->setInvoiceName('Pedido 123')
    ->setAmount(100.00)
    ->setValidate(date('Y-m-d', strtotime(date('Y-m-d') . ' +30 days')))
    ->setDescription('Compra na loja')
    ->setTaxpayerId('000.000.000-00'); // documento do pagador

$response = $pix->createOrderPixTaxDocument($chargePixTaxId);

echo $response;