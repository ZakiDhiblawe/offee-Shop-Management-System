# Coffee Shop Management System

![Coffee Shop Logo](https://example.com/coffee-shop-logo.png) 

## Table of Contents
1. [Introduction](#introduction)
2. [Key Features](#key-features)
3. [Prerequisites](#prerequisites)
4. [Installation Guide](#installation-guide)
   - [Step 1: Clone the Repository](#step-1-clone-the-repository)
   - [Step 2: Set Up the Oracle Database](#step-2-set-up-the-oracle-database)
   - [Step 3: Configure Database Connection](#step-3-configure-database-connection)
   - [Step 4: Deploy the Project Using XAMPP](#step-4-deploy-the-project-using-xampp)
5. [Running the Application](#running-the-application)
6. [Contributing](#contributing)
7. [License](#license)
8. [Author](#author)

## Introduction
The **Coffee Shop Management System** is a web-based application designed to help coffee shop owners and managers streamline their day-to-day operations, including managing employees, customers, products, orders, and sales reports. The system is developed using **PHP** for backend processing and **Oracle Database** for data management.

## Key Features
- **User Authentication:** Secure login system for administrators.
- **Dashboard:** A central hub to access all management functionalities.
- **Employee Management:** Manage employee records, including their details and roles.
- **Customer Management:** Track customer details and order history.
- **Product Management:** Manage the coffee shop's product inventory.
- **Order Management:** Efficiently process and manage customer orders.
- **Sales Reporting:** Generate and view detailed sales reports.

## Prerequisites
Ensure the following are installed on your system before setting up the project:
- **XAMPP:** A popular PHP development environment. Download from [Apache Friends](https://www.apachefriends.org/index.html).
- **Oracle Database:** Install Oracle Database Express Edition (XE) or another version. Download from the [Oracle website](https://www.oracle.com/database/technologies/appdev/xe.html).

## Installation Guide

### Step 1: Clone the Repository
1. Open your terminal or command prompt.
2. Run the following commands to clone the repository:
   ```bash
   git clone https://github.com/ZakiDhiblawe/offee-Shop-Management-System.git
   ```

   ```bash
   cd coffee-shop-management
   ```

### Step 2: Set Up the Oracle Database
1. Open Oracle SQL Developer or another Oracle client tool.
2. Create a new user (schema) for the Coffee Shop application.
3. Execute the SQL scripts found in `db.txt` to create the necessary database tables and insert sample data.

### Step 3: Configure Database Connection
1. Open the `db.php` file in the project directory.
2. Replace the placeholders with your Oracle database details:

   ```php
   $username = 'your_oracle_username';
   $password = 'your_oracle_password';
   $host = 'localhost';
   $port = '1521';
   $service_name = 'xe';
   ```

### Step 4: Deploy the Project Using XAMPP
1. Move the project folder to the `htdocs` directory inside your XAMPP installation directory (usually `C:/xampp/htdocs`).
2. Open the XAMPP Control Panel and start the Apache service.
3. In your web browser, go to `http://localhost/coffee-shop-management/` to access the application.

## Running the Application
- Navigate through the different sections like Employee Management, Product Management, Orders, and Sales Reporting using the dashboard.
- Ensure the Oracle database is running and properly connected before using the application.

## Contributing
If you’re interested in improving this project, feel free to fork the repository and submit a pull request.

## License
This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

## Author
**Zakaria Dahir Salad**  
Information Technology Student, Simad University  
Oracle Course Project
```
