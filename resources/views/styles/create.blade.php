<x-layouts.app title="Create Style - PLM Workspace">
    <div class="d-flex flex-column flex-column-fluid bg-light-app">
        <!-- Top Toolbar / Breadcrumbs -->
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-3">
            <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                        <li class="breadcrumb-item text-muted"><a href="{{ route('styles.index') }}" class="text-muted text-hover-primary">PLM</a></li>
                        <li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px mx-2"></span></li>
                        <li class="breadcrumb-item text-muted"><a href="{{ route('styles.index') }}" class="text-muted text-hover-primary">Styles</a></li>
                        <li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px mx-2"></span></li>
                        <li class="breadcrumb-item text-muted">New Style</li>
                        <li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px mx-2"></span></li>
                        <li class="breadcrumb-item text-gray-900 fw-bold">Data Entry</li>
                    </ul>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('styles.index') }}" class="btn btn-sm btn-light border border-gray-300 text-gray-700 fw-semibold px-4">
                        <i class="fa-solid fa-arrow-left me-1"></i> Back to Styles
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Workspace Content Container -->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-fluid">
                
                <form id="style_create_form" action="{{ route('styles.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="status" id="form_status_input" value="DEVELOPMENT">
                    <input type="hidden" name="submit_action" id="submit_action_input" value="save">

                    <!-- 1. TOP HEADER BAR -->
                    <div class="card card-flush shadow-xs mb-4 border-0 bg-white">
                        <div class="card-body p-5">
                            <div class="d-flex flex-wrap justify-content-between align-items-center gap-4">
                                <!-- Left Title & Status -->
                                <div>
                                    <div class="d-flex align-items-center gap-3 mb-1">
                                        <h1 class="fw-bolder text-gray-900 fs-1 my-0 tracking-tight" id="header_style_no_display">
                                            {{ old('style_no') ? 'STYLE ' . old('style_no') : 'CREATE NEW STYLE' }}
                                        </h1>
                                        <span class="badge bg-success-subtle text-success fw-bold fs-8 px-3 py-1 rounded-pill text-uppercase">DEVELOPMENT</span>
                                    </div>
                                    <div class="fw-semibold text-gray-600 fs-6" id="header_style_name_display">
                                        {{ old('style_name', 'Enter style specifications below to create master record.') }}
                                    </div>
                                </div>

                                <!-- Right Revision & Actions -->
                                <div class="d-flex align-items-center gap-3 flex-wrap">
                                    <div class="d-flex align-items-center gap-2 bg-light px-3 py-1-5 rounded border">
                                        <span class="text-muted fs-8 fw-semibold">Revision</span>
                                        <input type="text" name="revision_no" class="form-control form-control-sm w-80px form-select-solid fw-bold text-gray-800 border-0 bg-transparent py-0 fs-8" value="{{ old('revision_no', 'REV 01') }}" readonly />
                                        <span class="badge bg-success-subtle text-success fw-bold fs-9 px-2 py-1">Draft</span>
                                    </div>

                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-white border border-gray-300 text-gray-800 fw-semibold dropdown-toggle px-4" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            Actions
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm fs-7">
                                            <li><a class="dropdown-item fw-semibold" href="{{ route('styles.index') }}"><i class="fa-solid fa-list text-primary me-2"></i> All Styles List</a></li>
                                            <li><a class="dropdown-item fw-semibold text-danger" href="{{ route('styles.index') }}"><i class="fa-solid fa-times text-danger me-2"></i> Cancel Creation</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Metadata Top Summary Banner Row -->
                            <div class="row g-3 mt-3 pt-3 border-top border-gray-200">
                                <div class="col-12 col-md-10">
                                    <div class="row g-2">
                                        <div class="col-6 col-sm-4 col-md-2">
                                            <div class="d-flex align-items-center bg-light-subtle rounded p-2.5 border">
                                                <i class="fa-solid fa-user text-primary fs-3 me-3"></i>
                                                <div>
                                                    <span class="text-muted fs-9 fw-semibold d-block text-uppercase">Customer</span>
                                                    <span class="fw-bold text-gray-900 fs-8" id="summary_customer">
                                                        {{ $clients->where('id', old('client_id'))->first()?->name ?: 'Not Selected' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6 col-sm-4 col-md-2">
                                            <div class="d-flex align-items-center bg-light-subtle rounded p-2.5 border">
                                                <i class="fa-solid fa-calendar-alt text-primary fs-3 me-3"></i>
                                                <div>
                                                    <span class="text-muted fs-9 fw-semibold d-block text-uppercase">Season</span>
                                                    <span class="fw-bold text-gray-900 fs-8" id="summary_season">{{ old('season', 'Not Set') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6 col-sm-4 col-md-2">
                                            <div class="d-flex align-items-center bg-light-subtle rounded p-2.5 border">
                                                <i class="fa-solid fa-layer-group text-primary fs-3 me-3"></i>
                                                <div>
                                                    <span class="text-muted fs-9 fw-semibold d-block text-uppercase">Program</span>
                                                    <span class="fw-bold text-gray-900 fs-8" id="summary_program">{{ old('program', 'Not Set') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6 col-sm-4 col-md-2">
                                            <div class="d-flex align-items-center bg-light-subtle rounded p-2.5 border">
                                                <i class="fa-solid fa-shirt text-primary fs-3 me-3"></i>
                                                <div>
                                                    <span class="text-muted fs-9 fw-semibold d-block text-uppercase">Category</span>
                                                    <span class="fw-bold text-gray-900 fs-8" id="summary_category">{{ old('category', 'Not Set') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6 col-sm-4 col-md-2">
                                            <div class="d-flex align-items-center bg-light-subtle rounded p-2.5 border">
                                                <i class="fa-solid fa-ruler-horizontal text-primary fs-3 me-3"></i>
                                                <div>
                                                    <span class="text-muted fs-9 fw-semibold d-block text-uppercase">Sizes</span>
                                                    <span class="fw-bold text-gray-900 fs-8" id="summary_sizes">{{ old('size_range', 'Not Set') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6 col-sm-4 col-md-2">
                                            <div class="d-flex align-items-center bg-light-subtle rounded p-2.5 border justify-content-between">
                                                <div class="d-flex align-items-center">
                                                    <i class="fa-solid fa-barcode text-primary fs-3 me-3"></i>
                                                    <div>
                                                        <span class="text-muted fs-9 fw-semibold d-block text-uppercase">HTS Code</span>
                                                        <span class="fw-bold text-gray-900 fs-8" id="summary_hts">{{ old('hts_code', 'Not Set') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-2 d-flex align-items-center justify-content-md-end">
                                    <div class="text-gray-500 fs-7 d-flex align-items-center gap-2 bg-light px-3 py-2 rounded border">
                                        <i class="fa-solid fa-step-backward text-gray-400" title="First Style"></i>
                                        <i class="fa-solid fa-chevron-left text-gray-400 ms-1" title="Previous Style"></i>
                                        <i class="fa-solid fa-chevron-right text-gray-400 ms-1" title="Next Style"></i>
                                        <i class="fa-solid fa-step-forward text-gray-400 ms-1" title="Last Style"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 2. TAB WORKSPACE NAVIGATION -->
                    <div class="card card-flush shadow-xs mb-4 border-0 bg-white">
                        <div class="card-header border-bottom px-5 py-0 min-h-45px">
                            <ul class="nav nav-stretch nav-line-tabs border-transparent fs-7 fw-bold gap-4">
                                <li class="nav-item"><a class="nav-link text-active-primary py-3 active border-bottom border-primary border-2 text-primary" href="#">Overview</a></li>
                                <li class="nav-item"><a class="nav-link text-gray-500 py-3 disabled" href="#">Tech Pack</a></li>
                                <li class="nav-item"><a class="nav-link text-gray-500 py-3 disabled" href="#">Artwork</a></li>
                                <li class="nav-item"><a class="nav-link text-gray-500 py-3 disabled" href="#">Sample Pattern</a></li>
                                <li class="nav-item"><a class="nav-link text-gray-500 py-3 disabled" href="#">BOM</a></li>
                                <li class="nav-item"><a class="nav-link text-gray-500 py-3 disabled" href="#">Measurements</a></li>
                                <li class="nav-item"><a class="nav-link text-gray-500 py-3 disabled" href="#">Colors</a></li>
                                <li class="nav-item"><a class="nav-link text-gray-500 py-3 disabled" href="#">Samples</a></li>
                                <li class="nav-item"><a class="nav-link text-gray-500 py-3 disabled" href="#">Costing</a></li>
                                <li class="nav-item"><a class="nav-link text-gray-500 py-3 disabled" href="#">Files</a></li>
                                <li class="nav-item"><a class="nav-link text-gray-500 py-3 disabled" href="#">Approvals</a></li>
                                <li class="nav-item"><a class="nav-link text-gray-500 py-3 disabled" href="#">History</a></li>
                            </ul>
                        </div>
                    </div>

                    <!-- 3. TWO-COLUMN SPLIT LAYOUT -->
                    <div class="row g-4">
                        <!-- LEFT MAIN COLUMN -->
                        <div class="col-xl-8 col-lg-8">
                            
                            <!-- STYLE INFORMATION CARD -->
                            <div class="card card-flush shadow-xs mb-4 border-0 bg-white">
                                <div class="card-header border-0 pt-4 pb-2 px-5">
                                    <h3 class="card-title fw-bold text-gray-900 fs-7 text-uppercase d-flex align-items-center gap-2">
                                        <span class="d-inline-flex align-items-center justify-content-center border border-primary rounded w-20px h-20px text-primary"><i class="fa-solid fa-info fs-9"></i></span> STYLE INFORMATION
                                    </h3>
                                </div>
                                <div class="card-body pt-2 px-5 pb-5">
                                    <div class="row g-3">
                                        <!-- Row 1 -->
                                        <div class="col-md-3">
                                            <label class="form-label fw-bold text-gray-700 fs-8 mb-1">Style No. <span class="text-danger">*</span></label>
                                            <input type="text" name="style_no" id="input_style_no" class="form-control form-control-sm form-control-solid border" placeholder="e.g. TT96" value="{{ old('style_no') }}" required />
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold text-gray-700 fs-8 mb-1">Style Name / Title <span class="text-danger">*</span></label>
                                            <input type="text" name="style_name" id="input_style_name" class="form-control form-control-sm form-control-solid border" placeholder="e.g. Zone Hydrosport Heavyweight Hooded Sweatshirt" value="{{ old('style_name') }}" required />
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label fw-bold text-gray-700 fs-8 mb-1">Customer <span class="text-danger">*</span></label>
                                            <select name="client_id" id="input_client_id" class="form-select form-select-sm form-select-solid border" data-control="select2" data-placeholder="Select Customer" required>
                                                <option value="">Select Customer</option>
                                                @foreach($clients as $c)
                                                    <option value="{{ $c->id }}" {{ old('client_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label fw-bold text-gray-700 fs-8 mb-1">Season <span class="text-danger">*</span></label>
                                            <select name="season" id="input_season" class="form-select form-select-sm form-select-solid border" data-control="select2" data-placeholder="Select Season" required>
                                                <option value="">Select Season</option>
                                                @foreach($seasons as $s)
                                                    <option value="{{ $s }}" {{ old('season') == $s ? 'selected' : '' }}>{{ $s }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Row 2 -->
                                        <div class="col-md-3">
                                            <label class="form-label fw-bold text-gray-700 fs-8 mb-1">Program</label>
                                            <select name="program" id="input_program" class="form-select form-select-sm form-select-solid border" data-control="select2" data-placeholder="Select Program">
                                                <option value="">Select Program</option>
                                                @foreach($programs as $p)
                                                    <option value="{{ $p }}" {{ old('program') == $p ? 'selected' : '' }}>{{ $p }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold text-gray-700 fs-8 mb-1">Category <span class="text-danger">*</span></label>
                                            <select name="category" id="input_category" class="form-select form-select-sm form-select-solid border" data-control="select2" data-placeholder="Select Category" required>
                                                <option value="">Select Category</option>
                                                @foreach($categories as $cat)
                                                    <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label fw-bold text-gray-700 fs-8 mb-1">Gender</label>
                                            <select name="gender" id="input_gender" class="form-select form-select-sm form-select-solid border" data-control="select2" data-placeholder="Select Gender">
                                                <option value="">Select Gender</option>
                                                @foreach($genders as $g)
                                                    <option value="{{ $g }}" {{ old('gender') == $g ? 'selected' : '' }}>{{ $g }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label fw-bold text-gray-700 fs-8 mb-1">Revision</label>
                                            <input type="text" name="revision_no" class="form-control form-control-sm form-control-solid bg-light text-gray-600 border" value="{{ old('revision_no', 'REV 01') }}" readonly />
                                        </div>

                                        <!-- Row 3 -->
                                        <div class="col-md-3">
                                             <label class="form-label fw-bold text-gray-700 fs-8 mb-1">Origin Country <span class="text-danger">*</span></label>
                                             <select name="country" id="input_country" class="form-select form-select-sm form-select-solid border" data-control="select2" data-placeholder="Select Origin Country" required>
                                                 <option value="">Select Country</option>
                                                 @foreach($countries as $c)
                                                     <option value="{{ $c }}" {{ old('country') == $c ? 'selected' : '' }}>{{ $c }}</option>
                                                 @endforeach
                                             </select>
                                        </div>
                                        <div class="col-md-3">
                                             <label class="form-label fw-bold text-gray-700 fs-8 mb-1">HTS Code (US)</label>
                                             <input type="text" name="hts_code" id="input_hts_code" class="form-control form-control-sm form-control-solid border" placeholder="Enter US HTS (e.g. 6105.20.2010)" value="{{ old('hts_code') }}">
                                        </div>
                                        <div class="col-md-3">
                                             <label class="form-label fw-bold text-gray-700 fs-8 mb-1">HTS Code (Canada)</label>
                                             <input type="text" name="hts_code_ca" id="input_hts_code_ca" class="form-control form-control-sm form-control-solid border" placeholder="Enter Canada HTS (e.g. 6105.20.0030)" value="{{ old('hts_code_ca') }}">
                                        </div>
                                        <div class="col-md-3">
                                             <label class="form-label fw-bold text-gray-700 fs-8 mb-1">Size Range <span class="text-danger">*</span></label>
                                             <select name="size_range" id="input_size_range" class="form-select form-select-sm form-select-solid border" data-control="select2" data-placeholder="Select Size Range" required>
                                                 <option value="">Select Size Range</option>
                                                 @foreach($sizeRanges as $sr)
                                                     <option value="{{ $sr }}" {{ old('size_range') == $sr ? 'selected' : '' }}>{{ $sr }}</option>
                                                 @endforeach
                                             </select>
                                        </div>

                                        <!-- Sizes Multi-select -->
                                        <div class="col-12">
                                            <label class="form-label fw-bold text-gray-700 fs-8 mb-1">Sizes</label>
                                            <select name="sizes[]" id="input_sizes" class="form-select form-select-sm form-select-solid border" data-control="select2" data-placeholder="Select Sizes from sizes table (e.g. S, M, L, XL)" multiple>
                                                @foreach($allSizes as $sz)
                                                    <option value="{{ $sz->name }}" {{ in_array($sz->name, old('sizes', [])) ? 'selected' : '' }}>
                                                        {{ $sz->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Row 4 -->
                                        <div class="col-md-3">
                                            <label class="form-label fw-bold text-gray-700 fs-8 mb-1">Content</label>
                                            <select name="content" id="input_content" class="form-select form-select-sm form-select-solid border" data-control="select2" data-placeholder="Select Fabric Content">
                                                <option value="">Select Content</option>
                                                @foreach($contents as $cnt)
                                                    <option value="{{ $cnt }}" {{ old('content') == $cnt ? 'selected' : '' }}>{{ $cnt }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label fw-bold text-gray-700 fs-8 mb-1">GSM / Weight</label>
                                            <input type="text" inputmode="numeric" name="gsm" id="input_gsm" class="form-control form-control-sm form-control-solid border" placeholder="e.g. 240" value="{{ old('gsm') }}" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label fw-bold text-gray-700 fs-8 mb-1">Construction</label>
                                            <select name="construction" id="input_construction" class="form-select form-select-sm form-select-solid border" data-control="select2" data-placeholder="Select Construction">
                                                <option value="">Select Construction</option>
                                                @foreach($constructions as $cons)
                                                    <option value="{{ $cons }}" {{ old('construction') == $cons ? 'selected' : '' }}>{{ $cons }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label fw-bold text-gray-700 fs-8 mb-1">Treatment</label>
                                            <input type="text" name="treatment" id="input_treatment" class="form-control form-control-sm form-control-solid border" placeholder="e.g. Mineral Wash" value="{{ old('treatment') }}">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label fw-bold text-gray-700 fs-8 mb-1">Gauge</label>
                                            <select name="gauge" id="input_gauge" class="form-select form-select-sm form-select-solid border" data-control="select2" data-placeholder="Select Gauge">
                                                <option value="">Select Gauge</option>
                                                @foreach($gauges as $gauge)
                                                    <option value="{{ $gauge }}" {{ old('gauge') == $gauge ? 'selected' : '' }}>{{ $gauge }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Row 5 (Description & Interactive Tags) -->
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold text-gray-700 fs-8 mb-1">Description <span class="text-danger">*</span></label>
                                            <textarea name="description" class="form-control form-control-sm form-control-solid border" rows="3" required placeholder="Enter detailed style description...">{{ old('description') }}</textarea>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold text-gray-700 fs-8 mb-1">Tags</label>
                                            <div id="tags_container" class="d-flex align-items-center gap-1.5 flex-wrap p-2.5 bg-light rounded border border-dashed min-h-75px">
                                                @if(is_array(old('tags')))
                                                    @foreach(old('tags') as $tag)
                                                        <span class="badge bg-white text-gray-800 border fw-semibold px-2.5 py-1.5 fs-8 d-inline-flex align-items-center tag-pill">
                                                            {{ $tag }} <i class="fa-solid fa-xmark ms-2 cursor-pointer text-gray-400 hover-danger remove-tag-btn"></i>
                                                            <input type="hidden" name="tags[]" value="{{ $tag }}">
                                                        </span>
                                                    @endforeach
                                                @endif
                                                <button type="button" id="btn_add_tag" class="btn btn-sm btn-icon btn-light-primary w-22px h-22px rounded-circle ms-1" title="Add Tag"><i class="fa-solid fa-plus fs-9"></i></button>
                                            </div>
                                        </div>

                                        <!-- Row 6 (Instructions with Live Character Counters) -->
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold text-gray-700 fs-8 mb-1">Packing Instruction</label>
                                            <textarea id="packing_instructions_textarea" name="packing_instructions" class="form-control form-control-sm form-control-solid border" rows="3" placeholder="Enter packing instructions (e.g. Polybag each, 24 pcs / carton)...">{{ old('packing_instructions') }}</textarea>
                                            <div class="fs-9 text-muted text-end mt-1"><span id="packing_counter">300</span> characters remaining</div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold text-gray-700 fs-8 mb-1">Special Instructions</label>
                                            <textarea id="special_instructions_textarea" name="special_instructions" class="form-control form-control-solid border" rows="3" placeholder="Enter special instructions (e.g. Heat transfer logo at chest)...">{{ old('special_instructions') }}</textarea>
                                            <div class="fs-9 text-muted text-end mt-1"><span id="special_counter">300</span> characters remaining</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- RIGHT SIDEBAR COLUMN -->
                        <div class="col-xl-4 col-lg-4">
                            <!-- STYLE IMAGES GALLERY WIDGET -->
                            <div class="card card-flush shadow-xs mb-4 border-0 bg-white">
                                <div class="card-header border-0 pt-4 pb-2 px-4">
                                    <h3 class="card-title fw-bold text-gray-900 fs-7 text-uppercase d-flex align-items-center gap-2">
                                        <span class="d-inline-flex align-items-center justify-content-center border border-primary rounded w-20px h-20px text-primary"><i class="fa-solid fa-info fs-9"></i></span> STYLE IMAGES
                                    </h3>
                                </div>
                                <div class="card-body pt-0 px-4 pb-4">
                                    <!-- Dynamic Gallery Sub-Tabs -->
                                    <ul class="nav nav-tabs nav-line-tabs border-bottom mb-3 fs-8 fw-bold" id="gallery_tab_list" role="tablist">
                                        <li class="nav-item"><a class="nav-link active py-1.5 border-bottom border-primary border-2 text-primary" data-bs-toggle="tab" href="#create_gallery_front">Front <span id="count_create_Front" class="badge badge-sm badge-circle badge-light-primary ms-1 d-none">0</span></a></li>
                                        <li class="nav-item"><a class="nav-link py-1.5 text-gray-500" data-bs-toggle="tab" href="#create_gallery_back">Back <span id="count_create_Back" class="badge badge-sm badge-circle badge-light-primary ms-1 d-none">0</span></a></li>
                                        <li class="nav-item"><a class="nav-link py-1.5 text-gray-500" data-bs-toggle="tab" href="#create_gallery_details">Details <span id="count_create_Details" class="badge badge-sm badge-circle badge-light-primary ms-1 d-none">0</span></a></li>
                                        <li class="nav-item"><a class="nav-link py-1.5 text-gray-500" data-bs-toggle="tab" href="#create_gallery_colorways">Colorways <span id="count_create_Colorways" class="badge badge-sm badge-circle badge-light-primary ms-1 d-none">0</span></a></li>
                                    </ul>

                                    <div class="tab-content">
                                        @foreach(['Front' => 'front', 'Back' => 'back', 'Details' => 'details', 'Colorways' => 'colorways'] as $typeName => $typeKey)
                                            <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="create_gallery_{{ $typeKey }}">
                                                <div id="preview_{{ $typeName }}" class="d-flex flex-wrap gap-2 mb-2 p-2 bg-light rounded border border-dashed min-h-120px align-items-center justify-content-center text-center">
                                                    <div class="text-muted fs-8 empty-prompt py-3">
                                                        <i class="fa-solid fa-cloud-arrow-up fs-2x text-primary mb-1 d-block"></i>
                                                        <span>Upload {{ $typeName }} view photos</span>
                                                    </div>
                                                </div>
                                                <input type="file" name="images[{{ $typeName }}][]" class="form-control form-control-sm form-control-solid border image-category-input" data-category="{{ $typeName }}" multiple accept="image/*" />
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                        </div>
                    </div>

                    <!-- 4. BOTTOM FOOTER ACTION BAR -->
                    <div class="card card-flush shadow-xs border-0 bg-white mt-4 mb-8">
                        <div class="card-body d-flex flex-wrap justify-content-between align-items-center py-3 px-5">
                            <div class="d-flex align-items-center gap-2">
                                <a href="{{ route('styles.index') }}" class="btn btn-sm btn-white border border-gray-300 text-gray-700 fw-semibold px-4">Cancel</a>
                                <button type="button" id="btn_save_draft" class="btn btn-sm btn-light-primary text-primary fw-semibold px-4">Save as Draft</button>
                            </div>

                            <div class="d-flex align-items-center gap-2">
                                <button type="submit" id="btn_save" class="btn btn-sm btn-primary fw-bold px-6">Save</button>
                                <button type="button" id="btn_save_next" class="btn btn-sm btn-primary fw-bold px-6 d-inline-flex align-items-center gap-2">
                                    Save & Next <i class="fa-solid fa-arrow-right fs-8"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>



    <!-- INTERACTIVE JAVASCRIPT LOGIC -->
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Live Header Update
            const styleNoInput = document.getElementById('input_style_no');
            const styleNameInput = document.getElementById('input_style_name');
            const clientSelect = document.getElementById('input_client_id');
            const seasonInput = document.getElementById('input_season');
            const programInput = document.getElementById('input_program');
            const categoryInput = document.getElementById('input_category');
            const sizesInput = document.getElementById('input_size_range');
            const htsInput = document.getElementById('input_hts_code');

            if (styleNoInput) {
                styleNoInput.addEventListener('input', function () {
                    const headerDisplay = document.getElementById('header_style_no_display');
                    if (headerDisplay) headerDisplay.textContent = this.value ? 'STYLE ' + this.value : 'CREATE NEW STYLE';
                });
            }

            if (styleNameInput) {
                styleNameInput.addEventListener('input', function () {
                    const nameDisplay = document.getElementById('header_style_name_display');
                    if (nameDisplay) nameDisplay.textContent = this.value || 'Enter style specifications below to create master record.';
                });
            }

            const updateSummaryField = (inputElem, summaryId, defaultText = 'Not Set') => {
                if (!inputElem) return;
                const handler = function () {
                    const elem = document.getElementById(summaryId);
                    if (!elem) return;
                    let val = inputElem.value;
                    if (inputElem.tagName === 'SELECT' && inputElem.selectedIndex >= 0) {
                        val = inputElem.options[inputElem.selectedIndex].text;
                        if (inputElem.value === '') val = defaultText;
                    }
                    elem.textContent = val || defaultText;
                };
                inputElem.addEventListener('change', handler);
                inputElem.addEventListener('input', handler);
                if (typeof $ !== 'undefined') {
                    $(inputElem).on('change', handler);
                }
            };

            if (clientSelect) {
                const handler = function () {
                    const selectedText = clientSelect.options[clientSelect.selectedIndex] ? clientSelect.options[clientSelect.selectedIndex].text : 'Not Selected';
                    const elem = document.getElementById('summary_customer');
                    if (elem) elem.textContent = clientSelect.value ? selectedText : 'Not Selected';
                };
                clientSelect.addEventListener('change', handler);
                if (typeof $ !== 'undefined') {
                    $(clientSelect).on('change', handler);
                }
            }

            updateSummaryField(seasonInput, 'summary_season', 'Not Set');
            updateSummaryField(programInput, 'summary_program', 'Not Set');
            updateSummaryField(categoryInput, 'summary_category', 'Not Set');
            updateSummaryField(sizesInput, 'summary_sizes', 'Not Set');
            updateSummaryField(htsInput, 'summary_hts', 'Not Set');

            // Live Character Counter Logic
            const packingTextarea = document.getElementById('packing_instructions_textarea');
            const packingCounter = document.getElementById('packing_counter');
            if (packingTextarea && packingCounter) {
                const updatePackingCounter = () => {
                    const remaining = Math.max(0, 300 - packingTextarea.value.length);
                    packingCounter.textContent = remaining;
                };
                packingTextarea.addEventListener('input', updatePackingCounter);
                updatePackingCounter();
            }

            const specialTextarea = document.getElementById('special_instructions_textarea');
            const specialCounter = document.getElementById('special_counter');
            if (specialTextarea && specialCounter) {
                const updateSpecialCounter = () => {
                    const remaining = Math.max(0, 300 - specialTextarea.value.length);
                    specialCounter.textContent = remaining;
                };
                specialTextarea.addEventListener('input', updateSpecialCounter);
                updateSpecialCounter();
            }

            // Interactive Tag Pills Logic
            const tagsContainer = document.getElementById('tags_container');
            const btnAddTag = document.getElementById('btn_add_tag');

            if (tagsContainer) {
                tagsContainer.addEventListener('click', function (e) {
                    if (e.target.classList.contains('remove-tag-btn')) {
                        const pill = e.target.closest('.tag-pill');
                        if (pill) pill.remove();
                    }
                });
            }

            if (btnAddTag) {
                btnAddTag.addEventListener('click', function () {
                    const tagName = prompt('Enter new tag name:');
                    if (tagName && tagName.trim() !== '') {
                        const sanitized = tagName.trim();
                        const newPill = document.createElement('span');
                        newPill.className = 'badge bg-white text-gray-800 border fw-semibold px-2.5 py-1.5 fs-8 d-inline-flex align-items-center tag-pill';
                        newPill.innerHTML = `${sanitized} <i class="fa-solid fa-xmark ms-2 cursor-pointer text-gray-400 hover-danger remove-tag-btn"></i><input type="hidden" name="tags[]" value="${sanitized}">`;
                        tagsContainer.insertBefore(newPill, btnAddTag);
                    }
                });
            }

            // Form Action Buttons Logic
            const form = document.getElementById('style_create_form');
            const statusInput = document.getElementById('form_status_input');
            const actionInput = document.getElementById('submit_action_input');

            const btnSaveDraft = document.getElementById('btn_save_draft');
            if (btnSaveDraft && form) {
                btnSaveDraft.addEventListener('click', function () {
                    statusInput.value = 'DRAFT';
                    actionInput.value = 'draft';
                    form.submit();
                });
            }

            const btnSaveNext = document.getElementById('btn_save_next');
            if (btnSaveNext && form) {
                btnSaveNext.addEventListener('click', function () {
                    actionInput.value = 'save_next';
                    form.submit();
                });
            }


            // Categorized Image Preview & Management Logic
            const categoryDataTransfers = {
                Front: new DataTransfer(),
                Back: new DataTransfer(),
                Details: new DataTransfer(),
                Colorways: new DataTransfer()
            };

            function renderCategoryPreviews(category) {
                const previewContainer = document.getElementById('preview_' + category);
                const countBadge = document.getElementById('count_create_' + category);
                const dt = categoryDataTransfers[category];
                if (!previewContainer) return;

                previewContainer.innerHTML = '';
                const files = dt.files;

                if (countBadge) {
                    if (files.length > 0) {
                        countBadge.textContent = files.length;
                        countBadge.classList.remove('d-none');
                    } else {
                        countBadge.classList.add('d-none');
                    }
                }

                if (files.length === 0) {
                    previewContainer.innerHTML = `
                        <div class="text-muted fs-8 empty-prompt py-3">
                            <i class="fa-solid fa-cloud-arrow-up fs-2x text-primary mb-1 d-block"></i>
                            <span>Upload ${category} view photos</span>
                        </div>`;
                    return;
                }

                Array.from(files).forEach((file, index) => {
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function (e) {
                            const card = document.createElement('div');
                            card.className = 'position-relative d-inline-block border rounded shadow-xs overflow-hidden style-image-card me-2 mb-2';
                            card.style.width = '105px';
                            card.style.height = '125px';
                            card.style.cursor = 'zoom-in';
                            card.title = 'Click to view large';
                            card.innerHTML = `
                                <img src="${e.target.result}" class="w-100 h-100 object-fit-cover btn-enlarge-image" alt="${category}" data-src="${e.target.result}" data-type="${category}">
                                <div class="image-zoom-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center bg-dark bg-opacity-25 opacity-0 transition-opacity pointer-events-none">
                                    <span class="badge bg-dark bg-opacity-75 text-white fs-9 py-1 px-1.5 rounded-pill shadow-sm">
                                        <i class="fa-solid fa-magnifying-glass-plus text-primary"></i>
                                    </span>
                                </div>
                                <span class="position-absolute bottom-0 start-0 w-100 bg-primary text-white fs-9 py-0.5 text-center fw-semibold pointer-events-none">New</span>
                                <button type="button" class="btn btn-icon btn-circle w-20px h-20px bg-white shadow position-absolute top-0 end-0 m-1 btn-remove-create-img" data-category="${category}" data-index="${index}" title="Remove file" style="z-index: 50;">
                                    <i class="fa-solid fa-xmark fs-9 text-danger pointer-events-none"></i>
                                </button>
                            `;

                            previewContainer.appendChild(card);
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }

            // File selection listener
            document.querySelectorAll('.image-category-input').forEach(function (input) {
                input.addEventListener('change', function () {
                    const category = this.getAttribute('data-category');
                    if (!categoryDataTransfers[category]) categoryDataTransfers[category] = new DataTransfer();

                    Array.from(this.files).forEach(f => {
                        categoryDataTransfers[category].items.add(f);
                    });

                    this.files = categoryDataTransfers[category].files;
                    renderCategoryPreviews(category);
                });
            });

            // Individual file delete/remove listener
            $(document).on('click', '.btn-remove-create-img', function (e) {
                e.preventDefault();
                e.stopPropagation();
                const cat = $(this).data('category');
                const fileIdx = parseInt($(this).data('index'), 10);

                const newDt = new DataTransfer();
                Array.from(categoryDataTransfers[cat].files).forEach((f, idx) => {
                    if (idx !== fileIdx) newDt.items.add(f);
                });
                categoryDataTransfers[cat] = newDt;

                const fileInput = document.querySelector(`.image-category-input[data-category="${cat}"]`);
                if (fileInput) fileInput.files = newDt.files;

                renderCategoryPreviews(cat);

                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'info',
                        title: 'Removed',
                        text: 'Selected file removed from preview.',
                        toast: true,
                        position: 'top-end',
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            });

            // Lightbox Modal Logic
            if ($('#imageViewerModal').length && $('#imageViewerModal').parent().prop('tagName') !== 'BODY') {
                $('#imageViewerModal').appendTo('body');
            }

            let galleryImages = [];
            let currentImageIndex = 0;

            function collectGalleryImages() {
                galleryImages = [];
                const activeTabPane = document.querySelector('.tab-pane[id^="create_gallery_"].active');
                let targets = [];
                if (activeTabPane) {
                    targets = activeTabPane.querySelectorAll('.btn-enlarge-image');
                }
                if (!targets || targets.length === 0) {
                    targets = document.querySelectorAll('.btn-enlarge-image');
                }

                targets.forEach((img) => {
                    galleryImages.push({
                        src: img.getAttribute('data-src') || img.src,
                        type: img.getAttribute('data-type') || 'Style'
                    });
                });
            }

            function showImageAtIndex(idx) {
                if (!galleryImages.length) return;
                if (idx < 0) idx = galleryImages.length - 1;
                if (idx >= galleryImages.length) idx = 0;
                currentImageIndex = idx;

                const item = galleryImages[currentImageIndex];
                const viewerImg = document.getElementById('viewerImage');
                const badge = document.getElementById('viewerCategoryBadge');
                const counter = document.getElementById('viewerImageCounter');
                const downloadBtn = document.getElementById('viewerDownloadBtn');

                if (viewerImg) {
                    viewerImg.src = item.src;
                    viewerImg.style.opacity = '1';
                }
                if (badge) badge.textContent = (item.type || 'Style') + ' View';
                if (counter) counter.textContent = `Image ${currentImageIndex + 1} of ${galleryImages.length}`;
                if (downloadBtn) {
                    downloadBtn.href = item.src;
                    downloadBtn.setAttribute('download', (item.type || 'style') + '_image.png');
                }

                const prevBtn = document.getElementById('viewerPrevBtn');
                const nextBtn = document.getElementById('viewerNextBtn');
                if (galleryImages.length <= 1) {
                    if (prevBtn) prevBtn.style.display = 'none';
                    if (nextBtn) nextBtn.style.display = 'none';
                } else {
                    if (prevBtn) prevBtn.style.display = 'flex';
                    if (nextBtn) nextBtn.style.display = 'flex';
                }
            }

            $(document).on('click', '.style-image-card, .btn-enlarge-image', function (e) {
                if ($(e.target).closest('.btn-remove-create-img').length) {
                    return; // Ignore delete button clicks
                }

                collectGalleryImages();
                let clickedImg = $(this).is('.btn-enlarge-image') ? $(this) : $(this).find('.btn-enlarge-image');
                const clickedSrc = clickedImg.attr('data-src') || clickedImg.attr('src');

                const foundIdx = galleryImages.findIndex(i => i.src === clickedSrc);
                currentImageIndex = foundIdx >= 0 ? foundIdx : 0;

                showImageAtIndex(currentImageIndex);

                const viewerModalEl = document.getElementById('imageViewerModal');
                if (viewerModalEl && typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                    bootstrap.Modal.getOrCreateInstance(viewerModalEl).show();
                } else {
                    $('#imageViewerModal').modal('show');
                }
            });

            $(document).on('click', '#viewerPrevBtn', function (e) {
                e.stopPropagation();
                showImageAtIndex(currentImageIndex - 1);
            });

            $(document).on('click', '#viewerNextBtn', function (e) {
                e.stopPropagation();
                showImageAtIndex(currentImageIndex + 1);
            });

            $(document).on('keydown', function (e) {
                if ($('#imageViewerModal').hasClass('show')) {
                    if (e.key === 'ArrowLeft') {
                        showImageAtIndex(currentImageIndex - 1);
                    } else if (e.key === 'ArrowRight') {
                        showImageAtIndex(currentImageIndex + 1);
                    }
                }
            });
        });
    </script>
    @endpush

    <!-- Reusable Image Viewer Modal -->
    @include('styles.partials.image-viewer-modal')

    <style>
        .style-image-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .style-image-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.12) !important;
        }
        .style-image-card:hover .image-zoom-overlay {
            opacity: 1 !important;
        }
        .style-image-card .btn-enlarge-image {
            transition: transform 0.25s ease;
        }
        .style-image-card:hover .btn-enlarge-image {
            transform: scale(1.05);
        }
    </style>
</x-layouts.app>
