<div class="row g-7">
    <!-- Artwork List Column -->
    <div class="col-xl-4 col-lg-5">
        <div class="card card-flush shadow-sm border">
            <div class="card-header pt-5 border-0 d-flex justify-content-between align-items-center">
                <h3 class="card-title fw-bold text-gray-800 fs-6 text-uppercase my-0">ARTWORK LIST</h3>
                <button type="button" class="btn btn-sm btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#addArtworkModal">
                    <i class="fa-solid fa-plus fs-8 me-1"></i> Add Artwork
                </button>
            </div>
            <div class="card-body pt-0">
                <div class="nav flex-column nav-pills gap-2" role="tablist">
                    @forelse($style->artworks as $index => $art)
                        <button class="nav-link text-start p-3 border rounded {{ $index === 0 ? 'active bg-light-primary border-primary' : 'bg-light' }}" data-bs-toggle="pill" data-bs-target="#art_item_{{ $art->id }}" type="button">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="fw-bold text-gray-900 fs-7 text-truncate">{{ $art->artwork_name }}</div>
                                @if($art->artwork_file)
                                    <span class="badge badge-light-primary p-1 ms-1" title="Attached File: {{ $art->artwork_file }}">
                                        <i class="fa-solid fa-paperclip fs-9 text-primary"></i>
                                    </span>
                                @endif
                            </div>
                            <div class="text-muted fs-9 d-flex justify-content-between mt-1">
                                <span>{{ $art->type }}</span>
                                <span class="badge badge-light-dark fs-9">{{ $art->placement ?: 'General' }}</span>
                            </div>
                        </button>
                    @empty
                        <div class="text-muted fs-8 text-center py-5">No artworks added yet.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Artwork Details & Display Column -->
    <div class="col-xl-8 col-lg-7">
        <div class="tab-content">
            @forelse($style->artworks as $index => $art)
                <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" id="art_item_{{ $art->id }}">
                    <div class="card card-flush shadow-sm border">
                        <div class="card-header pt-5 border-0 d-flex justify-content-between align-items-center">
                            <h3 class="card-title fw-bold text-gray-800 fs-6 text-uppercase my-0">Artwork Details</h3>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-sm btn-outline btn-outline-warning btn-edit-artwork" data-artwork="{{ json_encode($art) }}">
                                    <i class="fa-solid fa-pen me-1"></i> Edit
                                </button>
                                <button type="button" class="btn btn-sm btn-outline btn-outline-danger btn-delete-artwork" data-id="{{ $art->id }}">
                                    <i class="fa-solid fa-trash me-1"></i> Delete
                                </button>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div class="row g-4 mb-4">
                                <div class="col-md-6">
                                    <label class="text-muted fs-8 d-block">Artwork Name</label>
                                    <div class="fw-bold text-gray-900 fs-7">{{ $art->artwork_name }}</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted fs-8 d-block">Artwork Code</label>
                                    <div class="fw-bold text-gray-900 fs-7">{{ $art->artwork_code ?: 'N/A' }}</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted fs-8 d-block">Artwork Type</label>
                                    <div class="fw-bold text-gray-900 fs-7">
                                        <span class="badge badge-light-primary">{{ $art->type }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted fs-8 d-block">Placement</label>
                                    <div class="fw-bold text-gray-900 fs-7">{{ $art->placement ?: 'N/A' }}</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted fs-8 d-block">Dimensions</label>
                                    <div class="fw-bold text-gray-900 fs-7">{{ $art->dimensions ?: 'N/A' }}</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted fs-8 d-block">Colors</label>
                                    <div class="d-flex gap-2 mt-1 flex-wrap">
                                        @if(!empty($art->colors))
                                            @foreach((array)$art->colors as $clr)
                                                <span class="badge bg-light text-dark border">{{ $clr }}</span>
                                            @endforeach
                                        @else
                                            <span class="text-muted fs-8">Standard</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Artwork File Section -->
                                <div class="col-12">
                                    <label class="text-muted fs-8 d-block mb-1">Attached Artwork File</label>
                                    @if($art->artwork_file)
                                        @php
                                            $ext = strtolower(pathinfo($art->artwork_file, PATHINFO_EXTENSION));
                                            $isImage = in_array($ext, ['png', 'jpg', 'jpeg', 'svg', 'webp', 'gif']);
                                            $iconClass = match($ext) {
                                                'pdf' => 'fa-file-pdf text-danger',
                                                'ai', 'eps' => 'fa-bezier-curve text-warning',
                                                'psd' => 'fa-image text-primary',
                                                'zip', 'rar' => 'fa-file-zipper text-info',
                                                'png', 'jpg', 'jpeg', 'svg', 'webp' => 'fa-file-image text-success',
                                                default => 'fa-file-lines text-primary'
                                            };
                                        @endphp
                                        <div class="d-flex flex-wrap align-items-center justify-content-between p-3 bg-light rounded border gap-2">
                                            <div class="d-flex align-items-center gap-2 text-truncate me-2">
                                                <i class="fa-solid {{ $iconClass }} fs-3"></i>
                                                <div class="text-truncate">
                                                    <div class="fw-bold text-gray-800 fs-7 text-truncate" title="{{ $art->artwork_file }}">{{ $art->artwork_file }}</div>
                                                    <span class="badge badge-light-secondary fs-9 text-uppercase">{{ $ext ?: 'FILE' }}</span>
                                                </div>
                                            </div>
                                            <div class="d-flex gap-2">
                                                @if($isImage || $ext === 'pdf')
                                                    <a href="{{ asset('uploads/styles/artworks/' . $art->artwork_file) }}" target="_blank" class="btn btn-sm btn-light border fw-bold">
                                                        <i class="fa-solid fa-eye me-1"></i> Preview
                                                    </a>
                                                @endif
                                                <a href="{{ route('styles.artworks.download', $art->id) }}" class="btn btn-sm btn-primary fw-bold">
                                                    <i class="fa-solid fa-download me-1"></i> Download
                                                </a>
                                            </div>
                                        </div>
                                    @else
                                        <div class="p-3 bg-light rounded border text-muted fs-8 d-flex align-items-center justify-content-between">
                                            <span><i class="fa-solid fa-circle-info me-1"></i> No artwork file attached yet.</span>
                                            <button type="button" class="btn btn-xs btn-outline btn-outline-primary btn-edit-artwork py-1 px-2" data-artwork="{{ json_encode($art) }}">
                                                <i class="fa-solid fa-upload me-1"></i> Upload File
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Graphic Display -->
                            <div class="border rounded p-4 bg-light text-center d-flex align-items-center justify-content-center" style="min-height: 240px;">
                                @if(!empty($art->artwork_file) && in_array(strtolower(pathinfo($art->artwork_file, PATHINFO_EXTENSION)), ['png', 'jpg', 'jpeg', 'svg', 'webp', 'gif']))
                                    <div class="p-3 bg-white rounded shadow-sm border w-100">
                                        <img src="{{ asset('uploads/styles/artworks/' . $art->artwork_file) }}" alt="{{ $art->artwork_name }}" class="img-fluid rounded" style="max-height: 280px; object-fit: contain;">
                                        <div class="fw-bold text-gray-700 fs-8 tracking-widest mt-2">{{ $art->artwork_name }} • {{ $art->artwork_code ?: 'N/A' }}</div>
                                    </div>
                                @else
                                    <div class="p-4 bg-white rounded shadow-sm border">
                                        <h2 class="fw-black text-primary tracking-widest display-6 mb-0">{{ $art->artwork_name }}</h2>
                                        <div class="fw-bold text-gray-600 fs-8 tracking-widest mt-1">{{ $art->artwork_code ?: 'N/A' }} • {{ $art->placement ?: 'General' }}</div>
                                        @if(!empty($art->artwork_file))
                                            <div class="mt-3">
                                                <a href="{{ route('styles.artworks.download', $art->id) }}" class="btn btn-sm btn-light-primary fw-bold">
                                                    <i class="fa-solid fa-download me-1"></i> Download {{ strtoupper(pathinfo($art->artwork_file, PATHINFO_EXTENSION)) }} File
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="card card-flush shadow-sm border">
                    <div class="card-body text-center text-muted py-8">
                        <i class="fa-solid fa-paintbrush fs-3x text-secondary mb-2 d-block"></i>
                        No artwork detail selected or created.
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>
