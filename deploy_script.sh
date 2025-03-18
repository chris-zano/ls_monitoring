#!/usr/bin/env bash

# Step 1: Create an ECS cluster
aws ecs create-cluster --cluster-name ls-monitoring-cluster

# Step 2: Create ECR repositories
aws ecr create-repository --repository-name nginx
aws ecr create-repository --repository-name php-app
aws ecr create-repository --repository-name mysql

# Step 3: Authenticate with docker
aws ecr get-login-password --region eu-west-1 | docker login --username AWS --password-stdin ************.dkr.ecr.eu-west-1.amazonaws.com

# Step 4: Tag and push docker images
docker-compose build 
docker tag php-app:latest ************.dkr.ecr.eu-west-1.amazonaws.com/php-app:latest
docker tag nginx:latest ************.dkr.ecr.eu-west-1.amazonaws.com/nginx:latest
docker tag mysql:latest ************.dkr.ecr.eu-west-1.amazonaws.com/mysql:latest
