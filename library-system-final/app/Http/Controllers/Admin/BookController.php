<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BookController extends Controller
{
    public function cover(string $filename): StreamedResponse
    {
        $path = 'covers/' . basename($filename);
        abort_unless(Storage::disk('public')->exists($path), 404);

        return Storage::disk('public')->response($path, headers: [
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }

    public function index(Request $request)
    {
        $categories = Category::all();

        $books = Book::with('category')
            ->when($request->search, fn($q) => $q->search($request->search))
            ->when($request->category, fn($q) => $q->where('category_id', $request->category))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->orderBy($request->sort ?? 'title')
            ->paginate(15)
            ->withQueryString();

        return view('admin.books.index', compact('books', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.books.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'isbn'             => 'required|string|max:20|unique:books',
            'title'            => 'required|string|max:255',
            'author'           => 'required|string|max:255',
            'publisher'        => 'nullable|string|max:255',
            'published_year'   => 'nullable|integer|min:1000|max:' . date('Y'),
            'category_id'      => 'required|exists:categories,id',
            'description'      => 'nullable|string',
            'cover_image'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
            'total_copies'     => 'required|integer|min:1',
            'location'         => 'nullable|string|max:50',
            'language'         => 'nullable|string|max:50',
            'pages'            => 'nullable|integer|min:1',
            'replacement_cost' => 'nullable|numeric|min:0',
            'status'           => 'required|in:available,unavailable,lost',
        ]);

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        $data['available_copies'] = $data['total_copies'];

        $book = Book::create($data);

        ActivityLog::log('create_book', "Added new book: {$book->title} (ISBN: {$book->isbn})", $book);

        return redirect()->route('admin.books.index')->with('success', "Book \"{$book->title}\" added successfully!");
    }

    public function edit(Book $book)
    {
        $categories = Category::all();
        return view('admin.books.edit', compact('book', 'categories'));
    }

    public function update(Request $request, Book $book)
    {
        $data = $request->validate([
            'isbn'             => 'required|string|max:20|unique:books,isbn,' . $book->id,
            'title'            => 'required|string|max:255',
            'author'           => 'required|string|max:255',
            'publisher'        => 'nullable|string|max:255',
            'published_year'   => 'nullable|integer|min:1000|max:' . date('Y'),
            'category_id'      => 'required|exists:categories,id',
            'description'      => 'nullable|string',
            'cover_image'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
            'total_copies'     => 'required|integer|min:' . ($book->total_copies - $book->available_copies),
            'location'         => 'nullable|string|max:50',
            'language'         => 'nullable|string|max:50',
            'pages'            => 'nullable|integer|min:1',
            'replacement_cost' => 'nullable|numeric|min:0',
            'status'           => 'required|in:available,unavailable,lost',
        ]);

        if ($request->hasFile('cover_image')) {
            if ($book->cover_image) Storage::disk('public')->delete($book->cover_image);
            $data['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        // Adjust available_copies if total changed
        $diff = $data['total_copies'] - $book->total_copies;
        $data['available_copies'] = max(0, $book->available_copies + $diff);

        $book->update($data);

        ActivityLog::log('update_book', "Updated book: {$book->title}", $book);

        return redirect()->route('admin.books.index')->with('success', "Book updated successfully!");
    }

    public function destroy(Book $book)
    {
        if ($book->activeBorrows()->count() > 0) {
            return back()->with('error', 'Cannot delete a book with active borrows.');
        }

        if ($book->cover_image) Storage::disk('public')->delete($book->cover_image);

        ActivityLog::log('delete_book', "Deleted book: {$book->title} (ISBN: {$book->isbn})");
        $book->delete();

        return redirect()->route('admin.books.index')->with('success', 'Book deleted successfully.');
    }

    // =============================================
    // CATEGORY MANAGEMENT
    // =============================================
    public function categories()
    {
        $categories = Category::withCount('books')->orderBy('name')->get();
        return view('admin.books.categories', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:100|unique:categories',
            'description' => 'nullable|string',
            'color'       => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);
        $data['slug'] = \Illuminate\Support\Str::slug($data['name']);
        Category::create($data);
        return back()->with('success', 'Category created!');
    }

    public function updateCategory(Request $request, Category $category)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:100|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'color'       => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);
        $data['slug'] = \Illuminate\Support\Str::slug($data['name']);
        $category->update($data);
        return back()->with('success', 'Category updated!');
    }

    public function destroyCategory(Category $category)
    {
        if ($category->books()->count() > 0) {
            return back()->with('error', 'Cannot delete a category that has books.');
        }
        $category->delete();
        return back()->with('success', 'Category deleted.');
    }
}
