
<div class="product-single-container product-single-default product-quick-view container tony">
    <div class="row">
        <div class="col-lg-6 col-md-6 product-single-gallery">
            <div class="product-slider-container product-item">
                <div class="product-single-carousel owl-carousel owl-theme">
                    <div class="product-item">
                        <img class="product-single-image" src="{{ asset('storage/uploads/items/'.$record->image) }}"
                            data-zoom-image="{{ asset('storage/uploads/items/'.$record->image) }}" />
                    </div>

                    @foreach($record->images as $row)

                    <div class="product-item">
                        <img class="product-single-image"
                            src="{{ asset('storage/uploads/items/'.$row->image) }}"
                            data-zoom-image="{{ asset('storage/uploads/items/'.$row->image) }}" />
                    </div>

                    @endforeach

                    <!--<div class="product-item">
                        <img class="product-single-image"
                            src="{{ asset('storage/uploads/items/'.$record->image_medium) }}"
                            data-zoom-image="{{ asset('storage/uploads/items/'.$record->image_medium) }}" />
                    </div> -->

                </div>

            </div>
            <div class="prod-thumbnail row owl-dots" id='carousel-custom-dots'>
                <div class="col-3 owl-dot">
                    <img src="{{ asset('storage/uploads/items/'.$record->image) }}" />
                </div>

                @foreach($record->images as $row)

                    <div class="col-3 owl-dot">
                        <img src="{{ asset('storage/uploads/items/'.$row->image) }}" />
                    </div>

                @endforeach

                <!--<div class="col-3 owl-dot">
                    <img src="{{ asset('porto_ecommerce/ajax/assets/images/products/zoom/product-2.html') }}" />
                </div>
                <div class="col-3 owl-dot">
                    <img src="{{ asset('porto_ecommerce/ajax/assets/images/products/zoom/product-3.html') }}" />
                </div>
                <div class="col-3 owl-dot">
                    <img src="{{ asset('porto_ecommerce/ajax/assets/images/products/zoom/product-4.html') }}" />
                </div>-->
            </div>
        </div><!-- End .col-lg-7 -->

        <div class="col-lg-6 col-md-6">
            <div class="product-single-details">
                <h1 class="product-title">{{$record->description}}</h1>
                
                <div class="ratings-container">
                    <div class="product-ratings">
                        <span class="ratings" style="width:60%"></span><!-- End .ratings -->
                    </div><!-- End .product-ratings -->

                    <a href="#" class="rating-link">( 6 Reviews )</a>
                </div><!-- End .product-container -->

                <div class="price-box">
                    <span class="old-price">{{ $record->currency_type['symbol'] }} {{ number_format( ($record->sale_unit_price * 1.2 ) , 2 ) }}</span>
                    <span class="product-price">{{ $record->currency_type['symbol'] }} {{ number_format($record->sale_unit_price, 2) }}</span>
                </div><!-- End .price-box -->

                <div class="product-desc">
                    <p class="product-category">Categoría: <span> {{$record->category->name}} </span></p>
                <p class="product-stock">Disponible: <span>{{number_format(($record->stock), 0)}} </span>
                <?php
                if($record->stock > 0){?>
                    <span 
                    class="alert-stock" role="alert">En stock</span>
                <?php
                }else{?>
                    <span 
                    class="alert-sin-stock" 
                    role="alert">Sin stock</span> 
                <?php
                }
                ?>
                </p>
                    <p>{{ $record->name }}</p>
                </div><!-- End .product-desc -->



                <div class="product-action">
                    <!-- div class="product-single-qty">
                        <input class="horizontal-quantity form-control" type="text">
                    </div> End .product-single-qty -->

                    <a href="#" onclick="cart_add('{{ json_encode( $record ) }}')" class="paction add-cart"
                        title="Add to Cart">
                        <span>Agregar a Carrito</span>
                    </a>

                </div><!-- End .product-action -->

            </div><!-- End .product-single-details -->
        </div><!-- End .col-lg-5 -->
    </div><!-- End .row -->
</div><!-- End .product-single-container -->
