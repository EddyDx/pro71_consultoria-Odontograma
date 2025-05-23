{{-- 
    - vista slider promociones
    - var items definida en Modules\Ecommerce\Http\ViewComposers\PromotionsViewComposer
--}}

<div class="home-slider owl-carousel owl-carousel-lazy owl-theme owl-theme-light mt-8">

    @foreach ($items as $item)

    <div class="home-slide">
        <div class="owl-lazy slide-bg" data-src="{{ asset('storage/uploads/promotions/'.$item->image) }}"></div>
        <div class="home-slide-content text-white">
            <!--<h3>Get up to <span>60%</span> off</h3> -->

            <h1>{{$item->name}}</h1>
            <p>{{$item->description}}</p>
            {{-- se agrega id de promoción para obtenerlo en vista detalle y poder obtener el nombre --}}
            <a href="/ecommerce/item/{{ $item->item_id }}/{{ $item->id }}" class="btn btn-dark">Comprar Ahora!</a>
            {{-- <a href="/ecommerce/item/{{ $item->item_id }}" class="btn btn-dark">Comprar Ahora!</a> --}}
        </div>
    </div>

    @endforeach

</div>
