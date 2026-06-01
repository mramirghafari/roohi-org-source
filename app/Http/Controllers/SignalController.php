<?php

namespace App\Http\Controllers;

use App\Models\Subscribe;
use Illuminate\Http\Request;
use App\Models\Signal;
use App\Models\SignalSmsJob;
use App\Models\ArchiveNotif;
use App\Jobs\SendArchivedNotificationsJob;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\Drivers\Imagick\Driver as ImagickDriver;
use Throwable;


class SignalController extends Controller
{
    private const SIGNAL_DELETE_MOBILE = '09396206108';
    private const SIGNAL_PHOTO_EXTENSIONS = ['jpg', 'jpeg', 'png', 'webp', 'heic', 'heif'];

    public function signalPhoto(string $photo)
    {
        abort_unless((bool) preg_match('/^[a-f0-9-]+\.webp$/i', $photo), 404);

        foreach ($this->signalPhotoPaths($photo) as $path) {
            if (is_file($path)) {
                return response()->file($path, [
                    'Cache-Control' => 'public, max-age=31536000, immutable',
                ]);
            }
        }

        abort(404);
    }

    public function NewSignalProcess(Request $request) {

        //dd($request->all());
        $this->validateSignalPhoto($request);

        /// Create Signal
        $user = \Auth::user();
        $data = $request->except('signal_photo');
        $data['user_id'] = $user->id;
        $rand = rand(111111,999999);
        $CheckSignal = Signal::where('tracking_code',$rand)->count();
        if ($CheckSignal == 0) {
            $data['tracking_code'] = $rand;
        }else {
            $data['tracking_code'] = rand(111111,999999);
        }

        if($request->hasFile('signal_photo')) {
            $data['photo'] = $this->storeSignalPhoto($request->file('signal_photo'));
        }

        //dd($request['photo']);

       // dd($request->all());

        $Signal = Signal::create($data);

        $this->queueNewSignalNotif($Signal, $user->id);

        if ((int)$request->sms === 1) {

            $signalId = $Signal->id;
            $now = now();

            Subscribe::query()
                ->where('status', 1)          // فقط فعال‌ها
                ->select(['id', 'user_id'])   // id برای chunkById
                ->orderBy('id')
                ->chunkById(500, function ($subs) use ($signalId, $now) {

                    $rows = [];

                    foreach ($subs as $sub) {
                        $rows[] = [
                            'signal_id' => $signalId,
                            'user_id' => (int)$sub->user_id,
                            'status' => 0,
                            'attempts' => 0,
                            'scheduled_at' => $now,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                    }

                    // با unique(signal_id,user_id) + upsert دوباره‌کاری هم امنه
                    SignalSmsJob::upsert(
                        $rows,
                        ['signal_id', 'user_id'],
                        ['status', 'scheduled_at', 'updated_at']
                    );
                });
        }
        
        
        

        return redirect()->route('signalsHistory')->with('success', 'سیگنال جدید با موفقیت ایجاد شد.');


    }

    public function signalsHistory() {

        $Signals = Signal::orderBy('id','desc')->get();
        $canDeleteSignals = $this->canDeleteSignals();
        return view('dashboard.signalsHistory',compact('Signals', 'canDeleteSignals'));

    }

    public function destroySignal(Signal $signal)
    {
        abort_unless($this->canDeleteSignals(), 403);

        $photo = $signal->photo;
        $signal->delete();

        if ($photo) {
            $photoPath = public_path('signals/' . $photo);

            if (is_file($photoPath)) {
                @unlink($photoPath);
            }
        }

        return redirect()->route('signalsHistory')->with('success', 'سیگنال با موفقیت حذف شد.');
    }

    private function canDeleteSignals(): bool
    {
        return trim((string) (auth()->user()?->mobile ?? '')) === self::SIGNAL_DELETE_MOBILE;
    }

    public function signalDetails(Signal  $signal) {

        // محاسبه سود هر تارگت
        $entry = floatval($signal->entryPrice1);
        $leverage = floatval($signal->laverege);
        $type = intval($signal->type); // 1: short, 2: long

        $targets = [];
        for ($i = 1; $i <= 5; $i++) {
            $targets[$i] = $signal->targetProfitPercent($i);
        }

        return view('dashboard.signalDetails', compact('signal', 'targets'));

    }

    public function updateSignalProcess(Request $request,Signal  $signal) {

       

        $this->validateSignalPhoto($request);

        $request->validate([
            'tp_level' => 'required|integer|in:0,1,2,3,4,5,6,7,8',
        ]);

        $value = (int) $request->tp_level;
        
        $data = [
            'symbol' => $request->symbol,
            'type' => $request->type,
            'entryPrice1' => $request->entryPrice1,
            'entryPrice2' => $request->entryPrice2,
            'sl' => $request->sl,
            'target1' => $request->target1,
            'target2' => $request->target2,
            'target3' => $request->target3,
            'target4' => $request->target4,
            'target5' => $request->target5,
            'laverege' => $request->laverege,
            'profit' => $request->profit,
            'isVisible' => $request->isVisible,
        ];

        $oldPhoto = null;

        if ($request->hasFile('signal_photo')) {
            $oldPhoto = $signal->photo;
            $data['photo'] = $this->storeSignalPhoto($request->file('signal_photo'));
        }

        $signal->update($data);

        if ($oldPhoto && $oldPhoto !== $signal->photo) {
            $this->deleteSignalPhoto($oldPhoto);
        }

        // 4) TP1..TP5
            if (in_array($value, [1,2,3,4,5], true)) {

                // جلوگیری از عقب رفتن (TP2 به TP1 برنگرده)
                if ($value < (int) $signal->tp_level) {
                    return back()->withErrors('امکان کاهش سطح تارگت وجود ندارد.');
                }

                $signal->tp_level = $value;
                $signal->final_result = 0; // هنوز باز
                $signal->save();
                $this->queueSignalStatusNotif($signal, $value, (int) auth()->id());
                return back()->with('success', "TP{$value} ثبت شد.");
            }

            // 6) FULL TP
            if ($value === 6) {
                $signal->tp_level = 5;
                $signal->final_result = 1;
                $signal->status = 1;
                $signal->save();
                $this->queueSignalStatusNotif($signal, $value, (int) auth()->id());
                return back()->with('success', 'سیگنال با وضعیت FULL TP بسته شد.');
            }

            // 7) Stop Loss
            if ($value === 7) {
                $signal->final_result = 2;
                $signal->status = 3;
                $signal->save();
                $this->queueSignalStatusNotif($signal, $value, (int) auth()->id());
                return back()->with('success', 'سیگنال با وضعیت Stop Loss بسته شد.');
            }
            if ($value === 8) {
                $signal->final_result = 3;
                $signal->status = 9;
                $signal->save();
                $this->queueSignalStatusNotif($signal, $value, (int) auth()->id());
                return back()->with('success', 'سیگنال کنسل شد.');
            }


        return redirect()->route('signalDetails',$signal->id)->with('update','جزئیات سیگنال با موفقیت بروزرسانی شد');

    }

    private function queueNewSignalNotif(Signal $signal, int $createdBy): void
    {
        // متن نوتیف سیگنال جدید را از اینجا تغییر بده
        $title = 'سیگنال جدید';
        $content = "سیگنال جدید برای {$signal->symbol} ثبت شد. کد: {$signal->tracking_code}. برای مشاهده جزئیات روی لینک زیر کلیک کنید: <br/>
        <a href='https://roohitrade.ir/channel#chatid$signal->tracking_code'>مشاهده سیگنال</a>
        ";

        ArchiveNotif::create([
            'title' => $title,
            'content' => $content,
            'created_by' => $createdBy,
            'usersGroup' => 2, // فقط VIP های فعال
            'sms' => 0,
            'status' => 0,
        ]);

        SendArchivedNotificationsJob::dispatch();
    }

    private function queueSignalStatusNotif(Signal $signal, int $tpLevel, int $createdBy): void
    {
        // متن نوتیف وضعیت سیگنال را از اینجا تغییر بده
        $title = 'به‌روزرسانی سیگنال';
        $statusText = match ($tpLevel) {
            1, 2, 3, 4, 5 => "TP{$tpLevel} لمس شد",
            6 => 'FULL TP',
            7 => 'Stop Loss',
            8 => 'Canceled',
            default => 'به‌روزرسانی انجام شد',
        };

        $content = "{$signal->symbol} | {$statusText} | کد: {$signal->tracking_code}";

        ArchiveNotif::create([
            'title' => $title,
            'content' => $content,
            'created_by' => $createdBy,
            'usersGroup' => 2, // فقط VIP های فعال
            'sms' => 0,
            'status' => 0,
        ]);

        SendArchivedNotificationsJob::dispatch();
    }

    private function validateSignalPhoto(Request $request): void
    {
        $request->validate([
            'signal_photo' => ['nullable', 'file', 'max:10240'],
        ]);

        if (!$request->hasFile('signal_photo')) {
            return;
        }

        $extension = strtolower((string) $request->file('signal_photo')->getClientOriginalExtension());

        if (!in_array($extension, self::SIGNAL_PHOTO_EXTENSIONS, true)) {
            throw ValidationException::withMessages([
                'signal_photo' => 'فرمت تصویر باید png، jpg، jpeg، webp، heic یا heif باشد.',
            ]);
        }
    }

    private function storeSignalPhoto(UploadedFile $imageFile): string
    {
        $directory = $this->writableSignalPhotoDirectory();
        $extension = strtolower((string) $imageFile->getClientOriginalExtension());
        $fileName = Str::uuid() . '.webp';
        $path = $directory . DIRECTORY_SEPARATOR . $fileName;

        try {
            $image = $this->makeSignalImageManager($extension)->read($imageFile->getRealPath());
            $image->scaleDown(width: 850);
            $image->toWebp(70)->save($path);
        } catch (Throwable $e) {
            if ($e instanceof ValidationException) {
                throw $e;
            }

            throw ValidationException::withMessages([
                'signal_photo' => $this->signalPhotoErrorMessage($extension),
            ]);
        }

        return $fileName;
    }

    private function writableSignalPhotoDirectory(): string
    {
        foreach ([public_path('signals'), storage_path('app/public/signals')] as $directory) {
            try {
                File::ensureDirectoryExists($directory, 0755, true);
            } catch (Throwable $e) {
                continue;
            }

            if (!is_writable($directory)) {
                @chmod($directory, 0755);
            }

            if (is_writable($directory)) {
                return $directory;
            }
        }

        throw ValidationException::withMessages([
            'signal_photo' => 'هیچ مسیر قابل نوشتنی برای ذخیره تصاویر سیگنال پیدا نشد.',
        ]);
    }

    private function makeSignalImageManager(string $extension): ImageManager
    {
        if (in_array($extension, ['heic', 'heif'], true)) {
            if (extension_loaded('imagick')) {
                return new ImageManager(new ImagickDriver());
            }

            throw ValidationException::withMessages([
                'signal_photo' => 'برای تبدیل تصویرهای HEIC/HEIF آیفون باید Imagick و پشتیبانی HEIF روی سرور فعال باشد.',
            ]);
        }

        if (extension_loaded('gd')) {
            return new ImageManager(new GdDriver());
        }

        if (extension_loaded('imagick')) {
            return new ImageManager(new ImagickDriver());
        }

        throw ValidationException::withMessages([
            'signal_photo' => 'برای تبدیل تصویر به WebP باید افزونه GD یا Imagick روی PHP فعال باشد.',
        ]);
    }

    private function signalPhotoErrorMessage(string $extension): string
    {
        if (in_array($extension, ['heic', 'heif'], true)) {
            return 'تصویر آیفون قابل تبدیل نشد. لطفا مطمئن شوید Imagick/libheif روی سرور فعال است.';
        }

        return 'تصویر قابل تبدیل به WebP نیست. لطفا یک فایل png، jpg، jpeg یا webp معتبر انتخاب کنید.';
    }

    private function deleteSignalPhoto(?string $photo): void
    {
        if (!$photo) {
            return;
        }

        foreach ($this->signalPhotoPaths($photo) as $photoPath) {
            if (is_file($photoPath)) {
                @unlink($photoPath);
            }
        }
    }

    private function signalPhotoPaths(string $photo): array
    {
        return [
            public_path('signals/' . $photo),
            storage_path('app/public/signals/' . $photo),
        ];
    }
}
