<?php 
echo "<pre>";
require('config.php');
require_once('./nusoap/lib/nusoap.php');


/*
$sid = isset($_GET['sid']) ? $_GET['sid'] : $_POST['sid'];

$sql = "SELECT c.name, sd.data FROM webform_submitted_data sd
        INNER JOIN webform_component c ON c.cid = sd.cid AND c.nid = sd.nid
        INNER JOIN webform_submissions s ON s.sid = sd.sid
        WHERE sd.sid = $sid";
*/


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

//faz a requisição SOAP para gerar o codigo do boleto
$retorno = $clienteSoap->call('gerarBoleto', array('requisicao' => $dados));
print_r($retorno);
print_r($clientSoap);

// VERIFICA HOUVE ERRO NA GERAÇÃO DO BOLETO
if ($clienteSoap->fault) {
	// Se não gerou o boleto
	
	// Mensagem de erro para E-mail
//	$msgErrBoleto = '<html><body><strong><font color="#f00">' 
//					. '</font></strong><br /><br />';
//	$msgErrBoleto .= 'WSException = <strong><font color="#f00">' 
//					. $retorno["detail"]["WSException"] . '</font></strong><br /><br />';

    Logger($retorno["detail"]["WSException"]);

}
else {
	// Gerou
}

$msgErrBoleto = '';
$msgCandidatoBoleto = '';
$msgEmailSVTI = '';
$msgEmailPgm = '';
$msgEmailCandidato = '';
//die();

//Redireciona para o site da ECA
//header("Location: http://www.algo.usp.br");
?>

