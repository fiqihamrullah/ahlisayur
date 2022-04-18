<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $heads = [
            'No',      
            'Order No',  
            __('validation.attributes.name'),         
            __('validation.attributes.phone_number'),    
            __('validation.attributes.status'),              
            ['label' => __('common.kolom_aksi'), 'no-export' => true, 'width' => 10],
        ];


        $config = [      
            'serverSide'=> true,       
            'processing' => true,
            'ajax' => ["url" => "/order/load","type" => "POST","data" => ["_token" =>  csrf_token() ]], 
            'order' => [[1, 'asc']],
            'columns' => [["data" => "number"],                       
                          ["data" => "order_no"],                        
                          ["data" => "name"],  
                          ["data" => "phone_number"],  
                          ["data" => "status"],  
                          ["data" => "aksi" ,'orderable' => false]],
        ];
      

        $data['heads'] =  $heads;
        $data['config'] =  $config;
         
        $data['title'] = "Order";
        $data['sub_title'] =  __('messages.sub_title_input', ['data' => $data['title'] ]);

        return view('order.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
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



        $totalDataRecord = Order::count();
        $totalFilteredRecord = $totalDataRecord; 

        $orders = Order::query();

        $orders = $orders->orderby($columnName,$columnSortOrder);
        $orders =  $orders->where("order_no","like","%" . $searchValue  . "%")                             
                                ->skip($start)
                                ->take($rowperpage)
                                ->get();

  
        $data_val =  array();

        $no = 0;

        foreach($orders as $order)
        {
             $data['no'] = $no+1;
             $data['ID'] = $customer->id;
             $data['name'] = $customer->name;
             $data['phone_number'] = $customer->phone_number;
             $data['email'] = $customer->email; 
             $data['address'] = $customer->address;


             $btnEdit = '<button class="btn-edit btn btn-xs btn-default text-primary mx-1 shadow" title="Edit" data-id="' .$customer->id.'">
             <i class="fa fa-lg fa-fw fa-pen"></i>
                        </button>';
                $btnDelete = '<button class="btn-delete btn btn-xs btn-default text-danger mx-1 shadow" title="Delete" data-id="' .$customer->id.'">
                            <i class="fa fa-lg fa-fw fa-trash"></i>
                        </button>';
                $btnDetails = '<button class="btn btn-xs btn-default text-teal mx-1 shadow" title="Details">
                                <i class="fa fa-lg fa-fw fa-eye"></i>
                            </button>';

          


             $data['aksi'] = $btnEdit .  $btnDelete  . $btnDetails ;

             $data_val[] = $data; 
              
             $no++;

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
