
# Search Availability Filter

A query filter to search for buyer-accessible availabilities by.

## Structure

`SearchAvailabilityFilter`

## Fields

| Name | Type | Tags | Description | Getter | Setter |
|  --- | --- | --- | --- | --- | --- |
| `startAtRange` | [`TimeRange`](../../doc/models/time-range.md) | Required | Represents a generic time range. The start and end values are<br>represented in RFC 3339 format. Time ranges are customized to be<br>inclusive or exclusive based on the needs of a particular endpoint.<br>Refer to the relevant endpoint-specific documentation to determine<br>how time ranges are handled. | getStartAtRange(): TimeRange | setStartAtRange(TimeRange startAtRange): void |
| `locationId` | `?string` | Optional | The query expression to search for buyer-accessible availabilities with their location IDs matching the specified location ID.<br>This query expression cannot be set if `booking_id` is set.<br>**Constraints**: *Maximum Length*: `32` | getLocationId(): ?string | setLocationId(?string locationId): void |
| `segmentFilters` | [`?(SegmentFilter[])`](../../doc/models/segment-filter.md) | Optional | The query expression to search for buyer-accessible availabilities matching the specified list of segment filters.<br>If the size of the `segment_filters` list is `n`, the search returns availabilities with `n` segments per availability.<br><br>This query expression cannot be set if `booking_id` is set. | getSegmentFilters(): ?array | setSegmentFilters(?array segmentFilters): void |
| `bookingId` | `?string` | Optional | The query expression to search for buyer-accessible availabilities for an existing booking by matching the specified `booking_id` value.<br>This is commonly used to reschedule an appointment.<br>If this expression is set, the `location_id` and `segment_filters` expressions cannot be set.<br>**Constraints**: *Maximum Length*: `36` | getBookingId(): ?string | setBookingId(?string bookingId): void |

## Example (as JSON)

```json
{
  "start_at_range": {
    "start_at": null,
    "end_at": null
  },
  "location_id": null,
  "segment_filters": null,
  "booking_id": null
}
```

