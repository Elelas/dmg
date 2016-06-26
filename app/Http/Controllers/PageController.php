<?php

namespace App\Http\Controllers;

use App\Helpers\ImageFile;
use App\Http\Requests;
use Dmg\Helpers\Path;

class PageController extends Controller
{
    /**
     * Страница массовых операций
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function massOperations()
    {
        $files = ImageFile::onlyImageFile(\Storage::disk('images')->allFiles());
        $statisticsFiles = \Storage::disk('statistics')->allFiles();
        return view('pages.mass_operations', [
            'files' => $files,
            'statFiles' => $statisticsFiles
        ]);
    }

    /**
     * Вывод всех изображений
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $images = ImageFile::onlyImageFile(\Storage::disk('images')->allFiles());
        return view('pages.index', [
            'images' => $images
        ]);
    }

    public function show(\Request $request, $file = null)
    {
        if (!$file) {
            $message = 'Необходимо ввести название файла';
            if ($request->ajax()) {

                return response()->json($message);
            }
            \Alert::error($message);

            return redirect()->route('image.index');
        }

        $imagick = new \Imagick(Path::images($file));

        return view('pages.show', [
            'fileName' => $file,
            'image' => $imagick,
        ]);
    }
}
