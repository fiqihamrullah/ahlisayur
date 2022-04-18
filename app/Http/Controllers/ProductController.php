<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;

use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $heads = [
            'No',        
            "Kategori",   
            __('validation.attributes.name'),                 
            __('validation.attributes.price'), 
            __('validation.attributes.unit'), 
            "Gambar",
            ['label' => __('common.kolom_aksi'), 'no-export' => true, 'width' => 10],
        ];


        $config = [      
            'serverSide'=> true,       
            'processing' => true,
            'ajax' => ["url" => "/product/load","type" => "POST","data" => ["_token" =>  csrf_token() ]], 
            'order' => [[1, 'asc']],
            'columns' => [["data" => "number"], 
                          ["data" => "category"],                       
                          ["data" => "name"],                  
                          ["data" => "price"],   
                          ["data" => "unit"],   
                          ["data" => "picture"],   
                          ["data" => "aksi" ,'orderable' => false]],
        ];
      

        $data['heads'] =  $heads;
        $data['config'] =  $config;

        $data['categories']  = Category::orderby("name","asc")->get();
         
        $data['title'] = "Produk Sayuran";
        $data['sub_title'] =  __('messages.sub_title_input', ['data' => $data['title'] ]);

        return view('product.index',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        //
        $data = $request->validated();


        $nameOfFile = "";

        if($request->hasFile('picture_path'))
        {
            $nameOfFile =   $this->uploadPhoto($request);
            $data['picture_path'] = $nameOfFile;
        }else {
            $data['picture_path'] = "";   
        }

        $newProduct= Product::create($data); 

        return ResponseFormatter::success($newProduct,__('messages.success_saved'),ResponseFormatter::HTTPCODE_RESOURCE_CREATED);
    }


    public function uploadPhoto(Request $request)
    {

        $name = $request->file('picture_path')->getClientOriginalName();
       // $extension = $request->file('picture')->extension();
        $newFile = time() . "_" .  $name;
        $res =  Storage::disk('produk')->putFileAs('', $request->file('picture_path'),$newFile);
        return $newFile;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
        $dat = $product->delete();
        return ResponseFormatter::success($dat,__('messages.success_deleted'));
    }


    public function load(Request $request)
    {
        $totalFilteredRecord = $totalDataRecord = $draw_val = "";

        $columns_list = array( 
                           0 =>'id', 
                           1 =>'name'                     
                            );
 
         $totalDataRecord = Product::count();         
         $totalFilteredRecord = $totalDataRecord; 
       
         $limit_val = $request->input('length');
         $start_val = $request->input('start');
         $order_val = $columns_list[$request->input('order.0.column')];
         $dir_val = $request->input('order.0.dir');
               
         if(empty($request->input('search.value')))
         {                             
             $product = Product::orderBy("name","asc");
             $totalFilteredRecord = $product->count();            
         }
         else 
         {

              $search_text = $request->input('search.value');              
              $product  = Product::filter($search_text);
                            
              $totalFilteredRecord = $product->count();      
       
             

          }


          $product_data = $product->offset($start_val)
                           ->limit($limit_val)
                           ->orderBy($order_val,$dir_val)
                           ->get();


       
          $data_val = array();
          if(!empty($product_data))
          {
              $no = 0;
              foreach ($product_data as $val)
              {                 
                  $data['number'] = $start_val + ($no+1);
                  $data['id'] =  $val->id;
                  $data['name'] =  $val->name;    
                  $data['category'] =  $val->category->name;  
                  $data['price'] =  $val->price;
                  $data['unit'] =  $val->unit;


                  $data['picture'] = "<a id='photo_profile'   title='{$val->name}' href='" . url('/'). "/foto_produk/" . $val->picture_path . "'><img src='" . url('/') . "/foto_produk/$val->picture_path' width='180' class='img-circle' /></a>";   
   


                 $btnEdit = '<button class="edit-modal btn btn-xs btn-success text-default mx-1  " title="' . __('common.button_perbaiki') . '" data-id="' . $val->id . '" data-title="{$val->name}" data-description="{$val->name}">
                 <i class="fas fa-lg fa-fw fa-pen"></i></button>';
                 $btnDelete = '<button class="delete-modal btn btn-xs btn-success text-default mx-1  " title="' .  __('common.button_hapus') . '" data-id="' . $val->id . '" data-title="{$val->name}" data-description="{$val->name}">
                                   <i class="fas fa-lg fa-fw fa-trash"></i></button>';
                                   /*
                 $btnDetails = '<button class="show-detail-modal btn btn-xs btn-success text-warning mx-1  " data-id="' . $val->id . '" data-title="{$val->name}" data-description="{$val->name}" title="' . __('common.button_detail')   . '">
                                   <i class="far fa-lg fa-fw fa-eye"></i>
                               </button>'; */

                 /* if ($val->orders_count>0)
                  {
                      $btnDelete ="";
                  }*/


                  $data['aksi'] = $btnEdit . $btnDelete;// . $btnDetails; 
                 
                  $data_val[] = $data;       
                  $no++;
              }
          }

          $draw_val = $request->input('draw');  
          $get_json_data = array(
              "draw"            => intval($draw_val),  
              "recordsTotal"    => intval($totalDataRecord),  
              "recordsFiltered" => intval($totalFilteredRecord), 
              "data"            => $data_val  
              );
               
          echo json_encode($get_json_data); 
    }

}
