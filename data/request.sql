# 01_bugr_budget.csv
SELECT b.id, b.name, b.amount, b.enable, b.created_at, b.updated_at
FROM budget b;

# 02_bugr_periodic_entry.csv
SELECT p.id, p.name, p.amount, p.execution_date, p.created_at, p.updated_at
FROM periodic_entry p;

# 03_bugr_periodic_entry_budget.csv
SELECT *
FROM periodic_entry_budget;

# 04_entry.csv
SELECT e.id, e.budget_id, e.name, e.amount, e.date as created_at
FROM entry e;

# 05_entry_missing_budget.csv
SELECT e.id, e.name, e.amount ,e.budget_id
FROM entry e
WHERE e.type = 'type-forecatst'
  AND (e.name != 'Provision budget annuel'
    AND e.name != 'Provison charge annuel')
  AND (e.budget_id IS NULL OR e.budget_id = 1266)
  AND e.id NOT IN (14579, 14557);

# 06_budget_balance_by_year.csv
SELECT b.name,
       e.budget_id,
       YEAR(e.date)                                   as year,
       b.amount,
       ROUND(SUM(e.amount), 3)                        as total_of_year
FROM entry e
         JOIN budget b on b.id = e.budget_id
WHERE e.budget_id IS NOT NULL
  AND e.amount < 0
GROUP BY year, e.budget_id
ORDER BY e.budget_id, `year`;

# 07_entries_forecast.csv
SELECT e.id, e.name, e.date, e.type
FROM entry e
WHERE (e.name = 'Provision budget annuel'
    OR e.name = 'Provison charge annuel');

# 08_entries_forecast_balance_by_year.csv
SELECT YEAR(e.date) as year, ROUND(SUM(e.amount), 3) as amount
FROM entry e
WHERE (e.name = 'Provision budget annuel'
          OR e.name = 'Provison charge annuel')
AND YEAR(e.date) != YEAR(NOW())
GROUP BY year;
