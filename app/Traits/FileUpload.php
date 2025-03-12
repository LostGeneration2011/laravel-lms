<?php

namespace App\Traits;

use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait FileUpload {

    public function uploadFile(UploadedFile $file, string $directory = 'uploads'): string {
        try {
            $filename = 'educore_' . uniqid() . '.' . $file->getClientOriginalExtension();

            // Lưu file vào disk 'public' để có thể truy xuất qua storage link
            $path = $file->storeAs($directory, $filename, 'public');

            return 'storage/' . $path; // Đường dẫn đúng để truy cập file từ trình duyệt
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function deleteFile(?string $path): bool {
        if (!$path) return false;

        // Chuyển từ đường dẫn public thành đường dẫn storage thực tế
        $storagePath = str_replace('storage/', '', $path);

        if (Storage::disk('public')->exists($storagePath)) {
            return Storage::disk('public')->delete($storagePath);
        }
        return false;
    }
}
