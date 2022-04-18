<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;



class CustomerController extends Controller
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
            __('validation.attributes.name'),         
            __('validation.attributes.phone_number'),    
            __('validation.attributes.address'),    
            __('validation.attributes.email'),    
            ['label' => __('common.kolom_aksi'), 'no-export' => true, 'width' => 10],
        ];


        $config = [      
            'serverSide'=> true,       
            'processing' => true,
            'ajax' => ["url" => "/customer/load","type" => "POST","data" => ["_token" =>  csrf_token() ]], 
            'order' => [[1, 'asc']],
            'columns' => [["data" => "number"],                       
                          ["data" => "name"],                        
                          ["data" => "phone_number"],  
                          ["data" => "address"],  
                          ["data" => "email"],  
                          ["data" => "aksi" ,'orderable' => false]],
        ];
      

        $data['heads'] =  $heads;
        $data['config'] =  $config;
         
        $data['title'] = "Pelanggan";
        $data['sub_title'] =  __('messages.sub_title_input', ['data' => $data['title'] ]);

        return view('customer.index',$data);

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
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
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



        $totalDataRecord = Customer::count();
        $totalFilteredRecord = $totalDataRecord; 

        $customers = Customer::query();

        $customers = $customers->orderby($columnName,$columnSortOrder);
        $customers =  $customers->where("name","like","%" . $searchValue  . "%")
                                ->orWhere("phone_number","like","%" . $searchValue  . "%")
                                ->orWhere("email","like","%" . $searchValue  . "%")
                                ->skip($start)
                               ->take($rowperpage)
                               ->get();

  
        $data_val =  array();

        $no = 0;

        foreach($customers as $customer)
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
