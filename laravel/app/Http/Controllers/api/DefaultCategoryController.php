<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DefaultCategoryPatch;
use App\Http\Requests\DefaultCategoryPost;
use App\Http\Resources\DefaultCategoryResource;
use App\Models\DefaultCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DefaultCategoryController extends Controller
{
    public function getDefaultCategory(DefaultCategory $defaultcategory)
    {
        return new DefaultCategoryResource($defaultcategory);
    }

    public function getDefaultCategories(Request $request)
    {
        $defaultcategories = DB::table('default_categories');
        if ($request->has("type")) {
            $defaultcategories = $defaultcategories->where("type", $request->type);
        }
        if ($request->has("name")) {
            $defaultcategories = $defaultcategories->where("name",'LIKE', "%".$request->name."%");
        }
        if ($request->has("page")) {
            $defaultcategories = $defaultcategories->orderBy('created_at', 'desc')->paginate(15);
        }else {
            $defaultcategories = $defaultcategories->orderBy('created_at', 'desc')->get();
        }
        return DefaultCategoryResource::collection($defaultcategories);
    }

    public function postDefaultCategory(DefaultCategoryPost $request)
    {
        $defaultcategory = new DefaultCategory();
        $validator = $request->validated();
        try {
            $defaultcategory->type = $validator["type"];
            $defaultcategory->name = strtolower($validator["name"]);
            $defaultcategory->save();

            return new DefaultCategoryResource($defaultcategory);
        } catch (\Throwable $th) {
            //DefaultCategory already exist error (1062)
            if ($th->errorInfo[1] == 1062) {
                $defaultcategory = DefaultCategory::query()
                                    ->where('type', '=', $defaultcategory->type)
                                    ->where('name', '=', strtolower($defaultcategory->name))->withTrashed()->get()->first();
                if ($defaultcategory->trashed()) {
                    try {
                        $defaultcategory->restore();
                        return new DefaultCategoryResource($defaultcategory);
                    } catch (\Throwable $th_restore) {
                        return response()->json(array(
                            'code'      =>  400,
                            'message'   =>  $th_restore->getMessage()
                            ), 400);
                    }
                }else
                    return response()->json(array(
                        'code'      =>  400,
                        'message'   =>  "This defaultcategory already exists."
                        ), 400);
            }
            return response()->json(array(
                    'code'      =>  400,
                    'message'   =>  $th->getMessage()
                    ), 400);
        }
    }

    public function putDefaultCategory(Request $request, DefaultCategory $defaultcategory)
    {
        try {
            if ($request->type != 'C' && $request->type != 'D') {
                return "Type of defaultcategory should be 'C' (Credit) or 'D' (Debit)";
            }
            $defaultcategory->type = $request->type;
            $defaultcategory->name = $request->name;
            $defaultcategory->save();
            return new DefaultCategoryResource($defaultcategory);
        } catch (\Throwable $th) {
            return response()->json(array(
                'code'      =>  400,
                'message'   =>  $th->getMessage()
            ), 400);
        }
    }

    public function patchDefaultCategory(DefaultCategoryPatch $request, DefaultCategory $defaultcategory)
    {
        try {

            if ($request->has("type")) {
                $defaultcategory->type = $request->type;
            }
            if ($request->has("name")) {
                $defaultcategory->name = $request->name;
            }
            $defaultcategory->save();
            return new DefaultCategoryResource($defaultcategory);
        } catch (\Throwable $th) {
            //DefaultCategory already exist error (1062)
            if ($th->errorInfo[1] == 1062) {
                $defaultcategory = DefaultCategory::query()
                                    ->where('type', '=', $defaultcategory->type)
                                    ->where('name', '=', strtolower($defaultcategory->name))->withTrashed()->get()->first();
                if ($defaultcategory->trashed()) {
                    try {
                        $defaultcategory->restore();
                        return new DefaultCategoryResource($defaultcategory);
                    } catch (\Throwable $th_restore) {
                        return response()->json(array(
                            'code'      =>  400,
                            'message'   =>  $th_restore->getMessage()
                            ), 400);
                    }
                }else
                    return response()->json(array(
                        'code'      =>  400,
                        'message'   =>  "This defaultcategory already exists."
                        ), 400);
            }
            return response()->json(array(
                    'code'      =>  400,
                    'message'   =>  $th->getMessage()
                    ), 400);
        }
    }

    public function deleteDefaultCategory(Request $request, DefaultCategory $defaultcategory)
    {
        $oldName = $defaultcategory->nome;
        $oldDefaultCategoryID = $defaultcategory->id;
        try {
            $defaultcategory->delete();
            return response()->json(array(
                    'code'      =>  200,
                    'message'   =>  "Default Category ".$oldName." was deleted."
                ), 200);
        } catch (\Throwable $th) {
            return response()->json(array(
                        'code'      =>  400,
                        'message'   =>  "Default Category ".$oldName." was NOT deleted"
                    ), 400);
        }
    }

    public function forceDeleteDefaultCategory(Request $request, $defaultcategory)
    {
        $categoria = DefaultCategory::withTrashed()->findOrFail($defaultcategory);
        $this->authorize('forceDelete', $categoria);
        $nome = $categoria->nome;
        try {
            $categoria->forceDelete();
            return response()->json(array(
                'code'      =>  200,
                'message'   =>  'Default category "' . $nome . '" was deleted for ever.'
            ), 200);
        } catch (\Throwable $th) {
            return response()->json(array(
                'code'      =>  400,
                'message'   =>  'Was not possible to force delete '. $nome .' default category.'
            ), 400);
        }
    }

    public function restore($id)
    {
        $defaultcategory = DefaultCategory::withTrashed()->findOrFail($id);
        $this->authorize('restore', $defaultcategory);
        try {
            $defaultcategory->restore();
        } catch (\Throwable $th) {
            return response()->json(array(
                'code'      =>  200,
                'message'   =>  $th->getMessage()
            ), 200);
        }

        return new DefaultCategoryResource($defaultcategory);
    }
}
