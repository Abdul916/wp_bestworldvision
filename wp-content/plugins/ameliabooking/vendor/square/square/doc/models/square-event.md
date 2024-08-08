
# Square Event

## Structure

`SquareEvent`

## Fields

| Name | Type | Tags | Description | Getter | Setter |
|  --- | --- | --- | --- | --- | --- |
| `merchantId` | `?string` | Optional | The ID of the target merchant associated with the event. | getMerchantId(): ?string | setMerchantId(?string merchantId): void |
| `locationId` | `?string` | Optional | The ID of the location associated with the event. | getLocationId(): ?string | setLocationId(?string locationId): void |
| `type` | `?string` | Optional | The type of event this represents. | getType(): ?string | setType(?string type): void |
| `eventId` | `?string` | Optional | A unique ID for the event. | getEventId(): ?string | setEventId(?string eventId): void |
| `createdAt` | `?string` | Optional | Timestamp of when the event was created, in RFC 3339 format. | getCreatedAt(): ?string | setCreatedAt(?string createdAt): void |
| `data` | [`?SquareEventData`](../../doc/models/square-event-data.md) | Optional | - | getData(): ?SquareEventData | setData(?SquareEventData data): void |

## Example (as JSON)

```json
{
  "merchant_id": null,
  "location_id": null,
  "type": null,
  "event_id": null,
  "created_at": null,
  "data": null
}
```

