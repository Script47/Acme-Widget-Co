# Summary

This solution outlines how a simple product/basket system would work. The idea was to make it in such a way that it would be flexible. This is exemplified through the approach taken with a rudimentary rules system which is baked into the basket for offers/discounts.

# Setup

```
# install dependencies
composer install

# run migrations and seed the db
php artisan migrate:fresh --seed
```

**Note:** Don't forget to set the db credentials in the `.env` file and create a db called `acme_widget_co`.

# Usage

```
# Create basket
php artisan basket:create

# Add product (using product code)
php artisan product:add --products=R01,B01 --basket=1

# List the contents of the basket (with cost breakdown)
php artisan basket:list --basket=1

# Empty the basket
php artisan basket:empty --basket=1

# Delete the basket
php artisan basket:delete --basket=1
```

# Test Cases

**B01, G01**

```
> php artisan basket:create
> Basket 1 has been created.
> php artisan product:add --products=G01,B01 --basket=1
> 2 product(s) have been added to the basket.
> php artisan basket:list --basket=1
> -------------------------
> Basket #1
> -------------------------
> Products: G01, B01
> Subtotal: 32.90
> Delivery: 4.95
> Discount: 0.00
> Total: 37.85
> -------------------------
> php artisan basket:empty --basket=1
> Basket 1 has been emptied.
```

**B01, B01, R01, R01, R01**

```
> php artisan product:add --products=B01,B01,R01,R01,R01 --basket=1
> 5 product(s) have been added to the basket.
> php artisan basket:list --basket=1
> -------------------------
> Basket #1
> -------------------------
> Products: B01, B01, R01, R01, R01
> Subtotal: 114.75
> Delivery: 0
> Discount: 16.48
> Total: 98.27
> -------------------------

```

# Assumptions

- The offer/discount system isn't supposed to be future proofed.
- We'll only ever have a single "active" rule being checked against proucts
