pipeline {
  agent any
  options { timestamps() }

  environment {
    CI_IMAGE   = "laravel-ci-image:latest"
    JENKINS_VOL = "jenkins-laravel_jenkins_home"
    WS         = "/var/jenkins_home/workspace/laravel-ci"
  }

  stages {
    stage('Checkout') {
      steps {
        sh 'pwd'
        sh 'ls -la'
        sh 'test -f composer.json && echo "✅ composer.json var" || (echo "❌ composer.json yok" && exit 1)'
      }
    }

    stage('Build CI Image') {
      steps {
        sh 'docker build -t ${CI_IMAGE} -f ci/Dockerfile.ci .'
      }
    }

    stage('Composer Install') {
      steps {
        sh '''
          docker run --rm \
            -v ${JENKINS_VOL}:/var/jenkins_home \
            -w ${WS} \
            ${CI_IMAGE} \
            composer install --no-interaction --prefer-dist
        '''
      }
    }
        stage('NPM Install & Build') {
      steps {
        sh '''
          docker run --rm \
            -v ${JENKINS_VOL}:/var/jenkins_home \
            -w ${WS} \
            node:20-alpine \
            sh -lc "npm ci && npm run build"
        '''
      }
    }

  }
}
