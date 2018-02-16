API para uso de boleto na USP. Para usá-la em seu projeto PHP, adicioná-a com
o composer:

    composer config repositories.boleto git https://github.com/uspdev/boleto.git
    composer require uspdev/boleto:dev-master

Dependências do PHP:

    apt-get install php php-curl 

Essa classe, por enquanto contém 3 métodos: obter, gerar e situacao. 
Para testá-los, adicione em seu arquivo PHP:

    require 'vendor/autoload.php';
    use Uspdev\Boleto;
    $boleto = new Boleto('usuario','senha'); 
    
    //array com campos mínimos para geração do boleto
    $data = array(
        'codigoUnidadeDespesa' => 8,
        'nomeFonte' => 'Taxas', 
        'nomeSubfonte' => 'Congressos/Seminários/Palestras/Simpósios' , 
        'estruturaHierarquica' => '\FFLCH\SCINFOR',   
        'codigoConvenio' => 0 ,  
        'dataVencimentoBoleto' => '10/11/2018', 
        'valorDocumento' => 18.20, 
        'valorDesconto' => 0, 
        'tipoSacado' => 'PF', 
        'cpfCnpj' => '99999999999', 
        'nomeSacado' => 'Fulano',
        'codigoEmail' => 'fulano@usp.br',  
        'informacoesBoletoSacado' => 'Qualquer informações que queira colocar',
        'instrucoesObjetoCobranca' => 'Não receber após vencimento!'
    );

    // gerar
    $id = $boleto->gerar($data);
    echo $id;

    // situação
    print_r($boleto->situacao($id));

    // obter PFD
    $boleto->obter($id);
