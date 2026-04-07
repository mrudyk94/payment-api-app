# 🚀 Payment API App

Backend API сервіс для роботи з користувачами.  
Проєкт запускається в Docker та використовує Symfony + MariaDB.

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