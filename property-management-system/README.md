
# BACKEND PROJRCT API
# Property Management System API

Introduction
This is a Laravel-based API for managing property rentals, tenant records, and rent distribution. The API provides authentication, CRUD operations, and automated rent calculations for property owners.

## System Requirements
## Technologies Used
- **PHP**: 8.1 or higher
- **Composer**: Latest version
- **Laravel**: 10^
- **Database**: MySQL
- **XAMPP/WAMP**: For running Apache and MySQL locally
- **Postman**: For API testing

## Getting Started

These instructions will guide you through setting up and running the project on your local machine for development and testing purposes.

### Prerequisites

Before you begin, ensure you have the following installed:
- Git
- PHP (>= 8.1)
- Composer
- MySQL or another relational database system

### Cloning the Repository

Start by cloning this repository to your local machine:

```bash
## Backend

### Inside the project directory, run the following commands
git clone https://github.com/iamarunyadhav/Property-Management-System.git

cd property-management-system

##use any sql server like xampp or wampp
xampp (phpMyAdmin, MySQL Workbench)

cp .env.example .env

Open .env and update the database credentials:
### setup the database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=property_management  #whatever you want to name your databas
DB_USERNAME=root #username
DB_PASSWORD=    #password

Start MySQL Server

### install package dependencies
composer install

### Generate key 
php artisan key:generate

### migarate the database
php artisan migrate

### Seed Sample Data
php artisan db:seed

## Start backend server
php artisan serve


### This will typically serve your backend on 

Note : http://127.0.0.1:8000   #(run this port as default)


## Running the API
### Import API Collection into Postman

### Inside the project, find the api_collection.json file. (inside documentation)

##  Testing Endpoints
Ensure the Laravel server is running.
Use Postman to send requests and check responses.

Example request to fetch tenant rent details:
GET /api/tenants/42/rent

### Check Request Parameters & Payloads
### Verify all required parameters before executing API requests.
### Use different tenant IDs to fetch respective rent distributions.

Happy start ____


## useful commands
php artisan migrate:refresh
php artisan optimize:clear
php artisan cache:clear

```

### API DOCUMENTATION : https://documenter.getpostman.com/view/24328222/2sAYdeLrnP
