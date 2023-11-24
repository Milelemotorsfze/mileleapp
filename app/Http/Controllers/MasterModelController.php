<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\MasterModel;
use App\Models\Varaint;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;

class MasterModelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Builder $builder)
    {
        $masterModel = MasterModel::orderBy('id','DESC')->get();

        if (request()->ajax()) {
            return DataTables::of($masterModel)
                ->editColumn('created_at', function($query) {
                    return Carbon::parse($query->created_at)->format('d M Y');
                })
                ->editColumn('created_by', function($query) {
                    return $query->CreatedBy->name ?? '';
                })
                ->editColumn('variant_id', function($query) {
                    return $query->variant->name ?? '';
                })
                ->addColumn('action', function(MasterModel $masterModel) {
                    return view('master-models.action',compact('masterModel'));
                })
                ->rawColumns(['action'])
                ->toJson();
        }

        $html = $builder->columns([
            ['data' => 'id', 'name' => 'id','title' => 'S.No'],
            ['data' => 'steering', 'name' => 'steering','title' => 'Steering'],
            ['data' => 'model', 'name' => 'model','title' => 'Model'],
            ['data' => 'sfx', 'name' => 'sfx','title' => 'SFX'],
            ['data' => 'model_year', 'name' => 'model_year','title' => 'Model Year'],
            ['data' => 'variant_id', 'name' => 'variant_id','title' => 'Variant'],
            ['data' => 'transcar_loi_description', 'name' => 'transcar_loi_description','title' => 'Trans Car LOI'],
            ['data' => 'milele_loi_description', 'name' => 'milele_loi_description','title' => 'Milele LOI'],
            ['data' => 'amount_uae', 'name' => 'amount_uae','title' => 'Amount in UAE '],
            ['data' => 'amount_belgium', 'name' => 'amount_belgium','title' => 'Amount in Belgium '],
            ['data' => 'action', 'name' => 'action','title' => 'Action'],

        ]);

        return view('master-models.index', compact('html'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $variants = Varaint::all();
        return view('master-models.create', compact('variants'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
//        return dd($request->all());
        $this->validate($request, [
            'model' => 'required',
            'sfx' => 'required',
            'variant_id' => 'required',
            'model_year' => 'required'
        ]);

        $isAlreadyExist = MasterModel::where('model', $request->model)
                                ->where('sfx', $request->sfx)
                                ->where('steering', $request->steering)
                                ->where('variant_id', $request->variant_id)
                                ->first();
        if($isAlreadyExist) {
            return  redirect()->back()->withErrors('This Model and Sfx is already existing');
        }

        $model = new MasterModel();

        $model->steering = $request->steering;
        $model->model = $request->model;
        $model->sfx = $request->sfx;
        $model->variant_id = $request->variant_id;
        $model->amount_uae = $request->amount_uae;
        $model->amount_belgium = $request->amount_belgium;
        $model->is_milele = $request->is_milele ? true : false;
        $model->is_transcar = $request->is_transcar ? true : false;
        $model->milele_loi_description = $request->milele_loi_description;
        $model->transcar_loi_description = $request->transcar_loi_description;
        $model->created_by = Auth::id();

        $model->save();

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
        $masterModel = MasterModel::find($id);
        $variants = Varaint::all();

        return view('master-models.edit', compact('masterModel','variants'));
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
        ]);

        $isAlreadyExist = MasterModel::where('model', $request->model)
                            ->where('sfx', $request->sfx)
                            ->where('steering', $request->steering)
                            ->where('variant_id', $request->variant_id)
                            ->whereNot('id',$id)->first();
        if($isAlreadyExist) {
            return  redirect()->back()->withErrors('This Model and Sfx is already existing');
        }

        $model = MasterModel::find($id);

        $model->steering = $request->steering;
        $model->model = $request->model;
        $model->sfx = $request->sfx;
        $model->variant_id = $request->variant_id;
        $model->amount_uae = $request->amount_uae;
        $model->amount_belgium = $request->amount_belgium;
        $model->updated_by = Auth::id();
        $model->save();

        return redirect()->route('master-models.index')->with('success','Model Updated Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function getLoiDescription(Request $request) {
        info("success");
        info($request->all());
        $variant = Varaint::find($request->id);
        if($request->is_milele == 1) {
            $data['milele_loi_format'] = $variant->master_model_lines->model_line.' '.$variant->engine.' Litre '.$variant->fuel_type.' '.$variant->steering;
        }
        if($request->is_transcar == 1) {
            $data['transcar_loi_format'] = $variant->steering.', BRAND NEW, '.$variant->brand->brand_name.' '.$variant->master_model_lines->model_line.', '.$variant->fuel_type.' ENGINE'.$variant->engine
                .' - SPECIFICATION ATTACHED IN APPENDIX';
        }
        info($data);
        return response()->json($data);

    }
}
