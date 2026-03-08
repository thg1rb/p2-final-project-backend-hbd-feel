FROM jenkins/jenkins:lts

USER root

# ติดตั้ง Docker CLI
RUN apt-get update && \
    apt-get install -y docker.io && \
    apt-get clean

USER jenkins
