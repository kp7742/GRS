$.validate({
    modules : 'location, date, security, file',
    onModulesLoaded : function() {
        $('#country').suggestCountry();
    }
});

$("#Type").change(function(){
    if(this.selectedIndex === 0){
        $("#field-pen").show();
        $("#field-dept").show();
        $("#field-sem").show();
    } else {
        $("#field-pen").hide();
        $("#field-dept").hide();
        $("#field-sem").hide();
    }
});

// Restrict presentation length
$('#presentation').restrictLength( $('#pres-max-length') );

new WOW().init();