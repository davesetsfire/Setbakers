<!-- The Delete Product Modal -->
<div class="modal fade global-modal" id="delete-item">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="model_disabled_container_class"></div>
            <button class="close btn" data-toggle="modal" data-target="#delete-item"><img src="{{ asset('assets/images/icons/cancel.png') }}"></button>
            <div class="modal-body">
                <form action="{{ route('fundus.destroy',[$product->slug]) }}" method="POST" id="delete_product_form">
                    @csrf
                    <input type="hidden" name="_method" value="DELETE">
                    <h4>Artikel löschen</h4>
                    <p>Möchtest Du diesen Artikel wirklich aus deinem Fundus entfernen?</p>
                    <div class="btns text-right">
                        <button type="button" class="btn global-btn style2" data-toggle="modal" data-target="#delete-item">Abbrechen</button>
                        <button type="submit" class="btn global-btn"><div class="spinner-border button_spinner"></div> Löschen</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>