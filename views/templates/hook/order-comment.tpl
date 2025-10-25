<div id="delivery" class="form-group order-comment-box mb-4">
    <label for="delivery_message">{l s='Add a comment to your order' d='Shop.Theme.Global'} ({l s='Optional' d='Shop.Forms.Labels'})</label>
    <textarea class="form-control" rows="3" id="delivery_message" name="delivery_message">{if !is_array($cart.delivery_message)}{$cart.delivery_message}{/if}</textarea>
	<p class="colorred">{l s='Notification at the end of order' d='Shop.Theme.Global'}</p>
</div>

