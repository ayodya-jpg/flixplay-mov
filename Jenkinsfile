pipeline {
    agent any

    environment {
        REGISTRY_NAME = 'dockerlixreg.azurecr.io'
        IMAGE_NAME    = 'flixplay'
        IMAGE_TAG     = "${env.BUILD_NUMBER}" // Menggunakan nomor build Jenkins sebagai tag
        AZURE_CREDENTIALS_ID = 'acr-auth' // ID yang Anda buat di Jenkins Credentials
    }

    stages {
        stage('Build Docker Image') {
            steps {
                script {
                    // Build image menggunakan Dockerfile yang sudah diperbaiki
                    sh "docker build -t ${REGISTRY_NAME}/${IMAGE_NAME}:${IMAGE_TAG} ."
                    sh "docker tag ${REGISTRY_NAME}/${IMAGE_NAME}:${IMAGE_TAG} ${REGISTRY_NAME}/${IMAGE_NAME}:latest"
                }
            }
        }

        stage('Push to Azure Container Registry') {
            steps {
                script {
                    // Gunakan kredensial Jenkins untuk login dan push
                    withCredentials([usernamePassword(credentialsId: AZURE_CREDENTIALS_ID,
                                     usernameVariable: 'ACR_USER',
                                     passwordVariable: 'ACR_PASS')]) {
                        sh "docker login ${REGISTRY_NAME} -u ${ACR_USER} -p ${ACR_PASS}"
                        sh "docker push ${REGISTRY_NAME}/${IMAGE_NAME}:${IMAGE_TAG}"
                        sh "docker push ${REGISTRY_NAME}/${IMAGE_NAME}:latest"
                    }
                }
            }
        }
    }
}
