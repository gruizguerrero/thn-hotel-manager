Feature: Booking rooms
  In order to reserve hotel rooms for a user
  As a client of the hotel API
  I want to be able to book one or more rooms

  @truncateDatabaseTables
  Scenario: Successfully creating a new booking
    When I send a "POST" request to "/bookings" with body
    """
    {
      "bookingId": "c89d006d-2a6c-4e63-a99d-763acd63725d",
      "userId": "3f1330c2-0780-46d0-873e-a4433bd86865",
      "hotelId": "b2c1f8d3-4e5a-4c6b-8d7e-9f0a1b2c3d4e",
      "checkInDate": "2025-09-01T15:00:00+00:00",
      "checkOutDate": "2025-09-03T11:00:00+00:00",
      "roomIds": [
        "cb802f58-e4af-432d-a4bd-1c002cbe8ca0",
        "c54b88bf-f2b6-4e08-889b-9c949ba59ab2"
      ]
    }
    """
    Then the response code should be 201
