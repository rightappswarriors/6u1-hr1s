## About HRIS

This right here needs to be provided with an apt description

### Installation Instructions
1. Run `git clone https://github.com/rightappswarriors/6u1-hr1s`
2. Create a PostgreSQL database for project
	* ```psql -h localhost -p 5432 -U postgres```
	* ```create database rightapps_guihulngan;```
	* ```\q```
3. Restore database from backup
	* ```cd db-backup```
	* ```pg_restore -h localhost -p 5432 -U postgres -d rightapps_guihulngan -v rssys-09-27-20```
	* ```pg_restore -h localhost -p 5432 -U postgres -d rightapps_guihulngan -v hris-09-27-20```
4. Configure your `.env` file
5. From the projects root folder run
	* ```composer update```
	* ```npm install```
	* ```npm run dev or npm run production```
	* You can watch assets with `npm run watch`

##### Seeded Users

|Username|Password|Access|
|:------------|:------------|:------------|
|admin|adminrss|Admin Access|


### Environment File
Example `.env` file:

```bash

APP_NAME=HRIS
APP_ENV=local
APP_KEY=base64:9i8muboS1GAI3FjwDcrcWrDJa62HglJZgzIHGWSMtA4=
APP_DEBUG=true
APP_URL=http://localhost
APP_COMPANY="CITY OF GUIHULNGAN"

LOG_CHANNEL=stack

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=rightapps_guihulngan
DB_USERNAME=homestead
DB_PASSWORD=secret
DB_SCHEMA=hris

BROADCAST_DRIVER=log
CACHE_DRIVER=file
SESSION_DRIVER=file
SESSION_LIFETIME=60
QUEUE_DRIVER=sync

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_DRIVER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_APP_CLUSTER=mt1

MIX_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
MIX_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"

TIMEZONE="Asia/Singapore"
CURRENCY="peso"

```