<?php

namespace Modules\Product\Http\Controllers;

use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\DB;
use Modules\Product\Entities\Product;
use Modules\Product\Enums\OfferType;
use Modules\Product\Http\Requests\MedicationRequest;
use Modules\Product\Http\Requests\ProductBonusRequest;
use Modules\Product\Http\Requests\ProductIndexRequest;
use Modules\Product\Http\Requests\ProductPercentageSlatOneRequest;
use Modules\Product\Http\Requests\ProductPercentageSlatTwoRequest;
use Modules\Product\Http\Requests\ProductShortageRequest;
use Modules\Product\Http\Requests\ProductViewRequest;
use Modules\Product\Http\Requests\UpdateProductRequest;
use Modules\Product\Http\Requests\ViewProductByBarcodeRequest;
use Modules\Product\Repositories\BatchRepository;
use Modules\Product\Repositories\ManufacturerRepository;
use Modules\Product\Repositories\ProductRepository;
use Modules\Product\Transformers\ManufacturerResourceCollection;
use Modules\Product\Transformers\ProductResource;
use Modules\Product\Transformers\ProductResourceCollection;
use Modules\Product\Transformers\MinimizedProductResourceCollection;

class ProductController extends BaseController
{
    public function __construct(
        protected BatchRepository $batchRepository,
        protected ProductRepository $productRepository,
        protected ManufacturerRepository $manufacturerRepository
    ) {
        $this->middleware('permission:listing_products')->only(['index', 'dropdown', 'view', 'indexPaginate']);
        $this->middleware('permission:update_products|retail_reviewer')->only(['viewByBarcode', 'update']);
        $this->middleware('permission:listing_manufacturers')->only(['manufacturers']);
        $this->middleware('permission:listing_products_with_offer')->only([
            'shortage',
            'bonus',
            'percentageOfferSlatOne',
            'percentageOfferSlatTwo',
            'medicationAlternatives',
            'relatedActiveIngredient',
        ]);
    }

    public function index(ProductIndexRequest $request)
    {
        $products = $this->productRepository->all($request->validated());

        return $this->apiResponse(new ProductResourceCollection($products));
    }

    public function dropdown()
    {
        $products = $this->productRepository->dropdown();

        return $this->apiResponse(new MinimizedProductResourceCollection($products));
    }

    public function indexPaginate(ProductIndexRequest $request)
    {
        $products = $this->productRepository->listAllPaginated($request->validated());

        return $this->respondResource(new ProductResourceCollection($products));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        DB::beginTransaction();

        $this->productRepository->update($product, $request->validated());

        DB::commit();

        return $this->apiResponse();
    }

    public function view(ProductViewRequest $request)
    {
        $product = $this->productRepository->view($request->validated());

        return $this->checkProductOfferResponse($product);
    }

    public function viewByBarcode(ViewProductByBarcodeRequest $request)
    {
        $product = $this->productRepository->all($request->validated())->first();

        return $this->checkProductOfferResponse($product);
    }

    // Refactor It's not dynamic using offset of 0 and offset of 1
    public function checkProductOfferResponse($product)
    {
        if ($product->is_limited == 1) {
            return $this->limitedProductResponse($product);
        }

        if ($product->offers()->exists()) {
            $productOffers = $product->offers();

            if ($productOffers->first()->type == OfferType::PERCENTAGE) {
                $productOffers = $productOffers->get();
                $first_offer = $productOffers[0];
                $second_offer = isset($productOffers[1]) ? $productOffers[1] : null;

                if (isset($productOffers[1])) {
                    return $this->productWithPercentageOffersResponse($product, $first_offer, $second_offer);
                } else {
                    return $this->productWithPercentageOffersResponse($product, $first_offer);
                }
            } else {
                $productOffer = $productOffers->first();

                return $this->productWithBonusOfferResponse($product, $productOffer);
            }
        }

        return $this->apiResponse(new ProductResource($product));
    }

    private function limitedProductResponse($product)
    {
        return $this->apiResponse(
            new ProductResource($product),
            trans('product::message.the_product_is_limited') . " {$product->limited_quantity}"
        );
    }

    private function productWithPercentageOffersResponse($product, $first_offer, $second_offer = null)
    {
        if ($second_offer == null) {
            return $this->apiResponse(
                new ProductResource($product),
                trans('product::message.the_product_has_offer') . " {$first_offer->quantity_for_offer} + %{$first_offer->offer_value}"
            );
        } else {
            return $this->apiResponse(
                new ProductResource($product),
                trans('product::message.the_product_has_offer') . " {$first_offer->quantity_for_offer} + %{$first_offer->offer_value}\n" .
                    trans('product::message.the_product_has_offer') . " {$second_offer->quantity_for_offer} + %{$second_offer->offer_value}"
            );
        }
    }

    private function productWithBonusOfferResponse($product, $productOffer)
    {
        return $this->apiResponse(
            new ProductResource($product),
            trans('product::message.the_product_has_bonus') . " {$productOffer->quantity_for_offer} + {$productOffer->offer_value}"
        );
    }

    public function shortage(ProductShortageRequest $request)
    {
        $products = $this->productRepository->shortage($request->validated());

        return $this->apiResponse(new ProductResourceCollection($products));
    }

    public function bonus(ProductBonusRequest $request)
    {
        $products = $this->productRepository->bonus($request->validated());

        return $this->apiResponse(new ProductResourceCollection($products));
    }

    public function percentageOfferSlatOne(ProductPercentageSlatOneRequest $request)
    {
        $data = $this->productRepository->percentageOfferSlatOne($request->validated());

        return $this->respondResource(new ProductResourceCollection($data));
    }

    public function percentageOfferSlatTwo(ProductPercentageSlatTwoRequest $request)
    {
        $data = $this->productRepository->percentageOfferSlatTwo($request->validated());

        return $this->respondResource(new ProductResourceCollection($data));
    }

    public function medicationAlternatives(MedicationRequest $request)
    {
        $products = $this->productRepository->medicationAlternatives($request->validated());

        return $this->respondResource(new ProductResourceCollection($products));
    }

    public function relatedActiveIngredient(MedicationRequest $request)
    {
        $products = $this->productRepository->relatedActiveIngredient($request->validated());

        return $this->respondResource(new ProductResourceCollection($products));
    }

    public function manufacturers()
    {
        $manufacturers = $this->manufacturerRepository->get();

        return $this->apiResponse(new ManufacturerResourceCollection($manufacturers));
    }
}
