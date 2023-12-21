pipeline {
    agent { docker { image 'php:5.6-cli' } }
    tools {
        jdk 'openjdk-17'
    }
    stages {
        stage('build') {
            steps {
                sh 'php --version'
            }
        }
        stage('SonarQube Analysis') {
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
