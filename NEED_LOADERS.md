# Livewire Loading State Tracker

This file tracks every Livewire-triggered UI interaction found in `resources/views/livewire` during the loading-state pass completed on 2026-03-28.

Status legend:

- `Implemented`: a visible loading state is now present for that interaction.
- `Covered by submit`: field binding is deferred and the user-facing loader is shown on the submit action that sends the request.

## User

| View | Interaction | Status | Loading treatment |
| --- | --- | --- | --- |
| `resources/views/livewire/user/profile-details-component.blade.php` | `updateProfile` submit | Implemented | Submit button disables and shows spinner/text. |
| `resources/views/livewire/user/profile-details-component.blade.php` | `changePassword` submit | Implemented | Submit button disables and shows spinner/text. |
| `resources/views/livewire/user/profile-details-component.blade.php` | `wire:model.defer` profile/password fields | Covered by submit | Requests are surfaced through the matching submit button loader. |

## Public Catalog

| View | Interaction | Status | Loading treatment |
| --- | --- | --- | --- |
| `resources/views/livewire/public-products-list-component.blade.php` | `search` live search | Implemented | Refreshing helper text plus result-grid dimming. |
| `resources/views/livewire/public-products-list-component.blade.php` | `categoryCode` filter | Implemented | Refreshing helper text plus result-grid dimming. |
| `resources/views/livewire/public-products-list-component.blade.php` | `stockFilter` filter | Implemented | Refreshing helper text plus result-grid dimming. |

## Agent

| View | Interaction | Status | Loading treatment |
| --- | --- | --- | --- |
| `resources/views/livewire/agent-products-component.blade.php` | `search` live search | Implemented | Toolbar refresh helper plus product-grid dimming. |
| `resources/views/livewire/agent-products-component.blade.php` | `categoryId` filter | Implemented | Toolbar refresh helper plus product-grid dimming. |
| `resources/views/livewire/agent-products-component.blade.php` | `stockFilter` filter | Implemented | Toolbar refresh helper plus product-grid dimming. |
| `resources/views/livewire/agent-products-component.blade.php` | `startPurchase(code)` | Implemented | Buy button disables and swaps icon/text for spinner text. |
| `resources/views/livewire/agent-products-component.blade.php` | `recipientPhone` live validation | Implemented | Inline validation helper with spinner. |
| `resources/views/livewire/agent-products-component.blade.php` | `backToBrowse` | Implemented | Close/cancel buttons disable and show closing state. |
| `resources/views/livewire/agent-products-component.blade.php` | `proceedToConfirm` | Implemented | Continue button disables and shows spinner/text. |
| `resources/views/livewire/agent-products-component.blade.php` | `$set('wizardStep','recipient')` | Implemented | Back button disables and shows an inline spinner while the step changes. |
| `resources/views/livewire/agent-products-component.blade.php` | `submitPurchase` | Implemented | Confirm button disables and shows spinner/text. |
| `resources/views/livewire/agent/manage-orders-component.blade.php` | `checkStatus(code)` | Implemented | Row action button disables and shows spinner/text. |

## Admin

| View | Interaction | Status | Loading treatment |
| --- | --- | --- | --- |
| `resources/views/livewire/admin/admin-dashboard-orders-component.blade.php` | `confirmPurchase(code)` | Implemented | Row action button disables and shows spinner/text. |
| `resources/views/livewire/admin/admin-dashboard-orders-component.blade.php` | `approvePurchase(code)` | Implemented | Row action button disables and shows spinner/text. |
| `resources/views/livewire/admin/admin-sales-chart-component.blade.php` | `range` change | Implemented | Selector disables, helper text appears, chart area dims with refresh badge. |
| `resources/views/livewire/admin/admin-topups-chart-component.blade.php` | `range` change | Implemented | Selector disables, helper text appears, chart area dims with refresh badge. |
| `resources/views/livewire/admin/agent-detail-component.blade.php` | `activateAcc` | Implemented | Action button disables and shows spinner/text. |
| `resources/views/livewire/admin/agent-detail-component.blade.php` | `amount` live update | Implemented | Inline helper text shows while Livewire syncs the amount. |
| `resources/views/livewire/admin/agent-detail-component.blade.php` | `topUp` submit | Implemented | Submit button disables and shows spinner/text. |
| `resources/views/livewire/admin/agents-component.blade.php` | `query` live search | Implemented | Search helper text plus table dimming. |
| `resources/views/livewire/admin/agents-component.blade.php` | `deleteAcc(code)` | Implemented | Row action button disables and shows spinner/text. |
| `resources/views/livewire/admin/agents-component.blade.php` | `activateAcc(code)` | Implemented | Row action button disables and shows spinner/text. |
| `resources/views/livewire/admin/agents-component.blade.php` | `amount` live update | Implemented | Inline helper text shows while Livewire syncs the amount. |
| `resources/views/livewire/admin/agents-component.blade.php` | `updateAgentWallet` | Implemented | Action button disables and shows spinner/text. |
| `resources/views/livewire/admin/category-component.blade.php` | `newCategory.name` blur sync | Implemented | Inline helper text shows while the field syncs. |
| `resources/views/livewire/admin/category-component.blade.php` | `saveCategory` submit | Implemented | Submit button disables and shows spinner/text. |
| `resources/views/livewire/admin/category-component.blade.php` | `clearSelection` | Implemented | Button disables and shows spinner/text. |
| `resources/views/livewire/admin/category-component.blade.php` | `setForEdit(code)` | Implemented | Row icon button disables and swaps to spinner. |
| `resources/views/livewire/admin/category-component.blade.php` | `deleteCat(code)` | Implemented | Row icon button disables and swaps to spinner. |
| `resources/views/livewire/admin/manage-credentials-component.blade.php` | `lock` | Implemented | Vault button disables and shows spinner/text. |
| `resources/views/livewire/admin/manage-credentials-component.blade.php` | `openUnlockModal` | Implemented | Vault button disables and shows spinner/text. |
| `resources/views/livewire/admin/manage-credentials-component.blade.php` | `requestSave` submit | Implemented | Save button disables and shows spinner. |
| `resources/views/livewire/admin/manage-credentials-component.blade.php` | `closeUnlockModal` | Implemented | Cancel button disables and modal displays a closing badge. |
| `resources/views/livewire/admin/manage-credentials-component.blade.php` | `unlock` button / enter key | Implemented | Unlock button disables and shows spinner/text. |
| `resources/views/livewire/admin/manage-credentials-component.blade.php` | `closeSaveModal` | Implemented | Cancel button disables and modal displays a closing badge. |
| `resources/views/livewire/admin/manage-credentials-component.blade.php` | `confirmSave` button / enter key | Implemented | Confirm button disables and shows spinner/text. |
| `resources/views/livewire/admin/manage-settings-component.blade.php` | `saveRecord` submit | Implemented | Submit button disables and shows spinner/text. |
| `resources/views/livewire/admin/manage-settings-component.blade.php` | Settings field bindings | Covered by submit | User-facing feedback appears on `saveRecord`, which commits the changes. |
| `resources/views/livewire/admin/orders-component.blade.php` | `clearFilters` | Implemented | Button disables and shows spinner/text. |
| `resources/views/livewire/admin/orders-component.blade.php` | `search` live search | Implemented | Refresh helper text plus table dimming. |
| `resources/views/livewire/admin/orders-component.blade.php` | `status` filter | Implemented | Refresh helper text plus table dimming. |
| `resources/views/livewire/admin/orders-component.blade.php` | `payment` filter | Implemented | Refresh helper text plus table dimming. |
| `resources/views/livewire/admin/orders-component.blade.php` | `agentId` filter | Implemented | Refresh helper text plus table dimming. |
| `resources/views/livewire/admin/orders-component.blade.php` | `categoryId` filter | Implemented | Refresh helper text plus table dimming. |
| `resources/views/livewire/admin/orders-component.blade.php` | `dateFrom` filter | Implemented | Refresh helper text plus table dimming. |
| `resources/views/livewire/admin/orders-component.blade.php` | `dateTo` filter | Implemented | Refresh helper text plus table dimming. |
| `resources/views/livewire/admin/orders-component.blade.php` | `confirmPurchase(id)` | Implemented | Row action button disables and shows spinner/text. |
| `resources/views/livewire/admin/orders-component.blade.php` | `approvePurchase(id)` | Implemented | Row action button disables and shows spinner/text. |
| `resources/views/livewire/admin/product-component.blade.php` | `categoryId` live change | Implemented | Catalog hint switches to loading state and shows animated loading bar. |
| `resources/views/livewire/admin/product-component.blade.php` | `fillFromCatalog(name)` | Implemented | Catalog panel enters loading state and shows the loading bar while applying a selection. |
| `resources/views/livewire/admin/product-component.blade.php` | `name` blur sync | Implemented | Form-level syncing helper appears during field updates. |
| `resources/views/livewire/admin/product-component.blade.php` | `retailPrice` blur sync | Implemented | Form-level syncing helper appears during field updates. |
| `resources/views/livewire/admin/product-component.blade.php` | `outOfStock` lazy change | Implemented | Form-level syncing helper appears during field updates. |
| `resources/views/livewire/admin/product-component.blade.php` | `saveProduct` submit | Implemented | Submit button disables and shows spinner/text. |
| `resources/views/livewire/admin/product-component.blade.php` | `clearSelection` | Implemented | Button disables and shows spinner/text. |
| `resources/views/livewire/admin/product-component.blade.php` | `setForEdit(code)` | Implemented | Row icon button disables and swaps to spinner. |
| `resources/views/livewire/admin/product-component.blade.php` | `deleteProduct(code)` | Implemented | Row icon button disables and swaps to spinner. |
| `resources/views/livewire/admin/product-component.blade.php` | `toggleStockStatus(code)` | Implemented | Row action button disables and shows spinner while toggling stock. |

## Notes

- This tracker only covers Livewire interactions found in `resources/views/livewire`.
- `wire:model.defer` fields are intentionally grouped under their submit loaders because they do not need a separate visible state in this pass.
- If new Livewire actions are added later, update this file in the same PR as the UI change.
