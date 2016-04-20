Feature: Test that enable and disable the core pages route works

  Background: A WP install
    Given a WP install

  Scenario: Enable and disable the core endpoints

    When I run `wp plugin activate rest-api-toolbox`
    And I run `wp rest-api-toolbox disable pages`
    And I run `wp rest-api-toolbox status pages`
    Then STDOUT should contain:
      """
      Core endpoint pages is disabled.
      """

    When I run `wp rest-api-toolbox enable pages`
    And I run `wp rest-api-toolbox status pages`
    Then STDOUT should contain:
      """
      Core endpoint pages is enabled.
      """


    When I run `wp rest-api-toolbox disable posts`
    And I run `wp rest-api-toolbox status posts`
    Then STDOUT should contain:
      """
      Core endpoint posts is disabled.
      """

    When I run `wp rest-api-toolbox enable posts`
    And I run `wp rest-api-toolbox status posts`
    Then STDOUT should contain:
      """
      Core endpoint posts is enabled.
      """


    When I run `wp rest-api-toolbox disable users`
    And I run `wp rest-api-toolbox status users`
    Then STDOUT should contain:
      """
      Core endpoint users is disabled.
      """

    When I run `wp rest-api-toolbox enable users`
    And I run `wp rest-api-toolbox status users`
    Then STDOUT should contain:
      """
      Core endpoint users is enabled.
      """


    When I run `wp rest-api-toolbox disable categories`
    And I run `wp rest-api-toolbox status categories`
    Then STDOUT should contain:
      """
      Core endpoint categories is disabled.
      """

    When I run `wp rest-api-toolbox enable categories`
    And I run `wp rest-api-toolbox status categories`
    Then STDOUT should contain:
      """
      Core endpoint categories is enabled.
      """


    When I run `wp rest-api-toolbox disable tags`
    And I run `wp rest-api-toolbox status tags`
    Then STDOUT should contain:
      """
      Core endpoint tags is disabled.
      """

    When I run `wp rest-api-toolbox enable tags`
    And I run `wp rest-api-toolbox status tags`
    Then STDOUT should contain:
      """
      Core endpoint tags is enabled.
      """


    When I run `wp rest-api-toolbox disable comments`
    And I run `wp rest-api-toolbox status comments`
    Then STDOUT should contain:
      """
      Core endpoint comments is disabled.
      """

    When I run `wp rest-api-toolbox enable comments`
    And I run `wp rest-api-toolbox status comments`
    Then STDOUT should contain:
      """
      Core endpoint comments is enabled.
      """


    When I run `wp rest-api-toolbox disable taxonomies`
    And I run `wp rest-api-toolbox status taxonomies`
    Then STDOUT should contain:
      """
      Core endpoint taxonomies is disabled.
      """

    When I run `wp rest-api-toolbox enable taxonomies`
    And I run `wp rest-api-toolbox status taxonomies`
    Then STDOUT should contain:
      """
      Core endpoint taxonomies is enabled.
      """


    When I run `wp rest-api-toolbox disable types`
    And I run `wp rest-api-toolbox status types`
    Then STDOUT should contain:
      """
      Core endpoint types is disabled.
      """

    When I run `wp rest-api-toolbox enable types`
    And I run `wp rest-api-toolbox status types`
    Then STDOUT should contain:
      """
      Core endpoint types is enabled.
      """


    When I run `wp rest-api-toolbox disable statuses`
    And I run `wp rest-api-toolbox status statuses`
    Then STDOUT should contain:
      """
      Core endpoint statuses is disabled.
      """

    When I run `wp rest-api-toolbox enable statuses`
    And I run `wp rest-api-toolbox status statuses`
    Then STDOUT should contain:
      """
      Core endpoint statuses is enabled.
      """
