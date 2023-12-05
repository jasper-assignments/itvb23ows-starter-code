FROM mysql:8.0

COPY ./hive.sql /hive.sql

CMD [ "mysqld", "--init-file=/hive.sql", "--character-set-server=utf8mb4", "--collation-server=utf8mb4_unicode_ci", "--default-authentication-plugin=mysql_native_password" ]
