pipeline {
  agent any
  options { timestamps() }

  environment {
    CI_IMAGE    = "laravel-ci-image:latest"
    JENKINS_VOL = "jenkins-laravel_jenkins_home"
    WS          = "/var/jenkins_home/workspace/laravel-ci"
    COMPOSE     = "docker compose -f docker-compose.app.yml -p laravelci"
  }

  stages {
    stage('Checkout') {
      steps {
        sh 'pwd'
        sh 'ls -la'
        sh 'test -f composer.json && echo "✅ composer.json var" || (echo "❌ composer.json yok" && exit 1)'
        sh 'test -f docker-compose.app.yml && echo "✅ docker-compose.app.yml var" || (echo "❌ docker-compose.app.yml yok" && exit 1)'
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

    stage('Unit Tests (JUnit)') {
      steps {
        sh '''
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
          junit allowEmptyResults: true, testResults: '**/storage/test-results/junit-unit.xml'
        }
      }
    }

    stage('Docker Up (App+DB)') {
      steps {
        sh '''
          ${COMPOSE} down -v || true
          ${COMPOSE} up -d --build

          echo "⏳ DB healthy bekleniyor..."
          for i in $(seq 1 60); do
            STATUS=$(docker inspect -f '{{if .State.Health}}{{.State.Health.Status}}{{else}}no-healthcheck{{end}}' laravelci-db-1 2>/dev/null || true)
            echo "DB health: $STATUS"
            [ "$STATUS" = "healthy" ] && break
            sleep 2
          done

          echo "⏳ APP ayağa kalkması için kısa bekleme..."
          sleep 5

          docker ps
        '''
      }
    }

    stage('Integration Tests (Feature + JUnit)') {
      steps {
        sh '''
          # app container adı compose project'e göre: laravelci-app-1
          docker exec laravelci-app-1 sh -lc "
            cd ${WS}
            mkdir -p storage/test-results
            php artisan test --testsuite=Feature --log-junit storage/test-results/junit-feature.xml
          "
        '''
      }
      post {
        always {
          junit allowEmptyResults: true, testResults: '**/storage/test-results/junit-feature.xml'
        }
      }
    }

    stage('E2E Scenarios (3 HTTP checks)') {
      steps {
        sh '''
          echo "⏳ HTTP ready bekleniyor..."
          for i in $(seq 1 60); do
            curl -fsS http://localhost:8000/ >/dev/null && break
            sleep 2
          done

          echo "✅ E2E-1: Ana sayfa 200 dönüyor"
          curl -fsS http://localhost:8000/ >/dev/null

          echo "✅ E2E-2: Login sayfası erişilebilir"
          curl -fsS http://localhost:8000/login >/dev/null

          echo "✅ E2E-3: Register sayfası erişilebilir"
          curl -fsS http://localhost:8000/register >/dev/null
        '''
      }
    }
  }

  post {
    always {
      sh '${COMPOSE} down -v || true'
    }
  }
}
