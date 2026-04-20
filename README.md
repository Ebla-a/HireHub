
## Overview
HireHub is a RESTful API for a freelance marketplace connecting clients and freelancers.

## Clients post projects
Freelancers submit offers
Projects are completed and reviewed
System enforces real-world business rules

## Tech Stack
Laravel 12
PHP 8.2
MySQL / PostgreSQL
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

## Public Endpoints
Authentication
POST /api/register → Register new user
POST /api/login → Login

## Public Endpoints
Authentication
POST /api/register → Register new user
POST /api/login → Login

## User & Profile
GET /api/user → Get authenticated user
GET /api/dashboard → Dashboard data
## Profile
GET /api/profile → Get current user profile
PUT /api/profile → Update profile
## Freelancers
GET /api/freelancers → List freelancers
## Projects (Public Read)
GET /api/projects → List projects
GET /api/projects/{project} → Project details
## Notifications
GET /api/notifications → Get user notifications

## Role-Based Endpoints

### Client Endpoints

Middleware: role.client


### Projects Management

POST /api/projects → Create project
PUT /api/projects/{project} → Update project
DELETE /api/projects/{project} → Delete project
### Offers
POST /api/offers/{offer}/accept → Accept offer


### Freelancer Endpoints

Middleware: role.freelancer + verified.freelancer

### Offers 
POST /api/projects/{project}/offers → Submit offer (bid)
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



