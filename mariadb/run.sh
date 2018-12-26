#!/usr/bin/env bash

mysqld --innodb-flush-method=littlesync --innodb-use-native-aio=OFF --log_bin=ON
