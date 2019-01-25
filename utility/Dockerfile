FROM ubuntu:latest
COPY db /root/db-starter
RUN chmod -R 0777 /root/db-starter
COPY .profile /root/.profile
COPY .profile /root/.bashrc
RUN apt-get update && apt-get install -y \
    cron \
    sudo \
    git \
    rsync \
    && rm -rf /var/lib/apt/lists/*
