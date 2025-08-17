Feature: Fetch Hotel Details
  In order to see hotel information and available rooms
  As a user
  I need to be able to fetch a hotel's basic details and its available rooms

  Scenario: It receives a valid request to fetch hotel details
    Given I have hotels
    #Given a hotel exists with the following details:
    #  | name           | city   | country |
    #  | NH Collection  | Madrid | ES      |
    #And the hotel has the following rooms:
    #  | number | type   |
    #  | 101    | single |
    #  | 102    | double |
    When I send a "GET" request to "/hotels/f4a8f92c-5208-4568-869c-3bc50bb28350"
    Then the response code should be 200
    And the response body should be:
    """
    {
      "data": {
        "id": "f4a8f92c-5208-4568-869c-3bc50bb28350",
        "name": "NH Collection",
        "city": "Madrid",
        "country": "ES"
      },
      "metadata": []
    }
    """
