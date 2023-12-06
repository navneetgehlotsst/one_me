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
    <h5 class="mb-2">Add Store Details</h5>
    <form action="{{route('register.post')}}"  method="post" class="">
        @csrf
        <input type="hidden" name="userid" value="{{$bissunesID}}">
        <div class="form-group floating-label-form-group enter-value">
            <label>Add Location</label>
            <input type="text" class="form-control" name="address" id="address" value="" placeholder="Enter Your Location" />
            @error('address')
                <span class="error-message">{{$message}}</span>
            @enderror
            <input type="hidden" name="area" id="area" value="{{old('area')}}">
            <input type="hidden" name="city" id="city" value="{{old('city')}}">
            <input type="hidden" name="state" id="state" value="{{old('state')}}">
            <input type="hidden" name="country" id="country" value="{{old('country')}}">
            <input type="hidden" name="zipcode" id="zipcode" value="{{old('zipcode')}}">
            <input type="hidden" name="latitude" id="latitude" value="{{old('latitude')}}">
            <input type="hidden" name="longitude" id="longitude" value="{{old('longitude')}}">
        </div>
        <div class="mt-3">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d26506.74914467074!2d151.19459004274387!3d-33.85503362350352!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6b12ae665e892fdd%3A0x3133f8d75a1ac251!2sSydney%20Opera%20House!5e0!3m2!1sen!2sin!4v1699332202927!5m2!1sen!2sin"
                width="100%"
                height="190"
                style="border: 0;"
                allowfullscreen=""
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
            ></iframe>
        </div>
        <div class="mt-3 gs-check-form-bg">
            <h6 class="mb-3">Select Business Category</h6>
            <div class="info-formfield info-formfield-check row">
                @foreach ($categories as $category)
                    <div class="mb-2 col-6 col-md-4 gs-div" style="display: block;">
                        <div class="form-check">
                            <input class="form-check-input form-gs-custom" type="checkbox" name="category[]" value="{{$category->id}}" id="Oid1" />
                            <label class="form-gs-custom-label" for="Oid1"> {{$category->name}}</label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="mt-3 gs-check-form-bg">
            <h6 class="mb-3">Business Operational hours & days</h6>
            <div class="info-field">
                <div class="row align-items-center">
                    <div class="col-12 col-md-3">
                        <div class="form-check">
                            <input class="form-check-input form-gs-custom" type="checkbox" checked="" value="" id="id1" />
                            <label class="form-gs-custom-label-g ml-1" for="id1"> Sun</label>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="form-check">
                            <p class="mb-0">Closed</p>
                        </div>
                    </div>
                </div>
                <div class="row gs-time align-items-center">
                    <div class="col-12 col-md-2">
                        <div class="form-check">
                            <input class="form-check-input form-gs-custom" type="checkbox" checked="" value="" id="id6" />
                            <label class="form-gs-custom-label-g ml-1" for="id6"> Mon</label>
                        </div>
                    </div>
                    <div class="col-6 col-md-5">
                        <div class="form-group floating-label-form-group enter-value">
                            <label>Open Time</label>
                            <input type="time" class="form-control" value="" placeholder="Open Time" />
                        </div>
                    </div>
                    <div class="col-6 col-md-5">
                        <div class="form-group floating-label-form-group enter-value">
                            <label>Close Time</label>
                            <input type="time" class="form-control" value="" placeholder="Close Time" />
                        </div>
                    </div>
                </div>
                <div class="row gs-time align-items-center">
                    <div class="col-12 col-md-2">
                        <div class="form-check">
                            <input class="form-check-input form-gs-custom" type="checkbox" checked="" value="" id="id6" />
                            <label class="form-gs-custom-label-g ml-1" for="id6"> Tue</label>
                        </div>
                    </div>
                    <div class="col-6 col-md-5">
                        <div class="form-group floating-label-form-group enter-value">
                            <label>Open Time</label>
                            <input type="time" class="form-control" value="" placeholder="Open Time" />
                        </div>
                    </div>
                    <div class="col-6 col-md-5">
                        <div class="form-group floating-label-form-group enter-value">
                            <label>Close Time</label>
                            <input type="time" class="form-control" value="" placeholder="Close Time" />
                        </div>
                    </div>
                </div>
                <div class="row gs-time align-items-center">
                    <div class="col-12 col-md-2">
                        <div class="form-check">
                            <input class="form-check-input form-gs-custom" type="checkbox" checked="" value="" id="id6" />
                            <label class="form-gs-custom-label-g ml-1" for="id6"> Wed</label>
                        </div>
                    </div>
                    <div class="col-6 col-md-5">
                        <div class="form-group floating-label-form-group enter-value">
                            <label>Open Time</label>
                            <input type="time" class="form-control" value="" placeholder="Open Time" />
                        </div>
                    </div>
                    <div class="col-6 col-md-5">
                        <div class="form-group floating-label-form-group enter-value">
                            <label>Close Time</label>
                            <input type="time" class="form-control" value="" placeholder="Close Time" />
                        </div>
                    </div>
                </div>
                <div class="row gs-time align-items-center">
                    <div class="col-12 col-md-2">
                        <div class="form-check">
                            <input class="form-check-input form-gs-custom" type="checkbox" checked="" value="" id="id6" />
                            <label class="form-gs-custom-label-g ml-1" for="id6"> Thu</label>
                        </div>
                    </div>
                    <div class="col-6 col-md-5">
                        <div class="form-group floating-label-form-group enter-value">
                            <label>Open Time</label>
                            <input type="time" class="form-control" value="" placeholder="Open Time" />
                        </div>
                    </div>
                    <div class="col-6 col-md-5">
                        <div class="form-group floating-label-form-group enter-value">
                            <label>Close Time</label>
                            <input type="time" class="form-control" value="" placeholder="Close Time" />
                        </div>
                    </div>
                </div>
                <div class="row gs-time align-items-center">
                    <div class="col-12 col-md-2">
                        <div class="form-check">
                            <input class="form-check-input form-gs-custom" type="checkbox" checked="" value="" id="id6" />
                            <label class="form-gs-custom-label-g ml-1" for="id6"> Fri</label>
                        </div>
                    </div>
                    <div class="col-6 col-md-5">
                        <div class="form-group floating-label-form-group enter-value">
                            <label>Open Time</label>
                            <input type="time" class="form-control" value="" placeholder="Open Time" />
                        </div>
                    </div>
                    <div class="col-6 col-md-5">
                        <div class="form-group floating-label-form-group enter-value">
                            <label>Close Time</label>
                            <input type="time" class="form-control" value="" placeholder="Close Time" />
                        </div>
                    </div>
                </div>
                <div class="row gs-time align-items-center">
                    <div class="col-12 col-md-2">
                        <div class="form-check">
                            <input class="form-check-input form-gs-custom" type="checkbox" checked="" value="" id="id6" />
                            <label class="form-gs-custom-label-g ml-1" for="id6"> Sat</label>
                        </div>
                    </div>
                    <div class="col-6 col-md-5">
                        <div class="form-group floating-label-form-group enter-value">
                            <label>Open Time</label>
                            <input type="time" class="form-control" value="" placeholder="Open Time" />
                        </div>
                    </div>
                    <div class="col-6 col-md-5">
                        <div class="form-group floating-label-form-group enter-value">
                            <label>Close Time</label>
                            <input type="time" class="form-control" value="" placeholder="Close Time" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="custom-file mt-3">
            <input type="file" class="custom-file-input" id="customFile" />
            <label class="custom-file-label" for="customFile">Upload logo.png</label>
        </div>

        <div class="row">
            <div class="mt-3 col-12 col-md-12">
                <hr style="margin-top: 7px; margin-bottom: 3px;" />
            </div>
            <div class="mt-3 col-6 col-md-6">
                <a href="register.html" class="btn btn-primary btn-primary-gs btn-block btn-lg">Go back </a>
            </div>
            <div class="mt-3 col-6 col-md-6">
                <a href="register-next-two.html" class="btn btn-primary btn-block btn-lg">Next </a>
            </div>
        </div>
    </form>
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
