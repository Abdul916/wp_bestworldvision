
# Customer Query

Represents filtering and sorting criteria for a [SearchCustomers](../../doc/apis/customers.md#search-customers) request.

## Structure

`CustomerQuery`

## Fields

| Name | Type | Tags | Description | Getter | Setter |
|  --- | --- | --- | --- | --- | --- |
| `filter` | [`?CustomerFilter`](../../doc/models/customer-filter.md) | Optional | Represents the filtering criteria in a [search query](../../doc/models/customer-query.md) that defines how to filter<br>customer profiles returned in [SearchCustomers](../../doc/apis/customers.md#search-customers) results. | getFilter(): ?CustomerFilter | setFilter(?CustomerFilter filter): void |
| `sort` | [`?CustomerSort`](../../doc/models/customer-sort.md) | Optional | Represents the sorting criteria in a [search query](../../doc/models/customer-query.md) that defines how to sort<br>customer profiles returned in [SearchCustomers](../../doc/apis/customers.md#search-customers) results. | getSort(): ?CustomerSort | setSort(?CustomerSort sort): void |

## Example (as JSON)

```json
{
  "filter": null,
  "sort": null
}
```

