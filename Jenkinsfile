pipeline {
    agent { docker { image 'php:5.6-cli-alpine' } }
    stages {
        stage('build') {
            steps {
                sh 'php --version'
            }
        }
    }
}
