
Instruções para instalação de um servidor central LabVad (módulo labvad-centralserver)

Maurício Bomfim
Atualizado em: 25/09/2015
======================================================================================



Pre-requisito:
--------------

0) Instalar o Ubuntu (testado na versão 14.04)



Instalação do labvad-centralserver:
-----------------------------------

Para instalar o servidor central, é necessária a instalação da infra-estrutura web (apache, PHP e MySQL) e de aplicação Labvad.

1 - Instalar o Apache, o PHP e o Mysql na máquina Ubuntu:

   sudo apt-get install apache2 php5
   sudo apt-get install php5-mysql   # instala a conexão php-mysql (PDO)
   sudo service apache2 restart      # é necessário restartar o apache após instalar o PDO senão vai dar erro qdo for conectar no banco.

   sudo apt-get install mysql-server
   sudo apt-get install mysql-workbench


2 - Copiar o sistema labvad (pasta labvad-centralserver) para o Apache (na pasta /var/www ou /var/www/html, dependendo da versão do apache instalada):


ATÉ AQUI FOI FEITO


3 - Copiar o banco de dados labvad

Na máquina origem:

    Entrar no MySql Workbench / Server Administration e clicar em "Data Export"
    Selecionar o database labvad e clicar em "Start Export"
    Será salvo um arquivo com um dump do database (Dump20150129.sql)
    Transferir este arquivo para a máquina que está sendo instalada.

Na máquina Ubuntu destino:

    Entrar no MySql Workbench e conectar em localhost;
    Clicar no ícone "Create a new schema in the connected server"
    Criar o database labvad;

    Para importar o dump, executar a linha de comando:

    mysql -p --database labvad -u root </media/8245-C9A4/Dump20150129.sql   # o arquivo .sql estava num pendrive.

    criar também um usuário "webuser" com senha (para não expor a conta root) e dar permissão a este usuário para select/insert/update/delete no database labvad (Management -> User and Privileges).


4 - Definir permissões de acesso do web server (usuário www-data) às pastas e arquivos do labvad-centralserver:

    Todas as pastas, subpastas e arquivos devem ser abertos para LEITURA ao usuário www-data;


5 - Editar o arquivo /var/www/html/labvad-centralserver/app.ado/TConnection.class.php incluindo a conta e senha de acesso ao banco de dados MySql.


6 - Configurar o envio de emails

6.1 - Instalar o postfix no servidor central
    sudo apt-get install postfix
  Durante a instalação, o Postfix irá perguntar:

       - Tipo de configuração do servidor de email:  Sistema Satélite
               Use algum nome com ponto que não exista (ex: algo.com.br),

       - Nome de email do sistema:  deixe vazio
               Assim em /etc/postfix/main.cf na linha 'mydestination = ' não conterá nenhum
               nome de domínio.
               Obs: Se usar nome de domínio e o email a ser enviado possuir a mesma extensão
                        ele não será enviado para o servidor de relay, mas para a máquina local.

       - Host de relay SMTP:    smtp.nce.ufrj.br
               Ele faz relay para todos computadores que enviam email, desde que a
               que o remetente esteja na rede interna do NCE.

6.2 - Verificar o arquivo de configuração do postfix
    cd /etc/postfix
    sudo vi main.cf

    se mydestination não estiver vazio, alterar sua definição da seguinte forma:
    #mydestination = labvad2, localhost.localdomain, localhost
    mydestination =

6.3 - Reiniciar o serviço
    sudo service postfix reload










