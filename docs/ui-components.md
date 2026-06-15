# UI Component Library

Reusable Laravel Blade components live in `resources/views/components/ui` and are available as `x-ui.*` components. They are designed for Laravel 12, Livewire 3, Tailwind, Alpine.js, Heroicons-style SVG icons, dark mode, RTL/LTR, keyboard focus states, and responsive layouts.

Visit `/ui-library` for a working catalog.

## Buttons

```blade
<x-ui.button>Primary</x-ui.button>
<x-ui.button variant="secondary">Secondary</x-ui.button>
<x-ui.button variant="success">Success</x-ui.button>
<x-ui.button variant="danger">Danger</x-ui.button>
<x-ui.button variant="warning">Warning</x-ui.button>
<x-ui.button variant="ghost">Ghost</x-ui.button>
<x-ui.button variant="outline">Outline</x-ui.button>
<x-ui.icon-button icon="bell" label="Notifications" />
<x-ui.loading-button target="save">Save</x-ui.loading-button>
<x-ui.floating-action-button label="Create" x-on:click="$dispatch('open-modal', 'create')" />
```

## Form Controls

Use `x-ui.input` for text, email, password, number, search, phone, URL, currency, icon, prefix/suffix, and floating label inputs.

```blade
<x-ui.input label="Email" name="email" type="email" wire:model.live="email" />
<x-ui.input label="Amount" name="amount" type="currency" suffix="AFN" />
<x-ui.floating-input label="Student name" name="student_name" icon="user" />
<x-ui.textarea label="Notes" name="notes" auto-resize wire:model="notes" />
<x-ui.rich-text-editor label="Announcement" name="announcement" wire:model="announcement" />
```

## Selects

```blade
<x-ui.select label="Role" name="role" :options="$roles" placeholder="Choose role" />
<x-ui.searchable-select label="Teacher" name="teacher_id" :options="$teachers" />
<x-ui.multi-select label="Permissions" name="permissions" :options="$permissions" :selected="$selected" />
<x-ui.tag-select label="Tags" name="tags" :options="['urgent', 'finance']" />
<x-ui.async-select label="Remote user" name="user_id" endpoint="/api/users/search" />
<x-ui.dependent-select label="Class" name="class_id" depends-on="#department" :groups="$classesByDepartment" />
```

Async endpoints should return JSON like:

```json
[{ "value": 1, "label": "Amina Rahimi" }]
```

## Choice Controls

```blade
<x-ui.checkbox label="Receive reminders" name="reminders" checked />
<x-ui.checkbox-group label="Modules" name="modules" :options="$modules" :selected="$enabled" />
<x-ui.radio-group label="Billing cycle" name="cycle" :options="$cycles" selected="monthly" />
<x-ui.toggle label="Online admissions" name="admissions" />
<x-ui.permission-matrix :roles="$roles" :permissions="$permissions" :values="$matrix" />
```

## Data Table

```blade
<x-ui.data-table
    title="Students"
    :columns="[
        ['key' => 'name', 'label' => 'Name', 'sortable' => true],
        ['key' => 'class', 'label' => 'Class', 'filterable' => true],
    ]"
    :rows="$students"
    :actions="[
        ['label' => 'View', 'url' => '/students/{id}', 'icon' => 'eye'],
        ['label' => 'Edit', 'event' => 'edit-student', 'icon' => 'pencil-square'],
    ]"
    loading-target="search"
    sticky
>
    <x-slot name="pagination">
        {{ $students->links() }}
    </x-slot>
</x-ui.data-table>
```

## Cards, Alerts, Badges

```blade
<x-ui.stat-card label="Students" value="1,248" change="+8.2%" trend="up" />
<x-ui.user-card name="Amina Rahimi" email="amina@example.com" role="Student" status="active" />
<x-ui.revenue-card value="$84,320" change="+18.6%" />
<x-ui.alert variant="success" title="Saved" dismissible>Profile updated.</x-ui.alert>
<x-ui.badge variant="notification">3 new</x-ui.badge>
```

## Navigation and Overlays

```blade
<x-ui.top-navbar brand="School ERP" :links="$links" />
<x-ui.sidebar-menu brand="ERP" :items="$items" />
<x-ui.breadcrumbs :items="$breadcrumbs" />
<x-ui.tabs :tabs="$tabs" />
<x-ui.modal name="edit-student" title="Edit student">...</x-ui.modal>
<x-ui.confirmation-modal name="delete-student">Delete this record?</x-ui.confirmation-modal>
<x-ui.slide-over name="filters" title="Filters">...</x-ui.slide-over>
```

## Loading and Uploads

```blade
<x-ui.skeleton avatar />
<x-ui.spinner label="Loading" />
<x-ui.progress value="72" label="Upload progress" />
<x-ui.file-upload name="photos" multiple image-preview accept="image/*" wire:model="photos" />
```

## Design Notes

Dark mode is class-based; add `class="dark"` to `html` or a parent element. RTL is supported through logical Tailwind utilities and `dir="rtl"`. Components include visible focus rings, semantic controls, and accessible labels where possible.
