#!/bin/sh

cat ./project/.env.example .env.docker.example > ./project/.env
cat .env.docker.example > .env

docker-compose build
docker-compose up -d
