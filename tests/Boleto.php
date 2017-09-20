<?php

require('../src/Boleto.php');

// Campos necess�rios para instanciar o webservice:
$data = array(
    'codigoUnidadeDespesa' => 8,
    'nomeFonte' => 'Taxas', 
    'nomeSubfonte' => 'Congressos/Semin�rios/Palestras/Simp�sios' , 
    'estruturaHierarquica' => '\FFLCH\SCINFOR',   
    'codigoConvenio' => 0 ,  
    'dataVencimentoBoleto' => '10/11/2018', 
    'valorDocumento' => 18.20, 
    'valorDesconto' => 0, 
    'tipoSacado' => 'PF', 
    'cpfCnpj' => '99999999999', 
    'nomeSacado' => 'Fulano',
    'codigoEmail' => 'fulano@usp.br',  
    'informacoesBoletoSacado' => 'Qualquer informa��es que queira colocar',
    'instrucoesObjetoCobranca' => 'N�o receber ap�s vencimento!'
);

$boleto = new Boleto('fflch','YOUR TOKEN', '/home/thiago/repos/php_boletousp/src/wsdl/prod.wsdl');
$id = $boleto->gerar($data);
echo $id;
echo "<pre>";
print_r($boleto->situacao($id));

//$boleto->getPDF($id);

?>
