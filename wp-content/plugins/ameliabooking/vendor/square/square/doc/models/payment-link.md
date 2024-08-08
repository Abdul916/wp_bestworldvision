
# Payment Link

## Structure

`PaymentLink`

## Fields

| Name | Type | Tags | Description | Getter | Setter |
|  --- | --- | --- | --- | --- | --- |
| `id` | `?string` | Optional | The Square-assigned ID of the payment link. | getId(): ?string | setId(?string id): void |
| `version` | `int` | Required | The Square-assigned version number, which is incremented each time an update is committed to the payment link.<br>**Constraints**: `<= 65535` | getVersion(): int | setVersion(int version): void |
| `description` | `?string` | Optional | The optional description of the `payment_link` object.<br>It is primarily for use by your application and is not used anywhere.<br>**Constraints**: *Maximum Length*: `4096` | getDescription(): ?string | setDescription(?string description): void |
| `orderId` | `?string` | Optional | The ID of the order associated with the payment link.<br>**Constraints**: *Maximum Length*: `192` | getOrderId(): ?string | setOrderId(?string orderId): void |
| `checkoutOptions` | [`?CheckoutOptions`](../../doc/models/checkout-options.md) | Optional | - | getCheckoutOptions(): ?CheckoutOptions | setCheckoutOptions(?CheckoutOptions checkoutOptions): void |
| `prePopulatedData` | [`?PrePopulatedData`](../../doc/models/pre-populated-data.md) | Optional | Describes buyer data to prepopulate in the payment form.<br>For more information,<br>see [Optional Checkout Configurations](https://developer.squareup.com/docs/checkout-api/optional-checkout-configurations). | getPrePopulatedData(): ?PrePopulatedData | setPrePopulatedData(?PrePopulatedData prePopulatedData): void |
| `url` | `?string` | Optional | The URL of the payment link.<br>**Constraints**: *Maximum Length*: `255` | getUrl(): ?string | setUrl(?string url): void |
| `createdAt` | `?string` | Optional | The timestamp when the payment link was created, in RFC 3339 format. | getCreatedAt(): ?string | setCreatedAt(?string createdAt): void |
| `updatedAt` | `?string` | Optional | The timestamp when the payment link was last updated, in RFC 3339 format. | getUpdatedAt(): ?string | setUpdatedAt(?string updatedAt): void |
| `paymentNote` | `?string` | Optional | An optional note. After Square processes the payment, this note is added to the<br>resulting `Payment`.<br>**Constraints**: *Maximum Length*: `500` | getPaymentNote(): ?string | setPaymentNote(?string paymentNote): void |

## Example (as JSON)

```json
{
  "id": null,
  "version": 172,
  "description": null,
  "order_id": null,
  "checkout_options": null,
  "pre_populated_data": null,
  "url": null,
  "created_at": null,
  "updated_at": null,
  "payment_note": null
}
```

