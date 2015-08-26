Base classes for entities.
Entities can be for example: user, product, event.

Every entity:
 - has its id and name
 - can have url (with seo friendly name)
 - can have admin (user who manages it)
 - can be cached

This module supports also "Record" class which extends ORM and is extended by Entity,
but can be extended by another non-entity classes too. It supports:
 - filters, validation rules and other methods common for many models
 - soft delete by copy data before delete/update to another database

Required modules:
 - orm
 - tools
 - components
