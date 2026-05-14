<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebsiteStyle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\View;

class WebsiteStyleController extends Controller
{
    public function editImages(): View
    {
        return view('admin.settings.style-images', [
            'style' => WebsiteStyle::singleton(),
        ]);
    }

    public function updateImages(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'map_background_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'map_background_opacity' => ['required', 'integer', 'min:0', 'max:100'],
            'map_background_position_x' => ['required', 'integer', 'min:0', 'max:100'],
            'map_background_position_y' => ['required', 'integer', 'min:0', 'max:100'],
            'map_background_size' => ['required', 'integer', 'min:40', 'max:180'],
            'remove_map_background_image' => ['nullable', 'boolean'],
            'brand_logo_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'brand_logo_opacity' => ['required', 'integer', 'min:0', 'max:100'],
            'brand_logo_position_x' => ['required', 'integer', 'min:0', 'max:100'],
            'brand_logo_position_y' => ['required', 'integer', 'min:0', 'max:100'],
            'brand_logo_size' => ['required', 'integer', 'min:40', 'max:180'],
            'remove_brand_logo_image' => ['nullable', 'boolean'],
        ]);

        $style = WebsiteStyle::singleton();

        if ($request->boolean('remove_map_background_image')) {
            $this->deleteManagedFile($style->map_background_image_path);
            $style->map_background_image_path = null;
        }

        if ($request->boolean('remove_brand_logo_image')) {
            $this->deleteManagedFile($style->brand_logo_image_path);
            $style->brand_logo_image_path = null;
        }

        if ($request->hasFile('map_background_image')) {
            $style->map_background_image_path = $this->replaceImage(
                $request->file('map_background_image'),
                $style->map_background_image_path,
            );
        }

        if ($request->hasFile('brand_logo_image')) {
            $style->brand_logo_image_path = $this->replaceImage(
                $request->file('brand_logo_image'),
                $style->brand_logo_image_path,
            );
        }

        $style->map_background_opacity = (int) $validated['map_background_opacity'];
        $style->map_background_position_x = (int) $validated['map_background_position_x'];
        $style->map_background_position_y = (int) $validated['map_background_position_y'];
        $style->map_background_size = (int) $validated['map_background_size'];
        $style->brand_logo_opacity = (int) $validated['brand_logo_opacity'];
        $style->brand_logo_position_x = (int) $validated['brand_logo_position_x'];
        $style->brand_logo_position_y = (int) $validated['brand_logo_position_y'];
        $style->brand_logo_size = (int) $validated['brand_logo_size'];
        $style->save();

        return redirect('/admin/settings/style/images')
            ->with('status', 'Style gambar berhasil diperbarui.');
    }

    public function editColors(): View
    {
        return view('admin.settings.style-colors');
    }

    private function replaceImage(UploadedFile $file, ?string $existingPath): string
    {
        $directory = public_path('uploads/website-style');
        File::ensureDirectoryExists($directory);

        $filename = Str::uuid()->toString() . '.' . $file->extension();
        $file->move($directory, $filename);

        $this->deleteManagedFile($existingPath);

        return 'uploads/website-style/' . $filename;
    }

    private function deleteManagedFile(?string $path): void
    {
        if (blank($path)) {
            return;
        }

        $normalizedPath = str_replace('\\', '/', (string) $path);
        if (! str_starts_with($normalizedPath, 'uploads/website-style/')) {
            return;
        }

        $fullPath = public_path($normalizedPath);
        if (File::exists($fullPath) && File::isFile($fullPath)) {
            File::delete($fullPath);
        }
    }
}