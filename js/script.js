$('#validate').on("click",function(){
    // validation
    var fileforupload = $('#file')[0].files[0];

    if ($('#psgc').val() == "" || typeof fileforupload === "undefined"){
        alert("Please check municipality/city psg code and file to be sanitized.");
    }

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
            alert("Please wait while compiling next process.");
            top.location.href = "lib/dupdownloader.php";
            $('#result').html(response);
         },
        error: function(data){
            alert("Uh oh! Please close the csv file before checking!");
        }
    });
})