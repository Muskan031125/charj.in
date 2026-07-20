-- Charj.in — brand logos (real logos from Wikimedia Commons). Run on prod DB. Safe to re-run.
UPDATE brands SET logo = 'https://upload.wikimedia.org/wikipedia/commons/f/f1/Tata_Motors_Logo.svg' WHERE slug = 'tata-motors';
UPDATE brands SET logo = 'https://upload.wikimedia.org/wikipedia/commons/d/d3/Ather-logo.svg'       WHERE slug = 'ather-energy';
UPDATE brands SET logo = 'https://upload.wikimedia.org/wikipedia/commons/c/c8/MG_Motor_2021_logo.svg' WHERE slug = 'mg-motor';
UPDATE brands SET logo = 'https://upload.wikimedia.org/wikipedia/commons/e/e5/OLA_Electric_logo.svg' WHERE slug = 'ola-electric';
UPDATE brands SET logo = 'https://upload.wikimedia.org/wikipedia/commons/7/77/Logo_TVS.svg'          WHERE slug = 'tvs-motor';
UPDATE brands SET logo = 'https://upload.wikimedia.org/wikipedia/commons/c/c1/Revolt_Logo.png'       WHERE slug = 'revolt-motors';
