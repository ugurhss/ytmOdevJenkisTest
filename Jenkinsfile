pipeline {
  agent any
  options { timestamps() }

  environment {
    CI_IMAGE    = "laravel-ci-image:latest"
    JENKINS_VOL = "jenkins-laravel_jenkins_home"
    WS          = "/var/jenkins_home/workspace/laravel-ci"

    // Her build için ayrı compose projesi -> isim çakışması biter
    COMPOSE_PROJECT_NAME = "laravelci-${BUILD_NUMBER}"
  }

  stages {
    stage('Checkout') {
      steps {
        sh '''
          set -e
          pwd
          ls -la
          test -f composer.json && echo "✅ composer.json var" || (echo "❌ composer.json yok" && exit 1)
          test -f docker-compose.app.yml && echo "✅ docker-compose.app.yml var" || (echo "❌ docker-compose.app.yml yok" && exit 1)
          test -f ci/Dockerfile.ci && echo "✅ ci/Dockerfile.ci var" || (echo "❌ ci/Dockerfile.ci yok" && exit 1)
        '''
      }
    }

    stage('Build CI Image') {
      steps {
        sh '''
          set -e
          docker build -t ${CI_IMAGE} -f ci/Dockerfile.ci .
        '''
      }
    }

    stage('Composer Install') {
      steps {
        sh '''
          set -e
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
          set -e
          docker run --rm \
            -v ${JENKINS_VOL}:/var/jenkins_home \
            -w ${WS} \
            node:20-alpine \
            sh -lc "npm ci && npm run build"
        '''
      }
    }

    stage('Unit Tests (JUnit)') {
      steps {
        sh '''
          set -e
          docker run --rm \
            -v ${JENKINS_VOL}:/var/jenkins_home \
            -w ${WS} \
            ${CI_IMAGE} \
            sh -lc "
              cp -n .env.example .env || true
              php artisan key:generate --force || true
              mkdir -p storage/test-results
              php artisan test --testsuite=Unit --log-junit storage/test-results/junit-unit.xml
            "
        '''
      }
      post {
        always {
          junit allowEmptyResults: true, testResults: 'storage/test-results/junit-unit.xml'
        }
      }
    }

    stage('Docker Up (App+DB)') {
      steps {
        sh '''
          set -e
          docker compose -p ${COMPOSE_PROJECT_NAME} -f docker-compose.app.yml up -d
          echo "⏳ DB bekleniyor..."
          for i in $(seq 1 60); do
            STATUS=$(docker inspect -f '{{if .State.Health}}{{.State.Health.Status}}{{else}}no-healthcheck{{end}}' ${COMPOSE_PROJECT_NAME}-db-1 2>/dev/null || true)
            echo "DB health: $STATUS"
            [ "$STATUS" = "healthy" ] && break
            sleep 5
          done

          echo "⏳ APP bekleniyor..."
          for i in $(seq 1 60); do
            AHS=$(docker inspect -f '{{if .State.Health}}{{.State.Health.Status}}{{else}}no-healthcheck{{end}}' ${COMPOSE_PROJECT_NAME}-app-1 2>/dev/null || true)
            echo "APP health: $AHS"
            [ "$AHS" = "healthy" ] && break
            sleep 5
          done

          docker ps
        '''
      }
    }

    stage('Integration Tests (Feature + JUnit)') {
      steps {
        sh '''
          set -e

          # Feature testler DB'ye root ile bağlanmasın diye .env.testing yazıyoruz:
          docker exec ${COMPOSE_PROJECT_NAME}-app-1 sh -lc "
            cd /app

            cat > .env.testing <<'EOF'
APP_ENV=testing
APP_DEBUG=true
APP_KEY=
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=laravel
DB_PASSWORD=secret
CACHE_DRIVER=array
SESSION_DRIVER=array
QUEUE_CONNECTION=sync
MAIL_MAILER=array
EOF

            php artisan key:generate --force --env=testing
            php artisan config:clear
            php artisan migrate:fresh --force --env=testing

            mkdir -p storage/test-results
            php artisan test --testsuite=Feature --env=testing --log-junit storage/test-results/junit-feature.xml
          "
        '''
      }
      post {
        always {
          junit allowEmptyResults: true, testResults: 'storage/test-results/junit-feature.xml'
        }
      }
    }

    stage('E2E Scenarios (3 HTTP checks)') {
      steps {
        sh '''
          set -e
          # wget yok -> php ile 3 senaryo kontrolü
          docker exec ${COMPOSE_PROJECT_NAME}-app-1 sh -lc "php -r 'exit(@file_get_contents(\"http://127.0.0.1:8000\")===false);'"
          docker exec ${COMPOSE_PROJECT_NAME}-app-1 sh -lc "php -r 'exit(@file_get_contents(\"http://127.0.0.1:8000/login\")===false);'"
          docker exec ${COMPOSE_PROJECT_NAME}-app-1 sh -lc "php -r 'exit(@file_get_contents(\"http://127.0.0.1:8000/register\")===false);'"
          echo "✅ E2E 3 HTTP senaryosu geçti"
        '''
      }
    }
  }

  post {
    always {
      sh '''
        docker compose -p ${COMPOSE_PROJECT_NAME} -f docker-compose.app.yml down -v || true
      '''
    }
  }
}
