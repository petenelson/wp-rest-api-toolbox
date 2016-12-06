Feature: Test SSL commands

  Scenario: Toggle SSL required/optional
    Given a WP install

    When I run `wp plugin activate rest-api-toolbox`
    And I run `wp rest-api-toolbox ssl required`
    And I run `wp rest-api-toolbox ssl status`
    Then STDOUT should contain:
      """
      SSL is required for REST API endpoints.
      """

    When I run `wp rest-api-toolbox enable`
    And I run `wp rest-api-toolbox ssl optional`
    And I run `wp rest-api-toolbox ssl status`
    Then STDOUT should contain:
      """
      SSL is optional for REST API endpoints.
      """
