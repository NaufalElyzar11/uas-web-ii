# Panduan Penambahan Galeri Wisata

Untuk menambahkan galeri foto untuk setiap destinasi wisata, ikuti langkah berikut:

1. Buat folder sesuai ID destinasi wisata di directory: `/public/uploads/wisata/gallery/{id}/`
   Contoh: `/public/uploads/wisata/gallery/1/` untuk destinasi dengan ID 1

2. Upload gambar-gambar dengan format .jpg, .jpeg, .png, atau .gif ke dalam folder tersebut

3. Gambar-gambar akan otomatis muncul di halaman detail destinasi

## Struktur Direktori

```
uploads/
  wisata/
    default.jpg  (gambar default jika tidak ada gambar lain)
    [gambar_wisata]  (gambar utama destinasi)
    gallery/
      1/  (untuk destinasi ID 1)
        image1.jpg
        image2.jpg
      2/  (untuk destinasi ID 2)
        image1.jpg
        ...
```

## Catatan

- Gambar utama destinasi diatur melalui field `gambar_wisata` di database
- Jika field `link_video` diisi dengan URL video (contoh: YouTube embed URL), video akan ditampilkan sebagai media utama
- Jika tidak ada video atau gambar galeri, gambar utama dari database akan ditampilkan
