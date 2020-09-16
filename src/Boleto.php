<?php

namespace Uspdev;

class Boleto {
    
    public const WSBOLETO_SERVICO_GERAR_BOLETO_REGISTRADO = 'gerarBoletoRegistrado';
    public const WSBOLETO_SERVICO_OBTER_SITUACAO = 'obterSituacao';
    public const WSBOLETO_SERVICO_OBTER_BOLETO = 'obterBoleto';
    public const WSBOLETO_SERVICO_CANCELAR_BOLETO = 'cancelarBoleto';
    
    private const WSBOLETO_URL_DEV = 'https://dev.uspdigital.usp.br/wsboleto/wsdl/boleto.wsdl';
    private const WSBOLETO_URL_PRD = 'https://uspdigital.usp.br/wsboleto/wsdl/boleto.wsdl';
    
    private $clienteSoap;

    public function __construct($user, $pass, $dev = False) {
        $wsdl = self::WSBOLETO_URL_PRD;
        
        if ($dev) {
            $wsdl = self::WSBOLETO_URL_DEV;
        }

        require_once __DIR__ . '/../../../econea/nusoap/src/nusoap.php';
        $this->clienteSoap = new \nusoap_client($wsdl, 'wsdl');

        $erro = $this->clienteSoap->getError();

        if ($erro) {
            print_r($erro); 
            die();
        }
        $this->clienteSoap->setHeaders(array('username' => $user,'password' => $pass));
    }

    /**
     * Gera Boleto Bancario Registrado integrado com o Sistema MercurioWeb.
     * 
     * @param mixed $data   Array com os campos representando os parametros de entrada 
     *                      do servico conforme especificacao WS - Boleto Bancario
     * @return array        Array associativo contendo o status de sucesso (indice status)
     *                      e o codigo de identificacao do boleto bancario (indice value)
     */
    public function gerar($data) {
        $return = array('status' => False, 'value' => "");
        
        /* aqui esperamos que tudo cheguem em utf8 e convertemos utf8_decode*/
        foreach($data as $key=>$value) {
            $data[$key] = utf8_decode($value);
        }

        $request = $this->clienteSoap->call(self::WSBOLETO_SERVICO_GERAR_BOLETO_REGISTRADO, 
            array('boletoRegistrado' => $data));
        $erro = $this->clienteSoap->getError();
        
        if ($erro) {            
            $return['value'] = utf8_encode($erro);
        } else {
            $return['status'] = True;
            if(!empty($request) && is_array($request)) {        
                $return['value'] = $request['identificacao']['codigoIDBoleto'];
            }
        }
        
        return $return;        
    }

    /**
     * Retorna a situacao atual do boleto (emitido, pago, cancelado, etc)
     *
     * @param string $codigoIDBoleto    Codigo de identificacao do boleto bancario
     * @return array                    Array associativo contendo o status de sucesso (indice status) e um array 
     *                                  com os valores do boleto (indice value) conforme especificacao WS - Boleto Bancario
     */
    public function situacao($codigoIDBoleto){
        $return = array('status' => False, 'value' => "");
        
        $param = array('codigoIDBoleto'=>$codigoIDBoleto);
        $request = $this->clienteSoap->call(self::WSBOLETO_SERVICO_OBTER_SITUACAO, 
            array('identificacao'=>$param));
        $erro = $this->clienteSoap->getError();
        
        if ($erro) {
            $return['value'] = utf8_encode($erro);
        } else {
            $return['status'] = True;
            if(!empty($request) && is_array($request)) {
                $return['value'] = array();
                $return['value']['situacao'] = $request['situacao']['statusBoletoBancario'];
                $return['value']['valorCobrado'] = $request['situacao']['valorCobrado'];
                $return['value']['valorEfetivamentePago'] = $request['situacao']['valorEfetivamentePago'];
                $return['value']['dataVencimentoBoleto'] = $request['situacao']['dataVencimentoBoleto'];
                $return['value']['dataEfetivaPagamento'] = $request['situacao']['dataEfetivaPagamento'];
                $return['value']['dataRegistro'] = $request['situacao']['dataRegistro'];
                $return['value']['dataCancelamentoRegistro'] = $request['situacao']['dataCancelamentoRegistro'];
            }
        }
        
        return $return;
    }

    /**
     * Retorna o boleto em PDF no formato binario codificado para Base64
     *
     * @param string $codigoIDBoleto    Codigo de identificacao do boleto bancario
     * @return array                    Array associativo contendo o status de sucesso (indice status) e o 
     *                                  Boleto em PDF no formato binário codificado para Base64 (indice value)
     */
    public function obter($codigoIDBoleto) {
        $return = array('status' => False, 'value' => "");
        
        $param = array('codigoIDBoleto' => $codigoIDBoleto);
        $request = $this->clienteSoap->call(self::WSBOLETO_SERVICO_OBTER_BOLETO, 
            array('identificacao' => $param));
        $erro = $this->clienteSoap->getError();
        
        if ($erro) {
            $return['value'] = utf8_encode($erro);
        } else {
            $return['status'] = True;
            if(!empty($request)) {
                $return['value'] = $request['boletoPDF'];
            }
        }      
        
        return $return;
    }

    /**
     * Cancela um boleto gerado que não foi pago
     *
     * @param string $codigoIDBoleto    Codigo de identificacao do boleto bancario
     * @return array                    Array associativo contendo o status de sucesso (indice status)
     *                                  e um array com os valores do boleto cancelado conforme 
     *                                  especificacao WS - Boleto Bancario (indice value)
     */
    public function cancelar($codigoIDBoleto) {
        $return = array('status' => False, 'value' => "");
        
        $param = array('codigoIDBoleto' => $codigoIDBoleto);
        $request = $this->clienteSoap->call(self::WSBOLETO_SERVICO_CANCELAR_BOLETO, 
            array('identificacao' => $param));

        $erro = $this->clienteSoap->getError();
        
        if ($erro) {
            $return['value'] = utf8_encode($erro);
        } else {
            $return['status'] = True;
            if(!empty($request) && is_array($request)) {
                $return['value'] = array();
                $return['value']['situacao'] = $request['situacao']['statusBoletoBancario'];
                $return['value']['valorCobrado'] = $request['situacao']['valorCobrado'];
                $return['value']['valorEfetivamentePago'] = $request['situacao']['valorEfetivamentePago'];
                $return['value']['dataVencimentoBoleto'] = $request['situacao']['dataVencimentoBoleto'];
                $return['value']['dataEfetivaPagamento'] = $request['situacao']['dataEfetivaPagamento'];
                $return['value']['dataRegistro'] = $request['situacao']['dataRegistro'];
                $return['value']['dataCancelamentoRegistro'] = $request['situacao']['dataCancelamentoRegistro'];
            }
        }
        
        return $return;
        
    }

}

