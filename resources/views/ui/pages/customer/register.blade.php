@extends('ui.layouts.frontend2')
@section('title', '| Register')
@section('content')


    <div class="ltn__utilize-overlay"></div>

    <!-- BREADCRUMB AREA START -->
    <div class="ltn__breadcrumb-area text-left bg-overlay-white-30 bg-image "  data-bg="{{ asset('sk-assets/assets/images/frontend/bg/Inner_Small_Banner_3.jpg') }}">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ltn__breadcrumb-inner">
                        <h1 class="page-title">Account</h1>
                        <div class="ltn__breadcrumb-list">
                            <ul>
                                <li><a href="index.html"><span class="ltn__secondary-color"><i class="fas fa-home"></i></span> Home</a></li>
                                <li>Register</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- BREADCRUMB AREA END -->

    <!-- LOGIN AREA START (Register) -->
    <div class="ltn__login-area pb-110">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-area text-center">
                        <h1 class="section-title">Register <br>Your Account</h1>
{{--                        <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. <br>--}}
{{--                            Sit aliquid,  Non distinctio vel iste.</p>--}}
                    </div>
                    {{-- ✅ Validation Errors --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>Whoops! Something went wrong:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- ✅ General Error (Exception or DB) --}}
                    @if (session('error'))
                        <div class="alert alert-danger">
                            <strong>Error:</strong> {{ session('error') }}
                        </div>
                    @endif

                    {{-- ✅ Success Message --}}
                    @if (session('success'))
                        <div class="alert alert-success">
                            <strong>Success:</strong> {{ session('success') }}
                        </div>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="account-login-inner">
                        <form action="{{route('customer.register')}}" method="post" class="ltn__form-box contact-form-box">
                            @csrf
                            <style>
                                .customer-type-selection {
                                    display: flex;
                                    gap: 20px;
                                    margin-bottom: 25px;
                                    background: #f8f9fa;
                                    padding: 15px;
                                    border-radius: 8px;
                                    border: 1px solid #e9ecef;
                                }
                                .type-option {
                                    flex: 1;
                                    position: relative;
                                }
                                .type-option input[type="radio"] {
                                    position: absolute;
                                    opacity: 0;
                                    width: 0;
                                    height: 0;
                                }
                                .type-option label {
                                    display: block;
                                    padding: 12px 15px;
                                    text-align: center;
                                    background: #fff;
                                    border: 2px solid #e9ecef;
                                    border-radius: 6px;
                                    cursor: pointer;
                                    font-weight: 600;
                                    color: #495057;
                                    transition: all 0.3s ease;
                                    margin-bottom: 0;
                                }
                                .type-option input[type="radio"]:checked + label {
                                    border-color: #f7941d; /* Using an orange/yellow tone common in many themes */
                                    background: #fff9f2;
                                    color: #f7941d;
                                }
                                .type-option label:hover {
                                    background: #fdfdfd;
                                    border-color: #ced4da;
                                }
                                .type-option input[type="radio"]:checked + label:after {
                                    content: '\f058'; /* FontAwesome check-circle icon */
                                    font-family: 'Font Awesome 5 Free';
                                    font-weight: 900;
                                    position: absolute;
                                    top: -10px;
                                    right: -5px;
                                    background: #fff;
                                    border-radius: 50%;
                                    font-size: 18px;
                                    line-height: 1;
                                }
                            </style>

                            <div class="customer-type-selection">
                                <div class="type-option">
                                    <input type="radio" name="customer_type" value="individual" checked id="type_individual">
                                    <label for="type_individual">Individual</label>
                                </div>
                                <div class="type-option">
                                    <input type="radio" name="customer_type" value="company" id="type_company">
                                    <label for="type_company">Company</label>
                                </div>
                            </div>
                            
                            <div id="company_name_div" style="display: none;">
                                <input type="text" name="company_name" id="company_name" placeholder="Company Name">
                            </div>
                            <input type="text" name="first_name" placeholder="First Name" required>
                            <input type="text" name="last_name" placeholder="Last Name" required>
                            <input type="text" name="email" placeholder="Email*" required>
                            <input type="password" name="password" placeholder="Password*" required>
                            <input type="password" name="password_confirmation" placeholder="Confirm Password*" required>

                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    const typeIndividual = document.getElementById('type_individual');
                                    const typeCompany = document.getElementById('type_company');
                                    const companyDiv = document.getElementById('company_name_div');
                                    const companyInput = document.getElementById('company_name');

                                    function toggleCompany() {
                                        if (typeCompany.checked) {
                                            companyDiv.style.display = 'block';
                                            companyInput.setAttribute('required', 'required');
                                        } else {
                                            companyDiv.style.display = 'none';
                                            companyInput.removeAttribute('required');
                                            companyInput.value = '';
                                        }
                                    }

                                    typeIndividual.addEventListener('change', toggleCompany);
                                    typeCompany.addEventListener('change', toggleCompany);
                                    
                                    // Initial check
                                    toggleCompany();
                                });
                            </script>

                            <div class="btn-wrapper">
                                <button class="theme-btn-1 btn reverse-color btn-block" type="submit">CREATE ACCOUNT</button>
                            </div>
                        </form>
                        <div class="by-agree text-center">
                            <p>By creating an account, you agree to our:</p>
                            <p><a href="#">TERMS OF CONDITIONS  &nbsp; &nbsp; | &nbsp; &nbsp;  PRIVACY POLICY</a></p>
                            <div class="go-to-btn mt-50">
                                <a href="{{ route('customer.login') }}">ALREADY HAVE AN ACCOUNT ?</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection