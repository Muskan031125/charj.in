# Recommendation Engine Logic

## Inputs

- category
- city
- daily_running_km
- monthly_running_km
- budget_min
- budget_max
- use_case
- home_charging_available
- fast_charging_needed
- passenger_count
- load_requirement
- finance_required
- preferred_brands

## Scoring weights

- Budget fit: 25
- Range fit: 25
- Use-case fit: 20
- Charging fit: 10
- Service/dealer availability: 10
- Warranty/reliability: 5
- User/expert rating: 5

## Output buckets

- Best overall
- Best budget
- Best range
- Best commercial value
- Best premium

## Initial pseudo logic

For each vehicle:
1. Check category match.
2. Score range against daily running with 25 percent safety buffer.
3. Score price against user budget.
4. Score use-case tags against selected use case.
5. Boost if city has dealer support.
6. Boost if charging mode fits user environment.
7. Return top 3 to 5 vehicles.
