<!--  IMAGE VIEWER MODAL -->
<div class="modal fade" id="imageViewerModal" tabindex="-1" aria-hidden="true" style="z-index: 99999; background: rgba(10, 12, 18, 0.90); backdrop-filter: blur(6px);">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content border-0 shadow-lg" style="background: rgba(22, 24, 35, 0.96); border-radius: 12px; overflow: hidden;">
            <!-- Top Controls Header -->
            <div class="d-flex justify-content-between align-items-center px-4 py-3 border-bottom border-secondary border-opacity-25">
                <div class="d-flex align-items-center gap-3">
                    <span id="viewerCategoryBadge" class="badge bg-primary text-white fw-bold fs-7 px-3 py-1.5 text-uppercase tracking-wider">
                        Front View
                    </span>
                    <span id="viewerImageCounter" class="text-white-50 fs-8 fw-semibold">
                        Image 1 of 1
                    </span>
                    @if(isset($style) && $style->style_no)
                        <span class="text-white-50 fs-9 d-none d-md-inline">
                            — Style {{ $style->style_no }}
                        </span>
                    @endif
                </div>
                <div class="d-flex align-items-center gap-2">
                    <a id="viewerDownloadBtn" href="#" download class="btn btn-sm btn-icon text-white bg-white bg-opacity-10 hover-bg-opacity-25 rounded-circle" title="Download Image">
                        <i class="fa-solid fa-download fs-6 text-white"></i>
                    </a>
                    <button type="button" class="btn btn-sm btn-icon text-white bg-white bg-opacity-10 hover-bg-opacity-25 rounded-circle" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fa-solid fa-xmark fs-5 text-white"></i>
                    </button>
                </div>
            </div>

            <!-- Modal Body with Enlarged Image and Prev/Next Navigation -->
            <div class="modal-body position-relative p-0 d-flex align-items-center justify-content-center" style="min-height: 520px; background: rgba(0,0,0,0.5);">
                <!-- Prev Button -->
                <button type="button" id="viewerPrevBtn" class="btn btn-icon btn-circle bg-dark bg-opacity-50 hover-bg-opacity-75 text-white position-absolute start-0 top-50 translate-middle-y ms-3 shadow border border-white border-opacity-10" style="width: 48px; height: 48px; z-index: 10;" title="Previous Image (Left Arrow)">
                    <i class="fa-solid fa-chevron-left fs-4"></i>
                </button>

                <!-- Enlarged Image Container -->
                <div class="text-center p-3 w-100">
                    <img id="viewerImage" src="" alt="Enlarged View" class="img-fluid rounded shadow" style="max-height: 75vh; max-width: 92%; object-fit: contain; transition: opacity 0.15s ease-in-out;">
                </div>

                <!-- Next Button -->
                <button type="button" id="viewerNextBtn" class="btn btn-icon btn-circle bg-dark bg-opacity-50 hover-bg-opacity-75 text-white position-absolute end-0 top-50 translate-middle-y me-3 shadow border border-white border-opacity-10" style="width: 48px; height: 48px; z-index: 10;" title="Next Image (Right Arrow)">
                    <i class="fa-solid fa-chevron-right fs-4"></i>
                </button>
            </div>

            <!-- Footer Hints -->
            <div class="d-flex justify-content-between align-items-center px-4 py-2 border-top border-secondary border-opacity-25 text-white-50 fs-9">
                <span>
                    <i class="fa-solid fa-info-circle me-1 text-primary"></i> Click outside or press <kbd class="bg-secondary text-dark px-1 py-0.5 rounded">ESC</kbd> to exit.
                </span>
                <span class="d-none d-sm-inline">
                    Use <kbd class="bg-secondary text-dark px-1 py-0.5 rounded">←</kbd> and <kbd class="bg-secondary text-dark px-1 py-0.5 rounded">→</kbd> to navigate between images.
                </span>
            </div>
        </div>
    </div>
</div>
