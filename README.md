# Endpoint

An endpoint is a compounding of a *dns* and a *path*.

We have the `Dns` and `Path` model to represent each one of those types. And have `DnsPath` model with represents the compound of both.

# Testing

For repository tests, which requires a real database connection, use this variables:

* DB_ENDPOINTSCATALOG_HOST_TEST
* DB_ENDPOINTSCATALOG_NAME_TEST
* DB_ENDPOINTSCATALOG_USER_TEST
* DB_ENDPOINTSCATALOG_PASSWORD_TEST