$('#validate').on("click",function(){
    var form = $('form')[0];
    var formData = new FormData();
    formData.append('forsanitizefile', $('input[type=file]')[0].files[0]);
    $.ajax({
        url: "lib/dupchecker.php",
        type: "POST",
        processData:false,
        contentType:false,
        data: formData,
        success: function (response) {
            $('#result').html(response);
        },
        error: function(data){
            alert("Uh oh! Please close the csv file before checking!");
        }
    });
})