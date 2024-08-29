# **Laravel Leaderboard Application**

This is a Laravel-based API application designed to manage a leaderboard where users can gain or lose points, be added or removed, and view details such as name, age, points, and address. The application also includes features such as QR code generation for user addresses, scheduled tasks to identify high scorers, and comprehensive test coverage.

## **Table of Contents**

1. [Features](#features)
2. [Installation](#installation)
3. [Environment Setup](#environment-setup)
4. [Running the Application](#running-the-application)
5. [API Documentation](#api-documentation)
6. [Commands and Scheduled Jobs](#commands-and-scheduled-jobs)
7. [Testing](#testing)
8. [Project Structure](#project-structure)
9. [Contributing](#contributing)
10. [License](#license)

## **Features**

-   **Leaderboard Management**: Add, update, and delete users, with real-time score adjustments.
-   **User Details**: Click on a user’s name to view their details, including name, age, points, and address.
-   **QR Code Generation**: Automatically generates and stores a QR code for the user’s address upon creation.
-   **Score Reset**: Command to reset all users' scores to zero.
-   **Highest Scorer Tracking**: Scheduled job that identifies and stores the highest scorer at regular intervals.
-   **API Responses Grouped by Score**: Returns user information grouped by score with average age calculation.

## **Installation**

Follow these steps to set up the application on your local machine.

### Prerequisites

-   PHP 8.x
-   Composer
-   PostgreSQL
-   Laravel 10.x
-   Node.js (for frontend scaffolding, optional)

### Steps

1.  **Clone the Repository**:

    ```
    git clone https://github.com/your-username/leaderboard-app.git
    cd leaderboard-app
    ```

2.  **Environment Setup**:
    Copy the `.env` File:

        ```
        cp .env.example .env

3.  **Configure Database Settings**:
    Update the `.env` file with your PostgreSQL configuration:
    ```
    DB_CONNECTION=pgsql
    DB_HOST=127.0.0.1
    DB_PORT=5432
    DB_DATABASE=leaderboard_db
    DB_USERNAME=`your_postgres_username`
    DB_PASSWORD=`your_postgres_password`
    ```
4.  **Generate Application Key**:
    ```
    php artisan key:generate
    ```
5.  **Run Migrations and Seed the Database**:
    ```
    php artisan migrate --seed
    ```
6.  **Create a Storage Symlink**:
    To make the QR code images accessible via URL:
    ```
    php artisan storage:link
    ```

### Running the Application

**Start the development server**:

```
php artisan serve
```

The application will be accessible at `http://localhost:8000`.

## **API Documentation**

**Base URL**
All endpoints are prefixed with `/api`.

**Endpoints**
Create User

-   Method: `POST`
-   URL: `/api/users`
-   Description: Creates a new user and generates a QR code for their address.
-   Request Body: `JSON`
    ```
    {
          "name": "John Doe",
          "age": 30,
          "address": "123 Main St, City"
    }
    ```
-   Response: Returns the created user object.

**Update User Points**

-   Method: `PATCH`
-   URL: `/api/users/{id}/points`
-   Description: Increment or decrement user points.
-   Request Body: `JSON`
    ```
    {
      "points": 5
    }
    ```
-   Response: Returns the updated user object.

**Delete User**

-   Method: `DELETE`
-   URL: `/api/users/{id}`
-   Description: Deletes a user by `ID`.

**Get Users Grouped by Score**

-   Method: `GET`
-   URL: `/api/users/grouped-by-score`
-   Description: Returns users grouped by their scores with the average age.
-   Response: `JSON`
    ```
    {
      "25": {
        "names": ["Emma"],
        "average_age": 18
      }
    }
    ```

## **Commands and Scheduled Jobs**

**Reset Scores Command**

-   Command: `php artisan leaderboard:reset-scores`
-   Description: Resets all user scores to zero.

**Scheduled Job to Identify Highest Scorer**

-   Job: `CheckHighestScorer`
-   Frequency: Every 5 minutes
-   Description: Identifies the highest scorer and records it in the winners table. No record is created if there is a tie.

**Testing**

-   Run the test suite to ensure everything is functioning correctly:
    ```
    php artisan test
    ```

Test cases are located in the `tests/Feature/` directory, covering key functionalities like user creation, score updates, and grouped responses.

Project Structure

-   `app/Models/`: Contains the models (User, Winner).
-   `app/Http/Controllers/`: Contains the controllers for managing the leaderboard.
-   `app/Console/Commands/`: Custom Artisan commands.
-   `app/Jobs/`: Background jobs for scheduled tasks.
-   `routes/api.php`: Defines the API routes.
-   `database/migrations/`: Database schema migrations.
-   `database/seeders/`: Database seeders for initial data.
-   `tests/Feature/`: Test cases for various features.

## **Contributing**

Contributions are welcome! Please fork the repository and submit a pull request for review.

## **License**

This project is licensed under the `MIT License`. See the LICENSE file for details.
