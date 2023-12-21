pipeline {
    agent any
    stages {
        stage('build') {
            agent { docker { image 'php:5.6-cli' } }
            steps {
                sh 'php --version'
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
