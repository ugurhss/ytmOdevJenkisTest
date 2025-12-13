pipeline {
  agent any

  options {
    timestamps()
  }

  stages {
    stage('Checkout') {
      steps {
        checkout scm
        sh 'ls -la'
      }
    }

    stage('Build (Composer)') {
      steps {
        sh '''
          if [ -f composer.json ]; then
            php -v || true
            composer -V || true
          fi
        '''
      }
    }
  }
}
