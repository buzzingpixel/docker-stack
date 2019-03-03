FROM postgres:latest
COPY .profile /root/.profile
COPY .profile /root/.bashrc
RUN apt-get update && apt-get install -y \
    cron \
    sudo \
    git \
    && rm -rf /var/lib/apt/lists/*
