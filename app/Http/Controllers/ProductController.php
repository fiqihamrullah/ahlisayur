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
        $draw = $request->post('draw');
        $start = $request->post("start");
        $rowperpage = $request->post("length");  

        $order = $request->post('order');        
        $columnName_arr = $request->post('columns');

        $search_arr = $request->post('search');

        $columnIndex = $order[0]['column'];  
        $columnName = $columnName_arr[$columnIndex]['data'];  
        $columnSortOrder = $order[0]['dir'];  
        
        $searchValue = $search_arr['value'];  


        $totalFilteredRecord = $totalDataRecord = $draw_val = "";               
 
         $totalDataRecord = Product::count();  
         $product = Product::query();    
         
         if(!empty($searchValue))
         {                             
          
              $product  = $product->where("name","like","%" . $searchValue . "%");           

          }

          $totalFilteredRecord = $product->count();

          $records = $product->offset($start)
                           ->limit($rowperpage)                          
                           ->get();


       
          $data_val = array();
          if(!empty($records))
          {
              $no = 0;
              foreach ($records as $val)
              {                 
                  $records[$no]['number'] = $start_val + ($no+1);                  
                  $records[$no]['category'] =  $val->category->name;  
                  
                  $records[$no]['picture'] = "<a id='photo_profile'   title='{$val->name}' href='" . url('/'). "/foto_produk/" . $val->picture_path . "'><img src='" . url('/') . "/foto_produk/$val->picture_path' width='180' class='img-circle' /></a>";   
   


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


                  $records[$no]['aksi'] = $btnEdit . $btnDelete;// . $btnDetails; 
               
                  $no++;
              }
          }

          $records = $records->sortBy([[$columnName, $columnSortOrder]]);

           $response = array(
                  "draw" => intval($draw),
                  "iTotalRecords" => $totalDataRecord,
                  "iTotalDisplayRecords" => $totalFilteredRecord,
                  "aaData" => $records,
              );
  
           return response()->json($response);
    }

}
