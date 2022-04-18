<x-adminlte-modal id="modalProduct" title="Produk" size="md" theme="success"
    icon="fas fa-fw fa-id-card" >

    <form action="#" id="formProduct">   

    <x-adminlte-select name="category_id" label="Kategori" label-class="text-lightblue"
    igroup-size="lg">
            <x-slot name="prependSlot">
                <div class="input-group-text bg-gradient-success">
                    <i class="fas fa-tag"></i>
                </div>
            </x-slot>

            @foreach($categories as $category) 
               <option value="{{ $category->id }}"  >{{ $category->name }} </option>
             
            @endforeach
            
         
                      
        </x-adminlte-select> 
      
 

    <x-adminlte-input name="name" label="{{ __('validation.attributes.name') }}" placeholder="{{ __('validation.attributes.name') }}" label-class="text-lightblue" igroup-size="lg">
        <x-slot name="prependSlot">
            <div class="input-group-text bg-gradient-success">
                <i class="fas fa-paper-plane  "></i>
            </div>
        </x-slot>
    </x-adminlte-input>


    <x-adminlte-input name="price" label="{{ __('validation.attributes.price') }}" placeholder="{{ __('validation.attributes.price') }}" label-class="text-lightblue" igroup-size="lg">
        <x-slot name="prependSlot">
            <div class="input-group-text bg-gradient-success">
                <i class="fas fa-card  "></i>
            </div>
        </x-slot>
    </x-adminlte-input>


    <x-adminlte-input name="unit" label="{{ __('validation.attributes.unit') }}" placeholder="{{ __('validation.attributes.unit') }}" label-class="text-lightblue" igroup-size="lg">
        <x-slot name="prependSlot">
            <div class="input-group-text bg-gradient-success">
                <i class="fas fa-paper-plane  "></i>
            </div>
        </x-slot>
    </x-adminlte-input>


    <x-adminlte-input-file name="picture_path" id="picture_path" igroup-size="lg" placeholder="{{ __('messages.pilih_file_gambar') }}"  label="{{ __('validation.attributes.picture_path') }}" label-class="text-lightblue">
                                    <x-slot name="prependSlot">
                                        <div class="input-group-text bg-gradient-success">
                                            <i class="fas fa-upload"></i>
                                        </div>
                                    </x-slot>
                                </x-adminlte-input-file>

  
    <input type="hidden" name="id" value="" />
  
    </form>

  
    <x-slot name="footerSlot"  >
        <x-adminlte-button class="mr-auto" theme="danger" label="{{ __('common.button_tutup') }}" data-dismiss="modal" icon="fas fa-ban" />         
        <x-adminlte-button  name="btnSave" type="button" label="{{ __('common.button_simpan') }}" theme="success" icon="fas fa-lg fa-save" onclick="save()"/>
    </x-slot>



</x-adminlte-modal>