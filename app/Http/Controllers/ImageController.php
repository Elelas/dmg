<?php

namespace App\Http\Controllers;

use App\Helpers\ImageFile;
use App\Http\Requests;
use Carbon\Carbon;
use Dmg\Helpers\Path;
use Dmg\Image\Image;
use Dmg\Managers\StatisticsManager;
use Dmg\Tests\Test;
use Illuminate\Http\Request;
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

    /**
     * Удаление всех файлов
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * Формирование статистики для всех файлов
     * @return \Illuminate\Http\RedirectResponse
     */
    public function massStatistics()
    {
        try {
            $start = Carbon::now();
            //Получение всех цветов из карты
            $colorMap = Test::testColorMap();
            $statManager = new StatisticsManager($colorMap);
            $colorMapStatistics = $statManager->colorsFrequency(22);
            $commonStat = array_fill_keys(array_keys($colorMapStatistics), 0);

            //только картинки
            $images = ImageFile::onlyImageFile(Storage::disk('images')->allFiles());

            //подсчет статистики
            foreach ($images as $imageFile) {
                $statManager = new StatisticsManager(Path::images($imageFile));
                $imageStat = $statManager->colorsFrequency(120, $colorMap);

                foreach ($imageStat as $color => $value) {
                    $commonStat[ $color ] += $value;
                }
            }

            //записываем в файл
            $fileName = preg_replace('@[ ]@', '_', Carbon::now()->toDateTimeString())
                . "-files_count-" . count($images);
            foreach ($commonStat as $color => $value) {
                Storage::disk('statistics')->append($fileName, $color . ' ' . $value);
            }

            //отправляем ответ
            $message = 'Обработано ' . count($images) . ' файлов. Затрачено времени: '
                . Carbon::now()->diffInSeconds($start) . ' сек';

            \Alert::success($message);

            return redirect()->back();
        } catch (\Exception $e) {
            \Alert::error($e->getMessage());

            return redirect()->back();
        }
    }

    /**
     * Выводит общую статистику
     * @param Request $request
     * @param null $fileName
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function massStatisticsResult(Request $request, $fileName = null)
    {
        if (!$fileName) {
            $message = 'Нужно указать название файла статистики';
            if ($request->ajax()) {
                return response()->json($message, 400);
            }
            \Alert::error($message);

            return redirect()->back();
        }
        $colors = array_map(function ($line) {
            return explode(' ', trim($line));
        }, file(storage_path('app/images_statistics/' . $fileName)));

        return view('includes.mass_stat_result', [
            'colors' => $colors,
        ])->render();
    }

    /**
     * Уменьшение изображения
     * @param Request $request
     * @param $file
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function reduceImage(Request $request, $file)
    {
        if (!$request->ajax()) {
            return response('', 404);
        }
        if (!Storage::disk('images')->exists($file)) {
            $message = 'Файла ' . $file . ' не существует';

            return response()->json($message, 400);
        }

        try {
            $image = new Image(Path::images($file));
            $path = $image->reduce($request->get('boxAmount'))->save(Path::results('reduced-' . $file));

            return response()->json('/images_after_edit/reduced-' . $file);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    public function remapImage(Request $request, $file)
    {
        $colorMap = Test::testColorMap();
        if ($request->hasFile('colorMap')) {
            $colorMap = $request->file('colorMap')->getPathname();
        }

        try {
            $image = new Image(Path::images($file));
            $fileName = 'remap-' . $file;
            $image->remap($colorMap)->save(Path::results($fileName));

            return response()->json('/images_after_edit/' . $fileName);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }
}
