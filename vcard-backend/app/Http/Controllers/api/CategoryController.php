<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use App\Models\VCard;
use Facade\FlareClient\Http\Response;
use Illuminate\Http\Request;

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
        return new CategoryResource($vcard->categories);
    }

    public function getCategoryByTransaction(Request $request, Transaction $transaction)
    {
        return new CategoryResource($transaction->category);
    }

    public function postCategory(Request $request)
    {
        $category = new Category();
        $category->vcard = VCard::findOrFail($request->vcard)->phone_number;
        try {
            if ($request->type != 'C' && $request->type != 'D') {
                return response()->json(array(
                    'code'      =>  400,
                    'message'   =>  "Type of category should be 'C' (Credit) or 'D' (Debit)."
                    ), 400);
            }
            $category->type = $request->type;
            $category->name = strtolower($request->name);
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
