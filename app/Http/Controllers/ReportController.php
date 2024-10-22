<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function ReportPage(){
        return view('pages.dashboard.report-page');
    }

    public function SalesReport(Request $request){

        $user_id=$request->header('id');
        $FromDate=date('Y-m-d',strtotime($request->FromDate));
        $ToDate=date('Y-m-d',strtotime($request->ToDate));

        $total=Invoice::where('user_id',$user_id)->whereDate('created_at', '>=', $FromDate)->whereDate('created_at', '<=', $ToDate)->sum('total');
        $vat=Invoice::where('user_id',$user_id)->whereDate('created_at', '>=', $FromDate)->whereDate('created_at', '<=', $ToDate)->sum('vat');
        $payable=Invoice::where('user_id',$user_id)->whereDate('created_at', '>=', $FromDate)->whereDate('created_at', '<=', $ToDate)->sum('payable');
        $discount=Invoice::where('user_id',$user_id)->whereDate('created_at', '>=', $FromDate)->whereDate('created_at', '<=', $ToDate)->sum('discount');



        $list=Invoice::where('user_id',$user_id)
            ->whereDate('created_at', '>=', $FromDate)
            ->whereDate('created_at', '<=', $ToDate)
            ->with('customer')->get();

        $data=[
            'payable'=> $payable,
            'discount'=>$discount,
            'total'=> $total,
            'vat'=> $vat,
            'list'=>$list,
            'FromDate'=>$request->FromDate,
            'ToDate'=>$request->FromDate
        ];
        
        $pdf = Pdf::loadView('report.sales-report',$data);

        return $pdf->download('invoice.pdf');

    }
}
