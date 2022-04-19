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
 
         $totalDataRecord = Category::count();         
         $category = Category::query();
               
         if(!empty($searchValue))
         {                             
          
              $category  = $category->where("name","like","%" . $searchValue . "%");           

          }

          $totalFilteredRecord = $category->count();   


          $records = $category->offset($start)
                           ->limit($rowperpage)                          
                           ->get();
                           // ->orderBy($columnName,$columnSortOrder)


       
         
          if(!empty($records))
          {
              $no = 0;
              foreach ($records as $val)
              {                 
                            
                  $records[$no]["number"]  = $start + ($no+1); 
                
                  $btnEdit = '<button class="edit-modal btn btn-xs btn-success text-default mx-1  " title="' . __('common.button_perbaiki') . '" data-id="' . $val->id . '" data-title="{$val->name}" data-description="{$val->name}">
                 <i class="fas fa-lg fa-fw fa-pen"></i></button>';
                  $btnDelete = '<button class="delete-modal btn btn-xs btn-success text-default mx-1  " title="' .  __('common.button_hapus') . '" data-id="' . $val->id . '" data-title="{$val->name}" data-description="{$val->name}">
                                   <i class="fas fa-lg fa-fw fa-trash"></i></button>';
                                

                 /* if ($val->orders_count>0)
                  {
                      $btnDelete ="";
                  }*/


                  $records[$no]["aksi"]= $btnEdit . $btnDelete;// . $btnDetails; 
                 
             
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
