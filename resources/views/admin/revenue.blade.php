@extends('master')

@section('content')
<div class="admin-store-page py-4 py-lg-5">
    <div class="row justify-content-center">
        <div class="col-12 col-xl-10">
            <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
                <div>
                    <p class="text-uppercase text-muted mb-1" style="letter-spacing: .18em; font-size: 12px;">Admin panel</p>
                </div>
                <a href="{{ route('admin.add_product') }}" class="btn btn-outline-secondary">
                    <i class="fa-solid fa-arrow-left me-2"></i>Quay lại
                </a>

            </div>
        </div>
    </div>
</div>
@endsection