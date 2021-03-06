<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\HowToOrder;
use App\Http\Controllers\Controller;

class HowToOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $howToOrder = HowToOrder::first();
        return view('admin.how-to-order.index', compact('howToOrder'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      $howToOrder = HowToOrder::first();
      $titlePage = 'How To Order';

      return view('admin.how-to-order.create', [
          'howToOrder' => $howToOrder, 
          'titlePage' => $titlePage
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $howToOrder = new HowToOrder;
        $howToOrder->header = $request->headerContent;
        $howToOrder->isi = $request->mainContent;
        $howToOrder->save();
        return redirect()->route('admin.how-to-order.index')->with('success_message', 'Sucessfully Save Changes');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $editHowToOrder = HowToOrder::findOrFail($id);

        return view('admin.how-to-order.edit', [
            'editHowToOrder' => $editHowToOrder,
            'titlePage' => 'Editing How To Order'
        ]);
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
      $howToOrder = HowToOrder::findOrFail($id);
      $howToOrder->header = $request->headerContent;
      $howToOrder->isi = $request->mainContent;
      $howToOrder->save();
      return redirect()->route('admin.how-to-order.index')->with('success_message', 'Sucessfully Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
