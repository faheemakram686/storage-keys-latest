@extends('ui.layouts.frontend')
@section('title', '| Moving Services')
@section('metaTitle', 'Moving & Storage Services in the UAE | Storagekeys')
@section('metaDescription', 'Reliable moving and storage services across the UAE, from packing and transport to secure short or long-term storage. Get a free quote today!')

@section('content')
<div class="sk-home">
    <section class="svc-hero">
        <div class="sk-container">
            <div class="svc-crumb"><a href="{{ url('/') }}">Home</a> <i class="fas fa-chevron-right"></i> <a href="{{ url('/storage-options') }}">Storage Solutions</a> <i class="fas fa-chevron-right"></i> <span>Moving Services</span></div>
            <div class="svc-hero-grid">
                <div>
                    <span class="sk-eyebrow" style="color:#ffcf9e;">Moving &amp; Storage</span>
                    <h1>Moving &amp; Storage Services in <span>Dubai</span></h1>
                    <p class="lead">Professional moving and storage services across Dubai and the UAE, with flexible transportation, packing, and temporary storage solutions for homes and businesses.</p>
                    <div class="svc-hero-cta">
                        <a href="#moving-quote" class="sk-btn sk-btn-primary"><i class="fas fa-file-invoice-dollar"></i> Get a Free Quote</a>
                        <a href="tel:+971565018785" class="sk-btn sk-btn-ghost"><i class="fas fa-phone-alt"></i> +971 56 501 8785</a>
                    </div>
                </div>
                <div class="svc-hero-card">
                    <h3>Moving and storage together</h3>
                    <ul>
                        <li><i class="fas fa-box-open"></i> Packing, loading and transportation</li>
                        <li><i class="fas fa-warehouse"></i> Temporary or longer-term storage</li>
                        <li><i class="fas fa-home"></i> Homes and businesses</li>
                        <li><i class="fas fa-clock"></i> Move out now, deliver when you’re ready</li>
                        <li><i class="fas fa-handshake"></i> One provider for moving and storage</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section class="sk-section" id="moving-quote">
        <div class="sk-container">
            <div class="svc-quote sk-reveal">
                <div>
                    <span class="sk-eyebrow" style="color:#ffcf9e;">Free Quote</span>
                    <h2>Plan Your Move With StorageKeys</h2>
                    <p>Need moving and storage in one place? Contact StorageKeys for a flexible solution tailored to your schedule.</p>
                    <div class="contacts">
                        <a href="tel:+971565018785"><i class="fas fa-phone-alt"></i> +971 56 501 8785</a>
                        <a href="mailto:sales@storagekeys.com"><i class="fas fa-envelope"></i> sales@storagekeys.com</a>
                        <a href="https://wa.me/971565018785"><i class="fab fa-whatsapp"></i> Message us on WhatsApp</a>
                    </div>
                </div>
                @include('ui.partials.inquiry-form', [
                    'variant' => 'business',
                    'formClass' => 'svc-form',
                    'fieldClass' => 'svc-field',
                    'rowClass' => 'svc-frow',
                    'title' => 'Request your quote',
                    'submitLabel' => 'Request Free Quote',
                    'submitClass' => 'sk-btn sk-btn-primary svc-form-submit',
                    'source' => 'moving-services',
                    'showStorageSelect' => false,
                    'defaultStorage' => 'Moving',
                    'storingOptions' => [
                        'Home moving' => 'Home moving',
                        'Office relocation' => 'Office relocation',
                        'Packing & moving' => 'Packing & moving',
                        'Moving & temporary storage' => 'Moving & temporary storage',
                        'A mix of the above' => 'A mix of the above',
                    ],
                ])
            </div>
        </div>
    </section>

    <div class="svc-mobilebar">
        <a href="tel:+971565018785"><i class="fas fa-phone-alt"></i> Call</a>
        <a href="https://wa.me/971565018785" class="wa"><i class="fab fa-whatsapp"></i> WhatsApp</a>
        <a href="#moving-quote"><i class="fas fa-file-invoice-dollar"></i> Quote</a>
    </div>
</div>
@endsection
