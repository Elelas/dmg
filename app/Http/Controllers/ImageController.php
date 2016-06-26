<?php

namespace App\Http\Controllers;

use Cartalyst\Alerts\Laravel\Facades\Alert;
use Illuminate\Http\Request;

use App\Http\Requests;
use Storage;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageController extends Controller
{
    /**
     * Удаление файла
     * @param Request $request
     * @param null $file
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request, $file = null)
    {
        if (!$file and $request->ajax()) {
            return response()->json('Необходимо указать название файла', 400);
        }

        Storage::disk('images')->delete($file);

        return response()->json('ok');
    }

    /**
     * Добавление файлов
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function add(Request $request)
    {
        $mimes = [
            'image/gif',
            'image/jpeg',
            'image/pjpeg',
            'image/png',
            'image/tiff',
        ];

        $filteredFiles = array_filter($request->file('image'), function ($file) use ($mimes) {
            return in_array($file->getClientMimeType(), $mimes);
        });

        /** @var UploadedFile $filteredFile */
        foreach ($filteredFiles as $filteredFile) {
            $filteredFile->move(public_path('images'), $filteredFile->getClientOriginalName());
        }

        $errorsFiles = array_diff($request->file('image'), $filteredFiles);
        if (!empty($errorsFiles)) {
            \Alert::danger(
                '<span class="badge">' . count($errorsFiles) . '</span>'
                . " файлов отброшено, так как mime типы не совпали с типами картинок"
            );
        }

        return redirect()->route('image.index');
    }

    public function massDelete()
    {
        try {
            $files = Storage::disk('images')->allFiles();
            foreach ($files as $file) {
                Storage::disk('images')->delete($file);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(), 'filesAmount' => count(Storage::disk('images')->allFiles()),
            ], 400);
        }

        return response()->json(count(Storage::disk('images')->allFiles()));
    }
}
