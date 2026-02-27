pipeline {
    agent any

    environment {
        DOCKER_IMAGE = "coconutdog5321/laravel-app"
        DOCKER_TAG = "latest"
        DOCKER_CREDS = "docker-hub-creds"
    }

    stages {

        stage('Unit & Feature Test') {
            agent {
                docker {
                    image 'php:8.4-cli'
                    args '-u root'
                }
            }
            steps {
                sh """
                    apt-get update
                    apt-get install -y git unzip curl libicu-dev libsqlite3-dev sqlite3 nodejs npm
                    docker-php-ext-install intl pdo pdo_sqlite

                    curl -sS https://getcomposer.org/installer | php
                    php composer.phar install

                    npm install
                    npm run build

                    cp .env.example .env
                    php artisan key:generate

                    DB_CONNECTION=sqlite \
                    DB_DATABASE=':memory:' \
                    php artisan test
                """
            }
        }

        stage('Build & Push Docker') {
            when {
                anyOf {
                    branch 'main'
                    branch 'feature/setup-cicd'
                }
            }
            steps {
                script {
                    docker.withRegistry('https://index.docker.io/v1/', DOCKER_CREDS) {
                        def customImage = docker.build("${DOCKER_IMAGE}:${DOCKER_TAG}")
                        customImage.push()
                    }
                }
            }
        }

        stage('Deploy to K8S') {
            when {
                branch 'main'
            }
            steps {
                sh """
                    curl -LO "https://dl.k8s.io/release/\$(curl -L -s https://dl.k8s.io/release/stable.txt)/bin/linux/amd64/kubectl"
                    chmod +x kubectl
                    mv kubectl /usr/local/bin/

                    kubectl apply -f k8s/laravel-deploy.yml
                    kubectl apply -f k8s/laravel-ingress.yml
                    kubectl rollout restart deployment/laravel-app
                """
            }
        }
    }

    post {
        success {
            echo 'Deployment Successful!'
        }
        failure {
            echo 'Pipeline Failed. Please check logs.'
        }
    }
}
