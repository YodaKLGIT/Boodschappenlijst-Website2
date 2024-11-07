<x-app-layout>
    <!-- Product List Title Section -->
    <div class="bg-gray-100 py-4">
        <div class="container mx-auto px-4">
            <div class="flex flex-col items-center">
                <h1 class="text-2xl font-bold text-gray-800 mb-2">Product List</h1>
                <img src="{{ asset('images/list.png') }}" alt="Product List" class="w-16 h-16 object-contain">
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8 mb-16">
        <!-- Use flex to center content horizontally -->
        <div class="flex justify-center w-full">
            <!-- Set max-width and allow it to center automatically -->
            <div class="w-full max-w-6xl mx-auto">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="w-full">
                        <div class="shopping-list-wrapper">
                            <article class="rounded-xl bg-white shadow-md overflow-hidden h-auto">
                                <div class="p-3 flex flex-col" 
                                     style="background-color: {{ $productlist->theme->strap_color }};"
                                     onclick="toggleProducts(event, '{{ $productlist->id }}')">
                                    <div class="flex items-center justify-between space-x-4">
                                        <!-- Favorite Star -->
                                        <form action="{{ route('lists.toggleFavorite', $productlist->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <input type="hidden" name="is_favorite" value="{{ $productlist->is_favorite ? 0 : 1 }}">
                                            <button type="submit" class="flex items-center focus:outline-none" onclick="event.stopPropagation();">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="{{ $productlist->is_favorite ? 'gold' : 'lightgray' }}" 
                                                     viewBox="0 0 24 24" class="w-4 h-4 mr-1">
                                                    <path d="M12 .587l3.668 7.568 8.332 1.207-6 5.848 1.416 8.25L12 18.896l-7.416 3.908L6 14.162l-6-5.848 8.332-1.207z"/>
                                                </svg>
                                            </button>
                                        </form>

                                        <!-- Name Editing Form -->
                                        <form action="{{ route('lists.updateName', $productlist->id) }}" method="POST" class="mt-2 flex-grow relative flex justify-center items-center">
                                            @csrf
                                            <input type="text" name="name" value="{{ $productlist->name }}" 
                                                   class="bg-transparent text-white focus:outline-none p-3 w-auto max-w-max border-2 border-transparent focus:border-blue-500 rounded-full text-center peer placeholder-black"
                                                   placeholder="Enter list name"
                                                   required 
                                                   onkeydown="if(event.key === 'Enter'){ this.form.submit(); }">
                                        </form>

                                        <!-- Product Count -->
                                        <span class="text-white text-sm font-bold px-3 py-1 rounded-full" 
                                              style="background-color: {{ $productlist->theme->count_circle_color }};">
                                            {{ $productlist->products->count() }}
                                        </span>
                                    </div>
                                    <span id="date-{{ $productlist->id }}" class="text-xs text-white mt-1 transition-opacity duration-300">
                                        {{ $productlist->updated_at->format('M d, Y') }}
                                    </span>
                                </div>
                                @if ($productlist->products->isNotEmpty()) <!-- Check if there are products -->
                                <div id="products-{{ $productlist->id }}" class="products overflow-hidden transition-all duration-300 ease-in-out flex-grow" 
                                     style="background-color: {{ $productlist->theme->content_bg_color }};">
                                    <div class="p-4 space-y-4">
                                        @foreach ($productlist->products->groupBy('category.name') as $category => $products)
                                            <div class="category-section pb-4 border-b border-gray-200 last:border-b-0 last:pb-0">
                                                <h4 class="font-medium text-gray-700 mb-2">{{ $category }}</h4>
                                                <ul class="space-y-2">
                                                    @foreach ($products as $product)
                                                        <li class="flex justify-between items-center text-sm p-2 rounded transition-colors duration-200"
                                                            style="background-color: {{ $productlist->theme->body_color }};">
                                                            <a href="{{ route('products.index', $product->id) }}" 
                                                               class="text-gray-600 truncate flex-1 hover:text-{{$productlist->theme->hover_color}} duration-200">
                                                                {{ $product->brand->name }} {{ $product->name }}
                                                            </a>
                                                            <span class="text-gray-500 ml-2 bg-white px-2 py-1 rounded-full text-xs">{{ $product->pivot->quantity }}</span>
                                                            <form action="{{ route('lists.products.remove', [$productlist->id, $product->id]) }}" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="text-red-600 hover:text-red-800 transition-colors duration-300">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 inline">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                                    </svg>
                                                                </button>
                                                            </form>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif <!-- End of product check -->
                            </article>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>











    



