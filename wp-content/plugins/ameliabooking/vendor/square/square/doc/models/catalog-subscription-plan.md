
# Catalog Subscription Plan

Describes a subscription plan. For more information, see
[Set Up and Manage a Subscription Plan](https://developer.squareup.com/docs/subscriptions-api/setup-plan).

## Structure

`CatalogSubscriptionPlan`

## Fields

| Name | Type | Tags | Description | Getter | Setter |
|  --- | --- | --- | --- | --- | --- |
| `name` | `string` | Required | The name of the plan. | getName(): string | setName(string name): void |
| `phases` | [`?(SubscriptionPhase[])`](../../doc/models/subscription-phase.md) | Optional | A list of SubscriptionPhase containing the [SubscriptionPhase](../../doc/models/subscription-phase.md) for this plan.<br>This field it required. Not including this field will throw a REQUIRED_FIELD_MISSING error | getPhases(): ?array | setPhases(?array phases): void |

## Example (as JSON)

```json
{
  "name": "name0",
  "phases": null
}
```

