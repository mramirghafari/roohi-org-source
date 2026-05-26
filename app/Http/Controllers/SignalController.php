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
use Carbon\Carbon;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;


class SignalController extends Controller
{
    private const SIGNAL_DELETE_MOBILE = '09396206108';

    public function NewSignalProcess(Request $request) {

        //dd($request->all());

        /// Create Signal
        $user = \Auth::user();
        $request['user_id'] = $user->id;
        $rand = rand(111111,999999);
        $CheckSignal = Signal::where('tracking_code',$rand)->count();
        if ($CheckSignal == 0) {
            $request['tracking_code'] = $rand;
        }else {
            $request['tracking_code'] = rand(111111,999999);
        }

        if($request->hasFile('signal_photo')) {
            $imageFile = $request->file('signal_photo');
            $manager = new ImageManager(new Driver());
            

            $image = $manager->read($imageFile);
            // تغییر سایز (حفظ نسبت)
            $image->scaleDown(width: 850);
            // نام فایل
            $fileName = Str::uuid() . '.webp';

            // ذخیره با کیفیت بهینه
            $image->toWebp(70)->save(
                public_path('signals/' . $fileName)
            );

           // $imageName = time().'.'.$image->getClientOriginalExtension() ?? 'jpg';
           // $image->move($_SERVER['DOCUMENT_ROOT'].'/roohibot/signals', $imageName);
            //$image->move(public_path('signals'), $imageName);
            $request['photo'] = $fileName;
        }

        //dd($request['photo']);

       // dd($request->all());

        $Signal = Signal::create($request->all());

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

       

        $request->validate([
            'tp_level' => 'required|integer|in:0,1,2,3,4,5,6,7,8',
        ]);

        $value = (int) $request->tp_level;
        
        

        $signal->update([
            'symbol' => $request->symbol,
            'type' => $request->type,
            'entryPrice1' => $request->entryPrice1,
            'entryPrice2' => $request->entryPrice1,
            'sl' => $request->sl,
            'target1' => $request->target1,
            'target2' => $request->target2,
            'target3' => $request->target3,
            'target4' => $request->target4,
            'target5' => $request->target5,
            'laverege' => $request->laverege,
            'profit' => $request->profit,
            'isVisible' => $request->isVisible,
        ]);

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
}
