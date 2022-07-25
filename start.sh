#!/bin/sh

#make .env
cat ./project/.env.example .env.docker.example > ./project/.env
cat .env.docker.example > .env

#build containers
docker-compose build
docker-compose up -d
docker-compose exec php composer install --no-interaction

#install bootstrap and node_modules (for front)
docker-compose exec php npm install
docker-compose exec php npm audit fix
docker-compose exec php npm run build

#generate security
docker-compose exec php php bin/console lexik:jwt:generate-keypair

#load migrations and fixtures
docker-compose exec php php bin/console doctrine:migrations:migrate --no-interaction
docker-compose exec php php bin/console doctrine:fixtures:load --no-interaction
