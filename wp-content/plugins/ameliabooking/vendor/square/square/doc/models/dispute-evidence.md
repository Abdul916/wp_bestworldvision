
# Dispute Evidence

## Structure

`DisputeEvidence`

## Fields

| Name | Type | Tags | Description | Getter | Setter |
|  --- | --- | --- | --- | --- | --- |
| `evidenceId` | `?string` | Optional | The Square-generated ID of the evidence.<br>**Constraints**: *Minimum Length*: `1`, *Maximum Length*: `40` | getEvidenceId(): ?string | setEvidenceId(?string evidenceId): void |
| `id` | `?string` | Optional | The Square-generated ID of the evidence.<br>**Constraints**: *Minimum Length*: `1`, *Maximum Length*: `40` | getId(): ?string | setId(?string id): void |
| `disputeId` | `?string` | Optional | The ID of the dispute the evidence is associated with.<br>**Constraints**: *Minimum Length*: `1`, *Maximum Length*: `40` | getDisputeId(): ?string | setDisputeId(?string disputeId): void |
| `evidenceFile` | [`?DisputeEvidenceFile`](../../doc/models/dispute-evidence-file.md) | Optional | A file to be uploaded as dispute evidence. | getEvidenceFile(): ?DisputeEvidenceFile | setEvidenceFile(?DisputeEvidenceFile evidenceFile): void |
| `evidenceText` | `?string` | Optional | Raw text<br>**Constraints**: *Minimum Length*: `1`, *Maximum Length*: `500` | getEvidenceText(): ?string | setEvidenceText(?string evidenceText): void |
| `uploadedAt` | `?string` | Optional | The time when the evidence was uploaded, in RFC 3339 format.<br>**Constraints**: *Minimum Length*: `1`, *Maximum Length*: `40` | getUploadedAt(): ?string | setUploadedAt(?string uploadedAt): void |
| `evidenceType` | [`?string (DisputeEvidenceType)`](../../doc/models/dispute-evidence-type.md) | Optional | The type of the dispute evidence. | getEvidenceType(): ?string | setEvidenceType(?string evidenceType): void |

## Example (as JSON)

```json
{
  "evidence_id": null,
  "id": null,
  "dispute_id": null,
  "evidence_file": null,
  "evidence_text": null,
  "uploaded_at": null,
  "evidence_type": null
}
```

