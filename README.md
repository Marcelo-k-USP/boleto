Depend�ncias do PHP:

    apt-get install php php-curl 

Adicione essa biblioteca em seu projeto PHP:

    composer config repositories.boleto git https://github.com/uspdev/boleto.git
    composer require uspdev/boleto:dev-master

Essa classe, por enquanto cont�m 3 m�todos: obter, gerar e situacao. 
Para test�-los, adicione em seu arquivo PHP:

    require 'vendor/autoload.php';
    use Uspdev\Boleto;
    $boleto = new Boleto('usuario','senha'); 
    
    // array com campos m�nimos para gera��o do boleto
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

    // gerar
    $id = $boleto->gerar($data);
    echo $id;

    // situa��o
    print_r($boleto->situacao($id));

    // obter PFD
    $boleto->obter($id);
