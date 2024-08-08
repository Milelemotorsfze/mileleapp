<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Models\LetterOfIndent;
use App\Models\LetterOfIndentItem;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;
use App\Models\LoiSoNumber;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Http\Request;

class LOIItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Builder $builder,Request $request)
    {
        $data = LetterOfIndentItem::orderBy('updated_at','DESC')->with([
                'LOI' => function ($query) {
                    $query->select('id','uuid','client_id','date','category','loi_approval_date',
                    'is_expired','dealers','status','sales_person_id','review','comments');
                },
                'LOI.client'  => function ($query) {
                    $query->select('id','name','customertype','country_id');
                },
                'LOI.client.country'  => function ($query) {
                    $query->select('id','name');
                },
                'masterModel' => function($query){
                    $query->select('id','model', 'sfx','master_model_line_id','steering');
                },
                'masterModel.modelLine' => function($query){
                    $query->select('id','model_line');
                },
                'LOI.salesPerson' => function($query){
                    $query->select('id', 'name');
                },
                'LOI.soNumbers' => function($query){
                    $query->select('id','so_number');
                }]);

                if($request->export == 'EXCEL') {
                    $data = $data->get();
                  return (new FastExcel($data))->download('LOI-ITEMS.csv', function ($data) {
                    $soNumbers = LoiSoNumber::where('letter_of_indent_id', $data->LOI->id)
                                     ->pluck('so_number')->toArray();

                        $soNumbers = implode(",", $soNumbers);
                        if($data->LOI->is_expired == true) {
                            $is_expired = 'Expired';
                        }else{
                            $is_expired = 'Not Expired';
                        }   
                        return [
                            'LOI Number' => $data->LOI->uuid,
                            'LOI Date' => $data->LOI->date,
                            'LOI Approval Date' => $data->LOI->loi_approval_date,
                            'Dealer' => $data->LOI->dealers,
                            'Category' => $data->LOI->category,
                            'Customer Name' => $data->LOI->client->name ?? '',
                            'Customer Type' => $data->LOI->client->customertype ?? '',
                            'Country' => $data->LOI->client->country->name ?? '',
                            'Matrix Code' => $data->uuid,
                            'Model' => $data->masterModel->model,
                            'SFX' => $data->masterModel->sfx,
                            'Steering' => $data->masterModel->steering,
                            'Model Line' => $data->masterModel->modelLine->model_line,
                            'Quantity' => $data->quantity,
                            'Utilized Quantity' => $data->utilized_quantity,
                            'Remaining Quantity' => $data->quantity - $data->utilized_quantity,
                            'Sales Person' => $data->LOI->salesPerson->name ?? '',
                            'So Numbers' => $soNumbers,
                            'Status' => $data->LOI->status,
                            'Is Expired' => $is_expired,
                            'Approval Remarks' => $data->LOI->review,
                            'LOI Comments' => $data->LOI->comments
                            
                        ];
                    });
                }


        if (request()->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('loi_date', function($query) {
                    return Carbon::parse($query->LOI->date)->format('d M Y') ?? '';
                })
                ->addColumn('loi_approval_date', function($query) {
                    return Carbon::parse($query->LOI->loi_approval_date)->format('d M Y') ?? '';
                })
                ->addColumn('remaining_quantity', function($query) {
                    return $query->quantity - $query->utilized_quantity;
                })
                ->addColumn('so_number', function($query) {
                    $soNumbers = LoiSoNumber::where('letter_of_indent_id', $query->LOI->id)
                            ->pluck('so_number')->toArray();

                   return implode(",", $soNumbers);
                })
                ->addColumn('sales_person_id', function($query) {                    
                    if($query->LOI->sales_person_id){
                        return $query->LOI->salesPerson->name ?? '';
                    }
                 })
                ->addColumn('is_expired', function($query) {
                
                    if($query->LOI->is_expired == true) {
                        $msg = 'Expired';
                        return  '<button class="btn btn-sm btn-secondary">'.$msg.'</button>';
                    }else{
                        $msg = 'Not Expired';
                        return '<button class="btn btn-sm btn-info">'.$msg.'</button>';
                    }                                           
                 })
                 ->addColumn('status', function($query) {
                    if($query->LOI->status == LetterOfIndent::LOI_STATUS_NEW) {
                        return  '<button class="btn btn-sm btn-primary">'.LetterOfIndent::LOI_STATUS_NEW.'</button>';
                    }else if($query->LOI->status == LetterOfIndent::LOI_STATUS_WAITING_FOR_APPROVAL) {
                         return '<button class="btn btn-sm btn-warning">'.LetterOfIndent::LOI_STATUS_WAITING_FOR_APPROVAL.'</button>';
                     }else if($query->LOI->status == LetterOfIndent::LOI_STATUS_SUPPLIER_APPROVED) {
                         return  '<button class="btn btn-sm btn-success">'.LetterOfIndent::LOI_STATUS_SUPPLIER_APPROVED.'</button>';
                     }else if($query->LOI->status == LetterOfIndent::LOI_STATUS_SUPPLIER_REJECTED) {
                         return  '<button class="btn btn-sm btn-danger">'.LetterOfIndent::LOI_STATUS_SUPPLIER_REJECTED.'</button>';
                     }else{
                        return $query->LOI->status;
                     }                                       
                 })
                ->rawColumns(['loi_date','remaining_quantity','is_expired','sales_person_id','so_number','status','loi_approval_date'])
                ->toJson();
            }
        
            return view('letter_of_indents.letter_of_indent_items.index');

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
