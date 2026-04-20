
## Overview
HireHub is a RESTful API for a freelance marketplace connecting clients and freelancers.

## Clients post projects
Freelancers submit offers
Projects are completed and reviewed
System enforces real-world business rules

## Tech Stack
Laravel 12

PHP 8.2

MySQL

REST API (JSON responses only)


## Features
User roles: client, freelancer ,admin

Freelancer verification system

Projects with budgets (fixed / hourly)

offers system with auto-rejection logic

Reviews & ratings

Filtering & sorting (projects & freelancers)

Performance optimized queries

Clean architecture (Service-based)

## Issue
N+1 queries in project listing & profiles
Fix
Eager loading (with)
withCount() for counts
## Result
Reduced queries
Faster responses

## API Endpoints


# Public Endpoints

Authentication

POST /api/register → Register new user

POST /api/login → Login

# User & Profile

GET /api/user → Get authenticated user

GET /api/dashboard → Dashboard data

# Profile

GET /api/profile → Get current user profile

PUT /api/profile → Update profile

# Freelancers

GET /api/freelancers → List freelancers

# Projects (Public Read)
GET /api/projects → List projects

GET /api/projects/{project} → Project details

# Notifications

GET /api/notifications → Get user notifications


#  Role-Based Endpoints

# Client Endpoints

Middleware: role.client


# Projects Management

POST /api/projects → Create project

PUT /api/projects/{project} → Update project

DELETE /api/projects/{project} → Delete project

# Offers

POST /api/offers/{offer}/accept → Accept offer


### Freelancer Endpoints

Middleware: role.freelancer + verified.freelancer

### Offers 

POST /api/projects/{project}/offers → Submit offer

GET /api/my-offers → Get my offers

DELETE /api/offers/{offer} → Delete offer

### Admin Endpoints

Middleware: is_admin

POST /api/admin/verify/{profile} → Verify freelancer

## Seeder
``` bash
php artisan migrate --seed
```
Includes:

Users
Projects
offers
Skills

## Architecture
Controllers → HTTP layer
Services → business logic
Models → relationships
Form Requests → validation

## Principles
SOLID
Clean Code
Scalable design

## Installation
``` bash
git clone <repo-url>
cd hirehub

composer install

cp .env.example .env
php artisan key:generate

php artisan migrate --seed

php artisan serve

```

## Example Responses
``` bash
# Register
{
  "status": "success",
  "message": "User registered successfully",
  "data": {
    "user": {
      "id": 6,
      "first_name": "Ebla",
      "last_name": "Ali",
      "email": "ebla@testt.com",
      "city_id": 1,
      "full_name": "Ebla Ali",
      "avatar_url": "https://ui-avatars.com/api/?name=Ebla+Ali"
    },
    "token": "YOUR_TOKEN"
  }
}
```
``` bash
# Login
{
  "status": "success",
  "message": "Login successful",
  "token": "YOUR_TOKEN",
  "user": {
    "id": 6,
    "first_name": "Ebla",
    "last_name": "Ali",
    "email": "ebla@testt.com",
    "role": "freelancer",
    "full_name": "Ebla Ali",
    "avatar_url": "https://ui-avatars.com/api/?name=Ebla+Ali"
  }
}
```

``` bash
Get Authenticated User
{
  "status": "success",
  "data": {
    "id": 6,
    "first_name": "Ebla",
    "last_name": "Ali",
    "email": "ebla@testt.com",
    "role": "freelancer",
    "full_name": "Ebla Ali",
    "avatar_url": "https://ui-avatars.com/api/?name=Ebla+Ali",
    "profile": null
  }
}
```
``` bash
Update Profile

{
  "status": "success",
  "message": "Profile updated successfully",
  "data": {
    "id": 6,
    "full_name": "Ebla Ali",
    "profile": {
      "bio": "Expert developer",
      "hourly_rate": "50.00",
      "availability": "available",
      "is_verified": false,
      "portfolio_links": [
        "https://github.com/Ebla-a"
      ],
      "rating_stars": "0.0 ⭐",
      "joined_since": "Member since April 2026",
      "skills": [
        {
          "name": "Laravel",
          "years_of_experience": 0
        },
        {
          "name": "Vue.js",
          "years_of_experience": 0
        }
      ]
    }
  }
}
```
``` bash
Freelancers List
{
  "status": "success",
  "data": {
    "data": [
      {
        "id": 1,
        "bio": "Laravel developer with one year of experience",
        "hourly_rate": "25.00",
        "availability": "available",
        "is_verified": true,
        "rating_stars": "0.0 ⭐",
        "joined_since": "Member since April 2026",
        "user": {
          "full_name": "Ebla ali",
          "avatar_url": "https://ui-avatars.com/api/?name=Ebla+ali"
        },
        "skills": [
          {
            "name": "Laravel",
            "years_of_experience": 1
          }
        ]
      }
    ]
  }
}
```
``` bash

Verify Freelancer (Admin)
{
  "status": "success",
  "message": "Freelancer is now verified."
}
```
``` bash
Create Project

{
  "message": "The project created successfully",
  "data": {
    "id": 2,
    "title": "E-commerce Website",
    "budget_type": "fixed",
    "budget": "1000.00",
    "formatted_budget": "$ 1000.00 (Fixed Price)",
    "time_left": "41 days left",
    "user": {
      "full_name": "client client"
    }
  }
}
```
``` bash
Projects List
{
  "data": [
    {
      "id": 1,
      "title": "Build E-commerce API",
      "status": "open",
      "days_left": "60 Days left",
      "offers_count": 0,
      "budget": "$1,500.00",
      "owner": {
        "full_name": "HireHub Client"
      }
    }
  ]
}
```
``` bash
Error Examples
{
  "message": "this action is for freelancers only"
}
```





