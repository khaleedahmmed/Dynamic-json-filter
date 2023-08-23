# Dynamic JSON Filter

**Author:** Khaled Ahmed

**Laravel Version:** 10.2

**PHP Version:** 8.1

## Introduction

Dynamic JSON Filter is a Laravel-based API project that allows you to filter and combine data from multiple JSON sources. This project is designed to provide a flexible way to filter and retrieve JSON data from various providers.

## Features

- Combines and filters data from multiple JSON sources.
- Supports filtering by provider, status code, balance, and currency.
- Uses a configuration file for key mapping, making it easy to adapt to different data structures.
- Includes unit tests for ensuring code correctness.

## Installation

Follow these steps to clone and set up the project:

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

6. Configure your database in the `.env` file.

7. Run database migrations:

   ```shell
   php artisan migrate
   ```

8. Start the Laravel development server:

   ```shell
   php artisan serve
   ```

## Usage

1. Start the Laravel development server as described above.

2. Make API requests to the `/api/v1/users` endpoint to filter and retrieve JSON data. You can use query parameters to specify filters, such as `provider`, `statusCode`, `balanceMin`, `balanceMax`, and `currency`.

   Example request:

   ```
   GET http://localhost:8000/api/v1/users?provider=DataProviderX&statusCode=authorised
   ```

   This request will retrieve data from `DataProviderX` with a `statusCode` of `authorised`.

## Configuration

- The key mapping for each data provider is defined in the `config/keys_mapping.php` file. You can customize this file to match the structure of your JSON data sources.

## Testing

To run unit tests, use the following command:

```shell
php artisan test
```

## Contributing

Feel free to contribute to this project by submitting issues or pull requests. Your feedback and contributions are highly appreciated!

## License

This project is open-source and available under the [MIT License](LICENSE).
