# Dockerized Symfony Application

This project is a Symfony application running inside Docker using:
- PHP-FPM
- Nginx

It is optimized for local development with WSL2 and Docker Desktop.

---

# Requirements

Make sure you have installed:

- Docker Desktop
- Docker Compose v2+
- WSL2 (Windows users)
- Git

---

# 🛠️ Project Setup

## 1. Clone repository

git clone https://github.com/adrianbidireanu/subscriptionIntegration.git
cd subscriptionIntegration

## 2. Build and start containers

docker compose up --build -d

This will start:

PHP-FPM (Symfony application)
Nginx web server
🌍 Access Application

## 3. Build and start containers

http://localhost:8080


