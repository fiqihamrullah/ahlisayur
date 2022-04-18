var table;
var save_method; //for save method string
var myData ={};
 

jQuery(function()
{
    myData._token = $('meta[name="csrf-token"]').attr('content');      

    $("#btnAdd").on('click',function() 
    {         
        save_method = 'add';  
        $('#modalProduct').modal('show');        
        resetValidation();
        $('#formProduct')[0].reset();                
        $('.modal-title').text('Tambahkan Produk');   
    });

    
 
    $(document).on('click', '.delete-modal', function() 
    {
      var id = $(this).data('id');


      Swal.fire({
        title: 'Apa kamu yakin?',
        text: 'Kamu akan menghapusnya?!',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'ya, hapus saja!',
        cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) 
            {         
                showLoading();
                deleteProduct(id);        
            }  
        });       


      });


     

});


function reloadTable()
{      
   table = $("#table").DataTable();     
   table.draw(false);
}



function save()
{

  var url,method;
  url ="product";
  method = "POST";

  var formData = new FormData($("#formProduct")[0]); 

  if(save_method == 'edit')
     {
        
         url += "/" + $('[name="id"]').val();// + "?_method=PUT";        
         formData.append('_method', 'PUT');
   }   
   

   if ($("#formProduct").valid())
   {
    
          $.ajax({
            headers: {
                'X-CSRF-TOKEN':  myData._token
            },
            url : url,
            method: method,
            data: formData,    
            processData: false,
            contentType: false,
            beforeSend: showLoading(),     
            success: function(response)
            { 
                doneLoading();         
                toastr.success(response.message);       
                $('#modalProduct').modal('hide');  
                reloadTable();            
                resetValidation();
              
              
              
            },
            error: function (jqXHR, textStatus, errorThrown)
            {               
                console.log(jqXHR.responseText);
                showErrors(jqXHR.responseText);
              
            }
        }); 
   }

}  


function deleteProduct(id)
{
     $.ajax({
                      type: 'delete',
                      url: 'product/' + id,
                      data: {
                        '_token': myData._token                       
                      },
                      success: function(response) 
                      {
                              
                              doneLoading(); 
                              Swal.fire({                              
                                icon: 'success',
                                title: response.message,
                                showConfirmButton: false,
                                timer: 1500
                              });
                         

                            reloadTable();
                         
                      },
                            error: function (jqXHR, textStatus, errorThrown)
                            {
                              console.log(jqXHR.responseText);
                            }

                    }); 
}





