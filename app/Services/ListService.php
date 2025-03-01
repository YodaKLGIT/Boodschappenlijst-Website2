<?php

namespace App\Services; // Ensure this is the correct namespace

use App\Services\Contracts\ListServiceInterface;
use App\Models\ListItem; 
use App\Models\Brand; 
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ListService implements ListServiceInterface
{
    /**
     * Filter and sort ListItems based on the request parameters.
     *
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Collection
     */
    
     public function filter(Request $request, $query)
     {
         // Retrieve filter inputs
         $sort = $request->input('sort', 'title');  // Default sort by title
         $search = $request->input('search');        // Get the search term
         $brandId = $request->input('brand');        // Get the selected brand ID
         $categoryId = $request->input('category');  // Get the selected category ID
     
         // Add search functionality if a search term is provided
         if ($search) {
             $query->where('name', 'like', '%' . $search . '%'); // Adjust the field name as necessary
         }
     
         // Filter by brand if a brand ID is selected
         if ($brandId) {
             $query->whereHas('products', function ($q) use ($brandId) {
                 $q->where('brand_id', $brandId);
             });
         }
     
         // Filter by category if a category ID is selected
         if ($categoryId) {
             $query->whereHas('products', function ($q) use ($categoryId) {
                 $q->where('category_id', $categoryId);
             });
         }
     
         // Apply sorting based on the selected criteria
         switch ($sort) {
             case 'last_added':
                 $query->orderBy('created_at', 'desc');
                 break;
             case 'last_updated':
                 $query->orderBy('updated_at', 'desc');
                 break;
             case 'brand':
                 $query->orderBy('brand_id'); // Adjust if needed based on your database structure
                 break;
             case 'category':
                 $query->orderBy('category_id'); // Adjust if needed based on your database structure
                 break;
             case 'product_count': // Sorting by product count
                 // Add withCount to calculate the sum of quantities from the pivot table
                 $query->withCount(['products as total_quantity' => function ($q) {
                     $q->select(DB::raw("sum(quantity)"));
                 }])->orderBy('total_quantity', 'desc');
                 break;
             default:
                 $query->orderBy('name', 'desc');
         }
     
         // Return the modified query to be executed
         return $query;
     }
     

     

    public function removeProductFromList(ListItem $list, Product $product)
    {
        // Detach the specified product
        $list->products()->detach($product);
    
        // Optionally return a success message or boolean
        return true;
    }

    // WORK IN PROGRESS 
    public function updateName(Request $request, ListItem $list)
{
    // Validate the incoming request
    $request->validate([
        'name' => 'required|string|max:255', // Validate the name
    ]);

    // Update the name of the list item
    $list->name = $request->name;

    // If a theme_id is provided, update it; otherwise, retain the existing value
    if ($request->has('theme_id')) {
        $list->theme_id = $request->theme_id;
    }

    // Attempt to save the updated ListItem
    if ($list->save()) {
        Log::info('ListItem updated successfully', [
            'id' => $list->id,
            'name' => $list->name,
        ]);
        return true;  // Return true if saved successfully
    } else {
        Log::error('Failed to update ListItem', ['id' => $list->id]);
        return false;  // Return false if failed to save
    }
}


    
public function toggleFavorite(Request $request, ListItem $list)
{
// Validate the incoming request
$request->validate([
    'is_favorite' => 'required|boolean',
]);

// Update the is_favorite status
$list->is_favorite = $request->is_favorite;

// Save the ListItem
if ($list->save()) {
    // Log the action (optional)
    Log::info('ListItem favorite status updated', [
        'id' => $list->id,
        'is_favorite' => $list->is_favorite,
    ]);
}
}


    public function getFavoriteLists()
    {
       $lists = ListItem::where('is_favorite', true)->get();

       return $lists; 
    }

    public function markProductAsSeen(ListItem $list, Product $product)
    {
       // Check if the product is associated with the list
       $list->products()->where('product_id', $product->id)->firstOrFail()->pivot;

       // Update the is_new field to false
       $list->products()->updateExistingPivot($product->id, ['is_new' => false]);

       return $list;
    }


    /**
     * Get all brands for filtering.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllBrands()
    {
        return Brand::all();
    }

    /**
     * Get all categories for filtering.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllCategories()
    {
        return Category::all();
    }

}



