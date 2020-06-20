pipeline {
    agent {
        label 'php7.4'
    }

    triggers {
        cron('H H(0-3) * * *')
    }

    environment {
        GIT_COMMIT_MESSAGE = sh(script: 'git log -1 --pretty=%B ${GIT_COMMIT}', returnStdout: true).trim()
    }

    stages {
        stage('Prepare') {
            steps {
                script {
                    currentBuild.displayName = "#${BUILD_NUMBER} - ${GIT_BRANCH}"
                    currentBuild.description = "${GIT_COMMIT_MESSAGE}"
                }
            }
        }

        stage('Build') {
            parallel {
                stage('Lowest') {
                    agent {
                        label 'php7.3'
                    }

                    stages {
                        stage('Build') {
                            steps {
                                composer('update --prefer-lowest')
                            }
                        }

                        stage('Test') {
                            steps {
                                phpunit()
                            }
                        }
                    }

                    post {
                        always {
                            deleteDir()
                        }
                    }
                }

                stage('Latest') {
                    stages {
                        stage('Build') {
                            steps {
                                composer()
                            }
                        }

                        stage('Test') {
                            steps {
                                phpunit('--coverage-clover build/logs/clover.xml --log-junit build/logs/phpunit.xml')
                                archiveArtifacts artifacts: 'build/logs/*.xml', fingerprint: true
                                phpunitClover()
                            }
                        }
                    }
                }
            }
        }

        stage('QA') {
            parallel {
                stage('Lint Code') {
                    steps {
                        composer('cs-diff')
                    }
                }

                stage('Lint composer.json') {
                    steps {
                        composer('normalize')
                    }
                }

                stage('Static Analysis') {
                    steps {
                        composer('phpstan')
                    }
                }

                stage('Missing dependencies') {
                    steps {
                        composer('deps')
                    }
                }

                stage('Unused dependencies') {
                    steps {
                        composer('unused')
                    }
                }

                stage('Outdated dependencies') {
                    steps {
                        composer('outdated -D')
                    }
                }
            }
        }
    }

    post {
        always {
            deleteDir()
        }
        failure {
            slackSend message: "${env.JOB_NAME} failed in build: #${env.BUILD_NUMBER}\nBuild-Link: <${env.BUILD_URL}|Open>\nDuration: ${currentBuild.duration} ms.", channel: "#jenkins", color: "danger"
        }
        unstable {
            slackSend message: "${env.JOB_NAME} went unstable: #${env.BUILD_NUMBER}\nBuild-Link: <${env.BUILD_URL}|Open>\nDuration: ${currentBuild.duration} ms.", channel: "#jenkins", color: "warning"
        }
        fixed {
            slackSend message: "${env.JOB_NAME} is back to stable in build: #${env.BUILD_NUMBER}\nBuild-Link: <${env.BUILD_URL}|Open>\nDuration: ${currentBuild.duration} ms", channel: "#jenkins", color: "good"
        }
    }
}

def composer(String command = 'update') {
    sh "composer ${command}"
}

def phpunit(String args = '') {
    sh "vendor/bin/phpunit ${args}"
}

def phpunitClover(String logDir = 'build/logs/') {
    step([
            $class    : 'XUnitPublisher', testTimeMargin: '3000', thresholdMode: 1,
            thresholds: [
                    [$class: 'FailedThreshold', failureNewThreshold: '', failureThreshold: '0', unstableNewThreshold: '', unstableThreshold: ''],
                    [$class: 'SkippedThreshold', failureNewThreshold: '', failureThreshold: '', unstableNewThreshold: '', unstableThreshold: '']
            ],
            tools     : [[
                                 $class               : 'PHPUnitJunitHudsonTestType',
                                 deleteOutputFiles    : true,
                                 failIfNotNew         : true,
                                 pattern              : "${logDir}phpunit.xml",
                                 skipNoTestFiles      : false,
                                 stopProcessingIfError: true
                         ]]
    ])

    step([
            $class              : 'CloverPublisher',
            cloverReportDir     : "${logDir}",
            cloverReportFileName: 'clover.xml',
            healthyTarget       : [methodCoverage: 70, conditionalCoverage: 80, statementCoverage: 80],
            unhealthyTarget     : [methodCoverage: 50, conditionalCoverage: 50, statementCoverage: 50],
            failingTarget       : [methodCoverage: 0, conditionalCoverage: 0, statementCoverage: 0]
    ])
}
