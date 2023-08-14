@extends('layouts.app')

@section('content')
<div class="container-content">
    <div class="row justify-content-center align-items-center">
        <div class="col-md-8">
            @if(session('success'))
            <div class="alert alert-success mt-3">
                {{ session('success') }}
            </div>
            @endif
            <h3 class="mt-4">Hi, {{ Auth::user()->username }}! Do you need any help?</h3>
        </div>

        <div class="col-md-5 mt-5">
            <div class="card">
                <div class="card-header text-center"> Get In Touch With Us </div>
                <div class="card-body justify-content-center align-items-center">
                    <div class="text-center m-4">
                        <h5>Have any concerns? Please contact us at any time!</h5>
                    </div>
                    <div class="text-center mb-4">
                        <a href="{{ route('customer.support.contactUs') }}" class="btn btn-primary">
                            {{ __('Contact Us') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8 mt-4">
            <h2 class="heading">Help & Support</h2>
            <div id="listAccordion">
                <div class="card m-2" style="border-width:3px">
                    <!-- Frequently Asked Questions -->
                    <div class="card-header d-flex justify-content-between align-items-center" id="listHeading_1">
                        <h5 class="m-3 highlight">Frequently Asked Questions</h5>
                        <a class="collapse-link" data-toggle="collapse" data-target="#listCollapse_1" aria-expanded="true" aria-controls="listCollapse_1">
                            <img src="{{ asset('images/icon/view.png') }}" alt="Expand/Collapse" style="width: 20px; height: 20px;">
                        </a>
                    </div>

                    <div id="listCollapse_1" class="collapse" aria-labelledby="listHeading_1" data-parent="#listAccordion">
                        <div class="card-body">
                            <div id="innerAccordion">
                                <div class="m-2">
                                    <h3 class="sub-heading"> About Membership Points </h3>
                                </div>
                                <div class="card m-2">
                                    <div class="card-header d-flex justify-content-between align-items-center" id="innerHeading_1">
                                        <h5 class="m-2">How are the points being calculated? </h5>
                                        <a class="collapse-link" data-toggle="collapse" data-target="#innerCollapse_1" aria-expanded="true" aria-controls="innerCollapse_1">
                                            <img src="{{ asset('images/icon/view.png') }}" alt="Expand/Collapse" style="width: 20px; height: 20px;">
                                        </a>
                                    </div>

                                    <div id="innerCollapse_1" class="collapse" aria-labelledby="innerHeading_1" data-parent="#innerAccordion">
                                        <div class="card-body">
                                            For every RM1 spent in a valid transaction at our stores, you will get to earn 1 points for every RM1 spent.
                                        </div>
                                    </div>
                                </div>

                                <div class="card m-2">
                                    <div class="card-header d-flex justify-content-between align-items-center" id="innerHeading_2">
                                        <h5 class="m-2">What is the validity of my coupon once redeemed?</h5>
                                        <a class="collapse-link" data-toggle="collapse" data-target="#innerCollapse_2" aria-expanded="true" aria-controls="innerCollapse_2">
                                            <img src="{{ asset('images/icon/view.png') }}" alt="Expand/Collapse" style="width: 20px; height: 20px;">
                                        </a>
                                    </div>

                                    <div id="innerCollapse_2" class="collapse" aria-labelledby="innerHeading_2" data-parent="#innerAccordion">
                                        <div class="card-body">
                                            Redemption coupons are valid for 30 days from the date of redemption.
                                        </div>
                                    </div>
                                </div>

                                <div class="card m-2">
                                    <div class="card-header d-flex justify-content-between align-items-center" id="innerHeading_3">
                                        <h5 class="m-2">What can I redeem my points for?</h5>
                                        <a class="collapse-link" data-toggle="collapse" data-target="#innerCollapse_3" aria-expanded="true" aria-controls="innerCollapse_3">
                                            <img src="{{ asset('images/icon/view.png') }}" alt="Expand/Collapse" style="width: 20px; height: 20px;">
                                        </a>
                                    </div>

                                    <div id="innerCollapse_3" class="collapse" aria-labelledby="innerHeading_3" data-parent="#innerAccordion">
                                        <div class="card-body">
                                            Our reward coupons are redeemable from as low as 1000 pints. Start earning now to make your next redemption!
                                        </div>
                                    </div>
                                </div>

                                <div class="card m-2">
                                    <div class="card-header d-flex justify-content-between align-items-center" id="innerHeading_4">
                                        <h5 class="m-2">How do l earn points?</h5>
                                        <a class="collapse-link" data-toggle="collapse" data-target="#innerCollapse_4" aria-expanded="true" aria-controls="innerCollapse_4">
                                            <img src="{{ asset('images/icon/view.png') }}" alt="Expand/Collapse" style="width: 20px; height: 20px;">
                                        </a>
                                    </div>

                                    <div id="innerCollapse_4" class="collapse" aria-labelledby="innerHeading_4" data-parent="#innerAccordion">
                                        <div class="card-body">
                                            Simply show your membership barcode or Qrcode to our cashier to scan and earn points upon valid payment.
                                        </div>
                                    </div>
                                </div>

                                <div class="card m-2">
                                    <div class="card-header d-flex justify-content-between align-items-center" id="innerHeading_5">
                                        <h5 class="m-2">Can I still earn points if I forgot to show my membership barcode or Qrcode upon payment? </h5>
                                        <a class="collapse-link" data-toggle="collapse" data-target="#innerCollapse_5" aria-expanded="true" aria-controls="innerCollapse_5">
                                            <img src="{{ asset('images/icon/view.png') }}" alt="Expand/Collapse" style="width: 20px; height: 20px;">
                                        </a>
                                    </div>

                                    <div id="innerCollapse_5" class="collapse" aria-labelledby="innerHeading_5" data-parent="#innerAccordion">
                                        <div class="card-body">
                                            No. We will not be able to credit points if your membership barcode or Qrcode is not presented during payment.
                                        </div>
                                    </div>
                                </div>

                                <div class="m-2">
                                    <h3 class="sub-heading"> Other </h3>
                                </div>
                                <div class="card m-2">
                                    <div class="card-header d-flex justify-content-between align-items-center" id="innerHeading_6">
                                        <h5 class="m-2"> Who can I reach out to on my enquiries? </h5>
                                        <a class="collapse-link" data-toggle="collapse" data-target="#innerCollapse_6" aria-expanded="true" aria-controls="innerCollapse_6">
                                            <img src="{{ asset('images/icon/view.png') }}" alt="Expand/Collapse" style="width: 20px; height: 20px;">
                                        </a>
                                    </div>

                                    <div id="innerCollapse_6" class="collapse" aria-labelledby="innerHeading_6" data-parent="#innerAccordion">
                                        <div class="card-body">
                                            Our Customer Service representatives are available from Monday through Friday, 9:00 am to
                                            6:00 pm (MY) excluding public holidays. For urgent enquiries on your purchase, please contact respective outlets directly.
                                        </div>
                                    </div>
                                </div>

                                <!-- Add more questions/answers here -->
                            </div>
                        </div>
                    </div>
                </div>
                <!-- About Us -->
                <div class="card m-2" style="border-width:3px">
                    <div class="card-header d-flex justify-content-between align-items-center" id="listHeading_2">
                        <h5 class="m-3 highlight">About Us</h5>
                        <a class="collapse-link" data-toggle="collapse" data-target="#listCollapse_2" aria-expanded="true" aria-controls="listCollapse_2">
                            <img src="{{ asset('images/icon/view.png') }}" alt="Expand/Collapse" style="width: 20px; height: 20px;">
                        </a>
                    </div>

                    <div id="listCollapse_2" class="collapse" aria-labelledby="listHeading_2" data-parent="#listAccordion">
                        <div class="card-body m-2">
                            <h2 class="heading">About Our Customer Loyalty Program</h2>
                            <p>Welcome to our Customer Loyalty Program! We're excited to have you on board as a valued member of our community. Our loyalty program is designed to reward you for your continued support and engagement with our products and services.</p>

                            <h3 class="sub-heading">Why Join Our Loyalty Program?</h3>
                            <p>Our loyalty program is our way of saying thank you for choosing us. By being a part of our program, you'll enjoy a range of exclusive benefits, discounts, and offers that are tailored to your preferences and needs. Whether you're a frequent shopper or just getting started, our loyalty program is designed to enhance your experience and provide you with added value.</p>

                            <h3 class="sub-heading">How It Works</h3>
                            <p>Participating in our loyalty program is simple. Every time you make a purchase, engage with our content, or refer a friend, you'll earn loyalty points. These points can be redeemed for exciting rewards such as discounts, free products, and special offers. The more you engage with us, the more you'll earn, and the more benefits you'll unlock.</p>

                            <h3 class="sub-heading">Our Commitment</h3>
                            <p>At our core, we're dedicated to providing you with the best products, services, and experiences. Our loyalty program is an extension of this commitment, designed to enhance your journey with us. We're continuously working to improve and expand our program to ensure that you receive the maximum value and benefits possible.</p>
                        </div>
                    </div>
                </div>

                <!-- Terms and Conditions     -->
                <div class="card m-2" style="border-width:3px">
                    <div class="card-header d-flex justify-content-between align-items-center" id="listHeading_3">
                        <h5 class="m-3 highlight">Terms and Conditions</h5>
                        <a class="collapse-link" data-toggle="collapse" data-target="#listCollapse_3" aria-expanded="true" aria-controls="listCollapse_3">
                            <img src="{{ asset('images/icon/view.png') }}" alt="Expand/Collapse" style="width: 20px; height: 20px;">
                        </a>
                    </div>

                    <div id="listCollapse_3" class="collapse" aria-labelledby="listHeading_3" data-parent="#listAccordion">
                        <div class="card-body m-2">
                            <h2 class="heading">Terms and Conditions</h2>
                            <p>Please read these terms and conditions carefully before participating in our Customer Loyalty Program. By joining the program, you agree to abide by these terms and conditions.</p>

                            <h3 class="sub-heading">Membership</h3>
                            <ul>
                                <li>Membership in our Customer Loyalty Program is free and open to all customers aged 18 and above.</li>
                                <li>One membership per individual is allowed.</li>
                                <li>Members are responsible for providing accurate and up-to-date information when signing up for the program.</li>
                            </ul>

                            <h3 class="sub-heading">Earning Loyalty Points</h3>
                            <ul>
                                <li>Loyalty points are earned on eligible purchases made at our retail store or online.</li>
                                <li>Points are calculated based on the total purchase amount after discounts and before taxes.</li>
                                <li>Points earned on a purchase will be credited to the member's account within 24 hours of the transaction.</li>
                            </ul>

                            <h3 class="sub-heading">Redeeming Rewards</h3>
                            <ul>
                                <li>Loyalty points can be redeemed for eligible rewards as specified in the program.</li>
                                <li>Rewards are subject to availability and may change without notice.</li>
                                <li>Points cannot be transferred or redeemed for cash.</li>
                            </ul>

                            <h3 class="sub-heading">Program Changes and Termination</h3>
                            <ul>
                                <li>We reserve the right to modify, suspend, or terminate the loyalty program at any time without prior notice.</li>
                                <li>Members will be notified of any significant program changes through email or other communication channels.</li>
                            </ul>

                            <h3 class="sub-heading">Contact Us</h3>
                            <p>If you have any questions or concerns about our Customer Loyalty Program or these terms and conditions, please contact our customer support team at support@email.com.</p>
                        </div>
                    </div>
                </div>

                <!-- Privacy Policy -->
                <div class="card m-2" style="border-width:3px">
                    <div class="card-header d-flex justify-content-between align-items-center" id="listHeading_4">
                        <h5 class="m-3 highlight">Privacy Policy</h5>
                        <a class="collapse-link" data-toggle="collapse" data-target="#listCollapse_4" aria-expanded="true" aria-controls="listCollapse_4">
                            <img src="{{ asset('images/icon/view.png') }}" alt="Expand/Collapse" style="width: 20px; height: 20px;">
                        </a>
                    </div>

                    <div id="listCollapse_4" class="collapse" aria-labelledby="listHeading_4" data-parent="#listAccordion">
                        <div class="card-body m-2">
                            <h2 class="heading">Privacy Policy</h2>
                            <p>Your privacy is important to us. This Privacy Policy outlines how we collect, use, and protect the personal information you provide as a member of our Customer Loyalty Program.</p>

                            <h3 class="sub-heading">Information We Collect</h3>
                            <p>We collect the following types of information when you sign up for our loyalty program:</p>
                            <ul>
                                <li>Your name, email address, phone number, and other contact details.</li>
                                <li>Purchase history, including transaction details and amounts.</li>
                            </ul>

                            <h3 class="sub-heading">How We Use Your Information</h3>
                            <p>We use your personal information for the following purposes:</p>
                            <ul>
                                <li>To administer the loyalty program, including crediting points and offering rewards.</li>
                                <li>To communicate with you about program updates, promotions, and special offers.</li>
                                <li>To improve our products, services, and customer experience.</li>
                            </ul>

                            <h3 class="sub-heading">Sharing Your Information</h3>
                            <p>We do not sell, trade, or rent your personal information to third parties. We may share your information with:</p>
                            <ul>
                                <li>Service providers who assist in program administration.</li>
                                <li>Legal authorities when required by law or to protect our rights.</li>
                            </ul>

                            <h3 class="sub-heading">Security</h3>
                            <p>We take reasonable measures to protect your personal information from unauthorized access and use. However, no data transmission over the internet is completely secure.</p>

                            <h3 class="sub-heading">Your Choices</h3>
                            <p>You can manage your communication preferences or update your personal information by logging into your loyalty program account.</p>

                            <h3 class="sub-heading">Contact Us</h3>
                            <p>If you have any questions or concerns about our Privacy Policy or how we handle your personal information, please contact our Privacy Officer at support@email.com.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection