1.0.1

* Upgrade para PHP 8, com JIT e preloading configurados
* Upgrade para composer 2.0
* Correção de bug na criação automática do protocolo e do arquivo de uma nova unidade
* Exibição correta da mensagem de erro na violação de campo único (classificação, entre outros)
* Correção para permitir a inativação de assuntos sem filhos ativos
* Correção de erro ao apagar tramitação

1.0.2

* Correçao de bug ao tentar arquivar processo em unidade como protocolo
* Correção no bug no log de consultas lentas no banco de dados no channel doctrine do kibana
* Correção de bug na criação de pasta de tarefas
* Correção de bug na transição arquivística deu erro de acesso por falta de permissão
* Correção de bug no alterão de classificação do processo que não está recomputando os prazos previstos de transição
* Correção de bug para não permitir mais de 10 níveis na árvore de classificação
* Correção de bug para não exibir mensagem completas de erro ao usuário em produção
* Correção de bug na criação de tarefas em segundo plano
* Correção de bug na geração de numero unico de documento em tabelas grandes
* Correção de bug no command que popula os componentes digitais
* Correção de bug no kibana (necessário build da imagem)
* Correção de bug na criação automática do protocolo e do arquivo de uma nova unidade

1.1.0

Documento DEPLOY.md contém as instruções de migração

* Feature etiquetas para o coordenador
* Feature destacar na interface quando estiver trabalhando no contexto de um workflow
* Feature suportar abstratamente outros formatos de NUP
* Feature para desfazer a conversão de componente digital html para pdf
* Feature para suportar novos inválidos de NUP gerados erroneamente por outros sistemas
* Feature para disponibilizar command para atribuição de perfil via console
* Correçao de bug na pesquisa elastic para suportar corretamente E OU " * etc
* Correção de rule ao salvar unidade
* Correçao de rule de juntada atual de documento
* Correção da trigger de distribuição automática de tarefas
* Correção da edição em bloco de tarefas para distribuiçao de tarefas
* Correção no elastic query build na formação das pesquisas
* Correção de bug para que um template seja criado com um conteudo default funcional
* Correção de bug para setor o enconding do filtro toUpper
* Correçao de bug na geração de p7s
* Correção da edição em bloco de tarefas para distribuiçao de tarefas

1.1.1

Documento DEPLOY.md contém as instruções de migração

* Correção de bug na indexação dos componentes digitais (necessario reindexar toda a base)
* Correção de bug no cadastro de classificação

1.1.2

Não há necessidade de procedimentos de deploy nessa versão

* Correção de bug na geração do numero único do documento
* Correção de bug na juntada de documentos em etapas subsequentes na mesma tarefa

1.1.3

Não há necessidade de procedimentos de deploy nessa versão

* Ajuste para documento que nasce com restrição de acesso
* Ajuste na classificação default para o protocolo eletrônico do usuário externo

1.2.0

Documento DEPLOY.md contém as instruções de migração

* Correção no serviço que importa o endereço dos correios a partir do CEP
* Correção na exibição de usuários disponíveis (não afastados) para recebimento de tarefas na criação de um processo na aba de distribuição
* Correção no índice do elasticsearch do processo, para permitir busca pela unidade
* Correção na juntada de mais de um componente digital no mesmo documento
* Correção no índice do elasticsearch do processo, para permitir busca pela unidade
* Correção na trigger de juntada para validar numeroSequencial no objeto restDto
* Correção na trigger de processo para validar dado antes de setar no objeto restDto
* Correção na regra de permissão de alteração de tarefas
* Correção na regra de permissão de alteração de vinculações de etiquetas
* Correção nas constraints unique para lidar com o sotfdeleteable
* Correção na ativação e na inativação de unidades e setores
* Melhoria na camada de criptografia para colocar a chave em variável de ambiente
* Melhoria para ao submerter à aprovação, a observação colocada na atividade migrar para a tarefa aberta
* Melhoria para gerar relatório em excel da listagem completa de tarefas de maneira simplificada, a partir da lista
* Melhoria para que o administrador possa transferir tarefas de usuário a ser inativado para coordenação e protocolo
* Melhoria para que o administrador possa transferir processos do setor a ser inativado para protocolo da unidade
* Melhoria para configurar a restrição acesso a processo restritos na distribuição/redistribuição de tarefas
* Melhoria no command de reinxação, para aferir automaticamente o max id do indice
* Correção no índice do elasticsearch do processo, para permitir busca pela unidade
* Correção na trigger de juntada para validar numeroSequencial no objeto restDto
* Correção na trigger de processo para validar dado antes de setar no objeto restDto
* Correção no TarefaResource para distribuição de tarefas sem tokenStorage
* Correção de regras e triggers de vinculação de processos para integração
* Ajuste de bug no TramitacaoRepository

1.2.1

Documento DEPLOY.md contém as instruções de migração

* Correção para não aplicar regras em elementos criados por integração (sem Criado Por)
* Correção para o lock de edição do componente digital ser de apenas 2 minutos
* Correção para exigir prefixo nup de setor na criação ou edição
* Correção para tratar problema legado de não existencia de prefixo nup no setor
* Correção para mostrar erro ao incluir coordenaçao duplicada [SUPERBR-202]
* Correçao na criação de unidades [SUPERBR-201]
* Correção para inserir ou editar municipios (access denied) [SUPERBR-182]
* Correção nas mensagens de validação das especies [SUPERBR-178]
* Correção no sumeter a aprovação, que não estava ocorrendo
* Correção para uso do LDAP
* Correção da trigger e rule de tramitação para processos com processo anexados/apensados
* Correção para adicionar context de transação na juntada na qual a remessa externa é recebida na mesma transação

1.3.0

Documento DEPLOY.md contém as instruções de migração

ATENÇÃO: para a assinatura digital no padrão ICP é necessário um certificado institucional válido no formato PFX.

Há alterações no supp-deploy-cicd

* Melhoria criação de cache no redis para pesquisa em entidades estáticas
* Melhoria criação do grupo de contatos
* Melhoria gerar volume automaticamente a cada X juntadas de maneira configurável
* Melhoria criação do comando para criação de tarefas para processos sem tarefas
* Melhoria criação de opção para alterar chave de acesso de processo
* Melhoria para criação de restrição de acesso para classificações
* Melhoria campo destinatario no documento
* Melhoria para limpeza do HTML antes da exibição (xss)
* Melhoria assinador digital no padrão ICP-Brasil Cades (PKCS7 dettached)]
* Melhoria criação de validação para no máximo 30 etiquetas por modalidade
* Correção download P7S 
* Correção assinatura de documento juntado #233 #227  
* Correção para tratar problema ao marcar lotação principal
* Correção em pipe de transição
* Correção no contador de senhas erradas 
* Correção no data de proxima transicao arquivistica
* Correção na processamento das transições arquivisticas
* Correção dos pipes que tratam de distribuidor e afastamentos
* Correção erro no cadastramento de processos utilizando opção de "Informar Protocolo Existente" #261
* Correção Erro ao fazer Download de um processo em ZIP/PDF #248
* Correção distribuidor não consegue distribuir tarefa automaticamente para outros colaboradores do setor #318
* Correção regras do compartilhamento #326
* Correção nos erros na Cadastramento de Processos em Testes com Usuários Simultâneos (aumento da tolerância na gestão otimista) #12 
* Correção de erro ao realizar transição arquivística #276
* Correção na mudança na Classificação do Processo no Módulo Arquivista não funciona #359
* Correção ao alterar nova senha não confere se a senha antiga está correta #161
* Correção campo adicionar etiqueta permite usuários criarem inúmeras etiquetas com mesmo nome #306
* Distribuidor não consegue distribuir tarefa automaticamente para outros colaboradores do setor #318
* Correção Erro SQL INSERT ao criar tarefas em bloco #308
* Refactoring de favoritos para segundo plano

1.3.1

* Correção erro ao cadastrar novo afastamento do usuário da unidade #153
* Correção erro ao editar um usuário da unidade #151
* Correção rule validando criação de modelos
* Correção rule validando criação de teses
* Correção rule validando criação de etiquetas
* Correção Entity VinculacaoModelo, VinculacaoEtiqueta, VinculacaoModelo
* Correção DTO VinculacaoModelo, VinculacaoEtiqueta, VinculacaoModelo, Coordenador

1.3.2

* JWT_EXP passa a ser em segundos
* Correção no refresh automatico de token
* Correção no calculo automatico de data de transição arquivistica
* Correção na aplicação de restrição de acesso por classificação
* Correção para que a senha plana vá apenas no payload da requisição, e não na URL

1.4.0

* Correção para constar URL em chancela de assinatura HTML de acordo com o ambiente
* Correção tarefa gerada pelo 'arquivar processo' perde o valor do campo 'Setor Origem' #278
* Correção municipios, não é possível inserir ou editar municipios #182
* Correção erro ao atribuir coordenador a unidade (ocorria apenas no Oracle) #396
* Correção erros no desentranhamento para o arquivo e para novo processo
* Correção cabeçalhos de documentos trazem o nome da AGU na primeira linha por padrão. #433

1.4.1

* Correção falha na Atribuição de Espécies de Setor para um Modelo de Unidade #453
* Correção falha na Atribuição de Espécie de Setor aos Modelos Nacionais #452
* Correção critério de pesquisa interessados.pessoa.numeroDocumentoPrincipal nao mapeado no elasticsearch #465
* Correção erro ao remover uma classificação #53
* Correção não é possível alterar o responsável por uma tarefa. #447
* Correção assessor, com permissão de 'Criar ofício' marcada, não consegue criar ofício na tarefa do outro usuário #284
* Correção usuários para distribuidor e afastamento
* Correção validação de arquivista para alterar classificação
* Correção ao tentar encerrar tarefa
* Correção validação de documento
* Correção atividade ao criar histórico
* Correção para validação de nups inválidos
* Correção de erro ao salvar assinatura de documentos do barramento
* Correção para salvar o protocolo como setor atual do processo
* Correção para salvar hash dos documentos em SHA-256
* Correção para buscar conteúdo dos binários utilizando download resource

1.4.2

* Não é possível alterar o responsável por uma tarefa. #447
* Arquivar processo em uma unidade marcada para receber processo somente pelo protocolo falha sem mensagem #449

1.5.0

* Importação do Módulo do Barramento para o core administrativo
* Correção importação de dados de outros processo
* Correção pesquisa notNull no ElasticSearch
* Correção regras de etiquetas
* Outras correções relevantes

1.5.1

* Correção Access Denied para o protocolo do usuário externo
* Correção na resposta de ofício por usuário externo
* Correção nas regras e validações do workflow

1.5.2

* Correção na tarefa inicial do workflow
* Correção no pipe para escolha de espécie de tarefa do workflow
* Correção na pesquisa por conteudo de documento (eliminação dos * automáticos)

1.5.3

* Melhoria para acrescentar autor, redator e destinatario no elasticsearch (documento/componente digital)
* Correção nos direitos de acesso dos usuários externos no protocolo eletrônico
* Correção para não carregar usuários inativos
* Correção para não permitir que um processo com workflow seja retirado do workflow
* Correção para não permiter a deleção de um workflow em uso
* Correção no cadastro de sigilo de minuta
* Correção para restringir o acesso aos usuários de nivel 0 aos processos sigilosos apenas nas hipóteses da LAI

1.6.0

* Melhoria para logar o agent nas estatisticas
* Melhoria para logar em produção apenas o necessário
* Correção para não exigir perfil de arquivista nas tarefas arquivisticas, pois é a transição que deve ser bloqueada, e não a tarefa
* Correção para regra "apenas protocolo" não se aplicar a NUPs com acesso restrito
* Correção de erro ao incluir ação de workflow
* Melhoria para juntar termo de desentranhamento
* Correção na criação e remoção de minutas em tarefas compartilhadas

1.6.1

* Correção no contador da lixeira

1.6.2

* Melhoria para marcar no banco a indexação de objetos no elasticsearch

1.6.3

* Correção de erro na exibição e edição de documentos

1.6.4

* Correção no protocolo de pasta/dossiê
* Correção na comparação de versão de minutas
* Correção na movimentação do processo após a inclusão de anexos
* Correção no recebimento de processo pelo barramento
* Correção na rule de vinculação de etiqueta para resposta de ofício

1.6.5

* Correção na ferramenta aprovar que gera um despacho anexo
* Melhoria de inserção de novos relatórios [LISTAGEM COMPLETA DO PLANO DE CLASSIFICAÇÃO, 
LISTAGEM PARCIAL DO PLANO DE CLASSIFICAÇÃO, 
LISTAGEM DE PROCESSOS POR CLASSIFICAÇÃO EM UM PERÍODO DE TEMPO, 
LISTAGEM DE PROCESSOS POR CLASSIFICAÇÃO E SETOR ATUAL EM UM PERÍODO DE TEMPO,
LISTAGEM DE PROCESSOS POR CLASSIFICAÇÃO E SETOR INICIAL EM UM PERÍODO DE TEMPO, 
LISTAGEM COMPLETA DA TABELA DE TEMPORALIDADE,
LISTAGEM PARCIAL DA TABELA DE TEMPORALIDADE,
LISTAGEM DE PROCESSOS CADASTRADOS COM UM DETERMINADO PRAZO DE GUARDA CORRENTE EM ANOS,
LISTAGEM DO FLUXO DE TRAMITAÇÕES DE UM PROCESSO,
LISTAGEM DO FLUXO DE TAREFAS DE UM PROCESSO,
LISTAGEM DO HISTÓRICO DE CLASSIFICAÇÕES DE UM PROCESSO,
LISTAGEM DE TRANSAÇÕES REALIZADAS EM UM PROCESSO]
* Melhoria para exibir corretamente o status da geração de um relatório
* Melhoria para transformar o html potencialmente inseguro (xss) em pdf para fins de visualização

1.6.6

* Melhoria para resincronizar os componentes digitais de processo do barramento
* Correção para inclusão de nova pessoa com vinculação da pessoa com barramento
* Correção na pesquisa de estrutura e repositorio através do barramento
* Correção editar conteúdo modelos
* Correção de envio de email de anexos de documentos

1.6.7

* Correção bug rules modelos
* Correção bug ao gerar relatório
* Correção bug de relatórios gerados em planilha
* Correção mensagem recusa barramento para extensões inválidas
* Correção bug para inativar juntada do barramento
* Correção não é possível editar uma espécie de setor de uma Tese Nacional #122
* Correção para permitir apenas a remessa de processos administrativos pelo barramento

1.6.8

* Correção de rule para não permitir envio de processos diferentes do administrativo
* Correção entity Coordenador SoftDeletable
* Correção Barramento para busca particionada de grandes componentes digitais
* Correção Barramento rule de envio de processos
* Correção Barramento no recebimento de processo anexo
* Correção bug geração de dossies automáticos
* Correção assert data de obito e lingua entity pessoa
* Correção da indexação de índices para o elasticsearch

1.6.10

* Correção das rules do cálculo automático data de próxima transição arquivística

1.6.11

* Correção rule compartilhamento de tarefa
* Correção mensagem de erro ao apagar Coordenador sem permissão
* Correção bug juntada de minutas selecionadas ao encerrar tarefa
* Melhoria de escolha de metadados para o Imprimir Relatório

1.6.12

* Correção no Criar Cópia da Juntada
* Correção de erro ao submeter minuta para aprovação
* Correção ao submeter à aprovação tarefa com juntadas anteriores
* Correção erro rule 13 de tarefa ao editar tarefa de lotação antiga
* Correção para não permitir dar ciência em tarefa com minutas
* Correção para não permitir mudança de modelos em documento assinado
* Correçao rule para lançar atividades em tarefa com outro responsavel
* Melhoria rule para limitar em 5 a quantidade de etiquetas privadas por tarefa
* Melhoria para permitir até 100 etiquetas por escopo de vinculacao
* Correção para não permitir juntadas na fase INTERMEDIARIA (NUP encerrado)
* Melhoria criação de roles para a modalidadeColaborador e Cargo do usuário
* Correção download parcial de processo para juntadas vinculadas
* Correção atribuição de acls para classificação e tipo de relatórios

1.6.12

* Melhoria rule para não permitir a conversão em PDF de minuta assinada

1.7.0

* Correção no download completo de processo em PDF e ZIP
* Alteração do Redis para o KeyDB (Há mudanças no Prod, favor verificiar o supp-deploy-cicd)
* Correção de bug ao gerar sigilo para documentos
* Correção para tipo do despacho de aprovação e numeração
* Correção resposta de ofício criação de tarefa

1.8.0

* Correção ao submeter minutas para aprovação
* Atualização elasticsearch, logstash e kibana para 7.15.2 (rebuild de imagens é necessario)
* Correção de bug ao conceder perfil via console (command)
* Correção está permitindo selecionar e salvar um usuário com nível de acesso negativo #690
* Correção não está realizando a abertura de uma tarefa automaticamente para o protocolo, deixando-a no limbo. #699
* Clonar as assinaturas ao clonar um documento
* Permitir reprocessar um documento já juntado como se fosse um modelo
* Criar um Administrador default 00000000000 quando uma base de produção for criado do zero
* Correção Rule0006 de Desentranhamento
* Correção Sistema está permitindo movimentar uma tarefa que já foi concluída #755
* Correção ao tentar vincular filho como pai no cadastro de 'Setores' da 'Unidade'. #754
* Erro ao tentar recuperar senha por usuário externo #756

1.8.1

* Exibe o prazo da tarefa vencida ou prestes a vencer em vermelho
* Upgrade elasticsearch, kibana e logstash para 7.17.0

1.8.2

* O assinador A1 suporta a configução de proxies
* O download as P7S agora entrega um aquivo unico CADES Atached .p7s

1.8.3

* Correção erro na criação de dossies
* Correção Rule0006 de Compartilhamento
* Correção Rule 19 de tarefa para validar criação de tarefa para processos sem assuntos
* Correção de recebimento de componentes digitais barramento
* Melhoria no tratamento de mensagens de erros do barramento
* Correção para validar envio de documentos sem extensão barramento
* Correção de erro para criação de minutas em processos sem assuntos 

1.8.4

* Correção no uso de custom mappers
* Correção na geracao de nups (providers)
* Correção para clonar a assinatura ao clonar o documento principal

1.8.5

* Correção para buscar o provider de processo com 15 dígitos
* Correção de rule para lançamento de atividade com minuta
* Criação de rule para atualização do label da vinculacao etiqueta
* Correção para criar Colaborador com usuário que só tem a ROLE_ADMIN
* Ajuste para recebimento de componentes digitais no barramento
* Correção trigger submeter tarefa para aprovação com várias minutas
* Correção proxy no assinador A1

1.8.6

* Correção envio de endereços para interessados barramento
* Correção envio de múltiplos componentes digitais barramento
* Correção para envio de anexo de modelos 
* Correção restauração de documento deletado por tarefa compartilhada
* Correção regras e ações das etiquetas
* Correção usuário habilitado como assessor não consegue dar ciência. #751
* Correção envio de dados de interessado barramento

1.8.7

* Correção bug data hora criação de processo com nup existente
* Correção bug anexar cópia de documentos
* Correção bug Setor Origem para despacho aprovação
* Correção de verificação de assinatura de componente digital
* Correção mensagem de erro para restrição de acesso para documentos
* Correção para juntar todas as minutas da resposta de ofício interno

1.8.8

* Resposta de documento avulso não deve ativar regra apenas distribuidor
* Regra para remoção de assinatura deve levar em consideração quem efetivamente assinou
* No caso de assinatura A3, apenas quem assinou consegue retirar a assinatura

1.8.9

* Correção de bugs e atualização de dependencias

1.8.10

* Correção de bugs e atualização de dependencias

1.8.11

* Correção de bugs e atualização de dependencias
* Não é possível excluir um modelo, apenas inativá-lo
* Inclusão no elastic do campo pessoaConveniada no index pessoa (será preciso reindexar a base)

1.8.12

* Correção de bugs e atualização de dependencias

1.8.13

* Correção de bug na assinatura do usuario que cria minuta em tarefa compartilhada
* Correção de bug na criação de unidade pelo coordenador de orgão central
* Correção para validar PDF com bytes iniciais diferentes
* Nova funcionalidade para registrar um histórico para o mero acesso/visualizacao de um processo
* Correção para permitir que o coordenador, tambem acessor sem poderes para tanto, encerre a tarefa

1.9.0

* Correção para apagar os arquivos .pdf que ficam no /tmp apos o download do processo
* Correção para impedir a geração de PDFs completos muito grandes
* Correção de erros na geração de relatórios
* Correção de acesso negado para visualização de processos para usuário externo
* Correção para ao anexar cópia de documento manter o tipo de documento no documento de destino
* Correção para anexar na minuta criada a partir de modelo os anexos desde ultimo
* Funcionalidade de configuração de cronjobs
* Funcionalidade para escolher apenas uma ação a ser disparada nas etiquetas automatizadas
* Correção de bug para não retornar os modelos e teses inativas
* Correção de bug na ordenação de pesquisas de modelos e componentes digitais

1.9.1

* Correção de bugs e atualização de dependencias

1.9.2

* Correção de bugs e atualização de dependencias

1.9.3

* Correção de bugs e atualização de dependencias

1.10.0

* Correção de bugs e atualização de dependencias

1.11.0

* Erro de sintaxe na mensagem de alteração da classificação de um processo. #952
* Atualização para o symfony 6.2
* Correção de bugs e atualização de dependencias

1.23.0

* Configurar tanto o CN institucional quanto o CNPJ institucional na configuração do módulo de Assinatura
