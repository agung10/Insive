<?php

namespace App\Http\Controllers\Home;

use App\User;
use App\Models\Cart;
use App\Models\Logic;
use App\Models\Sheet;
use App\Models\Answer;
use App\Models\Option;
use App\Models\Pricing;
use App\Models\Question;
use App\Mail\ContactMail;
use App\Models\ContactUs;
use App\Models\Fragrance;
use App\Models\HowToOrder;
use Illuminate\Http\Request;
use App\Models\CustomProduct;
use App\Mail\MessageFromCustomer;
use Laravolt\Indonesia\Indonesia;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;

class MainController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $allCities = Indonesia::allProvinces();
        return view('question', compact('allCities'));
    }

    public function summaryOrder()
    {
        $user_id = Auth::id();
        $cart = Cart::where([['user_id', '=', $user_id], ['type_cart', '=', 'custom'], ['status', '=', 'waiting']])->first();
        if (empty($cart)) {
            return redirect(url('/'));
        }
        $sub_cart = CustomProduct::select('custom_products.*', DB::raw('CASE WHEN sheets.id IS NULL THEN fragrances.fragrance_name ELSE sheets.sheet_name END as name'))
                                ->leftJoin('sheets', 'sheets.id', '=', 'custom_products.sheet_id')
                                ->leftJoin('fragrances', 'fragrances.id', '=', 'custom_products.fragrance_id')
                                ->where('cart_id', $cart->id)->get();
        $data['sub_cart'] = $sub_cart;
        return view('home.summary-order')->with($data);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function optionsFaceResult(Request $request)
    {
        $user_id = Auth::user()->id;
        $options = $request->input("options");
        $table = Cart::where([['user_id', '=', $user_id], ['type_cart', '=', 'custom'], ['status', '=', 'waiting']])->firstOrFail();
        // dd($table);
        if($options[0] == "sheet") {
            $table->checked_options = $options[0];
            $table->save();
            return redirect()->route('main.sheet');
        } elseif($options[0] == "serum") {
            $table->checked_options = $options[0];
            $table->save();
            return redirect()->route('main.fragrance');
        } else {
            return redirect()->back();
        }
    }

    public function fragrance()
    {
        $user_id = Auth::user()->id;
        $answers = Answer::select('answers.question_id', 'answers.option_id', 'options.text')
            ->join('options', 'options.id', 'answers.option_id')
            ->where('answers.user_id', '=', $user_id)
            ->get()->toArray();
        if (empty($answers)) {
            return redirect()->route('main.question');
        }
        $option_3;
        $option_4;
        foreach ($answers as $key => $value) {
            if ($value['question_id'] == 3) {
                $option_3 = $value['text'];
            } else if ($value['question_id'] == 4) {
                $option_4 = $value['text'];
            }
        }
        $logic = Logic::where([
            ['option_3', '=', $option_3],
            ['option_4', '=', $option_4]
        ])->firstOrFail();
        $code_cart = Cart::orderBy('id', 'desc')->first();
        if (empty($code_cart)) {
            $code = 'C' . date('HisYmd') . $user_id . sprintf('%05d', 1);
        } else {
            $code = 'C' . date('HisYmd') . $user_id . sprintf('%05d', substr($code_cart->cart_code, -5) + 1);
        }
        $table = Cart::firstOrCreate(
            [
                'user_id' => $user_id,
                'type_cart' => 'custom',
                'status' => 'waiting'
            ],
            [
                'user_id' => $user_id,
                'logic_id' => $logic->id,
                'cart_code' => $code,
                'formula_code' => '#' . $logic->no_formula,
                'type_cart' => 'custom',
                'status' => 'waiting'
            ]
        );
        CustomProduct::where([
            ['cart_id', "=", $table->id],
            ["fragrance_id", "<>", null]
        ])->delete();

        $data['fragrance'] = Fragrance::where('qty', '>', 0)->get();
        $data['table'] = $table;

        return view('fragrance.custom')->with($data);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function sheet()
    {
        $user_id = Auth::user()->id;
        $answers = Answer::select('answers.question_id', 'answers.option_id', 'options.text')
            ->join('options', 'options.id', 'answers.option_id')
            ->where('answers.user_id', '=', $user_id)
            ->get()->toArray();
        if (empty($answers)) {
            return redirect()->route('main.question');
        }
        $option_3;
        $option_4;
        foreach ($answers as $key => $value) {
            if ($value['question_id'] == 3) {
                $option_3 = $value['text'];
            } else if ($value['question_id'] == 4) {
                $option_4 = $value['text'];
            }
        }
        $logic = Logic::where([
            ['option_3', '=', $option_3],
            ['option_4', '=', $option_4]
        ])->firstOrFail();
        $code_cart = Cart::orderBy('id', 'desc')->first();
        if (empty($code_cart)) {
            $code = 'C' . date('HisYmd') . $user_id . sprintf('%05d', 1);
        } else {
            $code = 'C' . date('HisYmd') . $user_id . sprintf('%05d', substr($code_cart->cart_code, -5) + 1);
        }
        $table = Cart::firstOrCreate(
            [
                'user_id' => $user_id,
                'type_cart' => 'custom',
                'status' => 'waiting'
            ],
            [
                'user_id' => $user_id,
                'logic_id' => $logic->id,
                'cart_code' => $code,
                'formula_code' => '#' . $logic->no_formula,
                'type_cart' => 'custom',
                'status' => 'waiting'
            ]
        );

        CustomProduct::where([['cart_id', "=", $table->id], ["sheet_id", "<>", null]])->delete();

        $data['sheet'] = Sheet::where('qty', '>', 0)->get();
        $data['table'] = $table;

        return view('home.sheet')->with($data);
    }

    // /**
    // * Display a listing of the resource.
    // *
    // * @return \Illuminate\Http\Response
    // */
    // public function sheetAndFragrance()
    // {
    //     $user_id = Auth::user()->id;
    //     $cart_id = Cart::where([['user_id', '=', $user_id], ['status', '=', 'waiting']])->firstOrFail()->id;
    //     $data['product'] = CustomProduct::where('cart_id', '=', $cart_id)->get();
    //     return view('home.sheet_and_fragrance_page')->with($data);
    // }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function pricing()
    {
        $data['price'] = Pricing::all();
        return view('home.pricing')->with($data);
    }

    /**
     * Display face result of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function faceResult()
    {
        $user_id = Auth::user()->id;
        $answers = Answer::select('answers.question_id', 'answers.option_id', 'options.text')
            ->join('options', 'options.id', 'answers.option_id')
            ->where('answers.user_id', '=', $user_id)
            ->get()->toArray();
        if (empty($answers)) {
            return redirect()->route('main.question');
        } else {
            $option_3;
            $option_4;
            foreach ($answers as $key => $value) {
                if ($value['question_id'] == 3) {
                    $option_3 = $value['text'];
                } else if ($value['question_id'] == 4) {
                    $option_4 = $value['text'];
                }
            }
            $data['result'] = Logic::where([['option_3', '=', $option_3], ['option_4', '=', $option_4]])->firstOrFail();
            $code_cart = Cart::orderBy('id', 'desc')->first();
            if (empty($code_cart)) {
                $code = 'C' . date('HisYmd') . $user_id . sprintf('%05d', 1);
            } else {
                $code = 'C' . date('HisYmd') . $user_id . sprintf('%05d', substr($code_cart->cart_code, -5) + 1);
            }
            $table = Cart::firstOrCreate(
                [
                    'user_id' => $user_id,
                    'type_cart' => 'custom',
                    'status' => 'waiting'
                ],
                [
                    'user_id' => $user_id,
                    'logic_id' => $data['result']->id,
                    'cart_code' => $code,
                    'formula_code' => '#' . $data['result']->no_formula,
                    'type_cart' => 'custom',
                    'status' => 'waiting'
                ]
            );
            // dd($result);
            return view('custom.face-result')->with($data);
        }
    }

    /**
     * Display question of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function question()
    {
        $user_id = Auth::user()->id;
        $answers = Answer::select('answers.question_id', 'answers.option_id', 'options.text')
            ->join('options', 'options.id', 'answers.option_id')
            ->where('answers.user_id', '=', $user_id)
            ->get()->toArray();
        if (count($answers) > 0) {
            return redirect()->route('home.main.face.result');
        }
        $data['question'] = Question::findOrfail(1);
        return view('custom.question')->with($data);
    }

    public function getSoal(Request $request, $id, $act = 'next')
    {
        $option = $request->option_id;
        $question = Question::findOrfail($id);
        if ($question['status'] == "logic" && $option != null) {
            $match = ['user_id' => Auth::user()->id, 'question_id' => $id];
            Answer::updateOrCreate(
                $match,
                ['option_id' => $option]
            );
        }
        if ($id == 17) {
            return response()->json([], 201);
        } else {
            if ($act == 'next') {
                $data['question'] = Question::findOrfail(($id + 1));
            } else if ($act == 'prev') {
                $data['question'] = Question::findOrfail(($id - 1));
            } else {
                abort(404);
            }
            if ($id == 16) {
                $client = new GuzzleClient([
                    'headers' => ['key' => 'a9833b70a0d2e26d4f36024e66e6fdaa']
                ]);
                $requester = $client->get('https://api.rajaongkir.com/starter/city');
                $response = json_decode($requester->getBody()->getContents(), true);
                if ($response['rajaongkir']['status']['code'] === 200) {
                    $data['allCities'] = $response['rajaongkir']['results'];
                } else {
                    $data['allCities'] = Indonesia::allProvinces();
                }
            }
            return response()->json([
                'type' => $data['question']->type,
                'view' => view('home.question_soal_single')->with($data)->render()
            ], 200);
        }
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $client = new GuzzleClient(['headers' => ['key' => 'a9833b70a0d2e26d4f36024e66e6fdaa']]);
        $request = $client->get('https://api.rajaongkir.com/starter/city');
        // $response = $request->getBody()->getContents();
        $response = json_decode($request->getBody()->getContents(), true);
        if ($response['rajaongkir']['status']['code'] === 200) {
            dd($$response['rajaongkir']['results']);
        }
    }

    public function contact()
    {
        return view('contact', [
            'admin' => $this->adminAccount->first(),
            'aboutUs' => $this->aboutUs
        ]);
    }

    public function ContactStore(Request $request)
    {
        if ($request->has('email_customer') <> 'admin@insive.com') {
            $contactUs = new ContactUs;
            $contactUs->nama_customer = $request->peopleName;
            $contactUs->email_customer = $request->peopleEmail;
            $contactUs->pesan = $request->message;
            $contactUs->save();

            Mail::to($this->adminAccount->first()->email)->send(new MessageFromCustomer($contactUs));
            return redirect()->back()->with(
                'success_message',
                'Message Succesfully Sent! Please Wait We"ll Reply You Maximum 24 Hours From Now'
            );
        } else {
            return redirect()->back()->with('error_message', "Admin can't contact to itself");
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeSheet(Request $request)
    {
        $sheet = $request->input('sheet');
        $qty = $request->input('jumlah_qty');

        if ($sheet !== null) {
            $cart = Cart::where([
                ['user_id', Auth::id()],
                ['type_cart', 'custom'],
                ['status', 'waiting']
            ])->firstOrFail();

            $data = [];

            foreach ($sheet as $key => $value) {
                $qty = $request->input('jumlah_qty')[$key];
                $sheet = Sheet::where('id', $value)->firstOrFail();
                if ($qty > 0) {
                    $data[] = [
                        'cart_id' => $cart->id,
                        'sheet_id' => $value,
                        'qty' =>  $qty,
                        'price' => $sheet->price,
                        'total_price' => $sheet->price * $qty,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
            }
            $table = CustomProduct::insert($data);
            
            if($cart->checked_options == "sheet") {
                return redirect()->route('main.fragrance');
            } elseif ($cart->checked_options == "serum") {
                return redirect()->route('main.summary.orders');
            }
        } else {
            return redirect()->back();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeFragrance(Request $request)
    {
        $sheet = $request->input('fragrance');
        $qty = $request->input('jumlah_qty');

        if ($sheet !== null) {
            $cart = Cart::where([
                ['user_id', Auth::id()],
                ['type_cart', 'custom'],
                ['status', 'waiting']
            ])->firstOrFail();

            $data = [];

            foreach ($sheet as $key => $value) {
                $qty = $request->input('jumlah_qty')[$key];
                $fragrance = Fragrance::where('id', $value)->firstOrFail();
                if ($qty > 0) {
                    $data[] = [
                        'cart_id' => $cart->id,
                        'fragrance_id' => $value,
                        'qty' =>  $qty,
                        'price' => $fragrance->price,
                        'total_price' => $fragrance->price * $qty,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
            }
            $table = CustomProduct::insert($data);
            if($cart->checked_options == "sheet") {
                return redirect()->route('main.summary.orders');
            } elseif ($cart->checked_options == "serum") {
                return redirect()->route('main.sheet');
            }
        } else {
            return redirect()->back();
        }
        // $fragrance = $request->input('fragrance');
        // $user_id = Auth::user()->id;
        // $date_now = date('Y-m-d H:i:s');
        // if ($fragrance !== null) {
        //     $cart_id = Cart::where([['user_id', '=', $user_id], ['type_cart', '=', 'custom'], ['status', '=', 'waiting']])->firstOrFail()->id;

            // $count_sheet = CustomProduct::where('cart_id', '=', $cart_id)->count();
            // $count_fragrance = count($fragrance);
            // if ($count_sheet > 0) {
            //     $date_now = date('Y-m-d H:i:s');
            //     $sheet = CustomProduct::where('cart_id', '=', $cart_id)->get();
            //     $last_sheet_id = $sheet->last()->sheet_id;
            //     if ($count_sheet >= $count_fragrance) {
            //         for ($i = 0; $i < $count_sheet; $i++) {
            //             CustomProduct::where([['cart_id', '=', $cart_id], ['sheet_id', '=', $sheet[$i]->sheet_id]])
            //                 ->update(['fragrance_id' => ($i <= ($count_fragrance - 1)) ? $fragrance[$i] : last($fragrance)]);
            //         }
            //         return redirect('main.summary.orders');
            //     } elseif ($count_sheet <= $count_fragrance) {
            //         for ($j = 0; $j < $count_fragrance; $j++) {
            //             if ($j <= ($count_sheet - 1)) {
            //                 CustomProduct::where([['cart_id', '=', $cart_id], ['sheet_id', '=', $sheet[$j]->sheet_id]])
            //                     ->update(['fragrance_id' => $fragrance[$j]]);
            //             } else {
            //                 CustomProduct::create(['cart_id' => $cart_id, 'sheet_id' => $last_sheet_id, 'fragrance_id' => $fragrance[$j], 'qty' => 1, 'created_at' => $date_now, 'updated_at' => $date_now]);
            //             }
            //         }
            //         return redirect('main.summary.orders');
            //     } else {
            //         return redirect(url('/'));
            //     }
            // } else {
            //     return redirect()->route('main.question');
            // }
        // } else {
        //     return redirect()->back();
        // }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //s
    }

    public function howToOrder()
    {
        $howToOrder = HowToOrder::first();
        return view('how-to', compact('howToOrder'));
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect(url('/'));
    }
}
