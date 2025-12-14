pipeline {
  agent any
  options { timestamps() }

  environment {
    CI_IMAGE = "laravel-ci-image:latest"

    // Jenkins HOME Docker volume adı (senin setup)
    JENKINS_VOL = "jenkins-laravel_jenkins_home"

    // Jenkins container içindeki workspace (Job adı: laravel-ci)
    WS = "/var/jenkins_home/workspace/laravel-ci"

    // Her build için ayrı compose projesi
    COMPOSE_PROJECT_NAME = "laravelci-${BUILD_NUMBER}"
  }

  stages {

    stage('Checkout') {
      steps {
        sh '''
          set -e
          echo "== Checkout Debug =="
          pwd
          ls -la

          test -f composer.json && echo "✅ composer.json var" || (echo "❌ composer.json yok" && exit 1)
          test -f docker-compose.app.yml && echo "✅ docker-compose.app.yml var" || (echo "❌ docker-compose.app.yml yok" && exit 1)
          test -f ci/Dockerfile.ci && echo "✅ ci/Dockerfile.ci var" || (echo "❌ ci/Dockerfile.ci yok" && exit 1)

          echo "== Docker version =="
          docker version

          echo "== Compose version =="
          docker compose version || docker-compose version
        '''
      }
    }

    stage('Build CI Image') {
      steps {
        sh '''
          set -e
          echo "== Build CI Image =="
          docker build -t ${CI_IMAGE} -f ci/Dockerfile.ci .
        '''
      }
    }

    stage('Composer Install') {
      steps {
        sh '''
          set -e
          echo "== Composer Install =="
          docker run --rm \
            -v ${JENKINS_VOL}:/var/jenkins_home \
            -w ${WS} \
            ${CI_IMAGE} \
            sh -lc "pwd && ls -la && composer install --no-interaction --prefer-dist"
        '''
      }
    }

    stage('NPM Install & Build') {
      steps {
        sh '''
          set -e
          echo "== NPM Install & Build =="
          docker run --rm \
            -v ${JENKINS_VOL}:/var/jenkins_home \
            -w ${WS} \
            node:20-alpine \
            sh -lc "pwd && ls -la && npm ci && npm run build"
        '''
      }
    }

    stage('Unit Tests (JUnit)') {
      steps {
        sh '''
          set -e
          echo "== Unit Tests =="
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
          echo "== Docker Up (App+DB) =="

          cd ${WS}
          echo "PWD=$(pwd)"
          ls -la docker-compose.app.yml
          # Jenkins volume içindeki gerçek host path'i bulup compose'a APP_SOURCE olarak iletiyoruz
          HOST_WS=$(docker volume inspect ${JENKINS_VOL} -f '{{.Mountpoint}}')
          APP_SOURCE="$HOST_WS/workspace/laravel-ci"
          export APP_SOURCE
          echo "APP_SOURCE=$APP_SOURCE"

          echo "-> compose up"
          docker compose -p ${COMPOSE_PROJECT_NAME} -f docker-compose.app.yml up -d

          echo "-> compose ps"
          docker compose -p ${COMPOSE_PROJECT_NAME} -f docker-compose.app.yml ps || true

          DB_CID=$(docker compose -p ${COMPOSE_PROJECT_NAME} -f docker-compose.app.yml ps -q db || true)
          APP_CID=$(docker compose -p ${COMPOSE_PROJECT_NAME} -f docker-compose.app.yml ps -q app || true)

          echo "DB_CID=$DB_CID"
          echo "APP_CID=$APP_CID"

          echo "-> docker ps (host)"
          docker ps || true

          # DB yoksa direkt fail + log
          if [ -z "$DB_CID" ]; then
            echo "❌ DB container bulunamadı."
            docker compose -p ${COMPOSE_PROJECT_NAME} -f docker-compose.app.yml ps || true
            docker compose -p ${COMPOSE_PROJECT_NAME} -f docker-compose.app.yml logs db --tail=200 || true
            exit 1
          fi

          echo "⏳ DB health bekleniyor..."
          # 5 saniyelik bekleme yerine 2 saniye ile daha hızlı feedback alıyoruz
          for i in $(seq 1 60); do
            STATUS=$(docker inspect -f '{{if .State.Health}}{{.State.Health.Status}}{{else}}no-healthcheck{{end}}' "$DB_CID" 2>/dev/null || true)
            echo "DB health: $STATUS"
            [ "$STATUS" = "healthy" ] && break
            sleep 2
          done

          # APP yoksa log bas ve fail (app crash olmuştur)
          if [ -z "$APP_CID" ]; then
            echo "❌ APP container bulunamadı (muhtemelen crash)."
            echo "---- compose ps ----"
            docker compose -p ${COMPOSE_PROJECT_NAME} -f docker-compose.app.yml ps || true
            echo "---- compose logs (app) ----"
            docker compose -p ${COMPOSE_PROJECT_NAME} -f docker-compose.app.yml logs app --tail=200 || true
            echo "---- compose logs (db) ----"
            docker compose -p ${COMPOSE_PROJECT_NAME} -f docker-compose.app.yml logs db --tail=200 || true
            exit 1
          fi

          echo "⏳ APP health bekleniyor..."
          # APP sağlığı için de bekleme süresi 2 saniyeye indirildi
          for i in $(seq 1 60); do
            AHS=$(docker inspect -f '{{if .State.Health}}{{.State.Health.Status}}{{else}}no-healthcheck{{end}}' "$APP_CID" 2>/dev/null || true)
            echo "APP health: $AHS"
            [ "$AHS" = "healthy" ] && break
            sleep 2
          done

          echo "== HEALTHCHECK SON DURUM =="
          docker compose -p ${COMPOSE_PROJECT_NAME} -f docker-compose.app.yml ps || true
        '''
      }
    }

    stage('DB Migrate (Controlled)') {
      steps {
        sh '''
          set -e
          echo "== DB Migrate (Controlled) =="

          cd ${WS}
          # Compose çağrılarında aynı host path'ini tekrar export ediyoruz
          HOST_WS=$(docker volume inspect ${JENKINS_VOL} -f '{{.Mountpoint}}')
          APP_SOURCE="$HOST_WS/workspace/laravel-ci"
          export APP_SOURCE
          echo "APP_SOURCE=$APP_SOURCE"
          APP_CID=$(docker compose -p ${COMPOSE_PROJECT_NAME} -f docker-compose.app.yml ps -q app || true)

          if [ -z "$APP_CID" ]; then
            echo "❌ Migration öncesi APP container yok."
            docker compose -p ${COMPOSE_PROJECT_NAME} -f docker-compose.app.yml ps || true
            docker compose -p ${COMPOSE_PROJECT_NAME} -f docker-compose.app.yml logs app --tail=200 || true
            exit 1
          fi

          echo "-> migrate:fresh"
          docker exec "$APP_CID" sh -lc "
            cd /app
            php artisan migrate:fresh --force
          "
        '''
      }
    }

    stage('Integration Tests (Feature + JUnit)') {
      steps {
        sh '''
          set -e
          echo "== Integration Tests (Feature) =="

          cd ${WS}
          # APP_SOURCE olmadan container içinde proje dosyaları bulunamıyor
          HOST_WS=$(docker volume inspect ${JENKINS_VOL} -f '{{.Mountpoint}}')
          APP_SOURCE="$HOST_WS/workspace/laravel-ci"
          export APP_SOURCE
          echo "APP_SOURCE=$APP_SOURCE"
          APP_CID=$(docker compose -p ${COMPOSE_PROJECT_NAME} -f docker-compose.app.yml ps -q app || true)

          if [ -z "$APP_CID" ]; then
            echo "❌ Feature test öncesi APP container yok."
            docker compose -p ${COMPOSE_PROJECT_NAME} -f docker-compose.app.yml ps || true
            docker compose -p ${COMPOSE_PROJECT_NAME} -f docker-compose.app.yml logs app --tail=200 || true
            exit 1
          fi

          docker exec "$APP_CID" sh -lc "
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
          echo "== E2E Scenarios (3 HTTP checks) =="

          cd ${WS}
          # E2E öncesinde de aynı mount ayarı gerekli
          HOST_WS=$(docker volume inspect ${JENKINS_VOL} -f '{{.Mountpoint}}')
          APP_SOURCE="$HOST_WS/workspace/laravel-ci"
          export APP_SOURCE
          echo "APP_SOURCE=$APP_SOURCE"
          APP_CID=$(docker compose -p ${COMPOSE_PROJECT_NAME} -f docker-compose.app.yml ps -q app || true)

          if [ -z "$APP_CID" ]; then
            echo "❌ E2E öncesi APP container yok."
            docker compose -p ${COMPOSE_PROJECT_NAME} -f docker-compose.app.yml ps || true
            docker compose -p ${COMPOSE_PROJECT_NAME} -f docker-compose.app.yml logs app --tail=200 || true
            exit 1
          fi

          # container içinden localhost’a 3 senaryo (tek PHP scripti ile)
          docker exec "$APP_CID" sh -lc 'cat <<"PHP" >/tmp/http-check.php
<?php
$urls = explode(" ", trim(getenv("CHECK_URLS") ?: ""));
if (!$urls) {
    fwrite(STDERR, "No URLs provided for health check\n");
    exit(1);
}
foreach ($urls as $url) {
    if ($url === "") continue;
    fwrite(STDOUT, "Checking $url ... ");
    if (@file_get_contents($url) === false) {
        fwrite(STDOUT, "FAILED\n");
        exit(1);
    }
    fwrite(STDOUT, "OK\n");
}
PHP
CHECK_URLS="http://127.0.0.1:8000 http://127.0.0.1:8000/login http://127.0.0.1:8000/register" php /tmp/http-check.php
'

          echo "✅ E2E 3 HTTP senaryosu geçti"
        '''
      }
    }
  }

  post {
    always {
      sh '''
        echo "== Post: Cleanup =="
        cd ${WS} || true
        if docker volume inspect ${JENKINS_VOL} >/dev/null 2>&1; then
          # Cleanup sırasında bile APP_SOURCE'ı host path'i ile senkron tut
          HOST_WS=$(docker volume inspect ${JENKINS_VOL} -f '{{.Mountpoint}}')
          APP_SOURCE="$HOST_WS/workspace/laravel-ci"
          export APP_SOURCE
          echo "APP_SOURCE=$APP_SOURCE"
        else
          echo "⚠️ Jenkins volume ${JENKINS_VOL} inspect failed; using default APP_SOURCE"
        fi
        docker compose -p ${COMPOSE_PROJECT_NAME} -f docker-compose.app.yml ps || true
        docker compose -p ${COMPOSE_PROJECT_NAME} -f docker-compose.app.yml logs app --tail=120 || true
        docker compose -p ${COMPOSE_PROJECT_NAME} -f docker-compose.app.yml logs db --tail=120 || true
        docker compose -p ${COMPOSE_PROJECT_NAME} -f docker-compose.app.yml down -v || true
      '''
    }
  }
}
