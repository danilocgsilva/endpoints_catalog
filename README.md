# Endpoint

An endpoint is a compounding of a *dns* and a *path*.

We have the `Dns` and `Path` model to represent each one of those types. And have `DnsPath` model with represents the compound of both.

# Testing

For repository tests, which requires a real database connection, use this variables:

* DB_ENDPOINTSCATALOG_HOST_TEST
* DB_ENDPOINTSCATALOG_NAME_TEST
* DB_ENDPOINTSCATALOG_USER_TEST
* DB_ENDPOINTSCATALOG_PASSWORD_TEST

## Patterns

A simple and efective Model > Migration patterns. Look to the MigrationManager, which is required in case of having differents database versions schema. If the project can existis with just one migration version, no MigrationManager is required.

Also there have a nice database manipulations to manage data for testing the repositories, without using repositories itself.
