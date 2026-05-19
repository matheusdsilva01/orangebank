# GEMINI.md - OrangeJuiceBank

## Project Overview
OrangeJuiceBank is an investment banking simulator developed as part of a hackathon. It provides a RESTful API and a frontend interface for users to manage accounts (Current and Investment), perform financial operations (deposits, withdrawals, transfers), and invest in various assets (Stocks and Fixed Income like CDB and Tesouro Direto).

### Core Features
- **Account Management**: Separate Current and Investment accounts using Single Table Inheritance (STI).
- **Financial Operations**: Deposits, withdrawals, and internal/external transfers with automated tax calculation.
- **Investment Platform**: Buying and selling stocks and fixed income assets with real-time market simulation.
- **Reporting**: Financial statements, tax reports, and investment performance tracking.
- **Market Simulator**: Dynamic fluctuation of asset prices.

## Key Technologies
- **Backend**: Laravel 12, PHP 8.2+
- **Frontend**: Blade templates, Tailwind CSS 4, Vite, Chart.js
- **Database**: MySQL (supports UUIDs for all primary keys)
- **Financial Logic**: `brick/money` (via custom `MoneyCast` and `MoneyHelper`)
- **Testing**: Pest PHP 4
- **Code Quality**: PHPStan (Larastan), Rector, Laravel Pint

## Architecture & Design Patterns
- **Single Table Inheritance (STI)**: Used for the `Account` model, with `CurrentAccount` and `InvestmentAccount` sharing the same table.
- **Action Pattern**: Business logic is encapsulated in Action classes located in `app/Actions` (e.g., `DepositAction`, `BuyStockAction`).
- **Data Transfer Objects (DTOs)**: Used to pass structured data to actions, located in `app/Dto`.
- **Enum-Driven Logic**: Custom Enums in `app/Enums` manage types for accounts, transactions, and assets.
- **Strategy Pattern**: Used for investment calculators (e.g., `app/Strategies/FixedIncome`).
- **UUIDs**: All models use the `HasUuids` trait for primary keys.

## Building and Running
### Prerequisites
- PHP 8.2+
- Node.js 20+
- Composer & npm
- MySQL

### Setup Commands
```bash
# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Run migrations and seed database
php artisan migrate --seed

# Start development environment (Server, Vite, Queue)
composer run dev
```

### Testing
```bash
# Run all tests using Pest
composer test
```

## Development Conventions
- **Business Logic**: Always place business logic in Action classes. Avoid fat models or controllers.
- **Data Handling**: Use DTOs for passing data to Actions and Services.
- **Financial Accuracy**: Never use `float` for money values. Always use the `MoneyCast` or `MoneyHelper` which utilize `brick/money`.
- **Type Safety**: Use Enums for all "type" fields and ensure proper return types in methods.
- **Testing**: Follow the Pest testing conventions. New features must include corresponding feature tests in `tests/Feature`.
- **Coding Style**: Adhere to Laravel Pint standards (automatically enforced via `vendor/bin/pint`).
- **Static Analysis**: Ensure all changes pass PHPStan level 9 (run `vendor/bin/phpstan`).

## Key Files & Directories
- `app/Actions/`: Core business logic units.
- `app/Dto/`: Data structures for logic flow.
- `app/Models/Account/`: Account hierarchy (STI).
- `app/Support/MoneyHelper.php`: Centralized financial arithmetic.
- `public/regradenegocio.md`: Detailed business rules and taxation logic.
- `database/seeders/DatabaseSeeder.php`: Initial data for users and assets.
