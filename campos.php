<?php

//Campos necessários para instanciar o webservice:

$dados = array();

$dados['codigoUnidadeDespesa'] = 1;
$dados['nomeFonte'] = 'Prestação de Serviços'; //Conferir rocka 
$dados['nomeSubfonte'] = 'Concurso Público' ; 
$dados['estruturaHierarquica'] = '\GR\CODAGE\DRH\PROCSELET'; //Conferir  
$dados['codigoConvenio'] = 0 ;  
$dados['dataVencimentoBoleto'] = null; // date("d/m/Y",strtotime("14/03/2015 00:00:00")); 
$dados['valorDocumento'] = 100.00; 
$dados['valorDesconto'] = 0; 
$dados['tipoSacado'] = 'PF'; 
$dados['cpfCnpj'] = '123'; 
$dados['nomeSacado'] = 'Fulano'; //utf8_decode
$dados['codigoEmail'] = 'thiago@usp'; //utf8_decode 
$dados['informacoesBoletoSacado'] = 'Qualquer informações que queira colocar';
$dados['instrucoesObjetoCobranca'] = 'Não receber após vencimento!'; 

?>
