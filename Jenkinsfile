pipeline {
  agent any
  options { timestamps() }

  environment {
    CI_IMAGE = "laravel-ci-image:latest"
  }

  stages {
    stage('Checkout') {
      steps {
        // Pipeline script from SCM kullanıyorsan bu zaten checkout yapıyor.
        // Yine de garanti olsun diye workspace içeriğini gösteriyoruz.
        sh 'pwd'
        sh 'ls -la'
        sh 'test -f composer.json && echo "✅ composer.json var" || (echo "❌ composer.json yok" && exit 1)'
        sh 'test -d ci && echo "✅ ci klasörü var" || (echo "❌ ci klasörü yok" && exit 1)'
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
            -v "$WORKSPACE":/app \
            -w /app \
            ${CI_IMAGE} \
            composer install --no-interaction --prefer-dist
        '''
      }
    }
  }
}
