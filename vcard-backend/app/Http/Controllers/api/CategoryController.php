<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryPatch;
use App\Http\Requests\CategoryPost;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use App\Models\VCard;
use Facade\FlareClient\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /*public function getCategoriesOfUser(Request $request, VCard $vcard)
    {
        //CategoryResource::$format = 'detailed';
        //CategoryResource::$format = $request->query('format');
        return CategoryResource::collection($vcard->categories);
        // Previous version returns models
        //return $user->categorys;
    }*/

    public function getCategory(Category $category)
    {
        return new CategoryResource($category);
    }

    public function getCategories(Request $request)
    {
        return new CategoryResource(Category::all());
    }

    public function getCategoriesByVcard(Request $request, VCard $vcard)
    {
        $categories = $vcard->categories();
        if ($request->has("type")) {
            $categories = $categories->where("type", $request->type);
        }
        if ($request->has("page")) {
            $categories = $categories->orderBy('created_at', 'desc')->paginate(15);
        }else {
            $categories = $categories->orderBy('created_at', 'desc')->get();
        }
        return CategoryResource::collection($categories);
    }

    public function getCategoryByTransaction(Request $request, Transaction $transaction)
    {
        return new CategoryResource($transaction->category);
    }

    public function postCategory(CategoryPost $request)
    {
        $category = new Category();
        $category->vcard = Auth::user()->vcard_ref->phone_number;
        $validator = $request->validated();
        try {
            $category->type = $validator["type"];
            $category->name = strtolower($validator["name"]);
            $category->save();

            return new CategoryResource($category);
        } catch (\Throwable $th) {
            //Category already exist error (1062)
            if ($th->errorInfo[1] == 1062) {
                $category = Category::query()
                                    ->where('vcard', '=', $category->vcard)
                                    ->where('type', '=', $category->type)
                                    ->where('name', '=', strtolower($category->name))->withTrashed()->get()->first();
                if ($category->trashed()) {
                    try {
                        $category->restore();
                        return new CategoryResource($category);
                    } catch (\Throwable $th_restore) {
                        return response()->json(array(
                            'code'      =>  400,
                            'message'   =>  $th_restore->getMessage()
                            ), 400);
                    }
                }else
                    return response()->json(array(
                        'code'      =>  400,
                        'message'   =>  "This category already exists."
                        ), 400);
            }
            return response()->json(array(
                    'code'      =>  400,
                    'message'   =>  $th->getMessage()
                    ), 400);
        }
    }

    public function putCategory(Request $request, Category $category)
    {
        try {
            if ($category->transactions->count() && $request->type != $category->type) {
                return "You can't change a category type that already have transactions.";
            }
            if ($request->type != 'C' && $request->type != 'D') {
                return "Type of category should be 'C' (Credit) or 'D' (Debit)";
            }
            $category->type = $request->type;
            $category->name = $request->name;
            $category->save();
            return new CategoryResource($category);
        } catch (\Throwable $th) {
            return response()->json(array(
                'code'      =>  400,
                'message'   =>  $th->getMessage()
            ), 400);
        }
    }

    public function patchCategory(CategoryPatch $request, Category $category)
    {
        try {
            if ($category->transactions->count() && $request->type != $category->type) {
                return "You can't change a category type that already have transactions.";
            }
            if ($request->type != 'C' && $request->type != 'D') {
                return "Type of category should be 'C' (Credit) or 'D' (Debit)";
            }
            $category->type = $request->type;
            $category->name = $request->name;
            $category->save();
            return new CategoryResource($category);
        } catch (\Throwable $th) {
            return response()->json(array(
                'code'      =>  400,
                'message'   =>  $th->getMessage()
            ), 400);
        }
    }

    public function deleteCategory(Request $request, Category $category)
    {
        $oldName = $category->nome;
        $oldCategoryID = $category->id;
        try {
            if ($category->transactions->count()) {
                $category->delete();
            }else {
                $category->forceDelete();
            }

            Category::destroy($oldCategoryID);

            return "Category ".$oldName." was deleted.";
        } catch (\Throwable $th) {
            return response()->json(array(
                        'code'      =>  400,
                        'message'   =>  "Category ".$oldName." was NOT deleted"
                    ), 400);
        }

    }
}
