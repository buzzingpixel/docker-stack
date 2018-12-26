FROM mariadb:latest
COPY .profile /root/.profile
COPY .profile /root/.bashrc
RUN apt-get update && apt-get install -y \
    cron \
    sudo \
    git \
    && rm -rf /var/lib/apt/lists/*
RUN echo "mysql2:x:1000:50::/home/mysql:/bin/sh" >> /etc/passwd
RUN echo "mysql2 ALL=(ALL) NOPASSWD: ALL" >> /etc/sudoers
COPY run.sh /usr/local/config/run.sh
RUN chmod 0777 /usr/local/config/run.sh
COPY local.cnf /etc/mysql/conf.d/x-local.cnf
RUN chmod 0644 /etc/mysql/conf.d/x-local.cnf
ENTRYPOINT exec /usr/local/config/run.sh
