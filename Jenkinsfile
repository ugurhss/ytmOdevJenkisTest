pipeline {
  agent any
  options { timestamps() }

  environment {
    CI_IMAGE    = "laravel-ci-image:latest"
    JENKINS_VOL = "jenkins-laravel_jenkins_home"
    WS          = "/var/jenkins_home/workspace/laravel-ci"
  }

  stages {

    stage('Checkout') {
      steps {
        checkout scm
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
          junit allowEmptyResults: true, testResults: 'storage/test-results/junit-unit.xml'
        }
      }
    }

    stage('Docker Up (App+DB)') {
      steps {
        sh '''
          docker-compose -f docker-compose.app.yml up -d

          echo "⏳ DB healthy bekleniyor..."
          for i in $(seq 1 30); do
            STATUS=$(docker inspect -f '{{if .State.Health}}{{.State.Health.Status}}{{else}}no-healthcheck{{end}}' laravel_db 2>/dev/null || true)
            echo "DB status: $STATUS"
            [ "$STATUS" = "healthy" ] && echo "✅ DB healthy" && break
            sleep 5
          done

          echo "⏳ APP running bekleniyor..."
          for i in $(seq 1 30); do
            RUNNING=$(docker inspect -f '{{.State.Running}}' laravel_app 2>/dev/null || echo false)
            echo "APP running=$RUNNING ($i/30)"
            [ "$RUNNING" = "true" ] && echo "✅ APP running" && break
            sleep 5
          done

          echo "⏳ HTTP kontrolü (app cevap veriyor mu?)..."
          for i in $(seq 1 30); do
            if docker exec laravel_app sh -lc "wget -qO- http://127.0.0.1:8000/ >/dev/null"; then
              echo "✅ APP HTTP OK"
              break
            fi
            sleep 5
          done

          docker ps
        '''
      }
    }

    stage('Integration Tests (Feature + JUnit)') {
      steps {
        sh '''
          docker exec laravel_app sh -lc "
            cd /var/jenkins_home/workspace/laravel-ci
            mkdir -p storage/test-results
            php artisan test --testsuite=Feature --log-junit storage/test-results/junit-feature.xml
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
          docker exec laravel_app sh -lc "wget -qO- http://127.0.0.1:8000/ >/dev/null"
          docker exec laravel_app sh -lc "wget -qO- http://127.0.0.1:8000/login >/dev/null"
          docker exec laravel_app sh -lc "wget -qO- http://127.0.0.1:8000/groups >/dev/null"
          echo "✅ 3 adet E2E HTTP senaryosu OK"
        '''
      }
    }

  }

  post {
    always {
      sh 'docker-compose -f docker-compose.app.yml down -v || true'
    }
  }
}
