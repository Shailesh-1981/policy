# Project Setup Guide

## Clone the Repository
git clone <https://github.com/Shailesh-1981/policy.git>


## Install Dependencies
composer install

## Set Up the Database
php artisan migrate

## Seed the Database
Run the seeder service file to populate initial data:
php artisan db:seed --class=RolesTableSeeder
php artisan db:seed --class=AdminDataSeeder
admin seeder give email adminpolicy@yopmail.com and Admin@123


## Install Frontend Dependencies
npm install //its used for breeze 
npm run build

## Running the Project
Once all dependencies and migrations are set up, you can start the project:
php artisan serve

## Authentication Flow
- Users must first register an account.
- After registering, they can log in to access the dashboard.
- The dashboard displays:
  - Total Policies
  - Total Users
  - Total Employees

## Features

### Policy Management
- CRUD operations through API calls
- Search and Pagination functionality

### Employee Management
- CRUD operations through API calls
- Search and Pagination functionality

## Security & Middleware
- JWT authentication is used as middleware for API access.

## Email Configuration
- Ensure the `.env` file includes valid email credentials.
- This enables the 'Forgot Password' functionality to work properly.

### Example `.env` Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=guptashailesh38@gmail.com
MAIL_PASSWORD=rnfpfnmnbldareem
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=guptashailesh38@gmail.com
MAIL_FROM_NAME="Policy" 

## Additional Notes
- Make sure the `.env` file is configured correctly before running the project.
- If npm or composer dependencies change, rerun `composer install` and `npm install`.
- API calls should include JWT authentication headers to access protected routes.

---

This document outlines the full project setup, authentication flow, and features available in the system.
