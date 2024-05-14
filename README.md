# Symfony Vehicle Rental System

Welcome to Wiki-Test-Yan! This is a Symfony-based web application using MySQL and PHP.

## Overview

This Symfony-based website allows users to manage vehicles and their availabilities, including creation, modification, and deletion of vehicles. Additionally, users can use a search form to input departure and return dates and receive a list of available vehicles for those dates, along with the rental price.

## Installation

1. Clone the repository:

   ```bash
   git clone <repository-url>
   ```

2. Create a [.env file](https://symfony.com/doc/current/configuration.html#env-file-syntax) to configure the database connection.

3. Install dependencies:

   ```bash
   composer install
   ```
   ```bash
   composer require symfony/webpack-encore-bundle
   ```

4. Create the database schema:

   ```bash
   php bin/console doctrine:database:create
   ```
   ```bash
   php bin/console doctrine:migrations:migrate
   ```

5. Load sample data (optional):

   ```bash
   php bin/console doctrine:fixtures:load
   ```

6. In a new terminal, install npm dependencies and start the watcher:

   ```bash
   npm install
   ```
   ```bash
   npm run watch
   ```

7. In the first terminal, run the PHP development server:

   ```bash
   php -S localhost:8000 -t public
   ```

8. Visit `http://localhost:8000` in your web browser to access the application.

## Features

- **Vehicle Management**: Users can create, edit, and delete vehicles.
- **Availability Management**: Users can define availability periods for vehicles, specifying start and end dates, price per day, and status.
- **Search Form**: Users can input departure and return dates to find available vehicles for those dates, along with rental prices.

## Usage

1. **Create a Vehicle**: Navigate to the "Create Vehicle" page and fill in the details for the vehicle.
2. **Manage Availabilities**: From the vehicle edit page, users can edit availability periods.
3. **Search for Available Vehicles**: Use the search form to input departure and return dates. The system will display a list of available vehicles for those dates, along with rental prices.
