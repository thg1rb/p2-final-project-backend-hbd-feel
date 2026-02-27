pipeline {
    agent any

    environment {
        DOCKER_IMAGE = "coconutdog5321/laravel-app"
        DOCKER_TAG = "latest"
        DOCKER_CREDS = "docker-hub-creds" // ID ที่ตั้งไว้ใน Jenkins Credentials
    }

    stages {
        // STAGE 1: Setup & Unit Test
        stage('Unit Test') {
            agent {
                docker {
                    image 'php:8.4-cli'
                    args '-u root'
                }
            }
            steps {
                sh """
                    apt-get update
                    apt-get install -y \
                        git unzip \
                        libicu-dev \
                        libsqlite3-dev \
                        sqlite3

                    docker-php-ext-install intl pdo pdo_sqlite

                    curl -sS https://getcomposer.org/installer | php
                    php composer.phar install

                    cp .env.example .env
                    sed -i 's/DB_CONNECTION=mysql/DB_CONNECTION=sqlite/g' .env
                    sed -i 's/DB_DATABASE=laravel/DB_DATABASE=:memory:/g' .env

                    php artisan key:generate
                    php artisan test
                """
            }
        }

        // STAGE 2: Build & Push Docker Image
        stage('Build & Push Docker') {
            steps {
                script {
                    docker.withRegistry('https://index.docker.io/v1/', DOCKER_CREDS) {
                        def customImage = docker.build("${DOCKER_IMAGE}:${DOCKER_TAG}")
                        customImage.push()
                    }
                }
            }
        }

        // STAGE 3: Deploy to Local K8S
        stage('Deploy to K8S') {
            steps {
                script {
                    // ใช้ kubectl ที่ติดตั้งในเครื่อง Jenkins
                    sh "kubectl apply -f k8s/laravel-deploy.yml"
                    sh "kubectl rollout restart deployment/laravel-app"
                }
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
