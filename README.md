Dependência do PHP (testado em Ubuntu 16.04):

    apt-get install php php-curl 

Baixar nusoap:

    composer install

Rode o teste, há basicamente três métodos: gerar, situacao e getPDF:
    
    cd tests
    php Boleto.php
