@extends('layouts.app')
@section('content')
    <main class="container">
        <section>
            <div class="titlebar">
                <h1>Products</h1>
                <a href="{{ route('products.create') }}" class="btn-link">Add Product</a>
            </div>
            @if ($message = Session::get('success'))
              <script type="text/javascript">
                    //
                    Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "{{ $message }}",
                    showConfirmButton: false,
                    timer: 3000
                    });
              </script>

            @endif
            <div class="table">
                <div class="table-filter">
                    <div>
                        <ul class="table-filter-list">
                            <li>
                                <a href="{{ route('products.index') }}" class="table-filter-link link-active">All</a>
                            </li>
                        </ul>
                    </div>
                </div>

                <form method="GET" action="{{ route('products.index') }}" accept-charset="UTF-8" role="search">
                <div class="table-search">
                    <div>
                        <button class="search-select">
                           Search Product
                        </button>
                        <span class="search-select-arrow">
                            <i class="fas fa-caret-down"></i>
                        </span>
                    </div>
                    <div class="relative">
                        <input class="search-input" type="text" name="search" placeholder="Search product..." name="search" value="{{ request('search') }}">
                    </div>
                </div>
            </form>
                <div class="table-product-head">
                    <p>Image</p>
                    <p>Name</p>
                    <p>Category</p>
                    <p>Inventory</p>
                    <p>Actions</p>
                </div>
                <div class="table-product-body">
                    @if (count($products) > 0)
                        @foreach ($products as $product)
                            <img src="{{ asset('images/'. $product->image) }}" />
                            <p>{{ $product->name }}</p>
                            <p>{{ $product->category }}</p>
                            <p>{{ $product->quantity }}</p>
                            <div style="display: flex; gap: 10px; justify-content: center; align-items: center;">
                                    <a href="{{ route('products.edit', $product->id )}}" class="btn-link btn btn-success" style="padding-top: 4px;padding-bottom: 4px " >
                                        <i class="fas fa-pencil-alt" ></i>
                                    </a>
                                    <form method="post" action=" {{ route('products.destroy', $product->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger" onclick="deleteConfirm(event)" >
                                            <i class="far fa-trash-alt"></i>
                                        </button>

                                    </form>

                                </div>
                        @endforeach
                        @else
                        <p>
                            No products found
                        </p>
                    @endif
                </div>
                <div class="table-paginate">
                    {{ $products->links('layouts.pagination') }}
                    {{-- <div class="pagination">
                        <a href="#" disabled>&laquo;</a>
                        <a class="active-page">1</a>
                        <a>2</a>
                        <a>3</a>
                        <a href="#">&raquo;</a> --}}
                {{-- <i class="paginate-icon left fas fa-angle-left"></i>
                <i class="paginate-icon fas fa-angle-double-left"></i>
                <p class="paginate-text">Page 1 of 1</p>
                <i class="paginate-icon fas fa-angle-double-right"></i>
                <i class="paginate-icon right fas fa-angle-right"></i> --}}
            </div>
        </div>
        </section>
    </main>
    <script>
        window.deleteConfirm = function(event) {
            event.preventDefault();
            var form = event.target.form;
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    </script>

@endsection
