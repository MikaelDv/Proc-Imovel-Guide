# PHP Project - Instructions for Setup and Usage

This document provides instructions for setting up and running your PHP project locally using XAMPP or any other web server with PHP and MySQL support.

## Prerequisites

Before you begin, ensure you have the following installed on your system:

1. **PHP** (version 7.4 or higher is recommended)
2. **MySQL** (compatible version with your PHP setup)
3. **Composer** (for managing dependencies)
4. **XAMPP** (optional, for an easy setup of Apache and MySQL locally)

## Project Setup

Follow these steps to configure and run the project:

### 1. Clone the Repository
Clone the project repository to your local machine:
```bash
git clone <repository-url>
```
Navigate to the project directory:
```bash
cd <project-folder>
```

### 2. Install Dependencies
Use Composer to install the required PHP dependencies:
```bash
composer install
```

### 3. Configure the Environment File
Create a `.env` file in the project root directory if it doesn't already exist. Use the following template and update the values accordingly:

```env
host="your-database-host"
user="your-database-username"
password="your-database-password"
db="your-database-name"
```

- **host**: Specify your database host (e.g., `localhost` if using XAMPP).
- **user**: Enter your MySQL username (default for XAMPP is `root`).
- **password**: Enter your MySQL password (default for XAMPP is an empty string).
- **db**: Provide the name of the database used in the project.

### 4. Import the Database Schema
To set up the database, import the provided SQL file into your MySQL server:

1. Open **phpMyAdmin** (or any MySQL client).
2. Create a new database with the name specified in your `.env` file (e.g., `your-database-name`).
3. Import the SQL file located in the `database` folder of the project:
   - File: `database/schema.sql`

### 5. Start the Development Server
If you're using XAMPP:

1. Place the project folder inside the `htdocs` directory of your XAMPP installation.
   Example path: `C:\xampp\htdocs\<project-folder>`
2. Start the Apache and MySQL services from the XAMPP Control Panel.
3. Access the project in your browser:
   ```
   http://localhost/<project-folder>
   ```

If you're not using XAMPP, start the PHP built-in server:
```bash
php -S localhost:8000
```
Access the project at: `http://localhost:8000`

### 6. Usage Instructions

- **Add a Record**: Fill out the form on the homepage and click the submit button to add a new record to the database.
- **Edit a Record**: Click the "Editar" link next to a record to edit its details.
- **Delete a Record**: Click the "Excluir" link to remove a record from the database.

### 7. Error Handling

- If you encounter errors like "Connection failed" or "Database not found":
  - Verify your `.env` file configuration.
  - Ensure the database server is running and accessible.

- If CSS or JS doesn't load:
  - Check the paths in the `index.php` file to ensure they are correct.
  - Clear your browser cache and refresh.

---

## Project Structure

```
<project-folder>/
|-- database/
|   |-- schema.sql       # Database schema file
|-- public/
|   |-- css/             # Stylesheets
|   |-- js/              # JavaScript files
|-- .env                 # Environment variables (excluded from version control)
|-- composer.json        # Composer dependencies
|-- index.php            # Main entry point
```

---

## Troubleshooting

- **Issue:** Cannot connect to the database.
  - Solution: Double-check `.env` settings and ensure MySQL is running.

- **Issue:** Blank page appears.
  - Solution: Enable error reporting in `php.ini` or add this at the top of `index.php` during development:
    ```php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    ```

- **Issue:** Styles or scripts not loading.
  - Solution: Ensure file paths are relative to the `index.php` file and hosted correctly.

---

## License
This project is licensed under the MIT License. See the LICENSE file for more details.

---

## Author
Developed by Enzo Mikael Sanches. For any inquiries, feel free to contact me at [enzomikael@hotmail.com].

