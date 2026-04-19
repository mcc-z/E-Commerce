<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payroll;

class PayrollController extends Controller
{
    public function index() {
        $payrolls = Payroll::all();
        return view('payroll.index', compact('payrolls'));
    }

    public function create() {
        return view('payroll.create');
    }

    public function store(Request $request) {
        Payroll::create($request->all());
        return redirect('/payroll');
    }

    public function edit($id) {
        $payroll = Payroll::find($id);
        return view('payroll.edit', compact('payroll'));
    }

    public function update(Request $request, $id) {
        $payroll = Payroll::find($id);

        $payroll->employee_name = $request->employee_name;
        $payroll->salary = $request->salary;
        $payroll->bonus = $request->bonus;
        $payroll->deduction = $request->deduction;

        $payroll->save();

        return redirect('/payroll');
    }

    public function destroy($id) {
        $payroll = Payroll::find($id);
        $payroll->delete();

        return redirect('/payroll');
    }
}
