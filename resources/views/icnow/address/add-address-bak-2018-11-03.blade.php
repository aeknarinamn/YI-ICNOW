<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>IC.NOW</title>
    <link type="text/css" rel="stylesheet" href="/icnow/vendors/css/bulma.min.css">
    <link type="text/css" rel="stylesheet" href="/icnow/vendors/css/sumoselect.min.css">
    <link type="text/css" rel="stylesheet" href="/icnow/vendors/css/pretty-checkbox.min.css">
    <link type="text/css" rel="stylesheet" href="/icnow/vendors/css/datepicker.min.css">
    <link type="text/css" rel="stylesheet" href="/icnow/resources/css/fonts.css">
    <link type="text/css" rel="stylesheet" href="/icnow/resources/css/default.css">
    <link type="text/css" rel="stylesheet" href="/icnow/resources/css/page/address.css">
</head>

<body>
    <div class="container">
        @include('icnow.layout.header')
        <section>
            <div class="section-title" style="padding-top: 12px;">โปรดระบุที่อยู่สำหรับการจัดส่ง</div>
            <div class="address-input">
                <div class="input-close-wrap">
                    <input type="text" id="pac-input" class="input" placeholder="--แสดงที่อยู่จากที่ลูกค้าปักหมุดในโลเคชั่น--">
                    <button type="button" class="icon-close" onclick="clearInput()">
                        <img src="/icnow/resources/images/icon-close.png" alt="">
                    </button>
                </div>
            </div>
            <div class="address-map" id="map"></div>

        </section>
        <footer>
            <div class="footer-btn">
                <div class="footer-btn-column is-32 is-lg-20">
                    <a href="/address" class="image-btn">
                        <img src="/icnow/resources/images/btn-back.png" alt="Button">
                    </a>
                </div>
                <div class="footer-btn-column is-68 is-lg-80 text-right" style="padding-right: 40px">
                    <a href="#" onclick="submitForm()" class="link-to">ดำเนินการต่อ</a>
                </div>
            </div>
        </footer>
    </div>
    <form id="form-submit" action="{{ action('ICNOW\View\AddressController@addressAddStore') }}" method="post">
        {!! csrf_field() !!}
        <input type="hidden" name="lat" id="lat">
        <input type="hidden" name="long" id="long">
    </form>

    <div id="alertModal" class="modal">
        <div class="modal-background" id="backgroundModal"></div>
        <div class="modal-content">
            <div class="modal-title">ขออภัย</div>
            <div class="modal-detail">กรุณาระบุที่อยู่ของท่าน</div>
            <div class="modal-button">
                <a href="javascript:closeModal()" class="image-btn">
                    <img src="/icnow/resources/images/btn-cancel.png" alt="Button">
                </a>
            </div>
        </div>
    </div>

    <script src="/icnow/vendors/js/jquery-3.3.1.min.js"></script>
    <script src="/icnow/vendors/js/jquery.sumoselect.min.js"></script>
    <script src="/icnow/vendors/js/datepicker.min.js"></script>
    <script src="/icnow/vendors/js/datepicker.en.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBHKwJb9QKjnI9M0KUOVwosdF7JqVXO_Kc&libraries=places&callback=initMap&language=th"
        async defer></script>
    <script>
        var map, infoWindow;
        function handleLocationError(browserHasGeolocation, infoWindow, pos) {
            infoWindow.setPosition(pos);
            infoWindow.setContent(browserHasGeolocation ?
                                  'Error: The Geolocation service failed.' :
                                  'Error: Your browser doesn\'t support geolocation.');
            infoWindow.open(map);
          }
        function initMap() {
            map = new google.maps.Map(document.getElementById('map'), {
              center: {lat: 13.7248936, lng: 100.493024},
              zoom: 13
            });
            infoWindow = new google.maps.InfoWindow;

            // Try HTML5 geolocation.
            if (navigator.geolocation) {
              navigator.geolocation.getCurrentPosition(function(position) {
                var pos = {
                  lat: position.coords.latitude,
                  lng: position.coords.longitude
                };
                var marker = new google.maps.Marker({
                  map: map,
                  anchorPoint: new google.maps.Point(0, -29)
                });
                marker.setPosition(pos);
                marker.setVisible(true);
                geocodeLatLng(position.coords.latitude,position.coords.longitude);

                infoWindow.setPosition(pos);
                // infoWindow.setContent('Location found.');
                // infoWindow.open(map);
                map.setCenter(pos);
              }, function() {
                handleLocationError(true, infoWindow, map.getCenter());
              });
            } else {
              // Browser doesn't support Geolocation
              handleLocationError(false, infoWindow, map.getCenter());
            }
              
            // var card = document.getElementById('pac-card');
            // getLocation();
            var input = document.getElementById('pac-input');
            // var types = document.getElementById('type-selector');
            // var strictBounds = document.getElementById('strict-bounds-selector');

            // map.controls[google.maps.ControlPosition.TOP_RIGHT].push(card);

            var autocomplete = new google.maps.places.Autocomplete(input);

            // Bind the map's bounds (viewport) property to the autocomplete object,
            // so that the autocomplete requests use the current map bounds for the
            // bounds option in the request.
            autocomplete.bindTo('bounds', map);

            // Set the data fields to return when the user selects a place.
            autocomplete.setFields(
                ['address_components', 'geometry', 'icon', 'name']);

            var infowindow = new google.maps.InfoWindow();
            var infowindowContent = document.getElementById('infowindow-content');
            infowindow.setContent(infowindowContent);
            var marker = new google.maps.Marker({
              map: map,
              anchorPoint: new google.maps.Point(0, -29)
            });

            autocomplete.addListener('place_changed', function() {
              infowindow.close();
              marker.setVisible(false);
              var place = autocomplete.getPlace();
              if (!place.geometry) {
                // User entered the name of a Place that was not suggested and
                // pressed the Enter key, or the Place Details request failed.
                window.alert("No details available for input: '" + place.name + "'");
                return;
              }

              // If the place has a geometry, then present it on a map.
              if (place.geometry.viewport) {
                map.fitBounds(place.geometry.viewport);
              } else {
                map.setCenter(place.geometry.location);
                map.setZoom(17);  // Why 17? Because it looks good.
              }
              marker.setPosition(place.geometry.location);
              marker.setVisible(true);

              geocodeLatLng(marker.position.lat(),marker.position.lng());

              // console.log( marker.position.lat() );
              // console.log( marker.position.lng() );

              var address = '';
              if (place.address_components) {
                address = [
                  (place.address_components[0] && place.address_components[0].short_name || ''),
                  (place.address_components[1] && place.address_components[1].short_name || ''),
                  (place.address_components[2] && place.address_components[2].short_name || '')
                ].join(' ');
              }

              // infowindowContent.children['place-icon'].src = place.icon;
              // infowindowContent.children['place-name'].textContent = place.name;
              // infowindowContent.children['place-address'].textContent = address;
              // infowindow.open(map, marker);
            });

            google.maps.event.addListener(map,'click',function(event){    
              infowindow.setContent("LatLng = " + event.latLng);
              infowindow.setPosition(event.latLng);
              infowindow.open(map,marker);
              marker.setPosition(event.latLng);
              // $("#lat").val(event.latLng.lat());
              // $("#lng").val(event.latLng.lng());
              geocodeLatLng(event.latLng.lat(),event.latLng.lng());
            }); 

            // Sets a listener on a radio button to change the filter type on Places
            // Autocomplete.
            // function setupClickListener(id, types) {
            //   var radioButton = document.getElementById(id);
            //   radioButton.addEventListener('click', function() {
            //     autocomplete.setTypes(types);
            //   });
            // }

            // setupClickListener('changetype-all', []);
            // setupClickListener('changetype-address', ['address']);
            // setupClickListener('changetype-establishment', ['establishment']);
            // setupClickListener('changetype-geocode', ['geocode']);

            // document.getElementById('use-strict-bounds')
            //     .addEventListener('click', function() {
            //       console.log('Checkbox clicked! New state=' + this.checked);
            //       autocomplete.setOptions({strictBounds: this.checked});
            //     });
          }

          function geocodeLatLng(lat,lng) {
            map = new google.maps.Map(document.getElementById('map'), {
              center: {lat: lat, lng: lng},
              zoom: 13
            });
            var infowindow = new google.maps.InfoWindow({
                map:map,
                maxWidth: 200
            });
            var marker = new  google.maps.Marker({
                map:map,
                position: new google.maps.LatLng(lat,lng),
                draggalbe:true
            });
            var lat = lat;
            var lng = lng;
            var latlng = {lat: parseFloat(lat), lng: parseFloat(lng)};
            var geocoder = new google.maps.Geocoder;
            geocoder.geocode({'location': latlng}, function(results, status) {
              if (status === 'OK') {
                if (results[1]) {             
                  marker.setPosition(latlng);
                  var rs = results[1].formatted_address;
                  $('#pac-input').val(rs);
                  // alert(rs);
                  var tmp = rs.split(" ");
                  // console.log(tmp);
                  var tumbon_name = tmp[1];
                  var ampur_name = tmp[3];
                  var province_name = tmp[4];
                  var zip_code = tmp[5];

                  $('#lat').val(lat);
                  $('#long').val(lng);

                // $("#tumbon_name").val(tumbon_name);
                // $("#ampur_name").val(ampur_name);
                // $("#province_name").val(province_name);
                // $("#zip_code").val(zip_code);

                  infowindow.setContent(results[1].formatted_address);
                  infowindow.open(map, marker);

                  // $.get("https://maps.googleapis.com/maps/api/geocode/json?latlng=40.714224,-73.961452&key=AIzaSyBB1Rd5CN5S8taXbmNw-_YWGJyJ3CcFZik", function(data, status){
                  //       console.log(data);
                  //   });
                } else {
                  window.alert('No results found');
                }
              } else {
                window.alert('Geocoder failed due to: ' + status);
              }
            });

            google.maps.event.addListener(map,'click',function(event){    
              infowindow.setContent("LatLng = " + event.latLng);
              infowindow.setPosition(event.latLng);
              infowindow.open(map,marker);
              marker.setPosition(event.latLng);
              // $("#lat").val(event.latLng.lat());
              // $("#lng").val(event.latLng.lng());
              geocodeLatLng(event.latLng.lat(),event.latLng.lng());
            }); 

          }

          function closeModal(){
            $('#alertModal').hide();
          }

          function clearInput(){
            $('#pac-input').val('');
          }
          function submitForm(){
            var lat = $('#lat').val();
            var long = $('#long').val();
            $isCheck = 1;
            if(lat == "" || long == ""){
                $isCheck = 0;
            }

            if($isCheck == 1){
                $('#form-submit').submit();
            }else{
                var modal = document.getElementById('alertModal');
                modal.style.display = "block";
            }
          }
    </script>
</body>

</html>