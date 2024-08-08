
# Search Vendors Request Filter

Defines supported query expressions to search for vendors by.

## Structure

`SearchVendorsRequestFilter`

## Fields

| Name | Type | Tags | Description | Getter | Setter |
|  --- | --- | --- | --- | --- | --- |
| `name` | `?(string[])` | Optional | The names of the [Vendor](../../doc/models/vendor.md) objects to retrieve. | getName(): ?array | setName(?array name): void |
| `status` | [`?(string[]) (VendorStatus)`](../../doc/models/vendor-status.md) | Optional | The statuses of the [Vendor](../../doc/models/vendor.md) objects to retrieve.<br>See [VendorStatus](#type-vendorstatus) for possible values | getStatus(): ?array | setStatus(?array status): void |

## Example (as JSON)

```json
{
  "name": null,
  "status": null
}
```

