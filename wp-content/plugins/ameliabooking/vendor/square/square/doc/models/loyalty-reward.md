
# Loyalty Reward

Represents a contract to redeem loyalty points for a [reward tier](../../doc/models/loyalty-program-reward-tier.md) discount. Loyalty rewards can be in an ISSUED, REDEEMED, or DELETED state.
For more information, see [Manage loyalty rewards](https://developer.squareup.com/docs/loyalty-api/loyalty-rewards).

## Structure

`LoyaltyReward`

## Fields

| Name | Type | Tags | Description | Getter | Setter |
|  --- | --- | --- | --- | --- | --- |
| `id` | `?string` | Optional | The Square-assigned ID of the loyalty reward.<br>**Constraints**: *Maximum Length*: `36` | getId(): ?string | setId(?string id): void |
| `status` | [`?string (LoyaltyRewardStatus)`](../../doc/models/loyalty-reward-status.md) | Optional | The status of the loyalty reward. | getStatus(): ?string | setStatus(?string status): void |
| `loyaltyAccountId` | `string` | Required | The Square-assigned ID of the [loyalty account](../../doc/models/loyalty-account.md) to which the reward belongs.<br>**Constraints**: *Minimum Length*: `1`, *Maximum Length*: `36` | getLoyaltyAccountId(): string | setLoyaltyAccountId(string loyaltyAccountId): void |
| `rewardTierId` | `string` | Required | The Square-assigned ID of the [reward tier](../../doc/models/loyalty-program-reward-tier.md) used to create the reward.<br>**Constraints**: *Minimum Length*: `1`, *Maximum Length*: `36` | getRewardTierId(): string | setRewardTierId(string rewardTierId): void |
| `points` | `?int` | Optional | The number of loyalty points used for the reward.<br>**Constraints**: `>= 1` | getPoints(): ?int | setPoints(?int points): void |
| `orderId` | `?string` | Optional | The Square-assigned ID of the [order](../../doc/models/order.md) to which the reward is attached. | getOrderId(): ?string | setOrderId(?string orderId): void |
| `createdAt` | `?string` | Optional | The timestamp when the reward was created, in RFC 3339 format. | getCreatedAt(): ?string | setCreatedAt(?string createdAt): void |
| `updatedAt` | `?string` | Optional | The timestamp when the reward was last updated, in RFC 3339 format. | getUpdatedAt(): ?string | setUpdatedAt(?string updatedAt): void |
| `redeemedAt` | `?string` | Optional | The timestamp when the reward was redeemed, in RFC 3339 format. | getRedeemedAt(): ?string | setRedeemedAt(?string redeemedAt): void |

## Example (as JSON)

```json
{
  "id": null,
  "status": null,
  "loyalty_account_id": "loyalty_account_id0",
  "reward_tier_id": "reward_tier_id6",
  "points": null,
  "order_id": null,
  "created_at": null,
  "updated_at": null,
  "redeemed_at": null
}
```

