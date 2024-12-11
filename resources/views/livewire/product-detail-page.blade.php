 <div class="w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto">
        <section class="py-3 overflow-hidden bg-white font-poppins dark:bg-gray-800">
            <div class="max-w-6xl px-4 py-4 mx-auto lg:py-8 md:px-6">


                <div class="flex flex-wrap -mx-4 ">

                    <div class="sticky w-full mb-8 md:w-1/2 md:mb-0" x-data="{ mainImage: '{{ url('storage', $product->image) }}' }">


                        <div class="sticky top-0 z-50 overflow-hidden ">
                            <div class="relative mb-6 lg:mb-10 lg:h-2/4 ">
                                <img x-bind:src="mainImage" alt="" class="object-cover w-full lg:h-full ">
                            </div>
                            <div class="flex-wrap hidden md:flex ">


                                {{-- @foreach ($product->images as $image) --}}

                                <div class="w-1/2 p-2 sm:w-1/4"
                                    x-on:click="mainImage='{{ url('storage', $product->image) }}'">

                                    <img src="{{ url('storage', $product->image) }}" alt="{{ $product->name }}"
                                        class="object-cover w-full cursor-pointer lg:h-20 hover:border hover:border-blue-500">

                                </div>

                                {{-- @endforeach --}}



                            </div>
                            <div class="px-6 pb-6 mt-6 border-t border-gray-300 dark:border-gray-400 ">
                                <div class="flex flex-wrap items-center mt-2">
                                    {{-- <span class="mr-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                         fill="currentColor"
                                         class="w-4 h-4 text-gray-700 dark:text-gray-400 bi bi-truck"
                                         viewBox="0 0 16 16">
                                          <path
                                             d="M0 3.5A1.5 1.5 0 0 1 1.5 2h9A1.5 1.5 0 0 1 12 3.5V5h1.02a1.5 1.5 0 0 1 1.17.563l1.481 1.85a1.5 1.5 0 0 1 .329.938V10.5a1.5 1.5 0 0 1-1.5 1.5H14a2 2 0 1 1-4 0H5a2 2 0 1 1-3.998-.085A1.5 1.5 0 0 1 0 10.5v-7zm1.294 7.456A1.999 1.999 0 0 1 4.732 11h5.536a2.01 2.01 0 0 1 .732-.732V3.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5v7a.5.5 0 0 0 .294.456zM12 10a2 2 0 0 1 1.732 1h.768a.5.5 0 0 0 .5-.5V8.35a.5.5 0 0 0-.11-.312l-1.48-1.85A.5.5 0 0 0 13.02 6H12v4zm-9 1a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm9 0a1 1 0 1 0 0 2 1 1 0 0 0 0-2z">
                                         </path>
                                     </svg>
                                </span> --}}
                                    <div class=" col-md-12">
                                        <h2 class="text-lg font-bold text-gray-700 dark:text-gray-400">
                                            Description</h2>
                                        <p class="mt-2">
                                            {!! $product->description !!}
                                        </p>
                                       



                                        {{-- <livewire:rating :product="$product" /> --}}



                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="w-full px-4 md:w-1/2 ">
                        <div class="lg:pl-20">
                            <div class="mb-8 [&>ul]:list-disc [&>ul]:ml-4 ">
                                <h2 class="max-w-xl mb-6 text-2xl font-bold dark:text-gray-400 md:text-4xl">
                                    {{ $product->name }}
                                    {{-- <label style="font-size:16px; color:red;"
                                    class="float-end badge bg-danger trending_tag ">{{ $product->trending == '1' ? 'Trending' : '' }}Trending</label> --}}

                                </h2>


                                <p class="inline-block mb-6 text-4xl font-bold text-gray-700 dark:text-gray-400 ">

                                    {{-- {{$product->price,'Npr'}} --}}
                                    <span>{{ Number::currency($product->price, 'NPR') }}</span>
                                    {{-- <span
                                     class="text-base font-normal text-gray-500 line-through dark:text-gray-400">$1800.99</span> --}}


                                    <hr class="mb-3">

                                </p>

                                <p class="max-w-md mb-4 text-gray-700 text-l dark:text-gray-400">
                                    <label for=""
                                        class="w-full pb-1 text-xl font-semibold text-gray-700 border-b border-blue-300 dark:border-gray-600 dark:text-gray-400">Description</label>
                                    {!! Str::markdown($product->description) !!}
                                </p>

                            </div>
                            @if ($product->quantity > 0)
                                <button
                                    class="p-2 mb-5 text-white bg-green-500 rounded-md dark:text-gray-200 hover:bg-blue-600">
                                    In Stock
                                </button>
                            @else
                                <button
                                    class="p-2 mb-5 text-white bg-red-500 rounded-md dark:text-gray-200 hover:bg-black">
                                    Out of Stock
                                </button>
                            @endif

                            <div class="w-32 mb-8 ">

                                <label for=""
                                    class="w-full pb-1 text-xl font-semibold text-gray-700 border-b border-blue-300 dark:border-gray-600 dark:text-gray-400 ">Quantity</label>
                                <div class="relative flex flex-row w-full h-10 mt-6 bg-transparent rounded-lg">
                                    <button wire:click='decreaseQty'
                                        class="w-20 h-full text-gray-600 bg-gray-300 rounded-l outline-none cursor-pointer dark:hover:bg-gray-700 dark:text-gray-400 hover:text-gray-700 dark:bg-gray-900 hover:bg-gray-400">
                                        <span class="m-auto text-2xl font-thin">-</span>
                                    </button>
                                    <input type="number" wire:model='quantity' readonly
                                        class="flex items-center w-full font-semibold text-center text-gray-700 placeholder-gray-700 bg-gray-300 outline-none dark:text-gray-400 dark:placeholder-gray-400 dark:bg-gray-900 focus:outline-none text-md hover:text-black"
                                        placeholder="1">
                                    <button wire:click='increaseQty'
                                        class="w-20 h-full text-gray-600 bg-gray-300 rounded-r outline-none cursor-pointer dark:hover:bg-gray-700 dark:text-gray-400 dark:bg-gray-900 hover:text-gray-700 hover:bg-gray-400">
                                        <span class="m-auto text-2xl font-thin">+</span>
                                    </button>
                                </div>
                            </div>
                            <div class="flex flex-wrap items-center gap-4">
                                @if ($product->quantity > 0)
                                    <button wire:click='addToCart({{ $product->id }})'
                                        class="w-full p-4 bg-blue-500 rounded-md lg:w-2/5 dark:text-gray-200 text-gray-50 hover:bg-blue-600 dark:bg-blue-500 dark:hover:bg-blue-700">
                                        <span wire:loading.remove wire:target='addToCart({{ $product->id }}'>Add to
                                            cart</span>
                                        <span wire:loading wire:target='addToCart({{ $product->id }}'>Adding...</span>
                                    </button>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </section>


        <div class="py-4 overflow-hidden bg-gray-100 font-poppins">
            <div class="max-w-6xl px-4 py-3 mx-auto lg:py-8 md:px-6">
                <div class="relative flex flex-col items-center">
                    <h1 class="text-5xl font-bold "> Related
                        {{ $product->category->name }}
                        <span class="text-blue-500"> Products
                        </span>
                    </h1>
                    <div class="flex w-40 mt-2 mb-6 overflow-hidden rounded">
                        <div class="flex-1 h-2 bg-blue-200">
                        </div>
                        <div class="flex-1 h-2 bg-blue-400">
                        </div>
                        <div class="flex-1 h-2 bg-blue-600">
                        </div>
                    </div>

                </div>


                <div class="flex flex-wrap items-center ">

                    @foreach ($product->category->products()->take(4)->get() as $relatedProducts)
                        @if ($product->id != $relatedProducts->id)
                            <div class="w-full px-3 mb-6 sm:w-1/2 md:w-1/3" wire:key="{{ $relatedProducts->id }}">
                                <div class="border border-white dark:border-gray-700">
                                    <div class="relative bg-gray-200">

                                        <a href="/products/{{ $relatedProducts->slug }}" class="">

                                            <img src="{{ url('storage', $relatedProducts->image) }}"
                                                alt="{{ $relatedProducts->name }}"
                                                class="object-cover w-full mx-auto h-70 ">


                                        </a>
                                    </div>
                                    <div class="p-3 ">
                                        <div class="flex items-center justify-between gap-2 mb-2">
                                            <h3 class="text-xl font-medium dark:text-gray-400">
                                                {{ $relatedProducts->name }}
                                            </h3>
                                        </div>
                                        <p class="text-lg ">
                                            <span class="text-green-600 dark:text-green-600">
                                                {{ Number::currency($relatedProducts->price, 'NPR') }}
                                            </span>
                                        </p>
                                    </div>
                                    <div class="flex justify-center p-4 border-t border-gray-300 dark:border-gray-700">

                                        <a wire:click.prevent='addToCart({{ $relatedProducts->id }})' href="#"
                                            class="flex items-center space-x-2 text-gray-500 dark:text-gray-400 hover:text-red-500 dark:hover:text-red-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="w-4 h-4 bi bi-cart3 " viewBox="0 0 16 16">
                                                <path
                                                    d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .49.598l-1 5a.5.5 0 0 1-.465.401l-9.397.472L4.415 11H13a.5.5 0 0 1 0 1H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l.84 4.479 9.144-.459L13.89 4H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z">
                                                </path>
                                            </svg><span wire:loading.remove
                                                wire:target='addToCart({{ $relatedProducts->id }})'>
                                                Add to Cart</span><span wire:loading
                                                wire:target='addToCart({{ $relatedProducts->id }})'>Adding...</span>
                                        </a>

                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach


                </div>
            </div>
        </div>
    </div>


