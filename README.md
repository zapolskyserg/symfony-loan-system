# Loan Issuance System

This project is a basic implementation of a loan issuance system, developed on the **Symfony 7** framework. The main focus is on demonstrating software design principles such as **Clean Architecture**, **Domain-Driven Design (DDD)**, and **SOLID**.

---

## Architecture

The project is built on the principles of "Clean Architecture" and has a clear division into layers:

-   **Domain:** The core of the system, containing all business logic, entities, and rules. It is independent of frameworks and infrastructure.
-   **Application:** The layer that orchestrates business logic and implements use cases.
-   **Infrastructure:** The implementation of technical details—data access (files), sending notifications (logger), etc.
-   **Presentation:** The entry points to the application (REST API controllers).

---

## Functionality and Business Logic

The system implements three main scenarios as required by the task.

### Implemented Functionality

1.  ✅ **Create a New Client:** The system allows creating new clients via the API. The data is stored in a file-based storage, which makes it easy to add test users.

2.  ✅ **Check Loan Eligibility:** This is the key functionality. The system doesn't just give a "yes/no" answer but works as an **"offer factory,"** generating a personalized list of available loans for the client.

3.  ✅ **Issue a Loan:** After selecting an offer, the system simulates the issuance of a loan by sending the client a **stub notification** in the form of a log entry.

### How the "Loan Offer Factory" Works

The process of checking a client and forming offers goes through several stages:

1.  **Hard Rules:** First, the client goes through a set of basic rules. If at least one of them is not met, the process stops, and the client is rejected.
2.  **Product Matching:** If the basic checks are passed, the system finds all loan products that match the client's **credit score**.
3.  **Modifiers (Soft Rules):** A set of modifiers is applied to the list of suitable products, which adjust the terms or may lead to rejection based on special rules.

### Loan Issuance Conditions

#### Basic Conditions ("Hard" Rules)

-   **Age:** from **18** to **60** years old.
-   **Income:** at least **$1000** per month.
-   **Region:** the client must be from one of the serviced regions: **Prague (PR), Brno (BR), Ostrava (OS)**.

#### Special Conditions (Modifiers)

-   **Credit History (our enhancement):**
    -   `GOOD`: The amount for all offers is **increased by 5%**.
    -   `BAD`: The amount is **decreased by 10%**, and the rate is **increased by 0.5** percentage points.
    -   `BAD` + high score (\>750): The rate is **additionally increased by 0.5** (for a total of +1.0%).
-   **Regional Rules:**
    -   **Prague (PR):** There is a **random rejection** with a 50% probability.
    -   **Ostrava (OS):** The interest rate for all offers is **increased by 5** percentage points (e.g., from 10% to 15%).

### System Scalability

The architecture is designed for easy expansion:

-   **Adding Loan Products:** To add a new loan product, you just need to add a new object to the `var/data/loan_products.json` file. **No code changes are required.**
-   **Adding Clients:** New clients can be added either via the API or manually in the `var/data/clients.json` file for testing.
-   **Adding New Rules:** To add a new validation rule (e.g., checking employment status), you only need to create a single new PHP class. The system will automatically pick it up and start using it without changes to the existing logic.

---

## Requirements

-   Docker
-   Docker Compose

---

## Installation and Setup

1.  **Clone the repository:**

    ```bash
    git clone <your_repository_address>
    cd loan-system
    ```

2.  **Build and run the Docker containers:**

    ```bash
    docker-compose up -d --build
    ```

3.  **Install project dependencies:**

    ```bash
    docker-compose exec php composer install
    ```

4.  **Run the built-in web server:**

    ```bash
    docker-compose exec php php -S 0.0.0.0:8000 -t public
    ```

    After this, your application will be available at `http://localhost:8000`.

---

## API Documentation

The application provides three main endpoints.

### 1\. Create a New Client

Creates a new client in the system. The data is stored in the `var/data/clients.json` file.

-   **Method:** `POST`
-   **URL:** `/api/clients`
-   **Body (raw, JSON):**
    ```json
    {
        "name": "Maria Curie",
        "age": 42,
        "regionCode": "PR",
        "income": 3500,
        "score": 850,
        "creditHistoryStatus": "good",
        "pin": "987-65-4321",
        "email": "maria.curie@example.com",
        "phone": "+420999888777"
    }
    ```
-   **cURL Example:**
    ```bash
    curl -X POST http://localhost:8000/api/clients \
    -H "Content-Type: application/json" \
    -d '{"name": "Maria Curie", "age": 42, "regionCode": "PR", "income": 3500, "score": 850, "creditHistoryStatus": "good", "pin": "987-65-4321", "email": "maria.curie@example.com", "phone": "+420999888777"}'
    ```
-   **Successful Response:** `Status: 201 Created`
-   **Error Response (invalid region):** `Status: 400 Bad Request`
    ```json
    {
        "error": "Region code <XY> is not allowed."
    }
    ```

### 2\. Get Loan Offers for a Client

Checks the client against all business rules and returns a list of personalized loan offers.

-   **Method:** `GET`
-   **URL:** `/api/clients/{clientId}/loan-offers`
-   **cURL Example (for client Anna Novakova):**
    ```bash
    curl http://localhost:8000/api/clients/d290f1ee-6c54-4b01-90e6-d701748f0852/loan-offers
    ```
-   **Successful Response:** `Status: 200 OK`
    ```json
    [
        {
            "productName": "Standard Loan",
            "amount": 5000,
            "rate": 15
        }
    ]
    ```
-   **Rejection Response:** `Status: 403 Forbidden`
    ```json
    {
        "message": "Client age is not within the allowed range (18-60)."
    }
    ```

### 3\. Issue a Loan

Simulates the issuance of a loan and sends a notification to the client (writes it to the log).

-   **Method:** `POST`
-   **URL:** `/api/loans/issue`
-   **Body (raw, JSON):**
    ```json
    {
        "clientId": "d290f1ee-6c54-4b01-90e6-d701748f0852",
        "productName": "Standard Loan",
        "amount": 5000,
        "rate": 15.0
    }
    ```
-   **cURL Example:**
    ```bash
    curl -X POST http://localhost:8000/api/loans/issue \
    -H "Content-Type: application/json" \
    -d '{"clientId": "d290f1ee-6c54-4b01-90e6-d701748f0852", "productName": "Standard Loan", "amount": 5000, "rate": 15.0}'
    ```
-   **Successful Response:** `Status: 200 OK`
    ```json
    {
        "status": "success",
        "message": "Loan issued and notification queued."
    }
    ```
-   **Result:** A new notification entry will appear in the `var/log/dev.log` file.

---

## Data Storage

The project uses files for data storage, as was allowed in the task. You can edit them to test different scenarios:

-   **Clients:** `var/data/clients.json`
-   **Loan Products:** `var/data/loan_products.json`
