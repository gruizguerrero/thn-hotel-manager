# Hotel Management API

## Project Description

Hotel Management is a Symfony-based application that implements a modular monolith architecture with DDD (Domain-Driven Design) and CQRS (Command Query Responsibility Segregation). The project is designed to manage hotels, bookings, campaigns, and metrics related to the hotel industry.

## Architecture

The application is structured following Domain-Driven Design principles with clearly defined Bounded Contexts that coexist within a single application:

- **Hotel**: Administration of hotels and rooms
- **Booking**: Room reservation system
- **Metric**: User analytics and metrics per hotel (read-only)

Each context follows the CQRS pattern separating read and write operations, and uses Event Sourcing to maintain consistency between contexts.

## Project Structure

```
src/
  Context/
    Availability/      # Context for availability management
    Booking/           # Context for booking management
    Campaign/          # Context for campaign management
    Hotel/             # Context for hotel management
    Metric/            # Context for metrics (read-only)
  Shared/              # Code shared between contexts
    Application/       # Shared application logic
    Domain/            # Shared domain entities and rules
    Infrastructure/    # Infrastructure implementations
    UI/                # User interface components
```

## API Endpoints

Based on the routes configuration, the API provides endpoints for the following domains:

### Health Endpoints
- Health check endpoints to verify the application status

### Campaign Endpoints
- Endpoints for managing promotional campaigns

### Hotel Endpoints
- Endpoints for hotel management operations

### Booking Endpoints
- Endpoints for booking management

### Metric Endpoints
- Endpoints for retrieving metrics and analytics data

For detailed API documentation, you can use the following endpoints when available:
- Swagger UI: `/api/doc`
- OpenAPI Specification: `/api/doc.json`

## Requirements

- PHP 8.1 or higher
- Symfony 6.x
- MySQL 8.0
- RabbitMQ 3.x
- Redis (optional, for metrics optimization)

## Installation

1. Clone the repository:
   ```
   git clone [repository-url]
   cd hotel-management
   ```

2. Install dependencies:
   ```
   composer install
   ```

3. Set up environment variables:
   ```
   cp .env .env.local
   # Edit .env.local with your configurations
   ```

4. Start Docker containers:
   ```
   docker-compose up -d
   ```

5. Run migrations:
   ```
   bin/console doctrine:migrations:migrate
   ```

6. Load test data:
   ```
   bin/console doctrine:fixtures:load
   ```

## Testing

The project includes three levels of testing:

### Unit Tests
```
make test-unit
```

### Integration Tests
```
make test-integration
```

### Acceptance Tests
```
make test-acceptance
```

## Messaging Architecture

The system uses RabbitMQ for asynchronous communication between contexts:

- `async_domain_event` - Queue for domain events


## License

[Include license information]
