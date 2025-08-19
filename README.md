# Hotel Management API

A Symfony-based, modular monolith that applies DDD (Domain-Driven Design) and CQRS (Command Query Responsibility Segregation) to manage hotels, bookings, availability, and reporting metrics.

## Tech Stack

- **Framework**: PHP 8.3, Symfony 6.4, Doctrine (ORM + DBAL), Messenger
- **Storage**: MySQL 8 (InnoDB), RabbitMQ
- **Environment**: Docker + Make
- **Testing**: Unit, Integration, Acceptance (Behat)

## Architecture

The app is organized into Bounded Contexts living in a single repo (modular monolith):

- **Hotel** – Hotel + rooms inventory, exposes hotel basic info and availability.
- **Availability (Read Model)** – Materialized calendar (`hotel_id`, `room_id`, `day`, `status`, `capacity`) for fast availability queries. Internal API only.
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

- `GET /health` – liveness/readiness (simple 200).

### Hotel

- `GET /hotels/{hotelId}`
  Returns basic info:
  ```json
  {
    "id": "f1c8e1a8-57b8-4d2b-8460-76c280de773e",
    "name": "Hotel Demo",
    "city": "Madrid",
    "country": "ES",
    "number_of_rooms": 5
  }
  ```

- `GET /hotels/{hotelId}/availability?from=YYYY-MM-DD&to=YYYY-MM-DD`
  Returns rooms available for the whole range [from,to):
  ```json
  {
    "hotel_id": "f1c8e1a8-57b8-4d2b-8460-76c280de773e",
    "from": "2025-09-01",
    "to": "2025-09-05",
    "available_rooms": [
      {
        "room_id": "9876dfc9-ef7a-48a0-8f31-a18742c0c828",
        "capacity": 2
      }
    ]
  }
  ```

### Booking

- `POST /bookings`
  Body:
  ```json
  {
    "hotel_id": "f1c8e1a8-57b8-4d2b-8460-76c280de773e",
    "user_id": "c2d8f8a3-4e17-4c9f-a5e6-d0912e8b235f",
    "from": "2025-09-01",
    "to": "2025-09-05",
    "rooms": [
      "9876dfc9-ef7a-48a0-8f31-a18742c0c828",
      "7b1821a5-cd2e-4e78-a2bc-5a8aabd71e5e"
    ]
  }
  ```
  - Validates rooms belong to the hotel and are free (via Availability).
  - Returns 201 on success; 409 if any room is not available.

### Metric

- `GET /metrics/hotel-users`
  Returns unique users per hotel:
  ```json
  {
    "data": [
      {
        "id": "f1c8e1a8-57b8-4d2b-8460-76c280de773e",
        "users": 2
      },
      {
        "id": "b2c1f8d3-4e5a-4c6b-8d7e-9f0a1b2c3d4e",
        "users": 1
      }
    ],
    "metadata": []
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

---

### Some clarifications and notes

* I strongly recommend reviewing the **acceptance tests**, as they’re the best guide to understand the system’s expected behavior and the API contracts—especially `metric.feature`, which **receives events from Rabbit, consumes them, and builds the metric projections in the handler**. I implemented this in the **simplest** possible way for the exercise. In production it wouldn’t be the most adequate or efficient, because there’s a risk of a **race condition** that could increment the number of unique users concurrently. This could be addressed by wrapping the operation in a **database transaction** and locking appropriately, or by using **persistent Redis** as a lock/helper with **MySQL as the source of truth**, there are multiple options.

* In the **Booking** context, the “create booking” use case **should check availability again**. Even if we just fetched availability, there may be a tiny time window where the room gets booked by someone else. So we must verify those rooms are **still available** and also **lock the dates** before confirming.

* I implemented **Availability** with a **calendar table** (one row per hotel room per day). Without this, we’d have to compute “available = total − reserved” on the fly, which is more expensive and slower. Ideally in production, the calendar would be prepopulated by reacting to a **HotelCreated** event (including its rooms). I made **Availability** its **own bounded context** because it’s called by both **Booking** and **Hotel** (or should be). Even if it’s not a public resource, it can grow a lot; pushing it into Hotel or Booking would overload those contexts. In the future, if it becomes a bottleneck, it can be **scaled independently**, for example as a microservice backed by **Elasticsearch** or another very fast search engine, fed by events from **Kafka topics** or **Rabbit queues**.

* You’ll see I often model relationships as **N\:M** even when they could be **1\:N**. I do this because Doctrine tends to push you toward **bidirectional associations**, and I usually prefer to **avoid having an entity know about its aggregate**.



