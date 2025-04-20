@extends('layouts.auth', [
    'class' => 'overflow-auto mh-100 h-100',
    'navbarClass' => 'bg-white py-1',
    'sectionClass' => 'bg-light'
])

@section('content')
    <div class="content">
        <div class="container">
            <div class="row mt-5">
                <div class="col-12 terms-section english-section">
                    <div class="text-end mb-3">
                        <button type="button" class="btn btn-primary btn-translate" data-target=".bisaya-section">Translate to Bisaya</button>
                    </div>
                    <h5>Terms and Conditions</h5>
                    <p>Welcome to <b>Innovaplas Packaging Corporation</b>! By accessing or using our services, you agree to comply with and be bound by these Terms and Conditions. Please read them carefully before proceeding.</p>
                    <h6>1. General Terms</h6>
                    <div class="ms-3">
                        <p>1.1 These Terms and Conditions govern your use of Innovaplas Packaging Corporation’s website, products, and services.</p>
                        <p>1.2 We reserve the right to update or change these terms at any time without prior notice.</p>
                    </div>
                    <h6>2. User Responsibilities</h6>
                    <div class="ms-3">
                        <p>2.1 You agree to provide accurate and up-to-date information during registration or checkout.</p>
                        <p>2.2 Unauthorized use of our platform is prohibited and may result in suspension or termination of your account.</p>
                        <p>2.3 You are responsible for maintaining the confidentiality of your account details.</p>
                    </div>
                    <h6>3. Payment and Billing</h6>
                    <div class="ms-3">
                        <p>3.1 All prices are displayed in <b>Peso (₱)</b> and include applicable taxes unless stated otherwise.</p>
                        <p>3.2 Payment must be completed using approved payment methods listed on our platform.</p>
                        <p>3.3 In case of failed transactions or disputes, please contact our support team at <a href="mailto:innovaplaspackagingcorpo@gmail.com">innovaplaspackagingcorpo@gmail.com</a>.</p>
                    </div>
                    <h6>4. Cancellation and Refunds</h6>
                    <div class="ms-3">
                        <p>4.1 Orders can be canceled only under the conditions outlined in our <b>Cancellation Policy</b>.</p>
                        <p>4.2 Refunds are processed according to our <b>Refund Policy</b> and may take up to [X] business days.</p>
                        <p>4.3 Non-refundable items or services will be clearly marked during the purchase process.</p>
                    </div>
                    <h6>5. Intellectual Property</h6>
                    <div class="ms-3">
                        <p>5.1 All content, logos, and trademarks displayed on our website are the property of <b>Innovaplas Packaging Corporation</b>.</p>
                        <p>5.2 Unauthorized reproduction, distribution, or modification of our content is strictly prohibited.</p>
                    </div>
                    <h6>6. Limitation of Liability</h6>
                    <div class="ms-3">
                        <p>6.1 Innovaplas Packaging Corporation is not responsible for any indirect, incidental, or consequential damages arising from the use of our services.</p>
                        <p>6.2 We are not liable for delays, errors, or interruptions caused by external factors beyond our control.</p>
                    </div>
                    <h6>7. Privacy</h6>
                    <div class="ms-3">
                        <p>7.1 Your use of our services is subject to our <b>Privacy Policy</b>, which outlines how we collect, store, and process your data.</p>
                    </div>
                    <h6>8. Contact Information</h6>
                    <div class="ms-3">
                        <p>For any questions regarding these Terms and Conditions, please contact us:</p>
                        <p>Email: <a href="mailto:innovaplaspackagingcorpo@gmail.com">innovaplaspackagingcorpo@gmail.com</a></p>
                        <p>Phone: <a href="tel:0917 713 0990">0917 713 0990</a></p>
                    </div>
                </div>
                <div class="col-12 terms-section bisaya-section d-none">
                    <div class="text-end mb-3">
                        <button type="button" class="btn btn-primary btn-translate" data-target=".english-section">Translate to English</button>
                    </div>
                    <h5>Mga Termino ug Kundisyon</h5>
                    <p>Welcome sa <b>Innovaplas Packaging Corporation</b>! Pinaagi sa paggamit sa among mga serbisyo, miuyon ka nga mosunod ug matinud-anon nga motuman niining mga Termino ug Kundisyon. Palihug basaha kini pag-ayo sa dili pa magpadayon.</p>
                    <h6>1. Kasayuran sa Kinatibuk-an</h6>
                    <div class="ms-3">
                        <p>1.1 Kini nga mga Termino ug Kundisyon nagdumala sa imong paggamit sa website, mga produkto, ug serbisyo sa Innovaplas Packaging Corporation.</p>
                        <p>1.2 Adunay katungod kami nga mag-usab o mag-update niining mga termino sa bisan unsang oras nga walay pahibalo daan.</p>
                    </div>
                    <h6>2. Mga Katungdanan sa Gumagamit</h6>
                    <div class="ms-3">
                        <p>2.1 Miuyon ka nga maghatag og tinuod ug updated nga impormasyon sa panahon sa pagparehistro o pagpalit.</p>
                        <p>2.2 Dili gitugotan ang dili awtorisadong paggamit sa among plataporma, ug mahimong resulta sa pag-suspenso o pagpahunong sa imong account.</p>
                        <p>2.3 Ikaw ang responsable sa pagbantay sa sekreto sa imong account ug impormasyon niini.</p>
                    </div>
                    <h6>3. Pagbayad ug Pagsingil</h6>
                    <div class="ms-3">  
                        <p>3.1 Ang tanan nga presyo gipakita sa <b>Peso (₱)</b> ug naglakip sa buhis gawas kung gispecified nga wala.</p>
                        <p>3.2 Ang bayad kinahanglan kumpletohon gamit ang among gi-aprobahang pamaagi sa pagbayad nga makita sa plataporma.</p>
                        <p>3.3 Kung adunay kapakyasan sa transaksyon o panaglalis, palihug kontaka ang among support team sa <a href="mailto:innovaplaspackagingcorpo@gmail.com">innovaplaspackagingcorpo@gmail.com</a>.</p>
                    </div>
                    <h6>4. Pagkansela ug Pag-refund</h6>
                    <div class="ms-3">
                        <p>4.1 Ang pagkansela sa mga order mahimo lamang kung kini tuman sa among <b>Cancellation Policy</b>.</p>
                        <p>4.2 Ang mga refund kay iproseso sumala sa among <b>Refund Policy</b> ug mahimong mokuha og [X] ka adlaw aron makompleto.</p>
                        <p>4.3 Ang mga butang o serbisyo nga dili marefund ipahayag sa klaro sa panahon sa imong pagpalit.</p>
                    </div>
                    <h6>5. Intellectual Property</h6>
                    <div class="ms-3">
                        <p>5.1 Ang tanang sulud, logo, ug trademark nga makita sa among website iya sa <b>Innovaplas Packaging Corporation</b>.</p>
                        <p>5.2 Ang pagkopya, pagbahin, o pag-usab sa among sulud nga walay pagtugot kay mahug sa ilegal nga buhat ug ginadili.</p>
                    </div>
                    <h6>6. Limitasyon sa Pananagut</h6>
                    <div class="ms-3">
                        <p>6.1 Ang Innovaplas Packaging Corporation dili responsable sa bisan unsang indirect, incidental, o consequential damages nga resulta sa paggamit sa among serbisyo.</p>
                        <p>6.2 Dili usab kami liable sa mga paglangan, sayop, o pag-interrupt nga hinungdan sa mga external nga butang nga wala sa among kontrol.</p>
                    </div>
                    <h6>7. Privacy</h6>
                    <div class="ms-3">
                        <p>7.1 Ang imong paggamit sa among serbisyo supak sa among <b>Privacy Policy</b>, nga naghisgot kung unsaon namo pagkuha, pagtipig, ug pagproseso sa imong datos.</p>
                    </div>
                    <h6>8. Impormasyon sa Pakigkontak</h6>
                    <div class="ms-3">
                        <p>Kung adunay pangutana bahin niining mga Termino ug Kundisyon, palihug kontaka kami:</p>
                        <p>Email: <a href="mailto:innovaplaspackagingcorpo@gmail.com">innovaplaspackagingcorpo@gmail.com</a></p>
                        <p>Phone: <a href="tel:0917 713 0990">0917 713 0990</a></p>
                    </div>
                </div>
            </div>
        </div>
     </div> 
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            demo.checkFullPageBackgroundImage();

            $('.btn-translate').on('click', function () {
                var target = $(this).data('target');

                $('.terms-section').addClass('d-none');
                $(target).removeClass('d-none');
            });
        });
    </script>
@endpush