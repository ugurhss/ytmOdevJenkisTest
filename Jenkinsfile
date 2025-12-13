pipeline {
  agent any
  options { timestamps() }

  environment {
    CI_IMAGE = "laravel-ci-image:latest"
  }

  stages {
    stage('Checkout') {
      steps {
        checkout scm
        sh 'ls -la'
      }
    }

    stage('Build CI Image') {
      steps {
        sh '''
          docker build -t ${CI_IMAGE} -f ci/Dockerfile.ci .
        '''
      }
    }

    stage('Composer Install') {
      steps {
        sh '''
          docker run --rm \
            -v "$PWD":/app \
            -w /app \
            ${CI_IMAGE} \
            composer install --no-interaction --prefer-dist
        '''
      }
    }
  }
}
