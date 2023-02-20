<?php

namespace App\Http\Controllers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class InvoiceController extends Controller
{
    public function index()
    {
        $TBL_INVOICE = DB::table('TBL_INVOICE')
            ->leftJoin('TBL_MST_POPULATE AS A', 'TBL_INVOICE.ID_MST_POPULATE_FROM', '=', 'A.ID')
            ->leftJoin('TBL_MST_POPULATE AS B', 'TBL_INVOICE.ID_MST_POPULATE_FOR', '=', 'B.ID')
            ->select('TBL_INVOICE.*', 'A.NAME AS POPULATE_FROM', 'B.NAME AS POPULATE_FOR')
            ->paginate(5);
        return view('index', ['TBL_INVOICE' => $TBL_INVOICE]);
    }

    public function cari(Request $request)
    {
        $cari = $request->cari;
        $TBL_INVOICE = DB::table('TBL_INVOICE')
            ->where('INVOICE_ID', 'like', "%" . $cari . "%")
            ->paginate();
        return view('index', ['TBL_INVOICE' => $TBL_INVOICE]);
    }

    public function tambah()
    {
        $last_id = DB::table('TBL_INVOICE')
            ->orderBy('ID', 'DESC')
            ->limit(1)
            ->first();

        $invID = str_pad($last_id->INVOICE_ID + 1, 4, '0', STR_PAD_LEFT);

        $TBL_MST_POPULATE_FROM = DB::table('TBL_MST_POPULATE')->where('TYPE', 'FROM')->get();
        $TBL_MST_POPULATE_FOR = DB::table('TBL_MST_POPULATE')->where('TYPE', 'FOR')->get();
        $item = DB::table('TBL_MST_ITEM')->get();

        return view('tambah', [
            'TBL_MST_POPULATE_FROM' => $TBL_MST_POPULATE_FROM,
            'TBL_MST_POPULATE_FOR' => $TBL_MST_POPULATE_FOR,
            'ID_INVOICE' => $invID,
            'ITEM' => $item
        ]);
    }

    public function store(Request $request)
    {
        $DUE_DATE = $request->DUE_DATE;
        $ISSUE_DATE = $request->ISSUE_DATE;

        $DUE = date("Y-m-d", strtotime($DUE_DATE));
        $ISSUE = date("Y-m-d", strtotime($ISSUE_DATE));

        $idForm = $request->idForm;
        $qtyForm = $request->qtyForm;

        $tot = 0;
        foreach ($idForm as $key => $no) {
            $mst_item = DB::table('TBL_MST_ITEM')->where('ID', $no)->first();
            $tot += $mst_item->PRICE * $qtyForm[$key];
        }

        $idInv = DB::table('TBL_INVOICE')->insertGetId([
            'INVOICE_ID' => $request->INVOICE_ID,
            'DUE_DATE' => $DUE,
            'ISSUE_DATE' => $ISSUE,
            'SUBJECT' => $request->SUBJECT,
            'ID_MST_POPULATE_FROM' => $request->ID_MST_POPULATE_FROM,
            'ID_MST_POPULATE_FOR' => $request->ID_MST_POPULATE_FOR,
            'SUBTOTAL' => $tot,
            'TAX_10' => ($tot / 100) * 10,
            'PAYMENT' => $tot + ($tot / 100) * 10,
            'AMOUNT_DUE' => 0
        ]);

        foreach ($idForm as $key => $no) {
            $input['INVOICE_ID_PRIM'] = $idInv;
            $input['ID_MST_ITEM'] = $no;

            $mst_item = DB::table('TBL_MST_ITEM')->where('ID', $no)->first();

            $input['QTY'] = $qtyForm[$key];
            $input['AMOUNT'] = $mst_item->PRICE;

            DB::table('TBL_DTL_INVOICE')->insert($input);
        }
        return redirect('/invoice');
    }

    public function edit($id)
    {
        $TBL_INVOICE = DB::table('TBL_INVOICE')->where('ID', $id)->get();
        $TBL_MST_POPULATE_FROM = DB::table('TBL_MST_POPULATE')->where('TYPE', 'FROM')->get();
        $TBL_MST_POPULATE_FOR = DB::table('TBL_MST_POPULATE')->where('TYPE', 'FOR')->get();
        $item = DB::table('TBL_MST_ITEM')->get();
        $detail = DB::table('TBL_DTL_INVOICE')->where('INVOICE_ID_PRIM', $id)->get();

        return view('edit', [
            'TBL_INVOICE' => $TBL_INVOICE,
            'TBL_MST_POPULATE_FROM' => $TBL_MST_POPULATE_FROM,
            'TBL_MST_POPULATE_FOR' => $TBL_MST_POPULATE_FOR,
            'ITEM' => $item,
            'DETAIL' => $detail
        ]);
    }

    public function update(Request $request)
    {
        $DUE_DATE = $request->DUE_DATE;
        $ISSUE_DATE = $request->ISSUE_DATE;

        $DUE = date("Y-m-d", strtotime($DUE_DATE));
        $ISSUE = date("Y-m-d", strtotime($ISSUE_DATE));

        $idForm = $request->idForm;
        $qtyForm = $request->qtyForm;

        if ($request->idForm) {
            $tot = 0;
            foreach ($idForm as $key => $no) {
                $mst_item = DB::table('TBL_MST_ITEM')->where('ID', $no)->first();
                $tot += $mst_item->PRICE * $qtyForm[$key];
            }

            DB::table('TBL_INVOICE')->where('ID', $request->id)->update([
                'DUE_DATE' => $DUE,
                'ISSUE_DATE' => $ISSUE,
                'SUBJECT' => $request->SUBJECT,
                'ID_MST_POPULATE_FROM' => $request->ID_MST_POPULATE_FROM,
                'ID_MST_POPULATE_FOR' => $request->ID_MST_POPULATE_FOR,
                'SUBTOTAL' => $tot,
                'TAX_10' => ($tot / 100) * 10,
                'PAYMENT' => $tot + ($tot / 100) * 10,
                'AMOUNT_DUE' => 0
            ]);

            DB::table('TBL_DTL_INVOICE')->where('INVOICE_ID_PRIM', $request->id)->delete();

            foreach ($idForm as $key => $no) {
                $input['INVOICE_ID_PRIM'] = $request->id;
                $input['ID_MST_ITEM'] = $no;

                $mst_item = DB::table('TBL_MST_ITEM')->where('ID', $no)->first();

                $input['QTY'] = $qtyForm[$key];
                $input['AMOUNT'] = $mst_item->PRICE;

                DB::table('TBL_DTL_INVOICE')->insert($input);
            }
        } else {
            DB::table('TBL_INVOICE')->where('ID', $request->id)->update([
                'DUE_DATE' => $DUE,
                'ISSUE_DATE' => $ISSUE,
                'SUBJECT' => $request->SUBJECT,
                'ID_MST_POPULATE_FROM' => $request->ID_MST_POPULATE_FROM,
                'ID_MST_POPULATE_FOR' => $request->ID_MST_POPULATE_FOR
            ]);
        }

        return redirect('/invoice');
    }


    public function hapus($id)
    {
        DB::table('TBL_INVOICE')->where('ID', $id)->delete();
        DB::table('TBL_DTL_INVOICE')->where('INVOICE_ID_PRIM', $id)->delete();
        return redirect('/invoice');
    }


    public function invoice_api()
    {
        $TBL_INVOICE = DB::table('TBL_INVOICE')
            ->leftJoin('TBL_MST_POPULATE AS A', 'TBL_INVOICE.ID_MST_POPULATE_FROM', '=', 'A.ID')
            ->leftJoin('TBL_MST_POPULATE AS B', 'TBL_INVOICE.ID_MST_POPULATE_FOR', '=', 'B.ID')
            ->select('TBL_INVOICE.*', 'A.NAME AS POPULATE_FROM', 'B.NAME AS POPULATE_FOR')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'List Semua',
            'data'    => $TBL_INVOICE
        ], 200);
    }

    public function invoice_detail($id)
    {
        $DETAIL = DB::table('TBL_INVOICE')
            ->where('INVOICE_ID', $id)
            ->leftJoin('TBL_MST_POPULATE AS A', 'TBL_INVOICE.ID_MST_POPULATE_FROM', '=', 'A.ID')
            ->leftJoin('TBL_MST_POPULATE AS B', 'TBL_INVOICE.ID_MST_POPULATE_FOR', '=', 'B.ID')
            ->select('TBL_INVOICE.*', 'A.NAME AS POPULATE_FROM', 'B.NAME AS POPULATE_FOR')
            ->first();

        $DATA = DB::table('TBL_DTL_INVOICE')
            ->where('INVOICE_ID_PRIM', $DETAIL->ID)
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'List Semua',
            'detail'    => $DETAIL,
            'data' => $DATA
        ], 200);
    }
}
