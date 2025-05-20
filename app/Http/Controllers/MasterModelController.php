<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\MasterModel;
use App\Models\Varaint;
use App\Models\VariantItems;
use App\Models\PfiItemPurchaseOrder;
use App\Models\PfiItem;
use Carbon\Carbon;
use App\Models\LetterOfIndentItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;
use Rap2hpoutre\FastExcel\FastExcel;

class MasterModelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Builder $builder, Request $request)
    {
        (new UserActivityController)->createActivity('Open the listing page of Master Models.');

        $masterModel = MasterModel::orderBy('id','DESC')->with(['variant'  => function ($query) {
                $query->select('id','name','master_model_lines_id');
            }]);

        if($request->export == 'EXCEL') {
            $data = $masterModel->get();
            return (new FastExcel($data))->download('models.csv', function ($data) {
                return [
                    'Steering' => $data->steering,
                    'Model' => $data->model,
                    'SFX' => $data->sfx,
                    'Model Year' => $data->model_year,
                    'Model Line' => $data->modelLine->model_line,
                    'Trans Car LOI Description' => $data->transcar_loi_description,
                    'Milele LOI Description' => $data->milele_loi_description,
                    'Amount(UAE)' => $data->amount_uae,
                    'Amount(Belgium)' => $data->amount_belgium,
                    'Variant' => $data->variant->name ?? '',
                    'Created At' => Carbon::parse($data->created_at)->format('d-m-Y'),
                    'Created By' => $data->createdBy->name ?? '',
                ];
            });
        }

        if (request()->ajax()) {
            return DataTables::of($masterModel)
                ->addIndexColumn()
                ->editColumn('created_at', function($query) {
                    return Carbon::parse($query->created_at)->format('d M Y');
                })
                ->editColumn('created_by', function($query) {
                    return $query->createdBy->name ?? '';
                })
                ->editColumn('updated_by', function($query) {
                    return $query->updatedBy->name ?? '';
                })
                ->editColumn('updated_at', function($query) {
                   return Carbon::parse($query->updated_at)->format('d M Y');
                })
                ->editColumn('master_model_line_id', function($query) {
                    return $query->variant->master_model_lines->model_line ?? '';
                 })
                ->editColumn('variant_id', function($query) {
                    return $query->variant->name ?? '';
                })
                ->addColumn('action', function(MasterModel $masterModel) {
                    return view('master-models.action',compact('masterModel'));
                })
                ->editColumn('amount_uae', function($query) {
                    return number_format($query->amount_uae);
                })
                ->editColumn('amount_belgium', function($query) {
                    return  number_format($query->amount_belgium);
                })
                ->rawColumns(['action','variant_id'])
                 
                ->toJson();
        }

        $html = $builder->columns([
            ['data' => 'action', 'name' => 'action','title' => 'Action'],
            ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex','title' => 'S.No', 'orderable' =>  false, 'searchable' => false],
            ['data' => 'steering', 'name' => 'steering','title' => 'Steering'],
            ['data' => 'model', 'name' => 'model','title' => 'Model'],
            ['data' => 'sfx', 'name' => 'sfx','title' => 'SFX'],
            ['data' => 'pfi_model', 'name' => 'pfi_model','title' => 'New Model'],
            ['data' => 'pfi_sfx', 'name' => 'pfi_sfx','title' => 'New SFX'],
            ['data' => 'model_year', 'name' => 'model_year','title' => 'Model Year'],
            ['data' => 'model_description', 'name' => 'model_description','title' => 'Model Description'],
            ['data' => 'variant_id', 'name' => 'variant.name','title' => 'Variant'],
            ['data' => 'master_model_line_id', 'name' => 'master_model_line_id','title' => 'Model Line'],
            ['data' => 'transcar_loi_description', 'name' => 'transcar_loi_description','title' => 'Trans Car LOI Description'],
            ['data' => 'milele_loi_description', 'name' => 'milele_loi_description','title' => 'PFI Milele LOI Description'],
            ['data' => 'amount_uae', 'name' => 'amount_uae','title' => 'Amount in UAE '],
            ['data' => 'amount_belgium', 'name' => 'amount_belgium','title' => 'Amount in Belgium '],
            ['data' => 'created_at', 'name' => 'created_at','title' => 'Created At'],
            ['data' => 'created_by', 'name' => 'created_by','title' => 'Created By'],
            ['data' => 'updated_at', 'name' => 'updated_at','title' => 'Updated At'],
            ['data' => 'updated_by', 'name' => 'updated_by','title' => 'Updated By'],
           
        ]);

        return view('master-models.index', compact('html'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        (new UserActivityController)->createActivity('Opened the create page of Master Models.');
        $variants = Varaint::all();
        return view('master-models.create', compact('variants'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'model' => 'required',
            'sfx' => 'required',
            'variant_id' => 'required',
            'model_year' => 'required'
        ]);

        $isAlreadyExist = MasterModel::where('model', $request->model)
                                ->where('sfx', $request->sfx)
                                ->where('steering', $request->steering)
                                ->where('model_year', $request->model_year)
                                ->first();
        if($isAlreadyExist) {
            return  redirect()->back()->withErrors('This Model and Sfx and Model Year combination is already existing.');
        }

        $model = new MasterModel();
        $variant = Varaint::find($request->variant_id);

        $model->steering = $request->steering;
        $model->model = $request->model;
        $model->sfx = $request->sfx;
        $model->pfi_model = $request->pfi_model;
        $model->pfi_sfx = $request->pfi_sfx;
        $model->variant_id = $request->variant_id;
        $model->master_model_line_id = $variant->master_model_lines->id ?? '';
        $model->amount_uae = $request->amount_uae ? $request->amount_uae : 0;
        $model->amount_belgium = $request->amount_belgium ? $request->amount_belgium : 0;
        $model->model_year = $request->model_year;
        $model->is_milele = $request->is_milele ? true : false;
        $model->is_transcar = $request->is_transcar ? true : false;
        $model->milele_loi_description = $request->milele_loi_description;
        $model->transcar_loi_description = $request->transcar_loi_description;
        $model->model_description = $request->model_description;
        $model->created_by = Auth::id();

        $model->save();
        (new UserActivityController)->createActivity('Created new Master Model.');

        return redirect()->route('master-models.index')->with('success','Model Created Successfully.');

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
        (new UserActivityController)->createActivity('Open the edit page of Master Model.');

        $masterModel = MasterModel::find($id);
        $variants = Varaint::all();
        $ismasterModelExistLOI = LetterOfIndentItem::select('master_model_id')->where('master_model_id',$masterModel->id)->first();
        $ismasterModelExistPFI = PfiItem::select('master_model_id')->where('master_model_id',$masterModel->id)->first();
        $ismasterModelExistPO = PfiItemPurchaseOrder::select('master_model_id')->where('master_model_id',$masterModel->id)->first();
        $disableEdit = 0;
        $disableVariantEdit = 0;
        if($ismasterModelExistLOI || $ismasterModelExistPFI || $ismasterModelExistPO) {
            $disableEdit = 1;
            if($ismasterModelExistPO || $ismasterModelExistPFI) {
                $disableVariantEdit = 1;
            }
            
        }
        // return $disableEdit;
        return view('master-models.edit', compact('masterModel','variants','disableEdit','disableVariantEdit'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'model' => 'required',
            'sfx' => 'required',
            'variant_id' => 'required',
            'model_year' => 'required'
        ]);

        $isAlreadyExist = MasterModel::where('model', $request->model)
                            ->where('sfx', $request->sfx)
                            ->where('steering', $request->steering)
                            ->where('model_year', $request->model_year)
                            ->whereNot('id', $id)->first();
        if($isAlreadyExist) {
            return  redirect()->back()->withErrors('This Model and Sfx and Model Year combination is already existing.');
        }

        $model = MasterModel::find($id);
        $variant = Varaint::find($request->variant_id);

        $model->steering = $request->steering;
        $model->model = $request->model;
        $model->sfx = $request->sfx;
        $model->pfi_model = $request->pfi_model;
        $model->pfi_sfx = $request->pfi_sfx;
        $model->variant_id = $request->variant_id;
        $model->master_model_line_id = $variant->master_model_lines->id ?? '';
        $model->amount_uae = $request->amount_uae ? $request->amount_uae : 0;
        $model->amount_belgium = $request->amount_belgium ? $request->amount_belgium : 0;
        $model->model_year = $request->model_year;
        $model->is_milele = $request->is_milele ? true : false;
        $model->is_transcar = $request->is_transcar ? true : false;
        $model->milele_loi_description = $request->milele_loi_description;
        $model->transcar_loi_description = $request->transcar_loi_description;
        $model->model_description = $request->model_description;
        $model->updated_by = Auth::id();
        $model->save();
        (new UserActivityController)->createActivity('Upadated new Master Model.');

        return redirect()->route('master-models.index')->with('success','Model Updated Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        (new UserActivityController)->createActivity('Deleted Master Model.');

        $masterModel = MasterModel::find($id);
        $masterModel->deleted_by = Auth::id();
        $masterModel->delete();
        $masterModel->save();

        return response(true);
    }
    public function getLoiDescription(Request $request) {

        $data['transcar_loi_format'] = "";
        $data['milele_loi_format'] = "";
        $variant = Varaint::with('brand','master_model_lines')->find($request->id);
        if($request->is_transcar == 1) {
            $data['transcar_loi_format'] = $variant->master_model_lines->model_line.' '.$variant->engine.' Litre '.$variant->fuel_type.' '.$variant->steering;
        }
        if($request->is_milele == 1) {
            $data['milele_loi_format'] = $variant->steering.', BRAND NEW, '.$variant->brand->brand_name.' '.$variant->master_model_lines->model_line.', '.$variant->fuel_type.' ENGINE '.$variant->engine
                .'L - SPECIFICATION ATTACHED IN APPENDIX';
        }
        $variantItems = VariantItems::with('model_specification','model_specification_option')
            ->where('varaint_id', $request->id)->get();

        $data['variant'] = $variant;
        $data['variant_items'] = $variantItems;

        return response()->json($data);
    }
}
