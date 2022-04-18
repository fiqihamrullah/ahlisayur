function showLoading(str = "Mohon Tunggu...")
{

    Swal.fire({
        title: 'Loading!',
        html: str,
        didOpen: () => {
          Swal.showLoading();
        }
      });
  

}

function showErrors(responseText)
{
    let  resJSON = JSON.parse(responseText);    
    
    var errorText = "";

    for (let key in resJSON.errors) 
    {
      errorText = resJSON.errors[key];  
      break;        
    } 
    
    Swal.fire({                              
      icon: 'error',
      title: resJSON.message ,
      text: errorText,
      showConfirmButton: false,
      timer: 2000
    });
}

function doneLoading()
{
    Swal.close();
}

function resetValidation() {
    $('.is-invalid').removeClass('is-invalid')
    $('.is-valid').removeClass('is-valid')
    $('span.invalid-feedback').remove()
}

function formatNumber(num) 
{
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.')
}

