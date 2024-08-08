
# Bulk Upsert Location Custom Attributes Response

Represents a [BulkUpsertLocationCustomAttributes](../../doc/apis/location-custom-attributes.md#bulk-upsert-location-custom-attributes) response,
which contains a map of responses that each corresponds to an individual upsert request.

## Structure

`BulkUpsertLocationCustomAttributesResponse`

## Fields

| Name | Type | Tags | Description | Getter | Setter |
|  --- | --- | --- | --- | --- | --- |
| `values` | [`?array<string,BulkUpsertLocationCustomAttributesResponseLocationCustomAttributeUpsertResponse>`](../../doc/models/bulk-upsert-location-custom-attributes-response-location-custom-attribute-upsert-response.md) | Optional | A map of responses that correspond to individual upsert requests. Each response has the<br>same ID as the corresponding request and contains either a `location_id` and `custom_attribute` or an `errors` field. | getValues(): ?array | setValues(?array values): void |
| `errors` | [`?(Error[])`](../../doc/models/error.md) | Optional | Any errors that occurred during the request. | getErrors(): ?array | setErrors(?array errors): void |

## Example (as JSON)

```json
{
  "values": null,
  "errors": null
}
```

