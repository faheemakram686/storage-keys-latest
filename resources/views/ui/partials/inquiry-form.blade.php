@php
    $variant = $variant ?? 'hero';
    $formClass = $formClass ?? 'sk-quote';
    $fieldClass = $fieldClass ?? 'sk-field';
    $rowClass = $rowClass ?? 'sk-frow';
    $title = $title ?? 'Get a Free Quote';
    $subtitle = $subtitle ?? null;
    $submitLabel = $submitLabel ?? 'Get My Free Quote';
    $noteHtml = $noteHtml ?? null;
    $source = $source ?? 'website';
    $defaultStorage = $defaultStorage ?? old('storage_type');
    $showStorageSelect = $showStorageSelect ?? true;
    $storageOptions = $storageOptions ?? [
        'Personal Storage' => 'Personal Storage',
        'Business Storage' => 'Business Storage',
        'Warehouse Storage' => 'Warehouse Storage',
        'Climate Controlled Storage' => 'Climate Controlled Storage',
        'Moving' => 'Moving',
        'Luggage Storage' => 'Luggage Storage',
        'Car Storage' => 'Car Storage',
    ];
    $showCompany = in_array($variant, ['business', 'warehouse'], true);
    $showSize = $variant === 'hero';
    $showStoring = in_array($variant, ['business', 'warehouse'], true);
    $showDuration = $variant === 'business';
    $showVolume = $variant === 'warehouse';
    $showMessage = in_array($variant, ['simple', 'contact'], true);
    $showItemsField = $variant === 'simple' && ($showItemsField ?? false);
    $storingOptions = $storingOptions ?? [];
    $submitClass = $submitClass ?? 'sk-btn sk-btn-primary';
    $compact = $compact ?? false;
@endphp

<form class="{{ $formClass }}" action="{{ route('inquiry.store') }}" method="POST">
    @csrf
    <input type="hidden" name="source" value="{{ $source }}">
    @if(!$showStorageSelect)
        <input type="hidden" name="storage_type" value="{{ $defaultStorage }}">
    @endif
    @if($showItemsField)
        <input type="hidden" name="items" id="psItemsField" value="{{ old('items') }}">
    @endif

    @if($title)
        <h3>{{ $title }}</h3>
    @endif
    @if($subtitle)
        <p>{{ $subtitle }}</p>
    @endif

    @include('ui.partials.inquiry-alerts')

    @if($showCompany)
        <div class="{{ $rowClass }}">
            <div class="{{ $fieldClass }}">
                <label>Company name</label>
                <input type="text" name="company" placeholder="Your company" value="{{ old('company') }}">
            </div>
            <div class="{{ $fieldClass }}">
                <label>Your Name</label>
                <input type="text" name="name" placeholder="Your name" value="{{ old('name') }}" required>
            </div>
        </div>
        <div class="{{ $rowClass }}">
            <div class="{{ $fieldClass }}">
                <label>Phone / WhatsApp</label>
                <input type="tel" name="phone" placeholder="+971 __ ___ ____" value="{{ old('phone') }}" required>
            </div>
            <div class="{{ $fieldClass }}">
                <label>Email</label>
                <input type="email" name="email" placeholder="you@company.com" value="{{ old('email') }}" required>
            </div>
        </div>
    @else
        @if($compact)
            <div class="{{ $rowClass }}">
                <div class="{{ $fieldClass }}">
                    <label>Your Name</label>
                    <input type="text" name="name" placeholder="e.g. Ahmed Khan" value="{{ old('name') }}" required>
                </div>
                <div class="{{ $fieldClass }}">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="Enter email address" value="{{ old('email') }}" required>
                </div>
            </div>
            <div class="{{ $rowClass }}">
                <div class="{{ $fieldClass }}">
                    <label>Phone / WhatsApp</label>
                    <input type="tel" name="phone" placeholder="+971 __ ___ ____" value="{{ old('phone') }}" required>
                </div>
                @if($showStorageSelect)
                    <div class="{{ $fieldClass }}">
                        <label>Storage type</label>
                        <select name="storage_type" required>
                            <option value="">Select Storage Type</option>
                            @foreach($storageOptions as $value => $label)
                                <option value="{{ $value }}" {{ (string) old('storage_type', $defaultStorage) === (string) $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
            </div>
        @else
            <div class="{{ $fieldClass }}">
                <label>Your Name</label>
                <input type="text" name="name" placeholder="e.g. Ahmed Khan" value="{{ old('name') }}" required>
            </div>
            <div class="{{ $fieldClass }}">
                <label>Email</label>
                <input type="email" name="email" placeholder="Enter email address" value="{{ old('email') }}" required>
            </div>
            <div class="{{ $fieldClass }}">
                <label>Phone / WhatsApp</label>
                <input type="tel" name="phone" placeholder="+971 __ ___ ____" value="{{ old('phone') }}" required>
            </div>
        @endif
    @endif

    @if($showStorageSelect && !$compact)
        <div class="{{ $fieldClass }}">
            <label>Storage type</label>
            <select name="storage_type" required>
                <option value="">Select Storage Type</option>
                @foreach($storageOptions as $value => $label)
                    <option value="{{ $value }}" {{ (string) old('storage_type', $defaultStorage) === (string) $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
    @endif

    @if($showSize)
        <div class="{{ $fieldClass }}">
            <label>Unit size</label>
            <select name="size">
                <option value="Not sure — help me choose" {{ old('size') === 'Not sure — help me choose' ? 'selected' : '' }}>Not sure — help me choose</option>
                <option value="Small (boxes & small items)" {{ old('size') === 'Small (boxes & small items)' ? 'selected' : '' }}>Small (boxes &amp; small items)</option>
                <option value="Medium (1–2 bedroom)" {{ old('size') === 'Medium (1–2 bedroom)' ? 'selected' : '' }}>Medium (1–2 bedroom)</option>
                <option value="Large / Warehouse" {{ old('size') === 'Large / Warehouse' ? 'selected' : '' }}>Large / Warehouse</option>
            </select>
        </div>
    @endif

    @if($showStoring)
        <div class="{{ $rowClass }}">
            <div class="{{ $fieldClass }}">
                <label>What are you storing?</label>
                <select name="storing">
                    @foreach($storingOptions as $value => $label)
                        <option value="{{ $value }}" {{ (string) old('storing') === (string) $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            @if($showDuration)
                <div class="{{ $fieldClass }}">
                    <label>Approx. duration</label>
                    <select name="duration">
                        <option value="1–3 months" {{ old('duration') === '1–3 months' ? 'selected' : '' }}>1–3 months</option>
                        <option value="3–12 months" {{ old('duration') === '3–12 months' ? 'selected' : '' }}>3–12 months</option>
                        <option value="12 months +" {{ old('duration') === '12 months +' ? 'selected' : '' }}>12 months +</option>
                        <option value="Not sure yet" {{ old('duration') === 'Not sure yet' ? 'selected' : '' }}>Not sure yet</option>
                    </select>
                </div>
            @endif
            @if($showVolume)
                <div class="{{ $fieldClass }}">
                    <label>Approx. volume</label>
                    <select name="volume" id="whFormVolume">
                        <option value="Small (1–3 pallets)" {{ old('volume') === 'Small (1–3 pallets)' ? 'selected' : '' }}>Small (1–3 pallets)</option>
                        <option value="Medium (4–10 pallets)" {{ old('volume', 'Medium (4–10 pallets)') === 'Medium (4–10 pallets)' ? 'selected' : '' }}>Medium (4–10 pallets)</option>
                        <option value="Large (11–25 pallets)" {{ old('volume') === 'Large (11–25 pallets)' ? 'selected' : '' }}>Large (11–25 pallets)</option>
                        <option value="Custom (26+ pallets)" {{ old('volume') === 'Custom (26+ pallets)' ? 'selected' : '' }}>Custom (26+ pallets)</option>
                    </select>
                </div>
            @endif
        </div>
    @endif

    @if($showMessage)
        <div class="{{ $fieldClass }}">
            <label>Message</label>
            <textarea name="message" rows="2" placeholder="Enter message">{{ old('message') }}</textarea>
        </div>
    @endif

    <button type="submit" class="{{ $submitClass }}"><i class="fas fa-paper-plane"></i> {{ $submitLabel }}</button>
    @if($noteHtml)
        <p class="sk-quote-note">{!! $noteHtml !!}</p>
    @endif
</form>
