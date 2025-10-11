 
BESTCHECKOUT OVERRIDE ACTIVE
 
<div class="cart-summary-totals js-cart-summary-totals card-block">
    <ul class="checkout-summary">
        <li class="summary-line">Suma: {$cart.subtotals.products.value}</li>
        <li class="summary-line">Rabat: {$cart.subtotals.discounts.value}</li>
        <li class="summary-line">Dostawa: {$cart.subtotals.shipping.value}</li>
        <li class="summary-line total">Razem: {$cart.totals.total.value}</li>
    </ul>
</div>


<section id="js-checkout-summary" class="card js-cart" data-refresh-url="{$urls.pages.cart}?ajax=1&action=refresh">
 
  <div>
    <p class="m-0 p-0 h4">OVERWRITE{l s='Summary' d='Shop.Istheme'}</p>
  </div>
  <div class="card-body">
    {block name='hook_checkout_summary_top'}
      {include file='checkout/_partials/cart-summary-top.tpl' cart=$cart}
    {/block}

    {block name='cart_summary_products'}
      {include file='checkout/_partials/cart-summary-products.tpl' cart=$cart}
    {/block}

    {block name='cart_summary_subtotals'}
     {include file='checkout/_partials/cart-summary-subtotals.tpl' cart=$cart} 
    {/block}

    {block name='cart_summary_totals'} 
      {include file='checkout/_partials/cart-summary-totals.tpl' cart=$cart}
    {/block}

    {block name='cart_summary_voucher'}
      {include file='checkout/_partials/cart-voucher.tpl'}
    {/block}
  </div>


</section>

 