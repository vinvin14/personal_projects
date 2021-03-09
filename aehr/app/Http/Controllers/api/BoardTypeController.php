<?php
/**
 * Created by PhpStorm.
 * User: DOH-VIN
 * Date: 28/11/2020
 * Time: 11:57 AM
 */

namespace App\Http\Controllers\api;
use App\Http\Controllers\UtilitiesController;
use App\Models\Boardtype;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BoardTypeController
{
    private $util;
    public function __construct()
    {
        $this->util = new UtilitiesController();
    }
    public function index()
    {
        $query = Boardtype::orderBy('boardType', 'ASC')->get();
        if(empty($query->toArray()))
        {
            return response()->json('No Record(s) Found!', 404);
        }
        return response()->json($query, 200);
    }
    public function show($id)
    {
        $query = Boardtype::find($id);
        if(empty($query))
        {
            return response()->json('Board Type not found!', 404);
        }
        return response()->json($query, 200);
    }
    public function update($id, Request $request)
    {
        $query = Boardtype::find($id);
        $util = new UtilitiesController();
        $duplicate = Boardtype::where([
            'Boardtype' => $request->input('Boardtype'),
        ])->first();

        if(! empty($duplicate))
        {
            if($query->BoardType != $request->input('Boardtype'))
            {
                return response()->json('Entry that you want to push through will cause duplication!!', 404);
            }
        }
        if(empty($query))
        {
            return response()->json('Board Type not found!', 404);
        }
        try
        {
            $query->update($request->post());
            return response()->json('Board Type Successfully Updated!', 200);
        }
        catch (\Exception $exception)
        {
            $util->createError([
                $_SESSION['user'],
                'BTUPDATE',
                $exception->getMessage()
            ]);
            return response()->json('Oops something went wrong! Please contact your administrator.', 501);
        }
    }
    public function store(Request $request)
    {
        $input = $request->post();
        $util = new UtilitiesController();
        $duplicate = Boardtype::where([
            'boardType' => $input['boardType'],
        ])->first();
        if(empty($input['description']))
        {
            $input['description'] = 'No Data';
        }
        if(! empty($duplicate))
        {
            return response()->json('Board Type has been added previously!', 404);
        }
        try
        {
            $input['dateAdded'] = date('Y-m-d');
            Boardtype::create($input);
            return response()->json('You have added new Board Type!', 200);
        }
        catch(\Exception $exception)
        {
            $util->createError([
                $_SESSION['user'],
                'BTSTORE',
                $exception->getMessage()
            ]);
            return response()->json('Oops something went wrong! Please contact your administrator.', 501);
        }
    }
    public function get_existing_partnumber(Request $request)
    {
        return DB::table('boardtypes')
                    ->where('part_number', 'LIKE', '%'.$request->input('keyword').'%')
                    ->limit(10)
                    ->get();
    }
    public function destroy($id)
    {
        $query = Boardtype::find($id);
        if(empty($query))
        {
            return response()->json('Board Type not found!', 404);
        }
        $query->delete();
        return response()->json('Successfully deleted!', 200);
    }
}
