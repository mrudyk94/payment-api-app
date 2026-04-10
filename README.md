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

## 🐰 RabbitMQ

Проєкт використовує RabbitMQ як транспорт для Symfony Messenger.

### 🔗 Management UI
http://localhost:15672

**Credentials:**
- login: `guest`
- password: `guest`

### 📌 Використання
- черга для обробки платежів
- асинхронна обробка через worker-и
- можливість моніторингу повідомлень у реальному часі

### 🧪 Приклади API-запитів (curl)

🔹 Створення платежу
```bash
curl --location 'http://localhost:8045/v1/api/payments' \
--header 'Content-Type: application/json' \
--header 'Cookie: main_deauth_profile_token=85c1a0; sf_redirect=%7B%22token%22%3A%2290eb3d%22%2C%22route%22%3A%22api_payments_create%22%2C%22method%22%3A%22POST%22%2C%22controller%22%3A%7B%22class%22%3A%22App%5C%5CUI%5C%5CController%5C%5CApi%5C%5CPaymentController%22%2C%22method%22%3A%22create%22%2C%22file%22%3A%22%5C%2Fvar%5C%2Fwww%5C%2Fhtml%5C%2Fsrc%5C%2FUI%5C%2FController%5C%2FApi%5C%2FPaymentController.php%22%2C%22line%22%3A35%7D%2C%22status_code%22%3A201%2C%22status_text%22%3A%22Created%22%7D' \
--data '{
    "amount": 256691.58,
    "currency": "UAH",
    "key": "550e8400-e29b-41d4-a716-446655440000"
}'
```
🔹 Запуск обробки платежу
```bash
curl --location --request POST 'http://localhost:8045/v1/api/payments/2/process' \
--header 'Cookie: main_deauth_profile_token=85c1a0' \
--data ''
```

🔹 Webhook від провайдера
```bash
curl --location 'http://localhost:8045/v1/api/webhook/payment' \
--header 'Content-Type: application/json' \
--header 'Cookie: main_deauth_profile_token=85c1a0' \
--data '{
    "id": 1,
    "status": "success"
}'
```

### 🧪 Postman Collection
```text
### 6️⃣ Postman Collection

Щоб швидко тестувати API:

1. Скопіюй JSON нижче у файл `Payment API.postman_collection.json`
2. Відкрий Postman → **Import** → **File** → вибери цей файл
3. Тепер готові запити до API

{
  "info": {
    "_postman_id": "4994eaf5-9724-4b2a-a828-5d4dd7bd11af",
    "name": "Payment API",
    "schema": "https://schema.getpostman.com/json/collection/v2.1.0/collection.json",
    "_exporter_id": "6390017"
  },
  "item": [
    {
      "name": "Create payment",
      "event": [
        {
          "listen": "test",
          "script": {
            "exec": [
              "pm.test(\"Successful POST request\", function () {",
              "    pm.expect(pm.response.code).to.be.oneOf([200, 201]);",
              "});",
              ""
            ],
            "type": "text/javascript",
            "packages": {},
            "requests": {}
          }
        }
      ],
      "request": {
        "auth": {
          "type": "noauth"
        },
        "method": "POST",
        "header": [],
        "body": {
          "mode": "raw",
          "raw": "{\n    \"amount\": 256691.58,\n    \"currency\": \"UAH\",\n    \"key\": \"550e8400-e29b-41d4-a716-446655440000\"\n}",
          "options": {
            "raw": {
              "language": "json"
            }
          }
        },
        "url": {
          "raw": "http://localhost:8045/v1/api/payments",
          "protocol": "http",
          "host": [
            "localhost"
          ],
          "port": "8045",
          "path": [
            "v1",
            "api",
            "payments"
          ]
        },
        "description": "This is a POST request, submitting data to an API via the request body. This request submits JSON data, and the data is reflected in the response.\n\nA successful POST request typically returns a `200 OK` or `201 Created` response code."
      },
      "response": []
    },
    {
      "name": "Process payment",
      "event": [
        {
          "listen": "test",
          "script": {
            "exec": [
              "pm.test(\"Successful POST request\", function () {",
              "    pm.expect(pm.response.code).to.be.oneOf([200, 201]);",
              "});",
              ""
            ],
            "type": "text/javascript",
            "packages": {},
            "requests": {}
          }
        }
      ],
      "request": {
        "auth": {
          "type": "noauth"
        },
        "method": "POST",
        "header": [],
        "body": {
          "mode": "raw",
          "raw": "",
          "options": {
            "raw": {
              "language": "json"
            }
          }
        },
        "url": {
          "raw": "http://localhost:8045/v1/api/payments/2/process",
          "protocol": "http",
          "host": [
            "localhost"
          ],
          "port": "8045",
          "path": [
            "v1",
            "api",
            "payments",
            "2",
            "process"
          ]
        },
        "description": "This is a POST request, submitting data to an API via the request body. This request submits JSON data, and the data is reflected in the response.\n\nA successful POST request typically returns a `200 OK` or `201 Created` response code."
      },
      "response": []
    },
    {
      "name": "Webhook payment",
      "event": [
        {
          "listen": "test",
          "script": {
            "exec": [
              "pm.test(\"Successful POST request\", function () {",
              "    pm.expect(pm.response.code).to.be.oneOf([200, 201]);",
              "});",
              ""
            ],
            "type": "text/javascript",
            "packages": {},
            "requests": {}
          }
        }
      ],
      "request": {
        "auth": {
          "type": "noauth"
        },
        "method": "POST",
        "header": [],
        "body": {
          "mode": "raw",
          "raw": "{\r\n    \"id\": 1,\r\n    \"status\": \"fail\"\r\n}",
          "options": {
            "raw": {
              "language": "json"
            }
          }
        },
        "url": {
          "raw": "http://localhost:8045/v1/api/webhook/payment",
          "protocol": "http",
          "host": [
            "localhost"
          ],
          "port": "8045",
          "path": [
            "v1",
            "api",
            "webhook",
            "payment"
          ]
        },
        "description": "This is a POST request, submitting data to an API via the request body. This request submits JSON data, and the data is reflected in the response.\n\nA successful POST request typically returns a `200 OK` or `201 Created` response code."
      },
      "response": []
    }
  ]
}
```