# para a utilização das ferramentas de analise estática do código é preciso ter o php8.3 e php8.3-xml instalados na maquina local onde a IDE está rodando

# Aplicação Super/Supp (supp-backend-administrativo)#########
## Versionamento
O ciclo de versionamento da aplicação segue a convenção abaixo. A versão é definida no ``composer.json`` e será utilizada para build e provisionamento dos artefatos pelas pipelines de CI/CD.

| Fase                            | Versão            | Exemplo   |
|---------------------------------|-------------------|-----------|
| Desenvolvimento (``develop``)       | ``<version>-dev`` | 1.6.13-dev |
| Homologação (``staging``)           | ``<version>-rc``  | 1.6.12-rc  |
| Executando em produção (``master``) | ``<version>``     | 1.6.11    |


1. Enquanto a aplicação está no ciclo de desenvolvimento (brancho develop/feature/issues...) dentro a versão deve ser mantida com o sufixo ``dev``. As branchs de features, issues e similares devem ao final serem mergeadas na develop.

2. Quando a versão for fechada e liberada para avaliação do PO, deve-se mudar para o sufixo ``rc`` e aplicar o merge da develop para a branch staging. Após o merge, é importante incrementar a versão e voltar a versão para ``dev`` na branch develop. Sempre deixar a develop com a próxima release "aberta".

3. Quando for gerada uma nova versão para produção, deve-se ``remover o sufixo``, aplicar o merge na branch master e **criar a tag** correspondente à versão ``vX.Y.Z ou X.Y.Z``. Um boa prática é sempre deixar a develop como "próxima release em aberto". Por exemplo, se acabou de liberar a versão 1.6.12-rc em staging, já deixa a versão 1.6.13-dev em develop aberta versionada. 

Pode acontecer de uma versão ``rc`` não ser aprovada pelo PO. Neste caso deve-se continuar incrementando a versão. Não "voltar" a versão para dev.

## Instalação do ambiente dev no docker

****** Instalação no ubuntu 20.04 **************

 - git clone https://gitlab.agu.gov.br/supp-core/supp-backend-administrativo.git
 
 - antes de instalar adicione as credencias de acesso ao GIT.LAB no arquivo auth.json
 
 - sudo apt-get update
 - sudo apt-get install docker.io
 - sudo apt-get install docker-compose


Dentro do diretorio supp-backend-administrativo:
 1) Configurar a interface de rede que será usada pelo docker. Atenção, as configurações de sistema pressupõem que a interface de rede do docker será o IP 192.168.1.5. Verificar se há colisão com a sua rede local pois muitos roteadores usam este range de IP (192.168.x.y).
 ```json
 {
	"bip": "192.168.1.5/24"
 }
 ``` 
 2) Execute os comandos abaixo:
 ```shell
 docker-compose up php-dev
 ```
 3) acesse o endereco localhost:8000
 4) Usuário de login: joao.admin@teste.com:Agu123456 ou 00000000004:Agu123456 (Demais usuários veja ```LoadUsuarioData.php```)

# CI/CD deste módulo

## O que mudou para coloca-lo na esteira?
1) Criado o arquivo Jenkinsfile que descreve os passos da esteira
2) Deletado composer.lock e adicionado no gitignore, pois ele interfere nas builds que devem refletir o que está no compose.yaml. ``ATENÇÃO:`` A versão provisionada no repositório, após build, contem o lock file.
3) Adicionado sufixo DEV ou RC na versão para informar que é uma versão não fechada, passando a ser 1.3.0-DEV (develop) ou 1.3.0-RC (staging)
4) nginx.conf definido cache do fpm para apenas 200, 301 e 302 (fastcgi_cache_valid). 500 não irá cachear mais. Resolve a questão de debug no Kubernetes
5) nginx.conf server_name de suppbackend.agu.gov.br para "_", para deixar o ingress definir o DNS.
6) Comentando o ComposerDependeciesProd, pois as dependências de Test estão sendo usada em prod (Class "PHPUnit\Framework\TestCase" not found while loading "SuppCore\Admini strativoBackend\Utils\Tests\ContainerTestCase");
7) Criação do script ```docker/prod/create-database.sh``` para ser chamado nos deploys de integração, local e jenkins. Deste modo o banco destes ambientes (mysql?) poderão ser criados, caso não existam.


CommitManual1