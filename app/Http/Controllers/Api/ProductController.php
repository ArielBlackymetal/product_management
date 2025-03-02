<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Services\ImageService;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductCollection;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Interfaces\ProductRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    /**
     * @param protected App\Interfaces\ProductRepositoryInterface
     * @param protected App\Services\ImageService
     */
    public function __construct(
        protected ProductRepositoryInterface $productRepo,
        protected ImageService $imageService
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->all();
        $products = $this->productRepo->get($filters);
        return new ProductCollection($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = $this->imageService->uploadImage($request->file('image'));
        } else {
            // Get image from external service
            $data['image'] = $this->imageService->getPlaceholderImage($data['name']);
        }
        $product = $this->productRepo->store($data);
        return (new ProductResource($product))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED)
            ->header('Location', route('products.show', $product->id));
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return new ProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $data = $request->validated();

        // Handle the image if a new one is provided
        if ($request->hasFile('image')) {
            $data['image'] = $this->imageService->uploadImage($request->file('image'));
        }

        $product = $this->productRepo->update($product, $data);

        return new ProductResource($product);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $this->productRepo->delete($product);
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Get products stats
     */
    public function stats()
    {
        return response()->json($this->productRepo->stats());
    }
}
