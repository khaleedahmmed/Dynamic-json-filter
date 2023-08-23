# Dynamic JSON Filter

**Author:** Khaled Ahmed

**Laravel Version:** 10.2

**PHP Version:** 8.1

## Introduction

Dynamic JSON Filter is a Laravel-based API project that enables you to filter and combine data from multiple JSON sources. This project offers a flexible way to filter and retrieve JSON data from various providers.

## Features

- Combines and filters data from multiple JSON sources.
- Supports filtering by provider, status code, balance, and currency.
- Utilizes a configuration file for key mapping, facilitating adaptation to different data structures.
- Includes unit tests to ensure code correctness.

## Installation

To get started with this project, follow these steps:

1. Clone the repository:

   ```shell
   git clone https://github.com/khaleedahmmed/Dynamic-json-filter.git
   ```

2. Navigate to the project directory:

   ```shell
   cd Dynamic-json-filter
   ```

3. Install PHP dependencies using Composer:

   ```shell
   composer install
   ```

4. Create a `.env` file by copying `.env.example`:

   ```shell
   cp .env.example .env
   ```

5. Generate a new Laravel application key:

   ```shell
   php artisan key:generate
   ```

6. Start the Laravel development server:

   ```shell
   php artisan serve --host=0.0.0.0
   ```

   Access the application at `http://localhost:8000`.

## Usage

1. With the Laravel development server running, make API requests to the `/api/v1/users` endpoint to filter and retrieve JSON data. You can use query parameters to specify filters, such as `provider`, `statusCode`, `balanceMin`, `balanceMax`, and `currency`.

   Example request:

   ```
   GET http://localhost:8000/api/v1/users?provider=DataProviderX&statusCode=authorised
   ```

   This request will retrieve data from `DataProviderX` with a `statusCode` of `authorised`.

## Configuration

- Customize the key mapping for each data provider in the `config/keys_mapping.php` file. Adjust this file to match the structure of your JSON data sources.

## Testing

To run unit tests, use the following command:

```shell
php artisan test
```

## Docker Support

This project includes Docker support for containerization. You can use the provided `Dockerfile` and `docker-compose.yml` files to set up and run the project in a Docker container.

### Building and Running with Docker

1. Clone the repository as mentioned above.

2. Navigate to the project directory:

   ```shell
   cd Dynamic-json-filter
   ```

3. Build and start the Docker containers:

   ```shell
   docker-compose up -d
   ```

4. Access the application at `http://localhost:8000` in your web browser.

5. When done, stop the containers:

   ```shell
   docker-compose down
   ```

## License

This project is available under the [MIT License](LICENSE).
