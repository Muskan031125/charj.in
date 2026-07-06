<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>
<section class="mx-auto grid max-w-7xl gap-8 px-4 py-8 lg:grid-cols-[1fr_380px]">
    <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        <h1 class="text-4xl font-black">EV Running Cost Calculator</h1>
        <p class="mt-2 text-slate-600">Estimate daily, monthly and yearly EV running cost. This client-side version can later save results to `calculator_logs`.</p>
        <div class="mt-6 grid gap-4 md:grid-cols-2">
            <label class="grid gap-1 font-semibold">Daily running km<input id="km" type="number" value="40" class="rounded-xl border p-3"></label>
            <label class="grid gap-1 font-semibold">EV efficiency km/kWh<input id="eff" type="number" value="30" class="rounded-xl border p-3"></label>
            <label class="grid gap-1 font-semibold">Electricity cost ₹/kWh<input id="rate" type="number" value="8" class="rounded-xl border p-3"></label>
            <label class="grid gap-1 font-semibold">Petrol vehicle cost ₹/km<input id="petrol" type="number" value="3" class="rounded-xl border p-3"></label>
        </div>
        <button onclick="calcCost()" class="mt-5 rounded-xl bg-emerald-600 px-5 py-3 font-bold text-white">Calculate</button>
        <div id="result" class="mt-6 rounded-2xl bg-emerald-50 p-5 text-emerald-950"></div>
    </div>
    <aside><?= view('partials/lead_form', ['vehicle' => [], 'hideName' => true]) ?></aside>
</section>
<script>
function calcCost(){
 const km=Number(document.getElementById('km').value||0), eff=Number(document.getElementById('eff').value||1), rate=Number(document.getElementById('rate').value||0), petrol=Number(document.getElementById('petrol').value||0);
 const evPerKm=rate/eff, evMonthly=evPerKm*km*30, petrolMonthly=petrol*km*30, saving=petrolMonthly-evMonthly;
 document.getElementById('result').innerHTML = `<strong>Estimated EV cost:</strong> ₹${evPerKm.toFixed(2)}/km<br><strong>Monthly EV cost:</strong> ₹${evMonthly.toFixed(0)}<br><strong>Estimated monthly saving:</strong> ₹${saving.toFixed(0)}`;
}
calcCost();
</script>
<?= $this->endSection() ?>
