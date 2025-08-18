Feature: Fetch Metrics (unique users per hotel)
  In order to see hotel metrics
  As a client
  I need to fetch the number of unique users per hotel

  @truncateDatabaseTables @purgeQueues
  Scenario: It returns unique users per hotel
    And I publish booking created event with:
    """
    [
      {
        "payload": {
          "aggregate_root_id": "b72d581e-115c-4f43-9c99-4d4dffe7dccc",
          "hotel_id": "b2c1f8d3-4e5a-4c6b-8d7e-9f0a1b2c3d4e",
          "user_id": "b2c1f8d3-4e5a-4c6b-8d7e-9f0a1b2c3d4e",
          "check_in_date": "2025-08-12T16:32:43+00:00",
          "check_out_date": "2025-08-12T16:32:43+00:00"
        },
        "metadata": {
          "name": "thn.booking_management.domain_event.booking.created",
          "version": "1.0",
          "aggregate_root_id": "b72d581e-115c-4f43-9c99-4d4dffe7dccc",
          "aggregate_version": 1,
          "occurred_on": "2025-08-12T16:32:43+00:00"
        }
      },
      {
        "payload": {
          "aggregate_root_id": "8191f3be-eebb-481c-9dee-22a446da9bb4",
          "hotel_id": "8867d1c4-ed59-4045-8b21-1527e78be51c",
          "user_id": "63da5c9b-d567-47d1-88e2-04182dabfcdb",
          "check_in_date": "2025-08-12T16:32:43+00:00",
          "check_out_date": "2025-08-12T16:32:43+00:00"
        },
        "metadata": {
          "name": "thn.booking_management.domain_event.booking.created",
          "version": "1.0",
          "aggregate_root_id": "8191f3be-eebb-481c-9dee-22a446da9bb4",
          "aggregate_version": 1,
          "occurred_on": "2025-08-12T16:32:43+00:00"
        }
      }
    ]
    """
    And I have 2 domain messages of type "thn.booking_management.domain_event.booking.created" dispatched
    When I consume "2" messages from "async_domain_event" transport for "messenger.bus.events" bus
    When I send a "GET" request to "/metrics"
    Then the response code should be 200
    And the response body should be:
    """
    {
      "data": [
        {
          "id": "8867d1c4-ed59-4045-8b21-1527e78be51c",
          "users": "1"
        },
        {
          "id": "b2c1f8d3-4e5a-4c6b-8d7e-9f0a1b2c3d4e",
          "users": "1"
        }
      ],
      "metadata": []
    }
    """
