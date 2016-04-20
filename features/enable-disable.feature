Feature: Test that enable and disable the REST API works

  Scenario: Disable the REST API
    Given a WP install

    When I run `wp plugin activate rest-api-toolbox`
    And I run `wp rest-api-toolbox disable`
    Then STDOUT should contain:
      """
      Success: REST API disabled (other plugins can override this)
      """

  Scenario: Enable the REST API
    Given a WP install

    When I run `wp plugin activate rest-api-toolbox`
    And I run `wp rest-api-toolbox enable`
    Then STDOUT should contain:
      """
      Success: REST API enabled (other plugins can override this)
      """

