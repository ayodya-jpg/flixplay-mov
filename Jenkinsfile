pipeline {
    agent any

    environment {
        // Konfigurasi ACR Anda
        REGISTRY_URL = 'dockerlixreg.azurecr.io'
        IMAGE_NAME   = 'flixplay'

        // ID ini nanti kita buat di Dashboard Jenkins
        DOCKER_CRED_ID = 'acr-credentials'
    }

    stages {
        stage('Checkout') {
            steps {
                // Langkah 1: Tarik kode terbaru dari GitHub
                checkout scm
            }
        }

        stage('Build Docker Image') {
            steps {
                script {
                    echo '--- Building Docker Image ---'
                    // Build image dengan tag 'latest' dan nomor build (versi)
                    sh "docker build -t $REGISTRY_URL/$IMAGE_NAME:latestjens ."
                    sh "docker build -t $REGISTRY_URL/$IMAGE_NAME:${BUILD_NUMBER} ."
                }
            }
        }

        stage('Login to ACR') {
            steps {
                script {
                    echo '--- Logging in to Azure Container Registry ---'
                    // Mengambil username/password aman dari Jenkins Credentials
                    withCredentials([usernamePassword(credentialsId: DOCKER_CRED_ID, usernameVariable: 'ACR_USER', passwordVariable: 'ACR_PASS')]) {
                        sh "docker login $REGISTRY_URL -u $ACR_USER -p $ACR_PASS"
                    }
                }
            }
        }

        stage('Push Image') {
            steps {
                script {
                    echo '--- Pushing Image to ACR ---'
                    // Push ke Azure
                    sh "docker push $REGISTRY_URL/$IMAGE_NAME:latestjens"
                    sh "docker push $REGISTRY_URL/$IMAGE_NAME:${BUILD_NUMBER}"
                }
            }
        }
    }

    post {
        always {
            // Bersihkan sampah image di server Jenkins agar storage tidak penuh
            sh "docker logout $REGISTRY_URL"
            sh "docker rmi $REGISTRY_URL/$IMAGE_NAME:latestjens || true"
            sh "docker rmi $REGISTRY_URL/$IMAGE_NAME:${BUILD_NUMBER} || true"
        }
    }
}
