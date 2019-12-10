<?php

namespace Uspdev;

class Boleto
{
    private $clienteSoap;
    public function __construct($user, $pass, $wsdl = false)
    {
        /* É possível passar o wsdl como parâmetro, por exemplo, no ambiente dev.
           Se nada for passado, vamos assumir a url de produção */
        if (!$wsdl) {
           $wsdl = 'https://uspdigital.usp.br/wsboleto/wsdl/boleto.wsdl';
        }

        require_once __DIR__ . '/../../../econea/nusoap/src/nusoap.php';
        //$this->clienteSoap = new \nusoap_client($wsdl, true);
        $this->clienteSoap = new \nusoap_client($wsdl, 'wsdl');

        $erro = $this->clienteSoap->getError();

        if ($erro) {
            print_r($erro); 
            die();
        }
        $this->clienteSoap->setHeaders(array('username' => $user,'password' => $pass));
    }

    public function gerar($data)
    {
        /* aqui esperamos que tudo cheguem em utf8 e convertemos utf8_decode*/
        foreach($data as $key=>$value) {
            $data[$key] = utf8_decode($value);
        }

        $request = $this->clienteSoap->call('gerarBoletoRegistrado', array('boletoRegistrado' => $data));

        $data = [];
        if ($this->clienteSoap->fault) {
            $data['status'] = False;
            $data['value'] = $request["detail"]["WSException"];
            return $data;
        }
        else {
            $data['status'] = True;
            $data['value'] = $request['identificacao']['codigoIDBoleto'];
            return $data;
        }
    }

    public function situacao($codigoIDBoleto){
        $param = array('codigoIDBoleto'=>$codigoIDBoleto);
        return $this->clienteSoap->call('obterSituacao', array('identificacao'=>$param));
    }

    public function obter($codigoIDBoleto)
    {
        $param = array('codigoIDBoleto' => $codigoIDBoleto);
        $request = $this->clienteSoap->call('obterBoleto', array('identificacao' => $param));

        if ($this->clienteSoap->fault || $this->clienteSoap->getError()) {
            print_r($request);
            die();
        }

        //redirecionando os dados binarios do pdf para o browser
        header('Content-type: application/pdf'); 
        header('Content-Disposition: attachment; filename="boleto.pdf"'); 
        echo base64_decode($request['boletoPDF']);
    }
}

