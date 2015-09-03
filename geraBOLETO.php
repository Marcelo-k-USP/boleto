<?php
$codigo = array('codigoIDBoleto' => 'xxxxxxx'); 
require('config.php');
require_once('./nusoap/lib/nusoap.php');

//instanciando o cliente SOAP
$clienteSoap = new nusoap_client('boleto_dsv.wsdl', 'wsdl');
$erro = $clienteSoap->getError();
if ($erro){
        printf("%s", $erro);
        //exit;
}

//indicando usuario e senha para acesso ao SOAP
$soapHeaders = array('username' => USERNAME_WSDL,
                     'password' => PASSWORD_WSDL);

$clienteSoap->setHeaders($soapHeaders);

$r = $clienteSoap->call('obterBoleto', array('identificacao' => $codigo)); 

//print_r($clienteSoap->getError());

//redirecionando os dados binarios do pdf para o browser 
header('Content-type: application/pdf'); 
header('Content-Disposition: attachment; filename="boleto.pdf"'); 
echo base64_decode($r['boletoPDF']); 

?>
