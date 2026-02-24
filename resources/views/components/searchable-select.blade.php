@props([
    'options',        // Collection or array of items (must have ->id and ->libelle or custom keys)
    'wireModel',      // The wire:model path, e.g. "pays" or "administrateurs.0.nationalite_id"
    'selected' => '', // Currently selected value
    'placeholder' => '-- Choisir --',
    'disabled' => false,
    'idField' => 'id',
    'labelField' => 'libelle',
])

@php
    $uid = 'ss_' . str_replace(['.', ' '], '_', $wireModel) . '_' . uniqid();
@endphp

<div x-data="{
        open: false,
        search: '',
        selected: @entangle($wireModel),
        options: @js($options->map(fn($o) => ['id' => $o->{$idField}, 'label' => $o->{$labelField}])->toArray()),
        get filtered() {
            if (this.search === '') return this.options;
            let s = this.search.toLowerCase();
            return this.options.filter(o => o.label.toLowerCase().startsWith(s));
        },
        get selectedLabel() {
            let found = this.options.find(o => String(o.id) === String(this.selected));
            return found ? found.label : '';
        },
        select(id) {
            this.selected = id;
            this.search = '';
            this.open = false;
        }
     }"
     @click.outside="open = false"
     class="position-relative">

    {{-- Display field --}}
    @if($disabled)
        <input type="text" class="form-control" :value="selectedLabel" disabled readonly>
    @else
        <div class="input-group" @click="open = !open" style="cursor: pointer;">
            <input type="text"
                   class="form-control"
                   :value="open ? search : selectedLabel"
                   x-ref="searchInput"
                   @input="search = $event.target.value; open = true"
                   @focus="open = true; search = ''"
                   @keydown.escape="open = false"
                   placeholder="{{ $placeholder }}"
                   autocomplete="off">
            <span class="input-group-text" style="cursor: pointer;">
                <i class="ti" :class="open ? 'ti-chevron-up' : 'ti-chevron-down'"></i>
            </span>
        </div>

        {{-- Dropdown list --}}
        <ul x-show="open" x-transition
            class="list-group position-absolute w-100 shadow-sm border"
            style="z-index: 1050; max-height: 200px; overflow-y: auto; margin-top: 2px;">
            <template x-for="option in filtered" :key="option.id">
                <li class="list-group-item list-group-item-action py-1 px-2"
                    :class="{ 'active': String(option.id) === String(selected) }"
                    @click="select(option.id)"
                    x-text="option.label"
                    style="cursor: pointer; font-size: 0.9rem;">
                </li>
            </template>
            <li x-show="filtered.length === 0" class="list-group-item text-muted py-1 px-2" style="font-size: 0.9rem;">
                Aucun résultat
            </li>
        </ul>
    @endif
</div>
