$.ajax({
    method: "POST",
    url: "./php/requisicoes-motorista.php",
    data: { opt_localidade: "xxx"}
}).done(function( msg ) {
        var _data = JSON.parse(msg);
        var outPut = "<option selected></option>";
        for(i = 0; i < _data.length; i++){
            outPut += "<option>"+ _data[i].nome +"</option>";
        }
        $("#input_localidade").html(outPut);
    });



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