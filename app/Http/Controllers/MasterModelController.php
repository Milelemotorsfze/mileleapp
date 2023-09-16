<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\MasterModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
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
        return view('master-models.create');
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
