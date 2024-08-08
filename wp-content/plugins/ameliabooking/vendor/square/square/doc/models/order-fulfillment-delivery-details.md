
# Order Fulfillment Delivery Details

Describes delivery details of an order fulfillment.

## Structure

`OrderFulfillmentDeliveryDetails`

## Fields

| Name | Type | Tags | Description | Getter | Setter |
|  --- | --- | --- | --- | --- | --- |
| `recipient` | [`?OrderFulfillmentRecipient`](../../doc/models/order-fulfillment-recipient.md) | Optional | Information about the fulfillment recipient. | getRecipient(): ?OrderFulfillmentRecipient | setRecipient(?OrderFulfillmentRecipient recipient): void |
| `scheduleType` | [`?string (OrderFulfillmentDeliveryDetailsScheduleType)`](../../doc/models/order-fulfillment-delivery-details-schedule-type.md) | Optional | The schedule type of the delivery fulfillment. | getScheduleType(): ?string | setScheduleType(?string scheduleType): void |
| `placedAt` | `?string` | Optional | The [timestamp](https://developer.squareup.com/docs/build-basics/working-with-dates)<br>indicating when the fulfillment was placed.<br>The timestamp must be in RFC 3339 format (for example, "2016-09-04T23:59:33.123Z").<br>Must be in RFC 3339 timestamp format, e.g., "2016-09-04T23:59:33.123Z". | getPlacedAt(): ?string | setPlacedAt(?string placedAt): void |
| `deliverAt` | `?string` | Optional | The [timestamp](https://developer.squareup.com/docs/build-basics/working-with-dates)<br>that represents the start of the delivery period.<br>When the fulfillment `schedule_type` is `ASAP`, the field is automatically<br>set to the current time plus the `prep_time_duration`.<br>Otherwise, the application can set this field while the fulfillment `state` is<br>`PROPOSED`, `RESERVED`, or `PREPARED` (any time before the<br>terminal state such as `COMPLETED`, `CANCELED`, and `FAILED`).<br><br>The timestamp must be in RFC 3339 format<br>(for example, "2016-09-04T23:59:33.123Z"). | getDeliverAt(): ?string | setDeliverAt(?string deliverAt): void |
| `prepTimeDuration` | `?string` | Optional | The duration of time it takes to prepare and deliver this fulfillment.<br>The timestamp must be in RFC 3339 format (for example, "P1W3D"). | getPrepTimeDuration(): ?string | setPrepTimeDuration(?string prepTimeDuration): void |
| `deliveryWindowDuration` | `?string` | Optional | The time period after the `deliver_at` timestamp in which to deliver the order.<br>Applications can set this field when the fulfillment `state` is<br>`PROPOSED`, `RESERVED`, or `PREPARED` (any time before the terminal state<br>such as `COMPLETED`, `CANCELED`, and `FAILED`).<br>The timestamp must be in RFC 3339 format (for example, "P1W3D"). | getDeliveryWindowDuration(): ?string | setDeliveryWindowDuration(?string deliveryWindowDuration): void |
| `note` | `?string` | Optional | Provides additional instructions about the delivery fulfillment.<br>It is displayed in the Square Point of Sale application and set by the API.<br>**Constraints**: *Maximum Length*: `550` | getNote(): ?string | setNote(?string note): void |
| `completedAt` | `?string` | Optional | The [timestamp](https://developer.squareup.com/docs/build-basics/working-with-dates)<br>indicates when the seller completed the fulfillment.<br>This field is automatically set when  fulfillment `state` changes to `COMPLETED`.<br>The timestamp must be in RFC 3339 format (for example, "2016-09-04T23:59:33.123Z"). | getCompletedAt(): ?string | setCompletedAt(?string completedAt): void |
| `inProgressAt` | `?string` | Optional | The [timestamp](https://developer.squareup.com/docs/build-basics/working-with-dates)<br>indicates when the seller started processing the fulfillment.<br>This field is automatically set when the fulfillment `state` changes to `RESERVED`.<br>The timestamp must be in RFC 3339 format (for example, "2016-09-04T23:59:33.123Z"). | getInProgressAt(): ?string | setInProgressAt(?string inProgressAt): void |
| `rejectedAt` | `?string` | Optional | The [timestamp](https://developer.squareup.com/docs/build-basics/working-with-dates)<br>indicating when the fulfillment was rejected. This field is<br>automatically set when the fulfillment `state` changes to `FAILED`.<br>The timestamp must be in RFC 3339 format (for example, "2016-09-04T23:59:33.123Z"). | getRejectedAt(): ?string | setRejectedAt(?string rejectedAt): void |
| `readyAt` | `?string` | Optional | The [timestamp](https://developer.squareup.com/docs/build-basics/working-with-dates)<br>indicating when the seller marked the fulfillment as ready for<br>courier pickup. This field is automatically set when the fulfillment `state` changes<br>to PREPARED.<br>The timestamp must be in RFC 3339 format (for example, "2016-09-04T23:59:33.123Z"). | getReadyAt(): ?string | setReadyAt(?string readyAt): void |
| `deliveredAt` | `?string` | Optional | The [timestamp](https://developer.squareup.com/docs/build-basics/working-with-dates)<br>indicating when the fulfillment was delivered to the recipient.<br>The timestamp must be in RFC 3339 format (for example, "2016-09-04T23:59:33.123Z"). | getDeliveredAt(): ?string | setDeliveredAt(?string deliveredAt): void |
| `canceledAt` | `?string` | Optional | The [timestamp](https://developer.squareup.com/docs/build-basics/working-with-dates)<br>indicating when the fulfillment was canceled. This field is automatically<br>set when the fulfillment `state` changes to `CANCELED`.<br><br>The timestamp must be in RFC 3339 format (for example, "2016-09-04T23:59:33.123Z"). | getCanceledAt(): ?string | setCanceledAt(?string canceledAt): void |
| `cancelReason` | `?string` | Optional | The delivery cancellation reason. Max length: 100 characters.<br>**Constraints**: *Maximum Length*: `100` | getCancelReason(): ?string | setCancelReason(?string cancelReason): void |
| `courierPickupAt` | `?string` | Optional | The [timestamp](https://developer.squareup.com/docs/build-basics/working-with-dates)<br>indicating when an order can be picked up by the courier for delivery.<br>The timestamp must be in RFC 3339 format (for example, "2016-09-04T23:59:33.123Z"). | getCourierPickupAt(): ?string | setCourierPickupAt(?string courierPickupAt): void |
| `courierPickupWindowDuration` | `?string` | Optional | The period of time in which the order should be picked up by the courier after the<br>`courier_pickup_at` timestamp.<br>The time must be in RFC 3339 format (for example, "P1W3D"). | getCourierPickupWindowDuration(): ?string | setCourierPickupWindowDuration(?string courierPickupWindowDuration): void |
| `isNoContactDelivery` | `?bool` | Optional | Whether the delivery is preferred to be no contact. | getIsNoContactDelivery(): ?bool | setIsNoContactDelivery(?bool isNoContactDelivery): void |
| `dropoffNotes` | `?string` | Optional | A note to provide additional instructions about how to deliver the order.<br>**Constraints**: *Maximum Length*: `550` | getDropoffNotes(): ?string | setDropoffNotes(?string dropoffNotes): void |
| `courierProviderName` | `?string` | Optional | The name of the courier provider.<br>**Constraints**: *Maximum Length*: `255` | getCourierProviderName(): ?string | setCourierProviderName(?string courierProviderName): void |
| `courierSupportPhoneNumber` | `?string` | Optional | The support phone number of the courier.<br>**Constraints**: *Maximum Length*: `17` | getCourierSupportPhoneNumber(): ?string | setCourierSupportPhoneNumber(?string courierSupportPhoneNumber): void |
| `squareDeliveryId` | `?string` | Optional | The identifier for the delivery created by Square.<br>**Constraints**: *Maximum Length*: `50` | getSquareDeliveryId(): ?string | setSquareDeliveryId(?string squareDeliveryId): void |
| `externalDeliveryId` | `?string` | Optional | The identifier for the delivery created by the third-party courier service.<br>**Constraints**: *Maximum Length*: `50` | getExternalDeliveryId(): ?string | setExternalDeliveryId(?string externalDeliveryId): void |
| `managedDelivery` | `?bool` | Optional | The flag to indicate the delivery is managed by a third party (ie DoorDash), which means<br>we may not receive all recipient information for PII purposes. | getManagedDelivery(): ?bool | setManagedDelivery(?bool managedDelivery): void |

## Example (as JSON)

```json
{
  "recipient": null,
  "schedule_type": null,
  "placed_at": null,
  "deliver_at": null,
  "prep_time_duration": null,
  "delivery_window_duration": null,
  "note": null,
  "completed_at": null,
  "in_progress_at": null,
  "rejected_at": null,
  "ready_at": null,
  "delivered_at": null,
  "canceled_at": null,
  "cancel_reason": null,
  "courier_pickup_at": null,
  "courier_pickup_window_duration": null,
  "is_no_contact_delivery": null,
  "dropoff_notes": null,
  "courier_provider_name": null,
  "courier_support_phone_number": null,
  "square_delivery_id": null,
  "external_delivery_id": null,
  "managed_delivery": null
}
```

