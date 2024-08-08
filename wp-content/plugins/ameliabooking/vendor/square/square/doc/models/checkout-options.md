
# Checkout Options

## Structure

`CheckoutOptions`

## Fields

| Name | Type | Tags | Description | Getter | Setter |
|  --- | --- | --- | --- | --- | --- |
| `allowTipping` | `?bool` | Optional | Indicates whether the payment allows tipping. | getAllowTipping(): ?bool | setAllowTipping(?bool allowTipping): void |
| `customFields` | [`?(CustomField[])`](../../doc/models/custom-field.md) | Optional | The custom fields requesting information from the buyer. | getCustomFields(): ?array | setCustomFields(?array customFields): void |
| `subscriptionPlanId` | `?string` | Optional | The ID of the subscription plan for the buyer to pay and subscribe.<br>For more information, see [Subscription Plan Checkout](https://developer.squareup.com/docs/checkout-api/subscription-plan-checkout).<br>**Constraints**: *Maximum Length*: `255` | getSubscriptionPlanId(): ?string | setSubscriptionPlanId(?string subscriptionPlanId): void |
| `redirectUrl` | `?string` | Optional | The confirmation page URL to redirect the buyer to after Square processes the payment.<br>**Constraints**: *Maximum Length*: `2048` | getRedirectUrl(): ?string | setRedirectUrl(?string redirectUrl): void |
| `merchantSupportEmail` | `?string` | Optional | The email address that buyers can use to contact the seller.<br>**Constraints**: *Maximum Length*: `256` | getMerchantSupportEmail(): ?string | setMerchantSupportEmail(?string merchantSupportEmail): void |
| `askForShippingAddress` | `?bool` | Optional | Indicates whether to include the address fields in the payment form. | getAskForShippingAddress(): ?bool | setAskForShippingAddress(?bool askForShippingAddress): void |
| `acceptedPaymentMethods` | [`?AcceptedPaymentMethods`](../../doc/models/accepted-payment-methods.md) | Optional | - | getAcceptedPaymentMethods(): ?AcceptedPaymentMethods | setAcceptedPaymentMethods(?AcceptedPaymentMethods acceptedPaymentMethods): void |
| `appFeeMoney` | [`?Money`](../../doc/models/money.md) | Optional | Represents an amount of money. `Money` fields can be signed or unsigned.<br>Fields that do not explicitly define whether they are signed or unsigned are<br>considered unsigned and can only hold positive amounts. For signed fields, the<br>sign of the value indicates the purpose of the money transfer. See<br>[Working with Monetary Amounts](https://developer.squareup.com/docs/build-basics/working-with-monetary-amounts)<br>for more information. | getAppFeeMoney(): ?Money | setAppFeeMoney(?Money appFeeMoney): void |
| `shippingFee` | [`?ShippingFee`](../../doc/models/shipping-fee.md) | Optional | - | getShippingFee(): ?ShippingFee | setShippingFee(?ShippingFee shippingFee): void |

## Example (as JSON)

```json
{
  "allow_tipping": null,
  "custom_fields": null,
  "subscription_plan_id": null,
  "redirect_url": null,
  "merchant_support_email": null,
  "ask_for_shipping_address": null,
  "accepted_payment_methods": null,
  "app_fee_money": null,
  "shipping_fee": null
}
```

