Feature: Fetch Hotel Details
  In order to see hotel information and available rooms
  As a user
  I need to be able to fetch a hotel's basic details and its available rooms

  @truncateDatabaseTables
  Scenario: It receives a valid request to fetch hotel details
    Given I have hotels
    When I send a "GET" request to "/hotels/b2c1f8d3-4e5a-4c6b-8d7e-9f0a1b2c3d4e"
    Then the response code should be 200
    And the response body should be:
    """
    {
      "data": {
        "id": "b2c1f8d3-4e5a-4c6b-8d7e-9f0a1b2c3d4e",
        "name": "Hilton Garden Inn",
        "city": "Barcelona",
        "country": "ES",
        "numberOfRooms": 2
      },
      "metadata": []
    }
    """
