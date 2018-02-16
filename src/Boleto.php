<?php

namespace Uspdev;

class Boleto
{
    private $clienteSoap;

    public function __construct($user, $pass, $wsdl="./wsdl/prod.wsql")
    {
        require('../vendor/econea/nusoap/src/nusoap.php');

        // Instanciando o cliente SOAP
        $this->clienteSoap = new nusoap_client($wsdl, 'wsdl');
        $erro = $this->clienteSoap->getError();
        if ($erro) print_r($erro);

        // Indicando usuário e senha para acesso ao SOAP
        $this->clienteSoap->setHeaders(array('username' => $user,'password' => $pass));
    }

    public function gerar($data)
    {
        $request = $this->clienteSoap->call('gerarBoleto', array('requisicao' => $data));

        //verifica se houve erro na geração do boleto.
        if ($this->clienteSoap->fault) print_r($request["detail"]["WSException"]);
        else {
            $codigoIDBoleto = $request['identificacao']['codigoIDBoleto'];
        }
        return $codigoIDBoleto;
    }

    public function situacao($codigoIDBoleto){
        $param = array('codigoIDBoleto'=>$codigoIDBoleto);
        $situacao = $this->clienteSoap->call('obterSituacao', array('identificacao'=>$param));
        return $situacao;
    }

    public function obter($codigoIDBoleto)
    {
        $param = array('codigoIDBoleto' => $codigoIDBoleto);
	      $request = $this->clienteSoap->call('obterBoleto', array('identificacao' => $param));
	      if ($this->clienteSoap->fault) print_r($request); 
	      if ($this->clienteSoap->getError())  print_r($request);

        //redirecionando os dados binarios do pdf para o browser 
        header('Content-type: application/pdf'); 
        header('Content-Disposition: attachment; filename="boleto.pdf"'); 
        echo base64_decode($request['boletoPDF']);
    }    
}

