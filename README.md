## Boleto USP

Biblioteca que abstrai as chamadas do
SOAP para métodos no contexto dos boleto na USP.

Para usá-la em seu projeto PHP:

    composer require uspdev/boleto

Dependências mínimas do PHP:

    apt-get install php php-curl

Métodos disponíveis: 

 - gerar($data): recebe um array com os dados para geração do boleto (veja exemplo abaixo) e devolve outro com uma chave boleana *status* indicando se o boleto foi gerado e uma chave *value* que contém o id do boleto ou a mensagem de erro.
 - situacao($codigoIDBoleto): obtém situação do boleto 
 - obter($codigoIDBoleto): recebe uma string com id do boleto e devolve a uma string em base64 para geração do PDF. O retorno é um array com uma chave boleana *status* indicando se a string base64 foi gerada e uma chave *value* que contém tal string.
 - cancelar($codigoIDBoleto): TODO.

## Exemplo de utilização:

Para testá-los, adicione em seu arquivo PHP:

    <?php

    namespace Meu\Lindo\App;
    require_once __DIR__ . '/vendor/autoload.php';
    use Uspdev\Boleto;

	 // Inicialização do serviço com as credenciais criadas
	 // [Ambiente de DEV] = ('consumerdi','teste1')
	 // [Ambiente de PRD] = solicitar credenciais em https://servicos.sti.usp.br/ws-boleto/
    $boleto = new Boleto('usuario','senha'); 

    /* array com campos mínimos para geração do boleto */
    $data = array(
        'codigoUnidadeDespesa' => 8,
        'codigoFonteRecurso' => 32,
        'estruturaHierarquica' => '\FFLCH\SCINFOR',
        'dataVencimentoBoleto' => '10/11/2018', 
        'valorDocumento' => 18.20,
        'tipoSacado' => 'PF', 
        'cpfCnpj' => '99999999999', 
        'nomeSacado' => 'Fulano',
        'codigoEmail' => 'fulano@usp.br',  
        'informacoesBoletoSacado' => 'Qualquer informações que queira colocar',
        'instrucoesObjetoCobranca' => 'Não receber após vencimento!',
    );

	// [Método Gerar] gerar boleto
    $gerar = $boleto->gerar($data);
    if($gerar['status']) {
        $id = $gerar['value'];

		 // [Método Situacao] resgatar informações do boleto
        print_r($boleto->situacao($id));

		 // [Método Obter] recupera o arquivo PDF do boleto 
		 // (PDF no formato binário codificado para Base64)        
        $obter = $boleto->obter($codigoIDBoleto);
        
        //redirecionando os dados binarios do pdf para o browser
        header('Content-type: application/pdf'); 
        header('Content-Disposition: attachment; filename="boleto.pdf"'); 
        echo base64_decode($obter['value']);
        
       // [Método Cancelar] cancelar boleto		
		$boleto->cancelar($id);
    }
    



