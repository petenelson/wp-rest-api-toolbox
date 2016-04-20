Feature: Test that enable and disable the core pages route works

  Scenario: Disable the core pages endpoint
    Given a WP install

    When I run `wp plugin activate rest-api-toolbox`
    And I run `wp plugin install rest-api --activate`
    And I run `wp rest-api-toolbox disable pages`
    And I run `wp rest-api-toolbox status pages`
    Then STDOUT should contain:
      """
      Core endpoint pages is disabled.
      """

  Scenario: Enable the core pages endpoint
    Given a WP install

    When I run `wp plugin activate rest-api-toolbox`
    And I run `wp plugin install rest-api --activate`
    And I run `wp rest-api-toolbox enable pages`
    And I run `wp rest-api-toolbox status pages`
    Then STDOUT should contain:
      """
      Core endpoint pages is enabled.
      """

