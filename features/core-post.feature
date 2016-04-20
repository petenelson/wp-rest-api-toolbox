Feature: Test that enable and disable the core post route works

  Scenario: Disable the core post endpoint
    Given a WP install

    When I run `wp plugin activate rest-api-toolbox`
    And I run `wp plugin activate rest-api`
    And I run `wp rest-api-toolbox disable posts`
    And I run `wp rest-api-toolbox status posts`
    Then STDOUT should contain:
      """
      Core endpoint posts is disabled.
      """

  Scenario: Enable the REST API
    Given a WP install

    When I run `wp plugin activate rest-api-toolbox`
    And I run `wp plugin activate rest-api`
    And I run `wp rest-api-toolbox enable posts`
    And I run `wp rest-api-toolbox status posts`
    Then STDOUT should contain:
      """
      Core endpoint posts is enabled.
      """

