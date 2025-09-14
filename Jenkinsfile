node {

    def compileTimeout = 30
    def deployTimeout = 3 	
	def composerJson = "./composer.json"
    def customImg = null;
  
	def pipelineWrapper = workbench();

    stage('Check parameters'){
        script { 
            properties([
                parameters([
                    text(name: 'DOCKER_IMAGE_NAME',
                        defaultValue:  params.DOCKER_IMAGE_NAME ?:'docker-registry.agu.gov.br/govbr/app/supp-administrativo-backend', 
                        description: 'Nome da imagem docker (sem tag) do Super',
                        trim: true)
                    ])
            ]);
        }
    }
    stage('Checkout Git') {
        checkout scm
    }

    stage('Composer dependecies') {
        timeout(time: compileTimeout) {
            sh "rm -rf composer.lock" //Removendo o composer.lock, caso tenha sido reintegrado ao repo.
            pipelineWrapper.docker.runDevelPhp("composer install --no-scripts --ansi --no-interaction");
        }
    }
  
  /*
    Nenhum estágio de teste está passando...

    stage('Tests prepare') {
        timeout(time: compileTimeout) {			
            pipelineWrapper.docker.runDevelPhp("composer install -d vendor-bin/phpunit --ansi --no-interaction");
            pipelineWrapper.docker.runDevelPhp("rm -rf var/cache && rm -rf var/log && mkdir -p var/cache && mkdir -p var/log && mkdir -p filesystem");
        }
    }
    stage('Unit Tests') {
        timeout(time: compileTimeout) {			
            pipelineWrapper.docker.runDevelPhp("php -d xdebug.remote_enable=Off -d xdebug.remote_autostart=Off vendor-bin/phpunit/vendor/phpunit/phpunit/phpunit --bootstrap ./tests/bootstrap.php --configuration phpunit.fastest.xml tests/Unit");
        }
    }
    stage('Functional Tests') {
        timeout(time: compileTimeout) {			
            pipelineWrapper.docker.runDevelPhp("php -d xdebug.remote_enable=Off -d xdebug.remote_autostart=Off vendor-bin/phpunit/vendor/phpunit/phpunit/phpunit --bootstrap ./tests/bootstrap.php --configuration phpunit.fastest.xml tests/Functional");
        }
    }
    stage('Integration Tests') {
        timeout(time: compileTimeout) {			
            pipelineWrapper.docker.runDevelPhp("php -d xdebug.remote_enable=Off -d xdebug.remote_autostart=Off vendor-bin/phpunit/vendor/phpunit/phpunit/phpunit --bootstrap ./tests/bootstrap.php --configuration phpunit.fastest.xml tests/Integration");
        }
    }
*/
/*
    //Este estágio garante que somente dependências require irão fazer parte da aplicação. Ou seja, a aplicação não depende de artefatos de teste.
    stage('Composer prod dependencies') {
        timeout(time: compileTimeout) {
            pipelineWrapper.composer.installProdDependencies();
        }
    }  
*/
    stage('Composer push') {
        if (workbench.check.isDevelopOrStagingOrMasterBranch()){
            timeout(time: deployTimeout) {            
                pipelineWrapper.composer.push();
            }
        }else{
            workbench.pipelineUtils.skipCurrentStage();
        }
	}

    stage('Docker build') {
        timeout(time: compileTimeout) {            
            customImg = pipelineWrapper.docker.buildApp(params.DOCKER_IMAGE_NAME, "-f docker/prod/DockerFile .")
        }
    }

    /*stage('Test image') {
        //Roda os testes da imagem docker com o DGOSS
        timeout(time: compileTimeout) {
                pipelineWrapper.docker.test();
            }
    }*/

    stage('Docker push') {
        if (workbench.check.isDevelopOrStagingOrMasterBranch()){
            //Publica a imagem docker no repositório privado definido no repo jenkins-shared
            timeout(time: deployTimeout) {
                pipelineWrapper.docker.push(customImg);
            }
        }else{
            workbench.pipelineUtils.skipCurrentStage();
        }
    }


	/*
    stage('Sonar QA') {
        if (workbench.check.isDevelopOrStagingOrMasterBranch()){
            timeout(time: deployTimeout) {            
                pipelineWrapper.sonar.verify();
            }
        }else{
            workbench.pipelineUtils.skipCurrentStage();
        }
	}
	*/	

    stage('Deploy') {
        if (workbench.check.isDevelopOrStagingOrMasterBranch()){
            timeout(time: deployTimeout) {
                pipelineWrapper.kubernetes.deploy("docker/prod/deployment.yaml", "composer.json");
            }
        }else{
            workbench.pipelineUtils.skipCurrentStage();
        }
    }           
}
