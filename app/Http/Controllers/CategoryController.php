<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\CategoryRequest;
use App\Helpers\ResponseFormatter;
 

class CategoryController extends Controller
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
            __('validation.attributes.name'),         
            ['label' => __('common.kolom_aksi'), 'no-export' => true, 'width' => 10],
        ];


        $config = [      
            'serverSide'=> true,       
            'processing' => true,
            'ajax' => ["url" => "/category/load","type" => "POST","data" => ["_token" =>  csrf_token() ]], 
            'order' => [[1, 'asc']],
            'columns' => [["data" => "number"],                       
                          ["data" => "name"],                        
                          ["data" => "aksi" ,'orderable' => false]],
        ];
      

        $data['heads'] =  $heads;
        $data['config'] =  $config;
         
        $data['title'] = "Kategori Produk Sayuran";
        $data['sub_title'] =  __('messages.sub_title_input', ['data' => $data['title'] ]);

        return view('category.index',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {
        //
        $data = $request->validated();
        $newCategory = Category::create($data); 

        return ResponseFormatter::success($newCategory,__('messages.success_saved'),ResponseFormatter::HTTPCODE_RESOURCE_CREATED);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        //
        $dat = $category->delete();
        return ResponseFormatter::success($dat,__('messages.success_deleted'));
    }


    public function load(Request $request)
    {
        $totalFilteredRecord = $totalDataRecord = $draw_val = "";

        $columns_list = array( 
                           0 =>'id', 
                           1 =>'name'                     
                            );
 
         $totalDataRecord = Category::count();         
         $totalFilteredRecord = $totalDataRecord; 
       
         $limit_val = $request->input('length');
         $start_val = $request->input('start');
         $order_val = $columns_list[$request->input('order.0.column')];
         $dir_val = $request->input('order.0.dir');
               
         if(empty($request->input('search.value')))
         {                             
             $category = Category::orderBy("name","asc");
             $totalFilteredRecord = $category->count();            
         }
         else 
         {

              $search_text = $request->input('search.value');              
              $category  = Category::filter($search_text);
                            
              $totalFilteredRecord = $category->count();      
       
             

          }


          $category_data = $category->offset($start_val)
                           ->limit($limit_val)
                           ->orderBy($order_val,$dir_val)
                           ->get();


       
          $data_val = array();
          if(!empty($category_data))
          {
              $no = 0;
              foreach ($category_data as $val)
              {                 
                  $data['number'] = $start_val + ($no+1);
                  $data['id'] =  $val->id;
                  $data['name'] =  $val->name;    
   


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
          $response = array(
              "draw"            => intval($draw_val),  
              "recordsTotal"    => intval($totalDataRecord),  
              "recordsFiltered" => intval($totalFilteredRecord), 
              "data"            => $data_val  
              );
               
        
         return response()->json($response);
        
    }



}
