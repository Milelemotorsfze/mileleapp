<?php

namespace App\Http\Controllers;

use App\Models\posting_records;
use App\Models\LeadSource;
use Illuminate\Support\Facades\DB;
use App\Models\Varaint;
use Illuminate\Http\Request;
use App\Models\UserActivities;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;

class PostingRecordsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createposting(Request $request, $leadssource_id)
    {
        $variants = Varaint::whereNotExists(function ($query) use ($leadssource_id) {
            $query->select(DB::raw(1))
                  ->from('posting_records')
                  ->join('posting_platforms', 'posting_records.posting_platforms_id', '=', 'posting_platforms.id')
                  ->whereRaw('posting_records.varaints_id = varaints.id')
                  ->where('posting_platforms.lead_source_id', '=', $leadssource_id);
        })
        ->get();
        return view('calls.postingcreate', compact('variants')); 
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
    public function show(posting_records $posting_records)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(posting_records $posting_records)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, posting_records $posting_records)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(posting_records $posting_records)
    {
        //
    }
    public function postingrecords(Request $request, $id)
    {
        $leadssource = LeadSource::find($id);
        $useractivities =  New UserActivities();
        $useractivities->activity = "View Posting Records";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        if ($request->ajax()) {
        $posting = posting_records::select([
                    'posting_platforms.videos',
                    'posting_platforms.reels',
                    'posting_platforms.Pictures',
                    'posting_platforms.ads',
                    'brands.brand_name',
                    'master_model_lines.model_line',
                    'varaints.name',
                    'int_colors.name as interior_colour',
                    'ext_colors.name as exterior_colour',
                    'posting_platforms.stories'
                ])
                ->leftJoin('varaints', 'posting_records.varaints_id', '=', 'varaints.id')
                ->leftJoin('master_model_lines', 'varaints.master_model_lines_id', '=', 'master_model_lines.id')
                ->leftJoin('brands', 'varaints.brands_id', '=', 'brands.id')
                ->leftJoin('posting_platforms', 'posting_records.posting_platforms_id', '=', 'posting_platforms.id')
                ->leftJoin('lead_source', 'posting_platforms.lead_source_id', '=', 'lead_source.id')
                ->leftJoin('color_codes as int_colors', 'posting_records.int_colour', '=', 'int_colors.id')
                ->leftJoin('color_codes as ext_colors', 'posting_records.ext_colour', '=', 'ext_colors.id')
                ->where('lead_source.id', $id);
                return DataTables::of($posting)
                ->toJson();
        }
        return view('calls.postingrecords')->with('leadssource', $leadssource);
    }
    public function storeposting(Request $request, $leadssource_id)
    {
        $variant = $request->input('variant');
        $video = $request->input('video');
        $reels = $request->input('reels');
        $pictures = $request->input('pictures');
        $ads = $request->input('ads');
        $stories = $request->input('stories');
    }
}
