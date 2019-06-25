var bdvID = $_GET('bdv_id');

$.ajax({
    method: "POST",
    url: "./php/requisicoes-rota.php",
    data: { rota_info: bdvID}
}).done(function( msg ) {
    var info_rota = JSON.parse(msg);
    var km_total = parseFloat(info_rota[0].km_total);
    var km_calculado = parseFloat(info_rota[0].km_calculado);
    var km_rodovia = parseFloat(info_rota[0].km_rodovia);
    var km_cidade = parseFloat(info_rota[0].km_cidade);

    $("#info-nome").html(info_rota[0].motoristaNome);
    $("#info-frota").html(info_rota[0].frota_veiculo); 
    $("#info-horai").html(info_rota[0].hora_inicial); 
    $("#info-horaf").html(info_rota[0].hora_final); 
    $("#info-velocidadem").html(info_rota[0].velocidade_media+" km/h"); 
    $("#info-kmmotorista").html(parseFloat(km_total.toFixed(2))+" km"); 
    $("#info-kmcalculado").html(parseFloat(km_calculado.toFixed(2))+" km");    
    $("#info-kmrodovia").html(parseFloat(km_rodovia.toFixed(2))+" km"); 
    $("#info-kmcidade").html(parseFloat(km_cidade.toFixed(2))+" km"); 
    $("#info-placareserva").html(info_rota[0].placa_reserva); 
    });


var map;
function initMap() {
    $.ajax({
        method: "POST",
        url: "./php/requisicoes-rota.php",
        data: { rota_pontos: bdvID}
    }).done(function( msg ) {
            var dados_rota = JSON.parse(msg);
            
            map = new google.maps.Map(
                document.getElementById('map'),
                {center: new google.maps.LatLng(dados_rota[0].latitude, dados_rota[0].longitude), zoom: 16});
        
            var iconBase = './media/';
        
            var icons = {
            start: {
                icon: iconBase + 'start_point.png'
            },
            mid: {
                icon: iconBase + 'mid_point.png'
            },
            end: {
                icon: iconBase + 'end_point.png'
            }
            };
            
            var features = [];
            var flightPlanCoordinates = [];
            for(i=0; i<dados_rota.length; ++i){
                if(i==0) features[0]={
                    position: new google.maps.LatLng(dados_rota[i].latitude, dados_rota[i].longitude),
                    type: 'start'
                };
                else if(i==dados_rota.length-1) features[1]={
                    position: new google.maps.LatLng(dados_rota[i].latitude, dados_rota[i].longitude),
                    type: 'end'
                };
                // else features[i]={
                //     position: new google.maps.LatLng(dados_rota[i].latitude, dados_rota[i].longitude),
                //     type: 'mid'
                // };

                flightPlanCoordinates[i]={ lat: parseFloat(dados_rota[i].latitude), lng: parseFloat(dados_rota[i].longitude) };
            }
        
            // Create markers.
            for (var i = 0; i < features.length; i++) {
            var marker = new google.maps.Marker({
                position: features[i].position,
                icon: icons[features[i].type].icon,
                map: map
            });
            };

            var flightPath = new google.maps.Polyline({
                path: flightPlanCoordinates,
                geodesic: true,
                strokeColor: '#FF0000',
                strokeOpacity: 1.0,
                strokeWeight: 2
            });
    
            flightPath.setMap(map);
      
        });

    
}



function $_GET(param) {
    var vars = {};
    window.location.href.replace( location.hash, '' ).replace( 
        /[?&]+([^=&]+)=?([^&]*)?/gi, // regexp
        function( m, key, value ) { // callback
            vars[key] = value !== undefined ? value : '';
        }
    );
    if ( param ) {
        return vars[param] ? vars[param] : null;	
    }
    return vars;
}



    // // Initialize and add the map
    // function initMap() {
    //     // The location of Uluru
    //     var uluru = {lat: -25.344, lng: 131.036};
    //     // The map, centered at Uluru
    //     var map = new google.maps.Map(
    //         document.getElementById('map'), {zoom: 15, center: uluru});
    //     // The marker, positioned at Uluru
    //     var marker = new google.maps.Marker({position: uluru, map: map});
    // }
