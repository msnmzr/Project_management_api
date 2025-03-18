# Laravel Project Management API

A RESTful API for project management with dynamic attributes (EAV) built with Laravel.

## Features

- User Authentication (Register, Login, Logout)
- Project Management with dynamic attributes (EAV)
- Timesheet Management
- Dynamic Attribute Management
- RESTful API with proper validation and error handling
- Filtering system for both regular and EAV attributes

## Requirements

- PHP 8.1 or higher
- MySQL 5.7 or higher
- Composer
- Laravel 10.x

## Installation

1. Clone the repository:
```bash
git clone <repository-url>
cd laravel-practical
```

2. Install dependencies:
```bash
composer install
```

3. Copy the environment file:
```bash
cp .env.example .env
```

4. Configure your database in `.env`:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=astudio_practical_assessment
DB_USERNAME=root
DB_PASSWORD=123456
```

5. Generate application key:
```bash
php artisan key:generate
```

6. Run migrations and seeders:
```bash
php artisan migrate --seed
```

7. Install Laravel Passport:
```bash
php artisan passport:install
```

## Test Credentials

```
Email: test@example.com
Password: password
```

## API Documentation

### Authentication Endpoints

#### Register User
```http
POST /api/register

Request:
{
    "first_name": "John",
    "last_name": "Doe",
    "email": "john@example.com",
    "password": "password",
    "password_confirmation": "password"
}

Response: (201 Created)
{
    "user": {
        "id": 1,
        "first_name": "John",
        "last_name": "Doe",
        "email": "john@example.com",
        "created_at": "2024-03-18T12:00:00.000000Z",
        "updated_at": "2024-03-18T12:00:00.000000Z"
    },
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1..."
}
```

#### Login
```http
POST /api/login

Request:
{
    "email": "john@example.com",
    "password": "password"
}

Response: (200 OK)
{
    "user": {
        "id": 1,
        "first_name": "John",
        "last_name": "Doe",
        "email": "john@example.com",
        "created_at": "2024-03-18T12:00:00.000000Z",
        "updated_at": "2024-03-18T12:00:00.000000Z"
    },
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1..."
}
```

#### Logout
```http
POST /api/logout

Headers:
Authorization: Bearer {access_token}

Response: (200 OK)
{
    "message": "Successfully logged out"
}
```

### Project Endpoints

#### List Projects
```http
GET /api/projects

Headers:
Authorization: Bearer {access_token}

Query Parameters:
?filters[name]=Project
?filters[status]=active
?filters[department]=IT

Response: (200 OK)
{
    "data": [
        {
            "id": 1,
            "name": "Project A",
            "status": "active",
            "users": [...],
            "attributes": [
                {
                    "id": 1,
                    "name": "department",
                    "type": "text",
                    "value": "IT"
                }
            ],
            "created_at": "2024-03-18T12:00:00.000000Z",
            "updated_at": "2024-03-18T12:00:00.000000Z"
        }
    ],
    "links": {...},
    "meta": {...}
}
```

#### Create Project
```http
POST /api/projects

Headers:
Authorization: Bearer {access_token}

Request:
{
    "name": "New Project",
    "status": "active",
    "user_ids": [1],
    "attributes": [
        {
            "id": 1,
            "value": "IT"
        },
        {
            "id": 2,
            "value": "2024-01-01"
        }
    ]
}

Response: (201 Created)
{
    "data": {
        "id": 2,
        "name": "New Project",
        "status": "active",
        "users": [...],
        "attributes": [...],
        "created_at": "2024-03-18T12:00:00.000000Z",
        "updated_at": "2024-03-18T12:00:00.000000Z"
    }
}
```

### Timesheet Endpoints

#### List Timesheets
```http
GET /api/timesheets

Headers:
Authorization: Bearer {access_token}

Query Parameters:
?filters[task_name]=Meeting
?filters[date]=2024-03-18
?filters[project_id]=1

Response: (200 OK)
{
    "data": [
        {
            "id": 1,
            "task_name": "Meeting",
            "date": "2024-03-18",
            "hours": 2.5,
            "user": {...},
            "project": {...},
            "created_at": "2024-03-18T12:00:00.000000Z",
            "updated_at": "2024-03-18T12:00:00.000000Z"
        }
    ],
    "links": {...},
    "meta": {...}
}
```

#### Create Timesheet
```http
POST /api/timesheets

Headers:
Authorization: Bearer {access_token}

Request:
{
    "task_name": "Meeting",
    "date": "2024-03-18",
    "hours": 2.5,
    "project_id": 1
}

Response: (201 Created)
{
    "data": {
        "id": 1,
        "task_name": "Meeting",
        "date": "2024-03-18",
        "hours": 2.5,
        "user": {...},
        "project": {...},
        "created_at": "2024-03-18T12:00:00.000000Z",
        "updated_at": "2024-03-18T12:00:00.000000Z"
    }
}
```

### Attribute Endpoints

#### List Attributes
```http
GET /api/attributes

Headers:
Authorization: Bearer {access_token}

Response: (200 OK)
{
    "data": [
        {
            "id": 1,
            "name": "department",
            "type": "text",
            "created_at": "2024-03-18T12:00:00.000000Z",
            "updated_at": "2024-03-18T12:00:00.000000Z"
        }
    ],
    "links": {...},
    "meta": {...}
}
```

#### Create Attribute
```http
POST /api/attributes

Headers:
Authorization: Bearer {access_token}

Request:
{
    "name": "priority",
    "type": "select"
}

Response: (201 Created)
{
    "data": {
        "id": 2,
        "name": "priority",
        "type": "select",
        "created_at": "2024-03-18T12:00:00.000000Z",
        "updated_at": "2024-03-18T12:00:00.000000Z"
    }
}
```

## Error Handling

The API returns appropriate HTTP status codes and error messages:

```http
400 Bad Request: Invalid input data
401 Unauthorized: Invalid or missing authentication
403 Forbidden: Insufficient permissions
404 Not Found: Resource not found
422 Unprocessable Entity: Validation errors
500 Internal Server Error: Server error
```

Example error response:
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "email": [
            "The email has already been taken."
        ]
    }
}
```

## Testing

To run the tests:
```bash
php artisan test
```

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
