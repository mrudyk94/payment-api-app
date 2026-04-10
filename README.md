# 🚀 Payment Processing System

Backend API сервіс для обробки платежів із підтримкою асинхронних процесів та симуляцією взаємодії із зовнішнім платіжним провайдером.

Архітектура побудована на Docker та реалізована з використанням Symfony, Domain-Driven Design (DDD), Symfony Messenger з RabbitMQ для черг задач та MariaDB як основної бази даних.

---

## 📦 Вимоги

- 🐳 Docker
- 🐳 Docker Compose
- 🐧 Linux / macOS (або WSL для Windows)

## ⚙️ Швидкий старт

### 1️⃣ Клонування репозиторію
```bash
git clone https://github.com/mrudyk94/payment-api-app.git
cd payment-api-app
```

### 2️⃣ Створення .env
```bash
cp env.dist .env
```

### 3️⃣ Збірка та запуск Docker
```text
# Збірка Docker контейнерів
docker compose build --no-cache

# Підняття контейнерів у фоновому режимі
docker compose up -d
```

В проєкті також є скрипт `run.sh` для зручної роботи з контейнерами.
```text
# Збірка Docker контейнерів
run build

# Підняття контейнерів у фоновому режимі
run up
```

### 4️⃣ Встановлення залежностей (Composer) без входу в контейнер
```bash
docker compose exec api composer install
```

### 5️⃣ Міграції бази даних без входу в контейнер
> ⚠️ Перед виконанням міграцій **потрібно перезапустити контейнери**:
```bash
# Перезапуск контейнерів
docker compose down -v && docker compose up -d
```
```bash
docker compose exec api php bin/console doctrine:migrations:migrate
```