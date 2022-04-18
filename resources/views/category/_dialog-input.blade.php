<x-adminlte-modal id="modalCategory" title="Kategori" size="md" theme="success"
    icon="fas fa-fw fa-id-card" >

    <form action="#" id="formCategory">   
      
 

    <x-adminlte-input name="name" label="{{ __('validation.attributes.name') }}" placeholder="{{ __('validation.attributes.name') }}" label-class="text-lightblue">
        <x-slot name="prependSlot">
            <div class="input-group-text">
                <i class="fas fa-paper-plane text-teal"></i>
            </div>
        </x-slot>
    </x-adminlte-input>

  
    <input type="hidden" name="id" value="" />
  
    </form>

  
    <x-slot name="footerSlot"  >
        <x-adminlte-button class="mr-auto" theme="danger" label="{{ __('common.button_tutup') }}" data-dismiss="modal" icon="fas fa-ban" />         
        <x-adminlte-button  name="btnSave" type="button" label="{{ __('common.button_simpan') }}" theme="success" icon="fas fa-lg fa-save" onclick="save()"/>
    </x-slot>



</x-adminlte-modal>