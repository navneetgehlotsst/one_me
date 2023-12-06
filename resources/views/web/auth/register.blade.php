@extends('web.layouts.app')
@section('style')
<style>
    .iti{
        width: 100%;
    }
</style>
@endsection
@section('content')
<div class="form-design shadow mt-5">
    <h5 class="mb-2">Register Your account</h5>
    <form action="{{route('register.post')}}" method="post" class="">
        @csrf
        <div class="form-group floating-label-form-group enter-value">
            <label>Business Name</label>
            <input type="text" class="form-control" name="business_name" value="" placeholder="Enter Business  Name" />
            @error('business_name')
                <span class="error-message">{{$message}}</span>
            @enderror
        </div>
        <div class="form-group floating-label-form-group enter-value">
            <label>Contact Person Name</label>
            <input type="text" class="form-control" name= "full_name" value="" placeholder="Enter Your Full Name" />
            @error('full_name')
                <span class="error-message">{{$message}}</span>
            @enderror
        </div>
        <div class="form-group floating-label-form-group enter-value">
            <div class="input-gs">
                <label>Phone Number</label>
                <input type="text" class="form-control" name="phone" value="" placeholder="Enter Phone Number" />
                @error('phone')
                    <span class="error-message">{{$message}}</span>
                @enderror
            </div>
            <div class="input-group-gs">
                <button class="btn" type="button" id="button-addon2">Verify</button>
            </div>
            <div class="invalid-feedback form-error pr-4 mr-2">Not registered!</div>
        </div>
        <div class="form-group floating-label-form-group enter-value">
            <div class="input-gs">
                <label>Email</label>
                <input type="text" class="form-control" name="email" value="" placeholder="Enter Email" />
                @error('email')
                    <span class="error-message">{{$message}}</span>
                @enderror
            </div>
            <div class="input-group-gs">
                <button class="btn" type="button" id="button-addon2">Verify</button>
            </div>
            <div class="invalid-feedback form-error pr-4 mr-2">Not registered!</div>
        </div>
        <div class="form-group floating-label-form-group enter-value">
            <label>Password</label>
            <input type="password" class="form-control" name="password" value="" placeholder="Enter Password" />
            @error('password')
                <span class="error-message">{{$message}}</span>
            @enderror
        </div>
        <div class="form-group floating-label-form-group enter-value">
            <label>Repeat Password</label>
            <input type="password" class="form-control" name="repeat_password" value="" placeholder="Repeat Password" />
            @error('repeat_password')
                <span class="error-message">{{$message}}</span>
            @enderror
        </div>
        <div class="mt-3">
            <div class="form-check form-group">
                <input class="form-check-input form-gs-custom" type="checkbox" value="" id="Yes" />
                <label class="form-gs-custom-label" for="Yes"> I read and accept <a href="#">Term & Conditions</a> and <a href="#">Privacy Policy</a> </label>
            </div>
        </div>
        <div class="mt-3">
            <input type="submit" class="btn btn-primary btn-block btn-lg" value="Next" name="next">
        </div>
    </form>
    <div class="text-center mt-3">
        <p class="light-gray">If you have already account? <a href="login.html">Login Here</a></p>
    </div>
</div>
@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/js/intlTelInput-jquery.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
<script>
    $("#phone").intlTelInput({
        preferredCountries: ["us", "gb", "au"],
        separateDialCode: true,
        initialCountry: "us"
    }).on('countrychange', function (e, countryData) {
        $("#code").val(($("#phone").intlTelInput("getSelectedCountryData").dialCode));

    });
</script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key={!!env('GOOGLE_MAP_KEY')!!}&libraries=places"> </script>
<script>
    function init() {

        const options = {
            componentRestrictions: {
                country: "aus"
            },
            types: ["address"],
        };

        var input = document.getElementById('address');

        var autocomplete = new google.maps.places.Autocomplete(input, options);

        autocomplete.addListener("place_changed", fillInAddress);


        function fillInAddress() {
            // Get the place details from the autocomplete object.
            const place = autocomplete.getPlace();
            let area = "";
            let city = "";
            let state = "";
            let country = "";
            let zipcode = "";
            let latitude = place.geometry.location.lat();
            let longitude = place.geometry.location.lng();


            for (const component of place.address_components) {
                // @ts-ignore remove once typings fixed
                // console.log(component);
                const componentType = component.types[0];

                switch (componentType) {
                    case "street_number": {
                        area = `${component.long_name} ${area}`;
                        break;
                    }

                    case "route": {
                        area += component.short_name;
                        break;
                    }

                    case "postal_code": {
                        zipcode = component.long_name;
                        break;
                    }

                    case "postal_code_suffix": {
                        zipcode = `${zipcode}-${component.long_name}`;
                        break;
                    }

                    case "locality": {
                        city = component.long_name;
                        break;
                    }

                    case "administrative_area_level_1": {
                        state = component.short_name;
                        break;
                    }

                    case "country": {
                        country = component.long_name;
                        break;
                    }


                }
            }

            $("#area").val(area);
            $("#city").val(city);
            $("#state").val(state);
            $("#country").val(country);
            $("#zipcode").val(zipcode);
            $("#latitude").val(latitude);
            $("#longitude").val(longitude);

        }

    }

    google.maps.event.addDomListener(window, 'load', init);
</script>
@endsection
