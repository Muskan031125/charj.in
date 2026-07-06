<?php

namespace App\Controllers;

class CalculatorController extends BaseController
{
    // -------------------------------------------------------------------------
    // Page renderers (all calc logic is client-side JS)
    // -------------------------------------------------------------------------

    public function cost()
    {
        return $this->render('calculators/cost', [
            'meta_title'       => 'EV Running Cost Calculator - How Much Does it Cost to Charge? | Charj.in',
            'meta_description' => 'Calculate the per-km and monthly running cost of any electric vehicle in India. Compare EV cost vs petrol/diesel.',
        ]);
    }

    public function savings()
    {
        return $this->render('calculators/savings', [
            'meta_title'       => 'EV Total Savings Calculator - EV vs Petrol Cost Comparison | Charj.in',
            'meta_description' => 'Find out how much you save by switching to an electric vehicle. Calculate EMI, running cost, maintenance and total 5-year savings.',
        ]);
    }

    public function emi()
    {
        return $this->render('calculators/emi', [
            'meta_title'       => 'EV Loan EMI Calculator | Charj.in',
            'meta_description' => 'Calculate your monthly EMI for an EV loan. Enter vehicle price, down payment, interest rate and tenure to get instant results.',
        ]);
    }

    // -------------------------------------------------------------------------
    // API: Running Cost
    // POST: daily_km, efficiency_km_per_kwh, electricity_rate, petrol_rate_per_km, days_per_month
    // -------------------------------------------------------------------------

    public function apiCost()
    {
        $dailyKm         = (float) $this->request->getPost('daily_km');
        $efficiencyKmKwh = (float) $this->request->getPost('efficiency_km_per_kwh');
        $electricityRate = (float) $this->request->getPost('electricity_rate');   // ₹/kWh
        $petrolRatePerKm = (float) $this->request->getPost('petrol_rate_per_km'); // ₹/km
        $daysPerMonth    = (float) ($this->request->getPost('days_per_month') ?: 30);

        if ($dailyKm <= 0 || $efficiencyKmKwh <= 0 || $electricityRate <= 0) {
            return $this->jsonResponse(['error' => 'Invalid input parameters.'], 422);
        }

        $evCostPerKm   = $electricityRate / $efficiencyKmKwh;           // ₹/km
        $monthlyKm     = $dailyKm * $daysPerMonth;
        $evMonthly     = $evCostPerKm * $monthlyKm;
        $evYearly      = $evMonthly * 12;

        $petrolMonthly = $petrolRatePerKm * $monthlyKm;
        $monthlySaving = $petrolMonthly - $evMonthly;
        $yearlySaving  = $monthlySaving * 12;

        // Break-even months: undefined without vehicle price — return 0 if not passed
        $yearsToBreakeven = 0;

        return $this->jsonResponse([
            'ev_cost_per_km'    => round($evCostPerKm, 4),
            'ev_monthly'        => round($evMonthly, 2),
            'ev_yearly'         => round($evYearly, 2),
            'petrol_monthly'    => round($petrolMonthly, 2),
            'monthly_saving'    => round($monthlySaving, 2),
            'yearly_saving'     => round($yearlySaving, 2),
            'years_to_breakeven' => $yearsToBreakeven,
        ]);
    }

    // -------------------------------------------------------------------------
    // API: Savings (EV vs Petrol total cost of ownership)
    // POST: vehicle_price, down_payment, loan_amount, tenure_months, interest_rate,
    //       daily_km, electricity_rate, petrol_rate_per_km,
    //       annual_maintenance_ev, annual_maintenance_petrol
    // -------------------------------------------------------------------------

    public function apiSavings()
    {
        $vehiclePrice        = (float) $this->request->getPost('vehicle_price');
        $downPayment         = (float) $this->request->getPost('down_payment');
        $loanAmount          = (float) ($this->request->getPost('loan_amount') ?: max(0, $vehiclePrice - $downPayment));
        $tenureMonths        = (int)   ($this->request->getPost('tenure_months') ?: 36);
        $interestRate        = (float) ($this->request->getPost('interest_rate') ?: 9.0);
        $dailyKm             = (float) $this->request->getPost('daily_km');
        $electricityRate     = (float) $this->request->getPost('electricity_rate');
        $petrolRatePerKm     = (float) $this->request->getPost('petrol_rate_per_km');
        $annualMaintenanceEv = (float) ($this->request->getPost('annual_maintenance_ev') ?: 3000);
        $annualMaintenancePetrol = (float) ($this->request->getPost('annual_maintenance_petrol') ?: 12000);

        // EMI
        $monthlyRate = $interestRate / 12 / 100;
        $emi = 0;
        if ($loanAmount > 0 && $monthlyRate > 0 && $tenureMonths > 0) {
            $emi = ($loanAmount * $monthlyRate * pow(1 + $monthlyRate, $tenureMonths))
                 / (pow(1 + $monthlyRate, $tenureMonths) - 1);
        } elseif ($loanAmount > 0 && $tenureMonths > 0) {
            $emi = $loanAmount / $tenureMonths;
        }

        $totalInterest = ($emi * $tenureMonths) - $loanAmount;

        // Running costs over tenure period
        $years         = $tenureMonths / 12;
        $annualKm      = $dailyKm * 365;

        // EV running cost (electricity, assume 4 km/kWh typical if not passed separately)
        $efficiencyKmKwh = (float) ($this->request->getPost('efficiency_km_per_kwh') ?: 4.0);
        $evRunningAnnual = ($annualKm / $efficiencyKmKwh) * $electricityRate;

        // Petrol running cost
        $petrolRunningAnnual = $annualKm * $petrolRatePerKm;

        // Total cost of EV: down payment + loan repayments + running + maintenance
        $totalCostEv = $downPayment
            + ($emi * $tenureMonths)
            + ($evRunningAnnual * $years)
            + ($annualMaintenanceEv * $years);

        // Total cost of equivalent petrol vehicle (assume same price for simplicity)
        $totalCostPetrol = $vehiclePrice
            + ($petrolRunningAnnual * $years)
            + ($annualMaintenancePetrol * $years);

        $totalSaving = $totalCostPetrol - $totalCostEv;

        // Break-even: months until cumulative savings offset any EV premium
        $evPremium      = 0; // if same vehicle price, no premium; expand if petrol_price passed
        $monthlySaving  = (($petrolRunningAnnual + $annualMaintenancePetrol) - ($evRunningAnnual + $annualMaintenanceEv)) / 12;
        $breakEvenMonths = ($monthlySaving > 0 && $evPremium > 0)
            ? (int) ceil($evPremium / $monthlySaving)
            : 0;

        return $this->jsonResponse([
            'emi'               => round($emi, 2),
            'total_interest'    => round($totalInterest, 2),
            'total_cost_ev'     => round($totalCostEv, 2),
            'total_cost_petrol' => round($totalCostPetrol, 2),
            'total_saving'      => round($totalSaving, 2),
            'break_even_months' => $breakEvenMonths,
        ]);
    }

    // -------------------------------------------------------------------------
    // API: EMI with amortization
    // POST: principal, annual_rate, tenure_months
    // -------------------------------------------------------------------------

    public function apiEmi()
    {
        $principal     = (float) $this->request->getPost('principal');
        $annualRate    = (float) $this->request->getPost('annual_rate');
        $tenureMonths  = (int)   $this->request->getPost('tenure_months');

        if ($principal <= 0 || $tenureMonths <= 0) {
            return $this->jsonResponse(['error' => 'Invalid input parameters.'], 422);
        }

        $monthlyRate = $annualRate / 12 / 100;
        $emi = 0;
        if ($monthlyRate > 0) {
            $emi = ($principal * $monthlyRate * pow(1 + $monthlyRate, $tenureMonths))
                 / (pow(1 + $monthlyRate, $tenureMonths) - 1);
        } else {
            $emi = $principal / $tenureMonths;
        }

        $totalAmount   = $emi * $tenureMonths;
        $totalInterest = $totalAmount - $principal;

        // Build first 3 rows of amortization schedule
        $amortization  = [];
        $balance       = $principal;
        $rowCount      = min(3, $tenureMonths);
        for ($month = 1; $month <= $rowCount; $month++) {
            $interestComponent    = $balance * $monthlyRate;
            $principalComponent   = $emi - $interestComponent;
            $balance             -= $principalComponent;
            $amortization[]       = [
                'month'          => $month,
                'emi'            => round($emi, 2),
                'principal'      => round($principalComponent, 2),
                'interest'       => round($interestComponent, 2),
                'balance'        => round(max(0, $balance), 2),
            ];
        }

        return $this->jsonResponse([
            'emi'           => round($emi, 2),
            'total_amount'  => round($totalAmount, 2),
            'total_interest' => round($totalInterest, 2),
            'amortization'  => $amortization,
        ]);
    }
}
