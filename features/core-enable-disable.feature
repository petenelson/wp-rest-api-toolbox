Feature: Test that enable and disable the REST API works

  Scenario: Disable the REST API
    Given a WP install

    When I run `wp plugin activate rest-api-toolbox`
    And I run `wp rest-api-toolbox disable`
    And I run `wp rest-api-toolbox status`
    Then STDOUT should contain:
      """
      The REST API is disabled.
      """

    When I run `wp rest-api-toolbox enable`
    And I run `wp rest-api-toolbox status`
    Then STDOUT should contain:
      """
      The REST API is enabled.
      """
