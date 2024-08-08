
# Device Metadata

## Structure

`DeviceMetadata`

## Fields

| Name | Type | Tags | Description | Getter | Setter |
|  --- | --- | --- | --- | --- | --- |
| `batteryPercentage` | `?string` | Optional | The Terminal’s remaining battery percentage, between 1-100. | getBatteryPercentage(): ?string | setBatteryPercentage(?string batteryPercentage): void |
| `chargingState` | `?string` | Optional | The current charging state of the Terminal.<br>Options: `CHARGING`, `NOT_CHARGING` | getChargingState(): ?string | setChargingState(?string chargingState): void |
| `locationId` | `?string` | Optional | The ID of the Square seller business location associated with the Terminal. | getLocationId(): ?string | setLocationId(?string locationId): void |
| `merchantId` | `?string` | Optional | The ID of the Square merchant account that is currently signed-in to the Terminal. | getMerchantId(): ?string | setMerchantId(?string merchantId): void |
| `networkConnectionType` | `?string` | Optional | The Terminal’s current network connection type.<br>Options: `WIFI`, `ETHERNET` | getNetworkConnectionType(): ?string | setNetworkConnectionType(?string networkConnectionType): void |
| `paymentRegion` | `?string` | Optional | The country in which the Terminal is authorized to take payments. | getPaymentRegion(): ?string | setPaymentRegion(?string paymentRegion): void |
| `serialNumber` | `?string` | Optional | The unique identifier assigned to the Terminal, which can be found on the lower back<br>of the device. | getSerialNumber(): ?string | setSerialNumber(?string serialNumber): void |
| `osVersion` | `?string` | Optional | The current version of the Terminal’s operating system. | getOsVersion(): ?string | setOsVersion(?string osVersion): void |
| `appVersion` | `?string` | Optional | The current version of the application running on the Terminal. | getAppVersion(): ?string | setAppVersion(?string appVersion): void |
| `wifiNetworkName` | `?string` | Optional | The name of the Wi-Fi network to which the Terminal is connected. | getWifiNetworkName(): ?string | setWifiNetworkName(?string wifiNetworkName): void |
| `wifiNetworkStrength` | `?string` | Optional | The signal strength of the Wi-FI network connection.<br>Options: `POOR`, `FAIR`, `GOOD`, `EXCELLENT` | getWifiNetworkStrength(): ?string | setWifiNetworkStrength(?string wifiNetworkStrength): void |
| `ipAddress` | `?string` | Optional | The IP address of the Terminal. | getIpAddress(): ?string | setIpAddress(?string ipAddress): void |

## Example (as JSON)

```json
{
  "battery_percentage": null,
  "charging_state": null,
  "location_id": null,
  "merchant_id": null,
  "network_connection_type": null,
  "payment_region": null,
  "serial_number": null,
  "os_version": null,
  "app_version": null,
  "wifi_network_name": null,
  "wifi_network_strength": null,
  "ip_address": null
}
```

