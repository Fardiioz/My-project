<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

/**
 * Controller untuk mengelola operasi CRUD produk
 * Extends Controller untuk menggunakan fitur Laravel Controller
 */
class ProductController extends Controller
{
    /**
     * Menampilkan daftar semua produk
     * GET /products - route untuk halaman daftar produk
     * Menggunakan pagination untuk performa yang lebih baik
     */
    public function index()
    {
        // Mengambil semua produk dengan pagination 12 per halaman
        $products = Product::paginate(12);

        // Mengirim data products ke view products.index
        return view('products.index', compact('products'));
    }

    /**
     * Menampilkan detail produk berdasarkan ID
     * GET /products/{product} - route untuk halaman detail produk
     * Menggunakan route model binding untuk otomatis resolve Product model
     */
    public function show(Product $product)
    {
        // $product sudah di-resolve otomatis oleh Laravel melalui route model binding
        // Mengirim data product ke view products.show
        return view('products.show', compact('product'));
    }

    /**
     * Menampilkan form untuk membuat produk baru
     * GET /products/create - route untuk halaman form create produk
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Menyimpan produk baru ke database
     * POST /products - route untuk menyimpan produk baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'harga_produk' => 'required|integer|min:0',
            'jenis_produk' => 'required|in:Alat Elektronik,Furniture,Handphone,Kids Toys,Fashion,Book',
        ]);

        Product::create($request->all());

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    /**
     * Menampilkan form untuk mengedit produk
     * GET /products/{product}/edit - route untuk halaman form edit produk
     */
    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    /**
     * Mengupdate produk di database
     * PUT /products/{product} - route untuk update produk
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'harga_produk' => 'required|integer|min:0',
            'jenis_produk' => 'required|in:Alat Elektronik,Furniture,Handphone,Kids Toys,Fashion,Book',
        ]);

        $product->update($request->all());

        return redirect()->route('products.index')->with('success', 'Produk berhasil diupdate!');
    }

    /**
     * Menghapus produk dari database
     * DELETE /products/{product} - route untuk hapus produk
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus!');
    }

    // API Methods untuk integrasi dengan Nuxt.js

    /**
     * API: Mengambil semua produk
     * GET /api/products
     */
    public function apiIndex()
    {
        $products = Product::all();
        return response()->json($products);
    }

    /**
     * API: Mengambil detail produk berdasarkan ID
     * GET /api/products/{product}
     */
    public function apiShow(Product $product)
    {
        return response()->json($product);
    }

    /**
     * API: Menyimpan produk baru
     * POST /api/products
     */
    public function apiStore(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'harga_produk' => 'required|integer|min:0',
            'jenis_produk' => 'required|in:Alat Elektronik,Furniture,Handphone,Kids Toys,Fashion,Book',
        ]);

        $product = Product::create($request->all());

        return response()->json($product, 201);
    }

    /**
     * API: Mengupdate produk
     * PUT /api/products/{product}
     */
    public function apiUpdate(Request $request, Product $product)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'harga_produk' => 'required|integer|min:0',
            'jenis_produk' => 'required|in:Alat Elektronik,Furniture,Handphone,Kids Toys,Fashion,Book',
        ]);

        $product->update($request->all());

        return response()->json($product);
    }

    /**
     * API: Menghapus produk
     * DELETE /api/products/{product}
     */
    public function apiDestroy(Product $product)
    {
        $product->delete();

        return response()->json(['message' => 'Produk berhasil dihapus!']);
    }
}
