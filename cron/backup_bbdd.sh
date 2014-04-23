mysqldump -uroot -proot01 --opt instituto_access | gzip > "/home/server-turnos/backups/instituto.$(date +%F_%T).sql.gz" 2> /home/server-turnos/backups/dump.log

