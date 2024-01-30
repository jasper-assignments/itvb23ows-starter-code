pipeline {
    agent any
    stages {
        stage('Install dependencies') {
            agent { docker { image 'composer:2.6' } }
            steps {
                sh 'composer install --ignore-platform-reqs'
                stash name: 'vendor', includes: 'vendor/**'
            }
        }
        stage('Unit Tests') {
            steps {
                unstash name: 'vendor'
                sh 'vendor/bin/phpunit'
                xunit([
                    thresholds: [
                        failed ( failureThreshold: "0" ),
                        skipped ( unstableThreshold: "0" )
                    ],
                    tools: [
                        PHPUnit(pattern: 'build/logs/junit.xml', stopProcessingIfError: true, failIfNotNew: true)
                    ]
                ])
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
