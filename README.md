# Hotel Management API

A Symfony-based, modular monolith that applies DDD (Domain-Driven Design) and CQRS (Command Query Responsibility Segregation) to manage hotels, bookings, availability, and reporting metrics.

## Tech Stack

- **Framework**: PHP 8.3, Symfony 6.4, Doctrine (ORM + DBAL), Messenger
- **Storage**: MySQL 8 (InnoDB), RabbitMQ
- **Environment**: Docker + Make
- **Testing**: Unit, Integration, Acceptance (Behat)

## Architecture

The app is organized into Bounded Contexts living in a single repo (modular monolith):

- **Hotel (Catalog)** – Hotel + rooms inventory, exposes hotel basic info.
  - Stores `number_of_rooms` as a persisted counter (updated on addRoom/removeRoom) to avoid runtime counts.
- **Availability (Read Model)** – Materialized calendar (`hotel_id`, `room_id`, `day`, `status`, `capacity`) for fast availability queries and atomic slot locking. Internal API only.
- **Booking (Write Model)** – Creates bookings for 1..N rooms; validates availability before persisting; emits domain events.
- **Metric (Read Model)** – Reporting: unique users per hotel (projection fed by booking events).

### Project Structure

```
src/
  Context/
    Availability/   # Read model & locker for room/day slots
    Booking/        # Commands, Booking aggregate, events
    Hotel/          # Hotel aggregate + read models (basic info)
    Metric/         # Read-only projections/queries (unique users)
  Shared/           # Cross-cutting: buses, base classes, utils
```

## API Endpoints

All responses are JSON. Prefix shown as `/api`.

### Health

- `GET /api/health` – liveness/readiness (simple 200).

### Hotel

- `GET /api/hotels/{hotelId}`
  Returns basic info:
  ```json
  { "id":"HOTEL-001", "name":"Hotel Demo", "city":"Madrid", "country":"ES", "number_of_rooms":5 }
  ```

- `GET /api/hotels/{hotelId}/availability?from=YYYY-MM-DD&to=YYYY-MM-DD`
  Returns rooms available for the whole range [from,to):
  ```json
  {
    "hotel_id":"HOTEL-001",
    "from":"2025-09-01",
    "to":"2025-09-05",
    "available_rooms":[{"room_id":"R-101","capacity":2}]
  }
  ```

### Booking

- `POST /api/bookings`
  Body:
  ```json
  { "hotel_id":"HOTEL-001", "user_id":"USER-123", "from":"2025-09-01", "to":"2025-09-05", "rooms":["R-101","R-102"] }
  ```
  - Validates rooms belong to the hotel and are free (via Availability).
  - Returns 201 on success; 409 if any room is not available.

### Metric

- `GET /api/metrics/hotel-users`
  Returns unique users per hotel:
  ```json
  {
    "data": [
      { "id":"HOTEL-001", "users":"2" },
      { "id":"HOTEL-002", "users":"1" }
    ],
    "metadata":[]
  }
  ```

> Note: Availability is an internal read model; Hotel/Booking call it via application services/ports (no public HTTP).

## Messaging (Domain Events)

RabbitMQ is used for domain event propagation (Symfony Messenger):
- **Transport**: `async_domain_event`
- **Example events**: `booking.created` / `rooms.booked`
- **Availability projector** updates `availability_calendar`.
- **Metric projector** maintains:
  - `metric_hotel_users_detail(hotel_id, user_id)` PK (hotel_id,user_id)
  - `metric_hotel_users(hotel_id, unique_users)` PK (hotel_id)
  - Both projections are idempotent and rely on DB constraints to avoid double counting

## Installation

The app is fully dockerized. From the project root:
```bash
make install   # build containers, install deps, create DB, run migrations
```

## Running Tests

The project implements a comprehensive testing strategy with three distinct levels:

```bash
make test-unit         # Unit tests validate individual components in isolation
make test-integration  # Integration tests verify components work together correctly
make test-acceptance   # Acceptance tests validate end-to-end business behavior
```

Acceptance tests exercise the API and messaging, asserting business behavior end-to-end.
