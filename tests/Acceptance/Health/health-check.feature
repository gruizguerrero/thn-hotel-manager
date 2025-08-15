Feature: Health check
  In order to check the availability of the service
  As unauthenticated User
  I need to be able to check the service status

  Scenario: It receives a valid request to health check endpoint
    When I send a "GET" request to "/health"
    Then the response code should be 200
    And the response body should be:
    """
    {"status":"Service Available"}
    """
