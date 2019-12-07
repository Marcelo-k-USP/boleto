API para uso de boleto na USP. Para usá-la em seu projeto PHP, com o composer rode:

    composer require uspdev/boleto

Dependências do PHP (testado com php7.2):

    apt-get install php php-curl 

Essa classe, por enquanto contém 3 métodos: obter, gerar e situacao. 
Para testá-los, adicione em seu arquivo PHP:

    <?php
    namespace Meu\Lindo\App;
    require_once __DIR__ . '/vendor/autoload.php';
    use Uspdev\Boleto;
    $boleto = new Boleto('usuario','senha'); 
    
    //array com campos mínimos para geração do boleto
    $data = array(
        'codigoUnidadeDespesa' => 8,
       /* 'codigoFonteRecurso' => 32, Por enquanto isso não funciona */
        'nomeFonte' => '@codftercs', // Temporário
        'nomeSubFonte' => 32, // Temporário
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
        'instrucoesObjetoCobranca' => 'Não receber após vencimento!',
    );

    /* O método gerar() retorna um array com dois indices:
       status: true ou false indicando se o boleto foi ou não gerado
       value: o id do boleto gerado ou a mensagem de erro 
    */
    // gerar
    $r = $boleto->gerar($data);
    if($r['status']) echo $r['value'];

    // situação
    print_r($boleto->situacao($r['value']));

    // obter o PDF:
    // $boleto->obter($r['value']);
