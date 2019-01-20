$.validate({
    modules : 'location, date, security, file',
    onModulesLoaded : function() {
        $('#country').suggestCountry();
    }
});

//$("#modalOtpBox").click();

$("#Type").change(function(){
    if(this.selectedIndex === 0){
        $("#field-pen").show();
        $("#field-dept").show();
        $("#field-sem").show();
    } else if(this.selectedIndex === 2){
        $("#field-pen").hide();
        $("#field-dept").show();
        $("#field-sem").hide();
    } else {
        $("#field-pen").hide();
        $("#field-dept").hide();
        $("#field-sem").hide();
    }
});

// Restrict presentation length
$('#presentation').restrictLength( $('#pres-max-length') );

new WOW().init();