
# Create Customer Custom Attribute Definition Request

Represents a [CreateCustomerCustomAttributeDefinition](../../doc/apis/customer-custom-attributes.md#create-customer-custom-attribute-definition) request.

## Structure

`CreateCustomerCustomAttributeDefinitionRequest`

## Fields

| Name | Type | Tags | Description | Getter | Setter |
|  --- | --- | --- | --- | --- | --- |
| `customAttributeDefinition` | [`CustomAttributeDefinition`](../../doc/models/custom-attribute-definition.md) | Required | Represents a definition for custom attribute values. A custom attribute definition<br>specifies the key, visibility, schema, and other properties for a custom attribute. | getCustomAttributeDefinition(): CustomAttributeDefinition | setCustomAttributeDefinition(CustomAttributeDefinition customAttributeDefinition): void |
| `idempotencyKey` | `?string` | Optional | A unique identifier for this request, used to ensure idempotency. For more information,<br>see [Idempotency](https://developer.squareup.com/docs/build-basics/common-api-patterns/idempotency).<br>**Constraints**: *Maximum Length*: `45` | getIdempotencyKey(): ?string | setIdempotencyKey(?string idempotencyKey): void |

## Example (as JSON)

```json
{
  "custom_attribute_definition": {
    "description": "The favorite movie of the customer.",
    "key": "favoritemovie",
    "name": "Favorite Movie",
    "schema": null,
    "visibility": "VISIBILITY_HIDDEN"
  }
}
```

