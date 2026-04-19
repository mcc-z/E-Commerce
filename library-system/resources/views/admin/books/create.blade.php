@extends('layouts.app')
@section('title', 'Add New Book')
@section('page-title', 'Add New Book')

@section('content')
<div style="max-width:780px;">
    <div class="card">
        <div class="card-header">
            <span class="card-title">Book Information</span>
            <a href="{{ route('admin.books.index') }}" class="btn btn-outline btn-sm">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.books.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div style="font-size:12px;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-muted);font-weight:600;margin-bottom:14px;">Basic Info</div>
                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Title *</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Author *</label>
                        <input type="text" name="author" class="form-control" value="{{ old('author') }}" required>
                    </div>
                </div>
                <div class="form-grid-3">
                    <div class="form-group">
                        <label class="form-label">ISBN *</label>
                        <input type="text" name="isbn" class="form-control" value="{{ old('isbn') }}" placeholder="978-0-00-000000-0" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Publisher</label>
                        <input type="text" name="publisher" class="form-control" value="{{ old('publisher') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Published Year</label>
                        <input type="number" name="published_year" class="form-control" value="{{ old('published_year') }}" min="1000" max="{{ date('Y') }}" placeholder="{{ date('Y') }}">
                    </div>
                </div>

                <div class="form-grid-2" style="margin-top:4px;">
                    <div class="form-group">
                        <label class="form-label">Category *</label>
                        <select name="category_id" class="form-control" style="padding:10px 14px;" required>
                            <option value="">Select category...</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Language</label>
                        <input type="text" name="language" class="form-control" value="{{ old('language', 'English') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3" placeholder="Brief description of the book...">{{ old('description') }}</textarea>
                </div>

                <div style="border-top:1px solid var(--cream-2);padding-top:20px;margin:20px 0 16px;font-size:12px;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-muted);font-weight:600;">Inventory & Location</div>

                <div class="form-grid-3">
                    <div class="form-group">
                        <label class="form-label">Total Copies *</label>
                        <input type="number" name="total_copies" class="form-control" value="{{ old('total_copies', 1) }}" min="1" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Shelf Location</label>
                        <input type="text" name="location" class="form-control" value="{{ old('location') }}" placeholder="e.g. A-12">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Pages</label>
                        <input type="number" name="pages" class="form-control" value="{{ old('pages') }}" min="1">
                    </div>
                </div>

                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Replacement Cost ($)</label>
                        <input type="number" name="replacement_cost" class="form-control" value="{{ old('replacement_cost', 25.00) }}" step="0.01" min="0">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status *</label>
                        <select name="status" class="form-control" style="padding:10px 14px;" required>
                            <option value="available" {{ old('status','available') == 'available' ? 'selected' : '' }}>Available</option>
                            <option value="unavailable" {{ old('status') == 'unavailable' ? 'selected' : '' }}>Unavailable</option>
                        </select>
                    </div>
                </div>

                <div style="border-top:1px solid var(--cream-2);padding-top:20px;margin:20px 0 16px;font-size:12px;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-muted);font-weight:600;">Cover Image</div>
                <div class="form-group">
                    <label class="btn btn-outline" style="cursor:pointer;display:inline-flex;align-items:center;gap:8px;">
                        <i class="fas fa-image"></i> Upload Cover Image
                        <input type="file" name="cover_image" accept="image/*" style="display:none;" onchange="previewCover(this)">
                    </label>
                    <p style="font-size:12px;color:var(--text-muted);margin-top:6px;">JPG, PNG or WebP. Max 3MB.</p>
                    <img id="coverPreview" src="" style="display:none;margin-top:10px;width:80px;height:110px;object-fit:cover;border-radius:6px;border:1px solid var(--cream-2);">
                </div>

                <div style="display:flex;gap:12px;margin-top:8px;">
                    <button type="submit" class="btn btn-gold">
                        <i class="fas fa-plus"></i> Add Book
                    </button>
                    <a href="{{ route('admin.books.index') }}" class="btn btn-outline">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function previewCover(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            const img = document.getElementById('coverPreview');
            img.src = e.target.result;
            img.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
@endsection
