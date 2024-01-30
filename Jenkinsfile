pipeline {
    agent any
    stages {
        stage('Install dependencies') {
            agent { docker { image 'composer:2.6' } }
            steps {
                sh 'composer install'
            }
        }
        stage('SonarQube Analysis') {
            tools {
                jdk 'openjdk-17'
            }
            steps {
                script {
                    def scannerHome = tool 'SonarScanner'
                    withSonarQubeEnv() {
                        sh "${scannerHome}/bin/sonar-scanner"
                    }
                }
            }
        }
    }
}
