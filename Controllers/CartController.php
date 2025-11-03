<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

/**
 * Controller untuk mengelola keranjang belanja
 * Menggunakan session Laravel untuk menyimpan data keranjang
 * Tidak memerlukan autentikasi user
 */
class CartController extends Controller
{
    /**
     * Menampilkan isi keranjang belanja
     * GET /cart - route untuk halaman keranjang
     * Mengambil data dari session dan menghitung total
     */
    public function index()
    {
        // Mengambil data keranjang dari session menggunakan helper session()
        $cart = session()->get('cart', []);

        // Array untuk menyimpan item keranjang dengan detail lengkap
        $cartItems = [];

        // Loop melalui setiap item di keranjang (format: product_id => ['quantity', 'price'])
        foreach ($cart as $productId => $item) {
            // Cari produk berdasarkan ID untuk mendapatkan data terbaru
            $product = Product::find($productId);

            // Jika produk masih ada, tambahkan ke cartItems
            if ($product) {
                $cartItems[] = [
                    'product' => $product,                    // Data produk lengkap dari database
                    'quantity' => $item['quantity'],          // Jumlah yang dipesan
                    'price' => $item['price'],                // Harga snapshot saat ditambahkan
                    'subtotal' => $item['price'] * $item['quantity'] // Hitung subtotal
                ];
            }
        }

        // Hitung total keseluruhan dari semua item
        $total = array_sum(array_column($cartItems, 'subtotal'));

        // Kirim data ke view cart.index
        return view('cart.index', compact('cartItems', 'total'));
    }

    /**
     * Menambahkan produk ke keranjang
     * POST /cart/add/{product} - route untuk menambah item
     * Validasi quantity tidak boleh melebihi stok
     */
    public function add(Request $request, Product $product)
    {
        // Validasi input: quantity harus integer, min 1, max sesuai stok produk
        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $product->stock,
        ]);

        // Ambil keranjang dari session
        $cart = session()->get('cart', []);

        // Jika produk sudah ada di keranjang, tambahkan quantity
        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $request->quantity;
        } else {
            // Jika belum ada, buat entry baru dengan quantity dan harga snapshot
            $cart[$product->id] = [
                'quantity' => $request->quantity,
                'price' => $product->price, // Snapshot harga saat ditambahkan
            ];
        }

        // Simpan kembali ke session
        session()->put('cart', $cart);

        // Redirect kembali dengan pesan sukses
        return redirect()->back()->with('success', 'Product added to cart!');
    }

    /**
     * Memperbarui quantity produk di keranjang
     * PUT /cart/update/{product} - route untuk update quantity
     * Validasi quantity tidak boleh melebihi stok
     */
    public function update(Request $request, Product $product)
    {
        // Validasi input quantity
        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $product->stock,
        ]);

        // Ambil keranjang dari session
        $cart = session()->get('cart', []);

        // Jika produk ada di keranjang, update quantity-nya
        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
        }

        // Redirect dengan pesan sukses
        return redirect()->back()->with('success', 'Cart updated!');
    }

    /**
     * Menghapus produk dari keranjang
     * DELETE /cart/remove/{product} - route untuk menghapus item
     */
    public function remove(Product $product)
    {
        // Ambil keranjang dari session
        $cart = session()->get('cart', []);

        // Jika produk ada di keranjang, hapus entry-nya
        if (isset($cart[$product->id])) {
            unset($cart[$product->id]);
            session()->put('cart', $cart);
        }

        // Redirect dengan pesan sukses
        return redirect()->back()->with('success', 'Product removed from cart!');
    }

    /**
     * Mengosongkan seluruh keranjang
     * POST /cart/clear - route untuk clear cart
     * Menggunakan session()->forget() untuk menghapus key 'cart'
     */
    public function clear()
    {
        // Hapus seluruh data keranjang dari session
        session()->forget('cart');

        // Redirect dengan pesan sukses
        return redirect()->back()->with('success', 'Cart cleared!');
    }
}
